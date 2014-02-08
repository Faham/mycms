<?php

require_once "../common.php";

dbConnect();

$id = (int)($_GET['id']);

if ($id > 0)
{
	$query = mysql_query("SELECT * FROM `project` WHERE `Id`='$id' LIMIT 1");
	
	if (@mysql_num_rows($query) > 0)
	{
		$row = mysql_fetch_assoc($query);
		
		outputHeader(3, htmlspecialchars(stripslashes($row['Name'])));
		
		echo "<h2><span style=\"color: gray\">Project:</span> " . htmlspecialchars(stripslashes($row['Name'])) . "</h2>";
		
		/** IMAGE **/
		$imageQuery = mysql_query("SELECT `Id` FROM `projectimage` WHERE `ProjectId`='$id' LIMIT 1");
		
		if (@mysql_num_rows($imageQuery) > 0)
		{
			$imageRow = mysql_fetch_assoc($imageQuery);
			$imagePath = UPLOAD_DIR . "project-" . $imageRow['Id'] . ".jpg";
			
			if (file_exists($imagePath))
			{
				echo "<p><img src=\"../uploads/project-" . $imageRow['Id'] . ".jpg\" class=\"image\" /></p>";
			}
		}
		
		/** SUMMARY **/
		
		if ($row['Summary'])
		{
			echo paragraph(stripslashes($row['Summary']));
		}
		
		
		/** PARTICIPANTS **/
		
		$authorQuery = mysql_query("SELECT `person`.* FROM `projectperson`, `person` WHERE `projectperson`.`ProjectId`='$id' AND `projectperson`.`PersonId`=`person`.`Id` ORDER BY `projectperson`.`Order` ASC");
		
		if (mysql_num_rows($authorQuery) > 0)
		{
			outputStrikethrough("<h4>Participants</h4>");
			
			echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"persontable\" width=\"100%\">";
			
			$i = 0;
			
			while ($authorRow = mysql_fetch_assoc($authorQuery))
			{
				if ($i % 2 == 0)
				{
					echo "<tr>";
				}
				
				outputPersonCell($authorRow);
				
				if ($i % 2 == 1)
				{
					echo "</tr>";
				}
				
				$i++;
			}
			
			if ($i > 0 && $i % 2 == 1)
			{
				echo "<td></td><td></td></tr>";
			}
			
			echo "</table>";
		}
		
		
		/** PUBLICATIONS **/
		
		$pubQuery = mysql_query("SELECT * FROM `publication`, `projectpublication` WHERE `projectpublication`.`ProjectId`='$id' AND `projectpublication`.`PublicationId`=`publication`.`Id` ORDER BY `projectpublication`.`Order` ASC");
		
		if (mysql_num_rows($pubQuery) > 0)
		{
			outputStrikethrough("<h4>Publications</h4>");
			
			while ($pubRow = mysql_fetch_assoc($pubQuery))
			{
				outputPublicationWithTitle($pubRow);
			}
		}
	}
	else
	{
		outputHeader(3, "Error");
		echo "<h2>Error</h2>";
		echo "<p>The content that you are looking for could not be found!</p>";
	}
}
else
{
	outputHeader(3, "Error");
	echo "<h2>Error</h2>";
	echo "<p>The content that you are looking for could not be found!</p>";
}

outputFooter();

?>