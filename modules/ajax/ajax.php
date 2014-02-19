<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once 'DB.php';
require_once('ajax.class.php');

//-----------------------------------------------------------------------------

define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');

if (!IS_AJAX)
	return;

//-----------------------------------------------------------------------------

global $g;
if(!isset($_GET['operation'])) $_GET['operation'] = 'get';
$op  = $_GET['operation'];

$params = false;
if(isset($_POST['params']))
	$params = json_decode($_POST['params']);

$res = array(
	'status' => 'success', // success, error
	'message' => ''
);

//-----------------------------------------------------------------------------

if ('get' == $op && $params) {
	$ct = $params->content;
	$db = $g['content'][$ct];
	$w = '';

	if (isset($params->name)) {
		$delim = ' ';
		$fields = array();

		if (strpos($db->title_format, $delim) !== FALSE)
			$fields = explode($delim, $db->title_format);
		else
			$fields[] = $db->title_format;

		foreach ($fields as $f) {
			if (!empty($w))
				$w .= ' OR ';
			$w .= " $f LIKE '%" . $db->escape($params->name) . "%' ";
		}

	} else if (isset($params->id)) {
		$w .= " {$ct}_id = {$params->id} ";
	}


	$r = $db->view($display = 'teaser', $w, '', $limit = '0,5', $get_referenced_data = true);
	$res['count'] = $r['count'];
	$res['html'] = '';

	if (isset($params->list) && $params->list) {
		$g['smarty']->assign($ct, $r);
		$res['html'] = $g['smarty']->fetch("templates/snippets/{$ct}_{$params->display}_list.tpl");
	} else if ($res['count'] > 0) {
		$g['smarty']->assign($ct, $r['rows'][0]);
		$res['html'] = $g['smarty']->fetch("templates/snippets/{$ct}_{$params->display}.tpl");
	}

}

//-----------------------------------------------------------------------------

else if ('refer'  == $op && $params) {
	$db = $g['db'];
	$q = "INSERT INTO !!!{$params->referer_type}_{$params->referred_type}
		({$params->referer_type}_id, {$params->referred_type}_id )
		VALUES ({$params->referer_id},  {$params->referred_id})";
	$r = $db->query($q);
	if ($r['error']) {
		// made no change to references
		$res['status'] = 'error';
		$res['message'] = $r['message'];
	}
}

//-----------------------------------------------------------------------------

echo json_encode($res);
exit();