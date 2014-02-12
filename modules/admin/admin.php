<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once 'DB.php';
require_once('admin.class.php');

//-----------------------------------------------------------------------------

global $g;
if(!isset($_GET['content'])) $_GET['content'] = 'people';
$g['template'] = 'people_admin';
$ct = strtolower($_GET['content']);
$err = false;

//-----------------------------------------------------------------------------

// Set main menu options
$menu = [
	['name' => 'People',       'url' => 'admin/people',       ],
	['name' => 'Research',     'url' => 'admin/research',     ],
	['name' => 'Publications', 'url' => 'admin/publication', ]
];
$g['smarty']->assign('menu', $menu);

//-----------------------------------------------------------------------------

function checkparams ($params) {
	if (array_key_exists('_isset', $params)) {
		foreach ($params['_isset'] as $v) {
			if (!isset($_GET[$v]))
				return false;
		}
		unset($params['_isset']);
	}

	foreach ($params as $k => $v) {
		if (!isset($_GET[$k]) || $_GET[$k] !== $v)
			return false;;
	}

	return true;
}

//-----------------------------------------------------------------------------

function validate ($v) {
	return trim($v);
}

//-----------------------------------------------------------------------------

$content = 'unknown';
if (array_key_exists($ct, $g['content'])) {
	$content = $g['content'][$ct];
}

//-----------------------------------------------------------------------------

if ('unknown' == $content) {
	$err = true;
	$g['error']->push("uknown content type: $ct", 'error');
}

//-----------------------------------------------------------------------------

else if (checkparams([
	'operation' => 'create'])) {
	$content->create();
}

//-----------------------------------------------------------------------------

else if (checkparams([
	'operation' => 'remove',
	'_isset'    => ['id']])) {
	$r = $content->get($_GET['id']);
	if ($r == 1) {
		$content->delete();
		$g['error']->push("1 $ct removed successfully");
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $_GET['id'], 'error');
	}
}

//-----------------------------------------------------------------------------

else if (checkparams([
	'operation' => 'edit',
	'_isset'    => ['id']])) {
	$r = $content->get($_GET['id']);
	if ($r == 1) {

		foreach ($_POST as $k => $v) {
			if (property_exists("\\mycms\\$ct", $k)) {
				$content->$k = validate($v);
			}
		}
		$content->update();
		$g['smarty']->assign($ct, $content);
		$g['error']->push("1 $ct updated successfully");
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $_GET['id'], 'error');
	}
}

//-----------------------------------------------------------------------------

else if (checkparams([
	'operation' => 'view',
	'_isset'    => ['id']])) {
	$r = $content->get($_GET['id']);
	if ($r == 1) {
		$g['smarty']->assign($ct, $content);
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $_GET['id'], 'error');
	}
}

//-----------------------------------------------------------------------------

if ($err) {
	$g['smarty']->assign('page', 'Error');
	$g['template'] = 'notfound';
}

//-----------------------------------------------------------------------------

else {
	$objs = $content->getall();
	$g['smarty']->assign('selectedmenu', $ct);
	$g['smarty']->assign($ct . '_list', $objs);
	$g['template'] = $ct . '_admin';
}

//-----------------------------------------------------------------------------
