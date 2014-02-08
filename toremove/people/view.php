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
			$imagePath = UPLOAD_DIR . "person-" . $imageRow['Id'] . ".jpg";
			
			if (file_exists($imagePath))
			{
				echo "<img src=\"../uploads/person-" . $imageRow['Id'] . ".jpg\" class=\"imagewrap\" align=\"left\" />";
			}
		}
		
		echo "</td>";
		echo "<td class=\"profileright\">";
		
		if ($row['Bio'])
		{
			echo paragraph(stripslashes($row['Bio']));
		}
		
		if ($row['Email'])
		{
			echo "<img src=\"../email2image.php?email=" . $row['Email'] . "\" />";
		}
		
		/** PUBLICATIONS **/
		
		if ($row['PublicationPref'] != '1')
		{
			$pubQuery = mysql_query("SELECT `publication`.* FROM `publication`, `publicationperson` WHERE `publicationperson`.`PersonId`='$id' AND `publicationperson`.`PublicationId`=`publication`.`Id` ORDER BY `publication`.`Year` DESC, `publication`.`Id` DESC LIMIT 5");
		}
		else
		{
			$pubQuery = mysql_query("SELECT `publication`.* FROM `publication`, `profilepublication` WHERE `profilepublication`.`PersonId`='$id' AND `profilepublication`.`PublicationId`=`publication`.`Id` ORDER BY `profilepublication`.`ORDER` ASC LIMIT 5");
		}
		
		if (mysql_num_rows($pubQuery) > 0)
		{
			outputStrikethrough("<h4>Publications</h4>");
			
			while ($pubRow = mysql_fetch_assoc($pubQuery))
			{
				outputPublicationWithTitle($pubRow);
			}
		}
		
		$countQuery = mysql_query("SELECT COUNT(`publication`.`Id`) AS `Count` FROM `publication`, `publicationperson` WHERE `publicationperson`.`PersonId`='$id' AND `publicationperson`.`PublicationId`=`publication`.`Id`");
		
		$count = (int)(mysql_result($countQuery, 0, "Count"));
		
		if ($count > mysql_num_rows($pubQuery))
		{
			echo "<a href=\"viewpublications.php?id=" . $id . "\">View All <span class=\"arrows\">&gt;&gt;</span></a>";
		}
		
		/** RESEARCH **/
		
		if ($row['ProjectPref'] != '1')
		{
			$resQuery = mysql_query("SELECT `project`.* FROM `project`, `projectperson` WHERE `projectperson`.`PersonId`='$id' AND `projectperson`.`ProjectId`=`project`.`Id` ORDER BY `project`.`Id` DESC LIMIT 5");
		}
		else
		{
			$resQuery = mysql_query("SELECT `project`.* FROM `project`, `profileproject` WHERE `profileproject`.`PersonId`='$id' AND `profileproject`.`ProjectId`=`project`.`Id` ORDER BY `profileproject`.`Order` ASC LIMIT 5");
		}
		
		if (@mysql_num_rows($resQuery) > 0)
		{
			outputStrikethrough("<h4>Research</h4>");
			
			echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"projecttable\" style=\"width: 100%\">";
			
			while ($resRow = mysql_fetch_assoc($resQuery))
			{
				outputProjectRowWithImage($resRow);
			}
			
			echo "</table>";
		}
		
		$countQuery = mysql_query("SELECT COUNT(`project`.`Id`) AS `Count` FROM `project`, `projectperson` WHERE `projectperson`.`PersonId`='$id' AND `projectperson`.`ProjectId`=`project`.`Id`");
		
		$count = (int)(mysql_result($countQuery, 0, "Count"));
		
		if ($count > mysql_num_rows($resQuery))
		{
			echo "<a href=\"viewprojects.php?id=" . $id . "\">View All <span class=\"arrows\">&gt;&gt;</span></a>";
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