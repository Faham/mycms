<?php
	global $g;
	session_start();

	// Logout
	if(isset($_GET['action']))
	{	
		if($_GET['action'] == "logout"){
			unset($_SESSION['userid']);
			unset($_SESSION['username']);
			echo 'Logout successfully, Click here to <a href="../../login.html">Login</a>';
			exit;
		}
	}

	// Login
	if(!isset($_POST['submit'])){
	    exit('Illegal access');
	}
	$username = htmlspecialchars($_POST['username']);
	$password = MD5($_POST['password']);

	// Include connection file of database
	include('conn.php');
	// Check if username and password is correct
	$check_query = mysql_query("select uid from user where username='$username' and password='$password' 
	limit 1");
	if($result = mysql_fetch_array($check_query)){
		//echo "login successfully";
	    // Login successfully
	    $_SESSION['username'] = $username;
	    $_SESSION['userid'] = $result['uid'];
	    //echo $username,' Welcome to enter <a href="my.php">User Center</a><br />';
	    //echo $username,' Welcome to enter <a href="http://localhost/mycms">Home Page</a><br />';
	    //redirect to home page
	    header('Location: ../../');
	    //echo 'Click here to <a href="login.php?action=logout">Logout</a> <br />';
	    exit;
	} else {
	    exit('Login failed, click here to <a href="javascript:history.back(-1);">Return</a>');
	}
?>