<?php

//-----------------------------------------------------------------------------

namespace mycms;
require_once 'DB.php';
require_once('admin.class.php');

//-----------------------------------------------------------------------------

global $g;

if (!$g['user']['is_authenticated'])
	$g['auth']->authenticate();

// This page is only loaded for authorized users
if (!$g['user']['is_admin']) {
	$g['error']->push('This account has no administration privilage', 'error', true);
	system::redirect(system::genlink(''));
}

if(!isset($_GET['content'])) $_GET['content'] = 'people';
$ct = strtolower($_GET['content']);
$g['template'] = $ct . '_admin_create';
$err = false;

// Set main menu options
$menu = array(
	array('name' => 'people',       'url' => 'admin/people',       ),
	array('name' => 'research',     'url' => 'admin/research',     ),
	array('name' => 'publication',  'url' => 'admin/publication',  ),
);
$g['smarty']->assign('menu', $menu);

// Set secondary menu options
$menu = array(
	array('name' => 'trac',       'url' => 'https://papyrus.usask.ca/trac/hci/',  ),
	array('name' => 'logout',     'url' => 'logout', 'user_id' => $g['user']['id'],     ),
);
$g['smarty']->assign('menu_2', $menu);

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

function validate ($type, &$v) {
	global $g;
	$val      = trim($v);
	$format = '';

	if (false !== strpos($type, ':')) {
		$format = substr($type, strpos($type, ':') + 1);
		$type   = substr($type, 0, strpos($type, ':'));
	}

	switch ($type) {
	  case 'int': {
	  	if (is_int($val))
	  		$val = intval($val);
		$format = preg_split("/,/", $format);
		if ((count($format) >= 1 && $val < $format[0]) ||
			(count($format) >= 2 && $val > $format[1]))
			$val = false;
		break;
	} case 'alphabetic': {
		break;
	} case 'alphabetic-utf8': {
		break;
	} case 'string': {
		break;
	} case 'email': {
		break;
	} case 'enum': {
		// todo: '' should be allowed only for null allowed fields
		$format = preg_split("/,/", $format);
		if (!in_array(strtolower($val), $format) && '' !== $val)
			$val = false;
		break;
	} case 'date': {
		$val = date($format, strtotime($val));
		break;
	} default:
		$val = false;
		break;
	} //switch

	if (false === $val) {
			return false;
	} else {
		$v = $val;
		return true;
	}
}

//-----------------------------------------------------------------------------

function make_thumb($src, $dest, $desired_width) {

	/* read the source image */
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);

	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));

	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image, $dest);
}

//-----------------------------------------------------------------------------

function remove_file($ct, $id, $type) {
	global $g;
	$db = $g['db'];
	$r = $db->query(
		"SELECT t.{$type}_id, t.{$type}_filename FROM !!!$type AS t WHERE t.{$type}_id IN
		(SELECT {$type}_id FROM !!!{$type}_$ct AS _ct WHERE _ct.{$ct}_id = $id)"
	);
	if (!$r['error'] && $r['count'] > 0) {
		$resrc = $g['content'][$type];
		foreach ($r['rows'] as $v) {
			if (file_exists("files/$ct/$type/" . $v["{$type}_filename"]))
				unlink("files/$ct/$type/" . $v["{$type}_filename"]);
			if ('image' == $type && file_exists("files/$ct/$type/thumb/" . $v["{$type}_filename"]))
				unlink("files/$ct/$type/thumb/" . $v["{$type}_filename"]);
			$resrc->get($v["{$type}_id"]);
			$resrc->delete();
		}
	}
}

//-----------------------------------------------------------------------------

function save_file ($ct, $ct_id, $file, $type) {
	if (empty($file['name']))
		return;

	global $g;
	$db_type = $g['content'][$type];
	$ext = end(explode('.', $file['name']));

	if (in_array($file["type"], $db_type->mime) &&
		in_array($ext, $db_type->ext) &&
		$file["size"] < $db_type->max_size) {

		if ($file["error"] > 0) {
			$g['error']->push($file["error"], 'error');
		} else {
			$type_filename = $type . '_filename';
			$db_type->$type_filename = "temp.$ext";
			$id = $db_type->insert();

			if (!file_exists("files/$ct"))
				mkdir("files/$ct");

			if (!file_exists("files/$ct/$type"))
				mkdir("files/$ct/$type");

			move_uploaded_file($file["tmp_name"], "files/$ct/$type/$id.$ext");

			$g['db']->query("UPDATE !!!$type SET {$type}_filename = '$id.$ext' WHERE {$type}_id = $id");
			$g['db']->query("INSERT INTO !!!{$type}_$ct ({$type}_id , {$ct}_id) VALUES ($id,  $ct_id)");

			if ($type == 'image') {
				if (!file_exists("files/$ct/$type/thumb"))
					mkdir("files/$ct/$type/thumb");
				make_thumb("files/$ct/$type/$id.$ext", "files/$ct/$type/thumb/$id.$ext", 40);
			}
		}
	} else {
		$g['error']->push("{$file['name']} is not of supported types or exceeds max allowed size", 'error');
	}
}

