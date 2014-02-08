<?php

// timezone
date_default_timezone_set('America/Regina');

// global constants
define("BASE_URL", "/");
define("BASE_DIR", "/hci/www/html/");
define("UPLOAD_DIR", BASE_DIR . "uploads/");

$nsid = strtolower($_SERVER['HTTP_CAS_USER']);

dbConnect();

$userQuery = mysql_query("SELECT * FROM `person` WHERE `NSID`='$nsid' AND `IsAdmin`='1' LIMIT 1");

if (@mysql_num_rows($userQuery) == 0)
{
	die("You are not authorized to view this page!");
}

// create a connection to the database
// print an error message in case of failure
function dbConnect()
{
	if (! @mysql_connect('localhost', 'webuser', 'B47FPNFZuqQTSm6Q'))
	{
		die('Database error! Try reloading the page...');
	}
	
	if (! @mysql_select_db('webdb'))
	{
		die('Database error! Try reloading the page...');
	}
	
	mysql_query("SET NAMES 'utf8'");
}

// redirect the user to a new page
function redirect($page)
{
	if (!headers_sent())
	{
		header("Location: " . $page);
	}
	else
	{
		echo "<script type=\"text/javascript\">\n";
		echo "window.location = \"$page\";\n";
		echo "</script>\n";
	}
	
	exit;
}

// modify text so it is safe to insert into the database
// also replace funky characters (eg. from Word or PDF documents) with web-safe ones
function sanitize($text)
{
	$text = trim($text);
	
	$text = str_replace("‘", "'", $text);
	$text = str_replace("’", "'", $text);
	$text = str_replace("–", "-", $text);  // n dash
	$text = str_replace("—", "--", $text); // m dash
	
	$text = mysql_real_escape_string($text);
	return $text;
}

// output a header
// the supplied integer parameter will add a "selected" effect to the menu
function outputHeader($selectedTab = 0)
{

global $nsid;

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	<title>Interaction Lab</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="../main.css" type="text/css" media="screen">
	<link rel="stylesheet" href="admin.css" type="text/css" media="screen">
	<!--[if lte IE 6]>
    	<link type="text/css" rel="stylesheet" href="<?php echo BASE_URL; ?>ie.css" media="all">
	<![endif]-->
	<link rel="shortcut icon" href="/favicon.ico">
	<script type="text/javascript" src="js/main.js"></script>
</head>
<body onload="doLoad()">
<div id="container">
	<div id="top"></div>
	<div id="header">
		<div id="logo">
			<a href="<?php echo BASE_URL; ?>admin/"></a>
		</div>
		<div id="graphic">
			
		</div>
	</div>
	
	<ul id="menu">
		<li><a href="people.php"<?php if ($selectedTab == 1){ echo " class=\"selected\""; } ?>>People</a></li>
		<li><a href="research.php"<?php if ($selectedTab == 2){ echo " class=\"selected\""; } ?>>Research</a></li>
		<li><a href="publications.php"<?php if ($selectedTab == 3){ echo " class=\"selected\""; } ?>>Publications</a></li>
		<?php
		
		if ($nsid == "cag047" || $nsid == "rlm412" || $nsid=="ssb609")
		{
		?>
			<li><a href="privileges.php"<?php if ($selectedTab == 4){ echo " class=\"selected\""; } ?>>Privileges</a></li>
			<!-- <li style="margin-left: 449px"><a href="logout.php">Logout</a></li> -->
		<?php
		}
		else
		{
		?>
			<!-- <li style="margin-left: 527px"><a href="logout.php">Logout</a></li> -->
		<?php
		}
		
		?>
	</ul>
	
	<div id="content">
<?php
}

// output a footer
function outputFooter()
{?>
	</div>
</div>
<div id="footer">
	<div id="innerfooter">&copy; <?php echo date("Y"); ?> The Interaction Lab, University of Saskatchewan</div>
</div>
</body>
</html>
<?php
}

// generate a shorter form of really long publication titles
function shortenTitle($title, $maxLen = 75)
{
	$maxTrim = 25;
	
	$title = trim($title);
	
	if (strlen($title) > $maxLen)
	{
		$title = substr($title, 0, $maxLen);
		
		// keep trimming characters until we end on a whole word
		while ((substr($title, -1) != " ") && (strlen($title) > ($maxLen - $maxTrim)))
		{
			$title = substr($title, 0, -1);
		}
		$title = trim($title) . "...";
	}
	
	return $title;
}

// use GD to create a smaller version of an image
function resizeImage($type, $origpath, $savepath, $newX, $newY)
{
	if ($type == "image/jpeg" || $type == "image/pjpeg")
	{
		$source = imagecreatefromjpeg($origpath);
	}
	else if ($type == "image/png")
	{
		$source = imagecreatefrompng($origpath);
	}
	else if ($type == "image/gif")
	{
		$source = imagecreatefromgif($origpath);
	}
	else if ($type == "image/bmp")
	{
		$source = imagecreatefromwbmp($origpath);
	}
	
	$oldX = imagesx($source);
	$oldY = imagesy($source);
	
	$xRatio = $oldX / $newX;
	$yRatio = $oldY / $newY;
	
	if ($xRatio > $yRatio)
	{
		if ($xRatio < 1)
		{
			$thumbX = $oldX;
			$thumbY = $oldY;
		}
		else
		{
			$thumbX = $newX;
			$thumbY = $oldY / $xRatio;
		}
	}
	else
	{
		if ($yRatio < 1)
		{
			$thumbX = $oldX;
			$thumbY = $oldY;
		}
		else
		{
			$thumbX = $oldX / $yRatio;
			$thumbY = $newY;
		}
	}
	
	$dest = imagecreatetruecolor($thumbX, $thumbY);
	imagecopyresampled($dest, $source, 0, 0, 0, 0, $thumbX, $thumbY, $oldX, $oldY);
	imagejpeg($dest, $savepath, 85);
	imagedestroy($dest);
	imagedestroy($source);
}

// use GD to generate a square thumbnail of an image
function thumbnail($type, $origpath, $savepath, $newX, $newY, $bw = false)
{
	if ($type == "image/jpeg" || $type == "image/pjpeg")
	{
		$source = imagecreatefromjpeg($origpath);
	}
	else if ($type == "image/png")
	{
		$source = imagecreatefrompng($origpath);
	}
	else if ($type == "image/gif")
	{
		$source = imagecreatefromgif($origpath);
	}
	
	$oldX = imagesx($source);
	$oldY = imagesy($source);
	
	$thumbX = 0;
	$thumbY = 0;
	
	if ($oldX > $oldY) // landscape
	{
		$sourceX = $oldY;
		$sourceY = $oldY;
		$thumbX = ($oldX - $oldY) / 2;
	}
	else if ($oldX <= $oldY) // portrait or square
	{
		$sourceX = $oldX;
		$sourceY = $oldX;
		$thumbY = ($oldY - $oldY) / 2;
	}
	
	$dest = imagecreatetruecolor($newX, $newY);
	imagecopyresampled($dest, $source, 0, 0, $thumbX, $thumbY, $newX, $newY, $sourceX, $sourceY);
	
	if ($bw)
	{
		imagefilter($dest, IMG_FILTER_GRAYSCALE);
	}
	
	imagejpeg($dest, $savepath, 85);
	imagedestroy($dest);
	imagedestroy($source);
}

function returnBorderedImage($src)
{
	return "<img src=\"" . BASE_URL . "images/rounded-border-shadow.png\" width=\"43\" height=\"43\" class=\"thumbnail\" style=\"background-image: url(" . $src . ")\">";
}

?>
