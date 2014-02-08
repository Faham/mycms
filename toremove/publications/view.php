<?php

require_once "../common.php";

dbConnect();

$id = (int)($_GET['id']);

if ($id > 0)
{
	$query = mysql_query("SELECT * FROM `publication` WHERE `Id`='$id' LIMIT 1");
	
	if (@mysql_num_rows($query) > 0)
	{
		$row = mysql_fetch_assoc($query);
		
		outputHeader(4, htmlspecialchars(stripslashes($row['Title'])));
		
		echo "<h2><span style=\"color: gray\">Publication:</span> " . htmlspecialchars(stripslashes($row['Title'])) . "</h2>\n";
		
		/** IMAGE **/
		$imageQuery = mysql_query("SELECT `Id` FROM `publicationimage` WHERE `PublicationId`='$id' LIMIT 1");
		
		if (@mysql_num_rows($imageQuery) > 0)
		{
			$imageRow = mysql_fetch_assoc($imageQuery);
			$imagePath = UPLOAD_DIR . "publication" . $imageRow['Id'] . ".jpg";
			
			if (file_exists($imagePath))
			{
				echo "<p><img src=\"../uploads/publication" . $imageRow['Id'] . ".jpg\" class=\"image\" /></p>";
			}
		}
		
		/** ABSTRACT **/
		
		if ($row['Abstract'])
		{
			echo paragraph(stripslashes($row['Abstract']));
		}
		
		
		/** LINKS **/
		
		if ($row['PDFLink'] || $row['SecondaryLink'])
		{
			outputStrikethrough("<h4>Downloads</h4>");
			
			if ($row['PDFLink'])
			{
				echo "<p style=\"margin: 6px 0 9px\">";
				echo "<a href=\"";
				
				if (substr($row['PDFLink'], 0, 4) == "http")
				{
					echo htmlspecialchars(stripslashes($row['PDFLink']));
				}
				else
				{
					echo BASE_URL . htmlspecialchars(stripslashes($row['PDFLink']));
				}
				
				echo "\" class=\"pdf\">PDF</a>";
				echo "</p>\n";
			}
			
			if ($row['SecondaryLink'])
			{
				echo "<p>";
				echo "<a href=\"";
				
				if (substr($row['SecondaryLink'], 0, 4) == "http")
				{
					echo htmlspecialchars(stripslashes($row['SecondaryLink']));
				}
				else
				{
					echo BASE_URL . htmlspecialchars(stripslashes($row['SecondaryLink']));
				}
				
				echo "\" class=\"";
				
				if (strpos($row['SecondaryLink'], ".mov"))
				{
					echo "mov\">Video";
				}
				else
				{
					echo "wmv\">Video";
				}
				
				echo "</a>";
				echo "</p>\n";
			}
		}
		
		
		/** PARTICIPANTS **/
		
		$authorQuery = mysql_query("SELECT `person`.* FROM `publicationperson`, `person` WHERE `publicationperson`.`PublicationId`='$id' AND `publicationperson`.`PersonId`=`person`.`Id` ORDER BY `publicationperson`.`Order` ASC");
		
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
			
			echo "</table>\n";
		}
		
		
		/** PROJECTS **/
		
		$projectQuery = mysql_query("SELECT `project`.* FROM `projectpublication`, `project` WHERE `projectpublication`.`PublicationId`='$id' AND `projectpublication`.`ProjectId`=`project`.`Id` ORDER BY `projectpublication`.`Order` ASC");
		
		if (mysql_num_rows($projectQuery) > 0)
		{
			outputStrikethrough("<h4>Projects</h4>");
			
			echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"projecttable\">\n";
			
			while ($projectRow = mysql_fetch_assoc($projectQuery))
			{
				outputProjectRowWithImage($projectRow);
			}
			
			echo "</table>\n";
		}
		
		
		/** CITATION **/
		
		outputStrikethrough("<h4>Citation</h4>");
		
		echo "<p>\n";
		
		outputCitation($row);
		
		echo "</p>\n";
		
		
		/** BIBTEX **/
		
		outputStrikethrough("<h4>BibTeX</h4>");
		
		echo "<p>\n";
		
		outputBibTeX($row);
		
		echo "</p>\n";
	}
	else
	{
		outputHeader(4, "Error");
		echo "<h2>Error</h2>";
		echo "<p>The content that you are looking for could not be found!</p>";
	}
}
else
{
	outputHeader(4, "Error");
	echo "<h2>Error</h2>";
	echo "<p>The content that you are looking for could not be found!</p>";
}

outputFooter();

?>