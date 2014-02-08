<?php

require_once "../common.php";

dbConnect();

outputHeader(2, "People");

$currentGroup = "";

$query = mysql_query("SELECT `person`.`Id`, `person`.`FirstName`, `person`.`LastName`, `person`.`Affiliation`, `group`.`Name` AS `GroupName`, `group`.`Order` FROM `person`, `group` WHERE `person`.`GroupId`=`group`.`Id` ORDER BY `group`.`Order`, `person`.`FirstName`");

$i = 0;

echo "<h2>People</h2>\n";

?>

<img src="/images/people2012.jpg" width="794" height="532" style="margin-bottom: 20px" />

<?php

if (@mysql_num_rows($query) > 0)
{
	while ($row = mysql_fetch_assoc($query))
	{
		// if we are starting to output a new group
		if ($row['GroupName'] != $currentGroup)
		{
			if ($i > 0)
			{
				if ($i % 2 == 1)
				{
					echo "<td></td><td></td></tr>";
				}
				
				echo "</table>\n";
			}
			
			$i = 0;
			
			outputStrikethrough("<h4>" . $row['GroupName'] . "</h4>");
			$currentGroup = $row['GroupName'];
			echo "<table cellpadding=\"0\" cellspacing=\"0\" class=\"persontable\">";
		}
		
		if ($i % 2 == 0)
		{
			echo "<tr>\n";
		}
		
		outputPersonCell($row);
		
		if ($i % 2 == 1)
		{
			echo "</tr>\n";
		}
		
		$i++;
	}
	
	if ($i > 0 && ($i % 2) == 1)
	{
		echo "<td></td><td></td></tr>";
	}
	
	echo "</table>\n";
}

outputFooter();

?>
