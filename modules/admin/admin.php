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
//-----------------------------------------------------------------------------

// Set main menu options
$menu = array(
	array('name' => 'people',       'url' => 'admin/people',       ),
	array('name' => 'research',     'url' => 'admin/research',     ),
	array('name' => 'publication',  'url' => 'admin/publication',  ),
);
$g['smarty']->assign('menu', $menu);
// Set secondary menu options

// If trac_url is empty, let 
if($g['trac_url']){
	$trac_state = 'trac';
}else{
	$trac_state = null;
}
if(isset($_SESSION['username'])){
	$username = $_SESSION['username'];
}else{
	$username = null;
}
$menu = array(
	array('name' => $trac_state,       'url' => $trac_state,  ),
	//array('name' => 'logout',     'url' => 'logout', 'user_id' => $g['user']['id'],     ),
	array('name' => 'logout',     'url' => 'logout', 'user_id' => $username,     ),
);
$g['smarty']->assign('menu_2', $menu);

//-----------------------------------------------------------------------------
// if $g['auth_method'] = native, show password option in people pages.
if($g['auth_method']!='native'){
	?><style type="text/css">#people_password-container{
		display:none;
	}</style>
	<style type="text/css">#people_repassword-container{
		display:none;
	}</style>
	<?php
}

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
	  	//*********
	  	//This part of codes I tried to comment them then the priority part in research admin interface would work.
	  	//Harvey
		/*$format = preg_split("/,/", $format);
		if ((count($format) >= 1 && $val < $format[0]) ||
			(count($format) >= 2 && $val > $format[1]))
			$val = false;*/
		//*********
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
	} case 'password': {
		if($val!=='')
		{
			/*if(strlen($val) < 6 || strlen($val) > 32)
				$val = false;
			else*/
			$val = MD5($val);
		}
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
//new function for making thumbnail of jpg, png and gif file
//@param file - the source file of image
//@param savePath - the path that the thumb is going to be save
function thumbImage($file, $savePath='',$isCut=true,$quality=100){
	$result=array('status'=>0,'file'=>'','width'=>0,'height'=>0,'savePath'=>'','info'=>'');
	if(!file_exists($file)){
		return array('status'=>1,'file'=>'','width'=>0,'height'=>0,'savePath'=>'','info'=>'图片文件不存在');
	}
	//check file type
	$fp=fopen($file,'rb');
	$str=fread($fp,2); //read 2 bytes
	fclose($fp);
	$str=@unpack("c2chars",$str);
	$typeCode=intval($str['chars1'].$str['chars2']);
	$fileType='';
	switch($typeCode){
		case 255216:
			$fileType='jpg';
		break;
		case 7173:
			$fileType='gif';
		break;
		case 13780:
			$fileType='png';
		break;
		default:
			$fileType=$typeCode;
		break;
	}
	if($str['chars1']=='-1'&&$str['chars2']=='-40'){
		$fileType='jpg';
	}
	if($str['chars1']=='-119'&&$str['chars2']=='80'){
		$fileType='png';
	}
	//if(!in_array($fileType,array('jpg','gif','bmp','png'))){
	if(!in_array($fileType,array('jpg','gif', 'png'))){
		return array('status'=>2,'file'=>'','width'=>0,'height'=>0,'savePath'=>'','info'=>'Wrong file type'.$fileType);
	}
	//make thumb from different types of images
	if($fileType=='jpg'){
		$im=imagecreatefromjpeg($file);
	}
	if($fileType=='gif'){
		$im=imagecreatefromgif($file);
	}
	if($fileType=='png'){
		$im=imagecreatefrompng($file);
	}
	//set up width and height of thumb
	$thumbWidth=100;
	$thumbHeight=100;
	if(empty($savePath))
		$savePath=$file;
	$width=imagesx($im);
	$height=imagesy($im);
	if($width<$thumbWidth&&$height<$thumbHeight){
		return array('status'=>3,'file'=>'','width'=>0,'height'=>0,'savePath'=>'','info'=>'The size of image is smaller than thumbnail\'s.');
	}

	$ratio=$width/$height;//real ratio of image
	$thumbRatio=$thumbWidth/$thumbHeight;//changed ratio
	if($isCut){//doing the cutting
		if(function_exists('imagepng')&&(str_replace('.','',PHP_VERSION)>=512)){
			$quality=9;
		}
		if($ratio>=$thumbRatio){//hight advance
			$newimg=imagecreatetruecolor($thumbWidth,$thumbHeight);
			imagecopyresampled($newimg,$im,0,0,0,0,$thumbWidth,$thumbHeight,(($height)*$thumbRatio),$height);
			imagejpeg($newimg,$savePath,$quality);
		}
		if($ratio<$thumbRatio){//width advance
			$newimg=imagecreatetruecolor($thumbWidth,$thumbHeight);
			imagecopyresampled($newimg,$im,0,0,0,0,$thumbWidth,$thumbHeight,$width,(($width)/$thumbRatio));
			imagejpeg($newimg,$savePath,$quality);
		}
	}else{//uncutting
		if($ratio>=$thumbRatio){
			$newimg=imagecreatetruecolor($thumbWidth,($thumbWidth)/$ratio);
			imagecopyresampled($newimg,$im,0,0,0,0,$thumbWidth,($thumbWidth)/$ratio,$width,$height);
			imagejpeg($newimg,$savePath,$quality);
		}
		if($ratio<$thumbRatio){
			$newimg=imagecreatetruecolor(($thumbHeight)*$ratio,$thumbHeight);
			imagecopyresampled($newimg,$im,0,0,0,0,($thumbHeight)*$ratio,$thumbHeight,$width,$height);
			imagejpeg($newimg,$savePath,$quality);
		}
	}
	ImageDestroy($im);
	return array('status'=>0,'file'=>$file,'width'=>$thumbWidth,'height'=>$thumbHeight,'savePath'=>$savePath,'info'=>'Successfully get thumbnail.');
}

//-----------------------------------------------------------------------------

//remove all files of this type, by filename
function remove_file($ct, $id, $type) {
	global $g;
	$db = $g['db'];
	$r = $db->query(//get filename of the file which belongs to content' id is $id
		"SELECT t.{$type}_id, t.{$type}_filename FROM !!!$type AS t WHERE t.{$type}_id IN
		(SELECT {$type}_id FROM !!!{$type}_$ct AS _ct WHERE _ct.{$ct}_id = $id)"
	);
	if (!$r['error'] && $r['count'] > 0) {
		$resrc = $g['content'][$type];
		foreach ($r['rows'] as $v) {
			if (file_exists("files/$ct/$type/" . $v["{$type}_filename"]))
				unlink("files/$ct/$type/" . $v["{$type}_filename"]);//delete this file if it is existed
			if ('image' == $type && file_exists("files/$ct/$type/thumb/" . $v["{$type}_filename"]))
				unlink("files/$ct/$type/thumb/" . $v["{$type}_filename"]);//delete thumb if it is existed
			$resrc->get($v["{$type}_id"]);//get mysql's record of this file
			$resrc->delete();//delete it
		}
	}
}
//*********************************************************************************
//remove specific file
function remove_file_specific($ct, $id, $type) {
	global $g;
	$db = $g['db'];
	$r = $db->query(
		"SELECT t.{$type}_id, t.{$type}_filename FROM !!!$type AS t WHERE t.{$type}_id={$id}"
	);
	if (!$r['error'] && $r['count'] > 0) {
		$resrc = $g['content'][$type];
		//delete files loop
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
//*********************************************************************************
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
			//in mysql, each table of file type has type_id, and type_filename fields
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

function save_file_multiple ($ct, $ct_id, $filename, $fileTmpName, $fileType, $fileError, $fileSize, $type) {
	//if (empty($file['name']))
	if (empty($filename))
		return;

	global $g;
	$db_type = $g['content'][$type];
	//$ext = end(explode('.', $file['name']));
	$ext = end(explode('.', $filename));

	//if (in_array($file["type"], $db_type->mime) &&
	if (in_array($fileType, $db_type->mime) &&
		in_array($ext, $db_type->ext) &&
		//$file["size"] < $db_type->max_size) {
		$fileSize < $db_type->max_size) {

		//if ($file["error"] > 0) {
		if ($fileError > 0) {
			//$g['error']->push($file["error"], 'error');
			$g['error']->push($fileError, 'error');
		} else {
			$type_filename = $type . '_filename';
			$db_type->$type_filename = "temp.$ext";
			$id = $db_type->insert();

			if (!file_exists("files/$ct"))
				mkdir("files/$ct");

			if (!file_exists("files/$ct/$type"))
				mkdir("files/$ct/$type");

			//move_uploaded_file($file["tmp_name"], "files/$ct/$type/$id.$ext");
			move_uploaded_file($fileTmpName, "files/$ct/$type/$id.$ext");
			//in mysql, each table of file type has type_id, and type_filename fields
			$g['db']->query("UPDATE !!!$type SET {$type}_filename = '$id.$ext' WHERE {$type}_id = $id");
			$g['db']->query("INSERT INTO !!!{$type}_$ct ({$type}_id , {$ct}_id) VALUES ($id,  $ct_id)");

			if ($type == 'image') {
				if (!file_exists("files/$ct/$type/thumb"))
					mkdir("files/$ct/$type/thumb");
				//make_thumb("files/$ct/$type/$id.$ext", "files/$ct/$type/thumb/$id.$ext", 40);
				thumbImage("files/$ct/$type/$id.$ext", "files/$ct/$type/thumb/$id.$ext");
			}
		}
	} else {
		//$g['error']->push("{$file['name']} is not of supported types or exceeds max allowed size", 'error');
		$g['error']->push("{$filename} is not of supported types or exceeds max allowed size", 'error');
	}
}
//-----------------------------------------------------------------------------

function remove($ct, $id) {
	global $g;
	$db = $g['db'];
	$ctdb = $g['content'][$ct];
	foreach ($ctdb->references as $v) {
		//echo $v;
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
//******************************************
//remove image whose id is $id
function removeImage($ct, $id) {
	global $g;
	$db = $g['db'];
	$ctdb = $g['content'][$ct];
	foreach ($ctdb->references as $v) {//$v - validate type
		//echo $v;
		if ($v == 'image')
			remove_file_specific($ct, $id, $v);
	}
	return true;
}
//remove video whose id is $id
function removeVideo($ct, $id) {
	global $g;
	$db = $g['db'];
	$ctdb = $g['content'][$ct];
	foreach ($ctdb->references as $v) {//$v - validate type
		//echo $v;
		if ($v == 'video')
			remove_file_specific($ct, $id, $v);
	}
	return true;
}
//remove document whose id is $id
function removeDoc($ct, $id) {
	global $g;
	$db = $g['db'];
	$ctdb = $g['content'][$ct];
	foreach ($ctdb->references as $v) {//$v - validate type
		//echo $v;
		if ($v == 'doc')
			remove_file_specific($ct, $id, $v);
	}
	return true;
}
//-----------------------------------------------------------------------------
//This part is added on for remove image/video/document feature.  --Harvey
//remove image from edit interface
//@param id - content id
function removeAllImages($ct, $id) {
	global $g;
	$db = $g['db'];
	$ctdb = $g['content'][$ct];
	foreach ($ctdb->references as $v) {//$v - validate type
		//echo $v;
		if ($v == 'image')
			remove_file($ct, $id, $v);
	}
	return true;
}
//remove video from edit interface
function removeAllVideos($ct, $id) {
	global $g;
	$db = $g['db'];
	$ctdb = $g['content'][$ct];
	foreach ($ctdb->references as $v) {//$v - validate type
		//echo $v;
		if ($v == 'video')
			remove_file($ct, $id, $v);
	}
	return true;
}
//remove doc from edit interface
function removeAllDocs($ct, $id) {
	global $g;
	$db = $g['db'];
	$ctdb = $g['content'][$ct];
	foreach ($ctdb->references as $v) {//$v - validate type
		//echo $v;
		if ($v == 'doc')
			remove_file($ct, $id, $v);
	}
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
// @param $ct - people/research/publication
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
	//add multiple image
	if(isset($_FILES['image'])){
		$name_array = $_FILES['image']['name'];
		$tmp_name_array = $_FILES['image']['tmp_name'];
		$type_array = $_FILES['image']['type'];
		$size_array = $_FILES['image']['size'];
		$error_array = $_FILES['image']['error'];
		for($i = 0; $i < count($type_array); $i++){
			save_file_multiple($ct, $id, $name_array[$i], $tmp_name_array[$i], $type_array[$i], $error_array[$i], $size_array[$i], 'image');
		}
	}
	//add multiple video
	if(isset($_FILES['video'])){
		$name_array = $_FILES['video']['name'];
		$tmp_name_array = $_FILES['video']['tmp_name'];
		$type_array = $_FILES['video']['type'];
		$size_array = $_FILES['video']['size'];
		$error_array = $_FILES['video']['error'];
		for($i = 0; $i < count($type_array); $i++){
			save_file_multiple($ct, $id, $name_array[$i], $tmp_name_array[$i], $type_array[$i], $error_array[$i], $size_array[$i], 'video');
		}
	}
	//add multiple doc
	if(isset($_FILES['doc'])){
		$name_array = $_FILES['doc']['name'];
		$tmp_name_array = $_FILES['doc']['tmp_name'];
		$type_array = $_FILES['doc']['type'];
		$size_array = $_FILES['doc']['size'];
		$error_array = $_FILES['doc']['error'];
		for($i = 0; $i < count($type_array); $i++){
			save_file_multiple($ct, $id, $name_array[$i], $tmp_name_array[$i], $type_array[$i], $error_array[$i], $size_array[$i], 'doc');
		}
	}
	/*if (!empty($_FILES['image']['name'])) {
		save_file($ct, $id, $_FILES['image'], 'image');
	}*/

	/*if (!empty($_FILES['video']['name'])) {
		save_file($ct, $id, $_FILES['video'], 'video');
	}*/

	/*if (!empty($_FILES['doc']['name'])) {
		save_file($ct, $id, $_FILES['doc'], 'doc');
	}*/


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

//************************************************************************************
//This parts are modified from functions of operation remove and edit. --Harvey
//remove all image
//@param id - id of people/research/publication
else if (checkparams(array(
	'operation' => 'removeAllImages',
	'_isset'    => array('id')))) {
	$id = $_GET['id'];

	if (removeAllImages($ct, $id)) {
		$g['error']->push("Image of 1 $ct removed successfully");
	} else {
		$g['error']->push("No $ct found with id " . $id, 'error');
	}
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

		{
			$res = $content->update();
			if (false === $res)
				$g['error']->push("An error occured while trying to update a $ct.", 'error');
			else
				$g['error']->push("$ct updated successfully.");
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

//remove all video
else if (checkparams(array(
	'operation' => 'removeAllVideos',
	'_isset'    => array('id')))) {
	$id = $_GET['id'];

	if (removeAllVideos($ct, $id)) {
		$g['error']->push("Video of 1 $ct removed successfully");
	} else {
		$g['error']->push("No $ct found with id " . $id, 'error');
	}
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

		{
			$res = $content->update();
			if (false === $res)
				$g['error']->push("An error occured while trying to update a $ct.", 'error');
			else
				$g['error']->push("$ct updated successfully.");
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

//remove all document
else if (checkparams(array(
	'operation' => 'removeAllDocs',
	'_isset'    => array('id')))) {
	$id = $_GET['id'];

	if (removeAllDocs($ct, $id)) {
		$g['error']->push("Document of 1 $ct removed successfully");
	} else {
		$g['error']->push("No $ct found with id " . $id, 'error');
	}
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

		{
			$res = $content->update();
			if (false === $res)
				$g['error']->push("An error occured while trying to update a $ct.", 'error');
			else
				$g['error']->push("$ct updated successfully.");
		}

		$id = $_GET['id'];
		$r = $g['content'][$ct]->view('default', "$ct.{$ct}_id = $contentId");
		if (!$r['error'] && $r['count'] > 0) {
			$g['smarty']->assign($ct, $r['rows'][0]);
			$g['template'] = $ct . '_admin_edit';
		} else {
			$g['error']->push("No $ct found with id " . $contentId, 'error');
		}
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $contentId, 'error');
	}
}
//************************************************************************************
//remove specific image
else if (checkparams(array(
	'operation' => 'removeImage',
	'_isset'    => array('id')))) {
	$id = explode("--",$_GET['id'])[0];
	$contentId = explode("--",$_GET['id'])[1];
	//$filename = $_GET['name'];
	//echo $_GET['id'];
	//echo $id;
	//echo $researchId;

	if (removeImage($ct, $id)) {
		$g['error']->push("Image of 1 $ct removed successfully");
	} else {
		$g['error']->push("No $ct found with id " . $id, 'error');
	}
	$r = $content->get($contentId);
	if ($r == 1) {

		foreach ($_POST as $k => $v) {
			if (property_exists("\\mycms\\$ct", $k)) {
				if (validate($content->field_type[$k], $v))
					$content->$k = $v;
				else
					$g['error']->push("worng format($k ". $content->field_type[$k] . ") at $v");
			}
		}

		{
			$res = $content->update();
			if (false === $res)
				$g['error']->push("An error occured while trying to update a $ct.", 'error');
			else
				$g['error']->push("$ct updated successfully.");
		}

		$id = $_GET['id'];
		$r = $g['content'][$ct]->view('default', "$ct.{$ct}_id = $contentId");
		if (!$r['error'] && $r['count'] > 0) {
			$g['smarty']->assign($ct, $r['rows'][0]);
			$g['template'] = $ct . '_admin_edit';
		} else {
			$g['error']->push("No $ct found with id " . $contentId, 'error');
		}
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $contentId, 'error');
	}
}
//remove specific video
else if (checkparams(array(
	'operation' => 'removeVideo',
	'_isset'    => array('id')))) {
	$id = explode("--",$_GET['id'])[0];
	$contentId = explode("--",$_GET['id'])[1];
	//$filename = $_GET['name'];
	//echo $_GET['id'];
	//echo $id;
	//echo $researchId;

	if (removeVideo($ct, $id)) {
		$g['error']->push("Video of 1 $ct removed successfully");
	} else {
		$g['error']->push("No $ct found with id " . $id, 'error');
	}
	$r = $content->get($contentId);
	if ($r == 1) {

		foreach ($_POST as $k => $v) {
			if (property_exists("\\mycms\\$ct", $k)) {
				if (validate($content->field_type[$k], $v))
					$content->$k = $v;
				else
					$g['error']->push("worng format($k ". $content->field_type[$k] . ") at $v");
			}
		}

		{
			$res = $content->update();
			if (false === $res)
				$g['error']->push("An error occured while trying to update a $ct.", 'error');
			else
				$g['error']->push("$ct updated successfully.");
		}

		$id = $_GET['id'];
		$r = $g['content'][$ct]->view('default', "$ct.{$ct}_id = $contentId");
		if (!$r['error'] && $r['count'] > 0) {
			$g['smarty']->assign($ct, $r['rows'][0]);
			$g['template'] = $ct . '_admin_edit';
		} else {
			$g['error']->push("No $ct found with id " . $contentId, 'error');
		}
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $contentId, 'error');
	}
}
//remove specific document
else if (checkparams(array(
	'operation' => 'removeDoc',
	'_isset'    => array('id')))) {
	$id = explode("--",$_GET['id'])[0];
	$contentId = explode("--",$_GET['id'])[1];
	//$filename = $_GET['name'];
	//echo $_GET['id'];
	//echo $id;
	//echo $researchId;

	if (removeDoc($ct, $id)) {
		$g['error']->push("Document of 1 $ct removed successfully");
	} else {
		$g['error']->push("No $ct found with id " . $id, 'error');
	}
	$r = $content->get($contentId);
	if ($r == 1) {

		foreach ($_POST as $k => $v) {
			if (property_exists("\\mycms\\$ct", $k)) {
				if (validate($content->field_type[$k], $v))
					$content->$k = $v;
				else
					$g['error']->push("worng format($k ". $content->field_type[$k] . ") at $v");
			}
		}

		{
			$res = $content->update();
			if (false === $res)
				$g['error']->push("An error occured while trying to update a $ct.", 'error');
			else
				$g['error']->push("$ct updated successfully.");
		}

		$id = $_GET['id'];
		$r = $g['content'][$ct]->view('default', "$ct.{$ct}_id = $contentId");
		if (!$r['error'] && $r['count'] > 0) {
			$g['smarty']->assign($ct, $r['rows'][0]);
			$g['template'] = $ct . '_admin_edit';
		} else {
			$g['error']->push("No $ct found with id " . $contentId, 'error');
		}
	} else if ($r == 0) {
		$g['error']->push("No $ct found with id " . $contentId, 'error');
	}
}
//-----------------------------------------------------------------------------
//edit interface
else if (checkparams(array(
	'operation' => 'edit',
	'_isset'    => array('id')))) {
	$id = $_GET['id'];
	$r = $content->get($id);
	//$r=1;
	if ($r == 1) {
		foreach ($_POST as $k => $v) {
			if (property_exists("\\mycms\\$ct", $k)) {
				if (validate($content->field_type[$k], $v))
					$content->$k = $v;
				else
					$g['error']->push("worng format($k ". $content->field_type[$k] . ") at $v");
			}
		}
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
		//add multiple image
		if(isset($_FILES['image'])){
			$name_array = $_FILES['image']['name'];
			$tmp_name_array = $_FILES['image']['tmp_name'];
			$type_array = $_FILES['image']['type'];
			$size_array = $_FILES['image']['size'];
			$error_array = $_FILES['image']['error'];
			for($i = 0; $i < count($type_array); $i++){
				//save_file_image ($ct, $ct_id, $filename, $fileTmpName, $fileType, $fileError, $fileSize, $type)
				save_file_multiple($ct, $_GET['id'], $name_array[$i], $tmp_name_array[$i], $type_array[$i], $error_array[$i], $size_array[$i], 'image');
			}
		}
		//add multiple video
		if(isset($_FILES['video'])){
			$name_array = $_FILES['video']['name'];
			$tmp_name_array = $_FILES['video']['tmp_name'];
			$type_array = $_FILES['video']['type'];
			$size_array = $_FILES['video']['size'];
			$error_array = $_FILES['video']['error'];
			for($i = 0; $i < count($type_array); $i++){
				//save_file_image ($ct, $ct_id, $filename, $fileTmpName, $fileType, $fileError, $fileSize, $type)
				save_file_multiple($ct, $_GET['id'], $name_array[$i], $tmp_name_array[$i], $type_array[$i], $error_array[$i], $size_array[$i], 'video');
			}
		}
		//add multiple doc
		if(isset($_FILES['doc'])){
			$name_array = $_FILES['doc']['name'];
			$tmp_name_array = $_FILES['doc']['tmp_name'];
			$type_array = $_FILES['doc']['type'];
			$size_array = $_FILES['doc']['size'];
			$error_array = $_FILES['doc']['error'];
			for($i = 0; $i < count($type_array); $i++){
				//save_file_image ($ct, $ct_id, $filename, $fileTmpName, $fileType, $fileError, $fileSize, $type)
				save_file_multiple($ct, $_GET['id'], $name_array[$i], $tmp_name_array[$i], $type_array[$i], $error_array[$i], $size_array[$i], 'doc');
			}
		}
		/*if (!empty($_FILES['image']['name'])) {
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
		}*/

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
