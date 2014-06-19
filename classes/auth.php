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
    }

//-----------------------------------------------------------------------------

    public static function is_authenticated() {
        global $g;

        if ('cas' == $g['auth_method']) {
            return cas::is_authenticated();
        }
    }

//-----------------------------------------------------------------------------

    public static function get_user_id() {
        global $g;

        if ('cas' == $g['auth_method']) {
            return cas::get_user_id();
        }
    }

//-----------------------------------------------------------------------------

    // params: $url is the redirect url after loging out
    public static function logout($url) {
        global $g;

        if ('cas' == $g['auth_method']) {
            return cas::logout($url);
        }
    }

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------
