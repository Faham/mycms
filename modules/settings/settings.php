<?php

//-----------------------------------------------------------------------------

namespace mycms;

//-----------------------------------------------------------------------------

if(!isset($_GET['action'])) $_GET['action'] = 'list';

//-----------------------------------------------------------------------------

switch($_GET['action']){

//-----------------------------------------------------------------------------

case 'list':
    if(!acl::has(__FILE__, $_GET['action'])) break;

    if(isset($_POST['submit'])){
        $settings = '';
        foreach($_POST['var'] as $var => $val){
            $dummy = array('var'=>$var, 'val'=>$val);
            $settings[] = $dummy;
        }

        settings::set_all($settings);
    }

    $settings = settings::get_all();
    $settings = $settings['rows'];

    for($i=0; isset($settings[$i]); $i++){
        if($settings[$i]['var'] == 'last_index') unset($settings[$i]);
        else {
            $settings[$i]['hvar'] = str_replace('_', ' ', $settings[$i]['var']);
            $settings[$i]['hvar'] = ucwords($settings[$i]['hvar']);
        }
    }
    $g['smarty']->assign('settings', $settings);
    $g['template'] = 'form';
    break;
}

//-----------------------------------------------------------------------------
