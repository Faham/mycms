<?php
	if(!isset($_POST['submit'])){
	    exit('Illegal access');
	}
	$username = $_POST['username'];
	$password = $_POST['password'];
	$email = $_POST['email'];
	// Check register information
	if(!preg_match('/^[A-Za-z][A-Za-z0-9]{4,31}$/', $username)){
	    exit('Error: username does not follow the rules.<a href="javascript:history.back(-1);">Return</a>');
	}
	if(strlen($password) < 6){
	    exit('Error: Password is too short. <a href="javascript:history.back(-1);">Return</a>');
	}
	if(!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)){
	    exit('Error: Email address is wrong format.<a href="javascript:history.back(-1);">Return</a>');
	}
	// Include connection file to database
	include('conn.php');
	// Check if the username is existed.
	$check_query = mysql_query("select uid from user where username='$username' limit 1");
	if(mysql_fetch_array($check_query)){
	    echo 'Error: wrong username, ',$username,' is existed.<a href="javascript:history.back(-1);">Return</a>';
	    exit;
	}
	// Write data
	$password = MD5($password);
	$regdate = time();
	$sql = "INSERT INTO user(username,password,email,regdate)VALUES('$username','$password','$email',
	$regdate)";
	if(mysql_query($sql,$conn)){
	    header('Location: ../../login.html');
	    //exit('User register successfully. Click here to <a href="../../login.html">Login</a>');
	} else {
	    echo 'Data add error.',mysql_error(),'<br />';
	    echo 'Click here to <a href="javascript:history.back(-1);">Return</a>';
	}
?>