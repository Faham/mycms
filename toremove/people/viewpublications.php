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
		
		/** PUBLICATIONS **/
		
		$pubQuery = mysql_query("SELECT `publication`.* FROM `publication`, `publicationperson` WHERE `publicationperson`.`PersonId`='$id' AND `publicationperson`.`PublicationId`=`publication`.`Id` ORDER BY `publication`.`Year` DESC, `publication`.`Title` ASC");
		
		$currentYear = 0;
		
		if (mysql_num_rows($pubQuery) > 0)
		{	
			while ($pubRow = mysql_fetch_assoc($pubQuery))
			{
				// if we are starting to output a new year
				if ($pubRow['Year'] != $currentYear)
				{
					outputStrikethrough("<h4>" . $pubRow['Year'] . "</h4>");
					$currentYear = $pubRow['Year'];
				}
				
				echo "<p class=\"publication\">";
				outputPublicationWithTitle($pubRow);
				echo "</p>";
			}
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