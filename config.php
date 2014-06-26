<?php

//-----------------------------------------------------------------------------

//namespace mycms;

//-----------------------------------------------------------------------------

if (!file_exists('config.ini')) {
    die('<b>Error</b>: <b>config.ini</b> not found!');
}

$config = parse_ini_file('config.ini',TRUE);

if ($config['PHP']['use_session']) {
    session_set_cookie_params(0, dirname($_SERVER['PHP_SELF']));
    session_start();
    ini_set('use_only_cookies', '1');
}
$g['session'] =& $_SESSION;

if (!isset($g['session']['user'])) {
    $g['session']['user'] = array(
        'id' => '',
        'is_admin' => false,
        'is_authenticated' => false);
}
$g['user'] =& $g['session']['user'];

ini_set('error_reporting', $config['PHP']['error_reporting']);
ini_set('display_errors', $config['PHP']['display_errors']);

// do not change timezone too much!!!
$g['timezone'] = $config['GLOBAL']['timezone'];
date_default_timezone_set($g['timezone']);

$g['runmode'] = $config['GLOBAL']['runmode'];

//-----------------------------------------------------------------------------

$time_start                   = microtime(true);
$g['total_q']                 = 0;
$g['host']                    = $config['GLOBAL']['host'];
$g['admin']                   = $config['GLOBAL']['admin'];
$g['default_lang']            = $config['GLOBAL']['default_lang'];
$g['rewrite']                 = true;
$g['homepage']                = $config['GLOBAL']['homepage'];
$g['auth_method']             = $config['GLOBAL']['auth_method'];
$g['trac_url']                = $config['GLOBAL']['trac_url'];

$g['fullpath']                = dirname(__FILE__) . '/';
$g['weburl']                  = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
$g['content']                 = array ();
$g['default_page_to_display'] = "templates/index.tpl";
$g['lang']                    = $g['default_lang'];
$g['DB_DataObject']           = $config['DB_DataObject'];

//-----------------------------------------------------------------------------

include_once("lang/{$g['lang']}.php");

//-----------------------------------------------------------------------------

// include all base classes
foreach(glob('classes/*.php') as $c){
    require_once($c);
}

$g['db']     = new mycms\db();
$g['error']  = new mycms\error();
$g['smarty'] = new mysmarty();

//-----------------------------------------------------------------------------

switch ($g['auth_method']) {
    case 'cas':
        $g['cas'] = $config['CAS'];
        $g['cas']['cas_server_port']     = (int)($g['cas']['cas_server_port']);
        $g['cas']['cas_server_version']  = CAS_VERSION_1_0; //TODO: $g['cas']['cas_server_version'];
        break;

    default:
        break;
}

//-----------------------------------------------------------------------------

$g['urls'] = array (
    '^admin(/.*|)' => 'admin',
    '^ajax(/.*|)'  => 'ajax',
    '^.*'          => 'pages');

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

mycms\system::interpret_params();

$g['ajax'] = isset($_SERVER["HTTP_AJAX_REQUEST"]) ? true : false;

//-----------------------------------------------------------------------------

//function __autoload($name)
//{
//    require_once("modules/{$name}/{$name}.class.php");
//}

// loading setting variables
require_once("modules/settings/settings.class.php");
//__autoload('settings');
mycms\settings::get_all();

//  require_once("modules/users/users.class.php");
//__autoload('users');
$g['auth'] = new mycms\auth();
$g['auth']->init();

if (!$g['session']['user']['is_authenticated']) {
    if ($g['auth']->is_authenticated()) {
        $g['session']['user']['is_authenticated'] = true;
        $g['session']['user']['id'] = $g['auth']->get_user_id();
        $g['session']['user']['is_admin'] = $g['auth']->is_admin($g['session']['user']['id']);
    }
}

//-----------------------------------------------------------------------------

