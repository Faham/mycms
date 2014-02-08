<?php

require_once "../common.php";

dbConnect();

$id = (int)($_GET['id']);

if ($id > 0)
{
	$query = mysql_query("SELECT * FROM `person` WHERE `Id`='$id' LIMIT 1");
	
	if (@mysql_num_rows($query) > 0)
	{
		$row = mysql_fetch_assoc($query);
		
		outputHeader(2, htmlspecialchars(stripslashes($row['FirstName'])) . " " . htmlspecialchars(stripslashes($row['LastName'])));
		
		echo "<h2>" . htmlspecialchars(stripslashes($row['FirstName'])) . " " . htmlspecialchars(stripslashes($row['LastName'])) . " <span class=\"headeraffiliation\">" . $row['Affiliation'] . "</span></h2>";
		
		echo "<table cellpadding=\"0\" cellspacing=\"0\">";
		echo "<tr>";
		echo "<td class=\"profileleft\">";
		
		/** IMAGE **/
		$imageQuery = mysql_query("SELECT `Id` FROM `personimage` WHERE `PersonId`='$id' ORDER BY `Id` DESC LIMIT 1");
		
		if (@mysql_num_rows($imageQuery) > 0)
		{
			$imageRow = mysql_fetch_assoc($imageQuery);
			$imagePath = UPLOAD_DIR . "person" . $imageRow['Id'] . ".jpg";
			
			if (file_exists($imagePath))
			{
				echo "<img src=\"../uploads/person" . $imageRow['Id'] . ".jpg\" class=\"image imagewrap\" align=\"left\" />";
			}
		}
		
		echo "</td>";
		echo "<td class=\"profileright\">";
		
		echo "<a href=\"view.php?id=" . $id . "\"><span class=\"arrows\">&lt;&lt;</span> Back to Profile</a>";
		
		outputStrikethrough("<h4>Projects</h4>");
		
		/** RESEARCH **/
		
		$resQuery = mysql_query("SELECT `project`.* FROM `project`, `projectperson` WHERE `projectperson`.`PersonId`='$id' AND `projectperson`.`ProjectId`=`project`.`Id` ORDER BY `project`.`Id` DESC");
		
		if (@mysql_num_rows($resQuery) > 0)
		{
			echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"projecttable\" style=\"width: 100%\">";
			
			while ($resRow = mysql_fetch_assoc($resQuery))
			{
				outputProjectRowWithImage($resRow);
			}
			
			echo "</table>";
		}
		
		echo "</td>";
		echo "</tr>";
		echo "</table>";
	}
	else
	{
		outputHeader(2, "Error");
		echo "<h2>Error</h2>";
		echo "<p>The content that you are looking for could not be found!</p>";
	}
}
else
{
	outputHeader(2, "Error");
	echo "<h2>Error</h2>";
	echo "<p>The content that you are looking for could not be found!</p>";
}

outputFooter();

?>