<?php

require_once('config.php');

if ('debug' == $g['runmode']) {
	//$g['error']->push($_GET);
	//$g['error']->push($_POST);
}

// if no module option, show dashboard!
if(!isset($_GET['module']) || $_GET['module'] == ''){
    $_GET['module'] = 'pages';
}


//if($g['user']->role == 'guest'){
//    if(isset($_REQUEST['params']) && $_REQUEST['params'] != 'users/login'){
//        $_SESSION['request'] = $_REQUEST['params'];
//    }
//} else {
//    // user details
//    $user = $g['user']->get_one_by_id();
//    $g['smarty']->assign('cuser', $user['rows'][0]);
//}

// find and include requested action/page
if(is_file("modules/{$_GET['module']}/{$_GET['module']}.php")){
    require_once("modules/{$_GET['module']}/{$_GET['module']}.php");
}else{
    $g['error']->push(L_NO_PAGE, 'error');
    $g['template'] = 'empty';
}

//error handling
$g['smarty']->assign('error', $g['error']->get_all());

// finalize template
$g['smarty']->assign('g', $g);
$g['smarty']->assign('weburl', $g['weburl']);
$g['smarty']->assign('role', $g['user']->role);
$g['smarty']->assign('module', $_GET['module']);

if(isset($_GET['action'])) $g['smarty']->assign('action', $_GET['action']);
if(!isset($g['template'])) $g['template'] = $_GET['action'];
$g['smarty']->assign('template', $g['template']);

// set statistics variables
$g['smarty']->assign('exec_time', number_format(microtime(true) - $time_start, 2));
$g['smarty']->assign('query_qty', $g['total_q']);
$g['smarty']->assign('max_mem', number_format(memory_get_peak_usage() / 1048576, 2));
$g['smarty']->assign('year', date("Y"));

$g['smarty']->display($g['default_page_to_display']);
