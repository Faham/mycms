<?php
/*****************************
* Connect to database
*****************************/
	$conn = @mysql_connect("localhost","mycms_hci","");
	if (!$conn){
	    die("Database connection error" . mysql_error());
	}
	mysql_select_db("mycms", $conn);

?>