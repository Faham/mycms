<?php

//-----------------------------------------------------------------------------

set_time_limit(0);

//-----------------------------------------------------------------------------

require_once('config.php');

//-----------------------------------------------------------------------------

if(!isset($_GET['module']) || $_GET['module'] == '') {
    $_GET['module'] = 'pages';
}

if(is_file("modules/{$_GET['module']}/{$_GET['module']}.php")) {
    require_once("modules/{$_GET['module']}/{$_GET['module']}.php");
}

if(!isset($g['template'])) {
    $g['error']->push(L_NO_PAGE, 'error');
    $g['template'] = 'empty';
}

//-----------------------------------------------------------------------------

$g['smarty']->assign('g'         , $g);
$g['smarty']->assign('error'     , $g['error'    ]->get_all());
$g['smarty']->assign('user'      , $g['user'     ]);
$g['smarty']->assign('weburl'    , $g['weburl'   ]);
$g['smarty']->assign('template'  , $g['template' ]);
$g['smarty']->assign('query_qty' , $g['total_q'  ]);
$g['smarty']->assign('module'    , $_GET['module']);
$g['smarty']->assign('year'      , date("Y"));
$g['smarty']->assign('max_mem'   , number_format(memory_get_peak_usage() / 1048576, 2));
$g['smarty']->assign('exec_time' , number_format(microtime(true) - $time_start, 2));

//-----------------------------------------------------------------------------

$g['smarty']->display($g['default_page_to_display']);

//-----------------------------------------------------------------------------
