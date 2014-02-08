<?php

require_once "common.php";

dbConnect();

outputHeader(1, "University of Saskatchewan", "The Human-Computer Interaction Lab is a research facility in the Department of Computer Science at the University of Saskatchewan.");

?>

<table cellpadding="0" cellspacing="0">
<tr>
	<td>
	
	<?php
	
	$imageList = array();
	$linkList = array();
	
	$imageQuery = mysql_query("SELECT `personimage`.`PersonId`, MAX(`personimage`.`Id`) AS `MaxId`, `person`.`FirstName`, `person`.`LastName` FROM `personimage`, `person` WHERE `personimage`.`PersonId`=`person`.`Id` GROUP BY `personimage`.`PersonId`");
	
	if (@mysql_num_rows($imageQuery))
	{
		while ($imageRow = mysql_fetch_assoc($imageQuery))
		{
			$altText = htmlspecialchars(stripslashes($imageRow['FirstName'])) . " " . htmlspecialchars(stripslashes($imageRow['LastName']));
			
			$image = "";
			$image .= "<a href=\"" . BASE_URL . "people/view.php?id=" . $imageRow['PersonId'] . "\" title=\"" . $altText . "\">";
			$image .= returnBorderedImageWithText("uploads/person-" . $imageRow['MaxId'] . "-thumb.jpg", $altText);
			$image .= "</a>";
			$imageList[] = $image;
		}
	}
	
	$imageQuery2 = mysql_query("SELECT `projectimage`.`ProjectId`, MAX(`projectimage`.`Id`) AS `MaxId`, `project`.`Name` FROM `projectimage`, `project` WHERE `projectimage`.`ProjectId`=`project`.`Id` GROUP BY `projectimage`.`ProjectId`");
	
	if (@mysql_num_rows($imageQuery2))
	{
		while ($imageRow2 = mysql_fetch_assoc($imageQuery2))
		{
			$altText = htmlspecialchars(stripslashes($imageRow2['Name']));
			
			$image = "";
			$image .= "<a href=\"" . BASE_URL . "research/view.php?id=" . $imageRow2['ProjectId'] . "\" title=\"" . $altText . "\">";
			$image .= returnBorderedImageWithText("uploads/project-" . $imageRow2['MaxId'] . "-thumb.jpg", $altText);
			$image .= "</a>";
			$imageList[] = $image;
		}
	}
	
	$imageQuery3 = mysql_query("SELECT `publicationimage`.`PublicationId`, MAX(`publicationimage`.`Id`) AS `MaxId`, `publication`.`Title` FROM `publicationimage`, `publication` WHERE `publicationimage`.`PublicationId`=`publication`.`Id` GROUP BY `publicationimage`.`PublicationId`");
	
	if (@mysql_num_rows($imageQuery3))
	{
		while ($imageRow3 = mysql_fetch_assoc($imageQuery3))
		{
			$altText = htmlspecialchars(stripslashes($imageRow3['Title']));
			
			$image = "";
			$image .= "<a href=\"" . BASE_URL . "publications/view.php?id=" . $imageRow3['PublicationId'] . "\" title=\"" . $altText . "\">";
			$image .= returnBorderedImageWithText("uploads/publication-" . $imageRow3['MaxId'] . "-thumb.jpg", $altText);
			$image .= "</a>";
			$imageList[] = $image;
		}
	}
	
	$i = 0;
	$j = 0;
	$imageCount = 48;
	
	shuffle($imageList);
	
	if (sizeof($imageList))
	{
		echo "<p id=\"imagedisplay\">";
		
		for ($i=0; $i<$imageCount; $i++)
		{
			if ($j == sizeof($imageList))
			{
				$j = 0;
				shuffle($imageList);
			}
			
			if ($i % 16 == 15)
				echo "<span class=\"right\">";
			
			echo $imageList[$j];
			
			if ($i % 16 == 15)
				echo "</span>";
			
			$j++;
		}
		
		echo "</p>";
	}
	
	?>
	
	
	<p style="font-size: 15px; font-weight: bold; line-height: 21px; color: rgb(65, 65, 65)">
The Human-Computer Interaction Lab is a research facility in the
Department of Computer Science at the University of Saskatchewan. We
carry out research in computer-supported cooperation, next-generation
interfaces, computer games, affective computing, surface computing,
and information visualization.
	</p>
	
	<?php
	
	$query = mysql_query("SELECT `person`.`Id`, `person`.`FirstName`, `person`.`LastName`, `person`.`Affiliation`, `group`.`Name` AS `GroupName` FROM `person`, `group` WHERE `person`.`GroupId`=`group`.`Id` AND `group`.`Id`='1' ORDER BY `person`.`GroupId`");

	$i = 0;

	outputStrikethrough("<h4>Faculty</h4>");

	if (@mysql_num_rows($query) > 0)
	{
		echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"persontable\" width=\"100%\">";
		
		while ($row = mysql_fetch_assoc($query))
		{
			if ($i % 2 == 0)
			{
				echo "<tr>";
			}
			
			outputPersonCell($row);
			
			if ($i % 2 == 1)
			{
				echo "</tr>";
			}
			
			$i++;
		}
		
		if ($i > 0 && $i % 2 == 0)
		{
			echo "</tr>";
		}
		
		echo "</table>\n";
	}
	
	/** RESEARCH **/
	
	$resQuery = mysql_query("SELECT * FROM `project` WHERE `Past`='0' ORDER BY `Order` ASC LIMIT 3");
	
	if (@mysql_num_rows($resQuery) > 0)
	{
		outputStrikethrough("<h4>Current Research</h4>");
		
		echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"projecttable\" style=\"width: 100%\">";
		
		while ($resRow = mysql_fetch_assoc($resQuery))
		{
			outputProjectRowWithImage($resRow);
		}
		
		echo "</table>";
	}
	
	echo "<a href=\"research/\">View All <span class=\"arrows\">&gt;&gt;</span></a>";
	
	/** PUBLICATIONS **/
	
	$pubQuery = mysql_query("SELECT * FROM `publication` ORDER BY `Year` DESC, `Id` DESC LIMIT 3");
	
	if (@mysql_num_rows($pubQuery) > 0)
	{
		outputStrikethrough("<h4>Recent Publications</h4>");
		
		while ($pubRow = mysql_fetch_assoc($pubQuery))
		{
			outputPublicationWithTitle($pubRow);
		}
	}
	
	echo "<a href=\"publications/\">View All <span class=\"arrows\">&gt;&gt;</span></a>";
	
	?>
	</td>
</tr>
</table>

<?php

outputFooter();

?>
