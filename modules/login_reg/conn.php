<?php
/*****************************
* Connect to database
*****************************/
	$conn = @mysql_connect("localhost","root","");
	if (!$conn){
	    die("Database connection error" . mysql_error());
	}
	mysql_select_db("login", $conn);

?>