//-----------------------------------------------------------------------------

function remove($ct, $id) {
	global $g;
	$db = $g['db'];
	$ctdb = $g['content'][$ct];
	foreach ($ctdb->references as $v) {
		if ($v == 'image' || $v == 'video' || $v == 'doc')
			remove_file($ct, $id, $v);
	}
	$i = $ctdb->get($id);

	if ($i == 0)
		return false;

	$ctdb->delete();
	return true;
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

else if (checkparams(array(
	'operation' => 'create'))) {
	foreach ($_POST as $k => $v) {
		if (property_exists("\\mycms\\$ct", $k)) {
			if (validate($content->field_type[$k], $v))
				$content->$k = $v;
			else
				$g['error']->push("worng format($k ". $content->field_type[$k] . ") at $v");
		}
	}
	$id = $content->insert();
	$g['error']->push("$ct updated successfully.");

	if (!empty($_FILES['image']['name'])) {
		save_file($ct, $id, $_FILES['image'], 'image');
	}

	if (!empty($_FILES['video']['name'])) {
		save_file($ct, $id, $_FILES['video'], 'video');
	}

	if (!empty($_FILES['doc']['name'])) {
		save_file($ct, $id, $_FILES['doc'], 'doc');
	}


	$r = $content->view('default', "$ct.{$ct}_id = $id");
	if (!$r['error'] && $r['count'] > 0) {
		$g['smarty']->assign($ct, $r['rows'][0]);
		$g['template'] = $ct . '_admin_edit';
	} else {
		$g['error']->push("No $ct found with id " . $id, 'error');
	}

	$g['template'] = $ct . '_admin_edit';
}

//-----------------------------------------------------------------------------

else if (checkparams(array(
	'operation' => 'remove',
	'_isset'    => array('id')))) {
	$id = $_GET['id'];

	if (remove($ct, $id)) {
		$g['error']->push("1 $ct removed successfully");
	} else {
		$g['error']->push("No $ct found with id " . $id, 'error');
	}

	$g['template'] = $ct . '_admin_create';
}

//-----------------------------------------------------------------------------

else if (checkparams(array(
	'operation' => 'edit',
	'_isset'    => array('id')))) {
	$r = $content->get($_GET['id']);
	if ($r == 1) {
		foreach ($_POST as $k => $v) {
			if (property_exists("\\mycms\\$ct", $k)) {
				if (validate($content->field_type[$k], $v))
					$content->$k = $v;
				else
					$g['error']->push("worng format($k ". $content->field_type[$k] . ") at $v");
			}
		}

		// publication_toappear checkbox is only sent if it is checked
		if (!array_key_exists("publication_toappear", $_POST))
			$content->publication_toappear = 0;
		else
			$content->publication_toappear = 1;

		//$r = $content->validate();
		//if (true !== $r) {
		//	ob_start();
		//	var_dump($r);
		//	$r = ob_get_clean();
		//	$g['error']->push("An error occured while trying to update database $r");
		//} else
		{
			$res = $content->update();
			if (false === $res)
				$g['error']->push("An error occured while trying to update a $ct.", 'error');
			else
				$g['error']->push("$ct updated successfully.");
		}

		if (!empty($_FILES['image']['name'])) {
			remove_file($ct, $_GET['id'], 'image');
			save_file($ct, $_GET['id'], $_FILES['image'], 'image');
		}

		if (!empty($_FILES['video']['name'])) {
			remove_file($ct, $_GET['id'], 'video');
			save_file($ct, $_GET['id'], $_FILES['video'], 'video');
		}

		if (!empty($_FILES['doc']['name'])) {
			remove_file($ct, $_GET['id'], 'doc');
			save_file($ct, $_GET['id'], $_FILES['doc'], 'doc');
		}

		$id = $_GET['id'];
		$r = $g['content'][$ct]->view('default', "$ct.{$ct}_id = $id");
		if (!$r['error'] && $r['count'] > 0) {
			$g['smarty']->assign($ct, $r['rows'][0]);
			$g['template'] = $ct . '_admin_edit';
		} else {
			$g['error']->push("No $ct found with id " . $id, 'error');
		}
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $_GET['id'], 'error');
	}
}

//-----------------------------------------------------------------------------

else if (checkparams(array(
	'operation' => 'view',
	'_isset'    => array('id')))) {
	$id = $_GET['id'];
	$r = $g['content'][$ct]->view('default', "$ct.{$ct}_id = $id");
	if (!$r['error'] && $r['count'] > 0) {
		$g['smarty']->assign($ct, $r['rows'][0]);
		$g['template'] = $ct . '_admin_edit';
	} else {
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
	$g['smarty']->assign('selectedmenu', $ct);
}

//-----------------------------------------------------------------------------
