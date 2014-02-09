<?php

//-----------------------------------------------------------------------------

namespace mycms;

//-----------------------------------------------------------------------------

if(!isset($_GET['action']) && $g['user']->role == 'guest') $_GET['action'] = 'login';

//-----------------------------------------------------------------------------

switch($_GET['action']){

//-----------------------------------------------------------------------------

case 'login':
    if(isset($_POST['login'])){
        var_dump($_COOKIE);
        //you wanna to stay logged in?
        $_POST['remember'] = false;

        $res = $g['user']->login($_POST['username'], $_POST['password'], $_POST['remember']);

        // if login is not successful
        if($res['count'] == 0){
            $g['error']->push(L_LOGIN_ERROR, 'error', true);
            system::redirect(system::genlink('users/login'), true);
        }

        if($g['user']->role != 'guest'){
            //if($_SESSION['request']){
                //system::redirect(system::genlink($_SESSION['request']), true);
            //} else {
                system::redirect(system::genlink('pages/home'), true);
            //}
        }else{
            system::redirect(system::genlink('users/login'), true);
        }
    }
    $g['template'] = 'login';
    break;

//-----------------------------------------------------------------------------

case 'logout':

    $g['user']->logout();
    system::redirect($g['weburl']);
    break;

//-----------------------------------------------------------------------------

case 'edit_profile':

    if(isset($_POST['submit'])){
        if($_POST['password'] != $_POST['confirm_password']){
            $g['error']->push(L_SIGNUP_PASSWORD_ERROR, 'error');
        } else {
            // `id`, `password`, `fullname`, `email`
            $res = $g['user']->edit($_POST['id'], $_POST['password'], $_POST['fullname'],
                             $_POST['email']);

            if($res['error']){
               $g['error']->push($res['msg'], 'error');
            }else{
                $g['error']->push('به درستی تغییر داده شد', 'info');
            }
        }
    }
    $g['template'] = 'edit_profile';

    // user details & because we need updated version!
    $user = $g['user']->get_one_by_id();
    $g['smarty']->assign('cuser', $user['rows'][0]);

    break;

//-----------------------------------------------------------------------------

case 'list':
    if(!acl::has(__FILE__, $_GET['action'])) break;

    if(!isset($_GET['page'])){
        $_GET['page'] = 1;
    }

    if(isset($_GET['filter'])){
        $users = $g['user']->get_one_filtered_page($_GET['filter'], $_GET['page'], $g['count']);
        $max_p = $g['user']->get_max_filtered_pages($_GET['filter'], $g['count']);
    } else {
        //if($g['user']->role == 'admin'){
            $users = $g['user']->get_one_page($_GET['page'], $g['count']);
            $max_p = $g['user']->get_max_pages($g['count']);
        //} else {
        //    $users = $g['user']->get_adviser_users($_GET['page'], $g['count']);
        //    $max_p = $g['user']->get_max_adviser_pages($g['count']);
        //}
    }

    $g['smarty']->assign('users', $users['rows']);
    $g['smarty']->assign('max_pages', $max_p);
    $g['template'] = 'list';
    break;

//-----------------------------------------------------------------------------

case 'add':
    if(!acl::has(__FILE__, $_GET['action'])) break;

    if(isset($_POST['submit'])){
        if($_POST['password'] != $_POST['confirm_password']){
            $g['error']->push(L_SIGNUP_PASSWORD_ERROR, 'error');
        } else {
            $g['user']->add($_POST['username'], $_POST['password'], $_POST['fullname'], $_POST['email'], $_POST['role']);
        }
    }

    $roles = $g['user']->get_roles();
    foreach($roles as $i => $r){
        if($r == 'admin' || $r == 'member') unset($roles[$i]);
    }

    $g['smarty']->assign('roles', $roles);

    $g['template'] = 'form';
    break;

//-----------------------------------------------------------------------------

case 'edit':
    if(!acl::has(__FILE__, $_GET['action'])) break;

    if(isset($_POST['submit'])){
        $res = $g['user']->admin_edit($_GET['id'], $_POST['username'], $_POST['password'],
                                      $_POST['fullname'], $_POST['email'], $_POST['role'], $_POST['status']);

        if($res['error']){
            $g['error']->push($res['msg'], 'error');
        }else{
            $g['error']->push('به درستی تغییر داده شد', 'info');
        }
    }

    $user = $g['user']->get_one_by_id($_GET['id']);
    $g['smarty']->assign('user', $user['rows'][0]);

    $yegans = $g['user']->get_yegans($user['rows'][0]['id']);
    $g['smarty']->assign('yegans', $yegans['rows']);

    $roles = $g['user']->get_roles();
    $g['smarty']->assign('roles', $roles);

    $statuses = $g['user']->get_statuses();
    $g['smarty']->assign('statuses', $statuses);

    $g['template'] = 'form';
    break;

//-----------------------------------------------------------------------------

case 'add-yegan':
    $g['user']->add_yegan($_POST['user_id'], $_POST['code_yegan']);
    die();
    break;

//-----------------------------------------------------------------------------

case 'remove-yegan':
    $g['user']->remove_yegan($_POST['user_id'], $_POST['code_yegan']);
    die();
    break;

//-----------------------------------------------------------------------------

}

//-----------------------------------------------------------------------------

