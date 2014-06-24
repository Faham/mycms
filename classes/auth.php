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
    }

//-----------------------------------------------------------------------------

    public static function get_user_id() {
        global $g;

        if ('cas' == $g['auth_method']) {
            return cas::get_user_id();
        }
        else if ('shibboleth' == $g['auth_method']) {
            return "ssb001";
        }
    }

//-----------------------------------------------------------------------------

    // params: $url is the redirect url after loging out
    public static function logout($url) {
        global $g;

        if ('cas' == $g['auth_method']) {
            return cas::logout($url);
        }
        else if ('shibboleth' == $g['auth_method']) {
            return "http://localhost/mycms";
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

            if ($r !== 0)
                return false;

            return $ppl->people_role === 'administrator';
        }

        return false;
    }

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------
