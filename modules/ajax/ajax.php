<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once 'DB.php';
require_once('ajax.class.php');

//-----------------------------------------------------------------------------

global $g;
if(!isset($_GET['operation'])) $_GET['operation'] = 'get';
$op  = $_GET['operation'];

$params = false;
if(isset($_POST['params']))
	$params = json_decode($_POST['params']);

$res = [
	'status' => 'success',
];

//-----------------------------------------------------------------------------

if ('get' == $op && $params) {
	$db = $g['content'][$params->content];

	$fields = explode('\s', $db->title_format);
	$w = '';
	foreach ($fields as $f) {
		if (!empty($w))
			$w .= ' OR ';
		$w .= " $f LIKE '%" . $db->escape($params->name) . "%' ";
	}

	$r = $db->view($display = 'teaser', $w, '', $limit = '0,5', $get_referenced_data = true);
	$g['smarty']->assign('research', $r);
	$res['count'] = $r['count'];
	$res['html'] = $g['smarty']->fetch('templates/snippets/research_tiny_list.tpl');
}

//-----------------------------------------------------------------------------

//else if () {
//
//}

//-----------------------------------------------------------------------------

echo json_encode($res);
exit();