<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once('classes/cas.php');

//-----------------------------------------------------------------------------

class auth {

//-----------------------------------------------------------------------------

    public static function init() {
        global $g;

        if ('cas' == $g['auth_method']) {
            return cas::init();
        }
    }

//-----------------------------------------------------------------------------

    public static function authenticate() {
        global $g;

        if ('cas' == $g['auth_method']) {
            return cas::authenticate();
        }
        else if ('shibboleth' == $g['auth_method']) {
            return true;
        }
        else if ('native' == $g['auth_method']) {
            //header('Location: http://localhost/mycms/login.html');
            header('Location: '.$g['weburl'].'login.html');
        }
    }

//-----------------------------------------------------------------------------

    public static function is_authenticated() {
        global $g;

        if ('cas' == $g['auth_method']) {
            return cas::is_authenticated();
        }
        else if ('shibboleth' == $g['auth_method']) {
            return true;
        }
        else if ('native' == $g['auth_method']) {
            if(isset($_SESSION['userid'])){
                return true;
            }
            else{
                return false;
            }
        }
    }

//-----------------------------------------------------------------------------

    public static function get_user_id() {
        global $g;

        if ('cas' == $g['auth_method']) {
            return cas::get_user_id();
        }
        else if ('shibboleth' == $g['auth_method']) {
            //return "ssb001";
            return $g['admin'];
        }
        else if ('native' == $g['auth_method']) {
           
            //return "http://localhost/login";
            if(isset($_SESSION['userid']))
                return $_SESSION['userid'];
            else
                return null;
        }
    }

//-----------------------------------------------------------------------------

    // params: $url is the redirect url after loging out
    //TODO: shibboleth is not implemented
    public static function logout($url) {
        global $g;

        if ('cas' == $g['auth_method']) {
            return cas::logout($url);
        }
        else if ('shibboleth' == $g['auth_method']) {
            return "http://localhost/mycms";
        }
        else if ('native' == $g['auth_method']) {
            unset($_SESSION['userid']);
            unset($_SESSION['username']);
            $g['user']['id'] = '';
            $g['user']['is_authenticated'] = false;
            $g['user']['is_admin'] = false;
            //return "http://localhost/mycms";
            return $url;
;
        }
    }

//-----------------------------------------------------------------------------

    public static function is_admin($user_id) {
        global $g;

        if ($user_id === $g['admin']) {
            return true;
        } else {
            $ppl = $g['content']['people'];
            $r = $ppl->get('people_nsid', $user_id);

            if ($r === 0)
                return false;
            return $ppl->people_role === 'administrator';
        }

        return false;
    }

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------
