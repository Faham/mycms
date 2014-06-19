<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once('classes/cas/CAS.php');
use \phpCAS as phpCAS;

//-----------------------------------------------------------------------------

class cas {

//-----------------------------------------------------------------------------

    private static $client_is_set;
    private static $cas_server_cert_is_set;

//-----------------------------------------------------------------------------

    public static function init() {
        cas::$client_is_set          = false;
        cas::$cas_server_cert_is_set = false;
    }

//-----------------------------------------------------------------------------

    private static function _set_client() {
        global $g;

        if (!cas::$client_is_set)
            phpCAS::client($g['cas']['cas_server_version']
                , $g['cas']['cas_server_hostname']
                , $g['cas']['cas_server_port']
                , $g['cas']['cas_server_uri']
            );

        cas::$client_is_set = true;
    }

//-----------------------------------------------------------------------------

    private static function _set_cas_server_cert() {
        global $g;

        if (!cas::$cas_server_cert_is_set) {
            if (empty($g['cas']['cas_server_cert']))
                phpCAS::setNoCasServerValidation();
            else
                phpCAS::setCasServerCACert($g['cas']['cas_server_cert']);
        }
        cas::$cas_server_cert_is_set = true;
    }

//-----------------------------------------------------------------------------

    public static function authenticate() {

        cas::_set_client();
        cas::_set_cas_server_cert();

        phpCAS::forceAuthentication();
    }

//-----------------------------------------------------------------------------

    public static function is_authenticated() {
        cas::_set_client();
        cas::_set_cas_server_cert();

        return phpCAS::isAuthenticated();
    }

//-----------------------------------------------------------------------------

    public static function get_user_id() {
        cas::_set_client();

        if (cas::is_authenticated())
            return phpCAS::getUser();
        else
            return false;
    }

//-----------------------------------------------------------------------------

    public static function logout($url) {
        cas::_set_client();

        if (cas::is_authenticated())
            phpCAS::logout(
                array(
                    'service' => $url,
                )
            );
    }

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------
