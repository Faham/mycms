<?php

// session init
//session_set_cookie_params(0, dirname($_SERVER['PHP_SELF']));
//session_start();
//ini_set('use_only_cookies', '1');

set_time_limit(0);
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

// do not change timezone too much!!!
$g['timezone'] = 'America/Regina';
date_default_timezone_set($g['timezone']);

// it can be [debug, release]
$g['runmode'] = 'debug';

// remove slashes when magic_quotes_gpc is on
if (get_magic_quotes_gpc()) {
    function stripslashes_gpc(&$value)
    {
        $value = stripslashes($value);
    }
    array_walk_recursive($_GET, 'stripslashes_gpc');
    array_walk_recursive($_POST, 'stripslashes_gpc');
    array_walk_recursive($_COOKIE, 'stripslashes_gpc');
    array_walk_recursive($_REQUEST, 'stripslashes_gpc');
}

$time_start           = microtime(true);
$g['total_q']         = 0;

$g['db_user']         = 'root';
$g['db_pass']         = '';
$g['db_name']         = 'hci';
$g['db_host']         = 'localhost';
$g['db_table_prefix'] = 'hci';

$g['host']            = 'localhost';
$g['user']            = 'root';

$g['default_lang']    = 'en';
$g['rewrite']         = true;
$g['homepage']        = 'home';

$g['fullpath']        = dirname(__FILE__) . '/';
$g['weburl']          = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';

$g['content']         = array();

// include all base classes
foreach(glob('classes/*.php') as $c){
    require_once($c);
}

$g['db']     = new db();
$g['error']  = new error();
$g['smarty'] = new mysmarty();

system::interpret_params();

$g['default_page_to_display'] = "templates/index.tpl";

$g['lang'] = $g['default_lang'];
include_once("lang/{$g['lang']}.php");

function __autoload($name)
{
    require_once("modules/{$name}/{$name}.class.php");
}

// loading setting variables
__autoload('settings');
settings::get_all();

__autoload('users');
$g['user']   = new users();

// set ajax
//$g['ajax'] = isset($_SERVER["HTTP_AJAX_REQUEST"]) ? true : false;
