<?php

require_once "../common.php";

dbConnect();

outputHeader(3, "Research");

$currentYear = 0;

$query = mysql_query("SELECT * FROM `project` WHERE `Past`='0' ORDER BY `Order` ASC");

echo "<h2>Research</h2>\n";

if (@mysql_num_rows($query) > 0)
{
	//outputStrikethrough("<h4>Current Projects</h4>");
	
	echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"projecttable\">";
	
	while ($row = mysql_fetch_assoc($query))
	{
		outputProjectRowWithImage($row);
	}
	
	echo "</table>";
}

$query = mysql_query("SELECT * FROM `project` WHERE `Past`='1' ORDER BY `Order` ASC");

if (@mysql_num_rows($query) > 0)
{
	outputStrikethrough("<h4>Past Projects</h4>");
	
	echo "<p>";
	
	while ($row = mysql_fetch_assoc($query))
	{
		echo "<a href=\"view.php?id=" . $row['Id'] . "\" class=\"proj\">" . htmlspecialchars(stripslashes($row['Name'])) . "</a><br />";
	}
	
	echo "</p>";
}

outputFooter();

?>