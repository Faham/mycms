<?php
	session_start();

	//检测是否登录，若没登录则转向登录界面
	if(!isset($_SESSION['userid'])){
	    header("Location:login.html");
	    exit();
	}
	//date.timezone = "America/Halifax";
	date_default_timezone_set("America/Halifax");
	//包含数据库连接文件
	include('conn.php');
	$userid = $_SESSION['userid'];
	$username = $_SESSION['username'];
	$user_query = mysql_query("select * from user where uid=$userid limit 1");
	$row = mysql_fetch_array($user_query);
	echo 'User Information<br />';
	echo 'User ID: ',$userid,'<br />';
	echo 'Username: ',$username,'<br />';
	echo 'Email Address: ',$row['email'],'<br />';
	echo 'Register Date: ',date("Y-m-d", $row['regdate']),'<br />';
	echo '<a href="login.php?action=logout">Logout</a><br />';
?>