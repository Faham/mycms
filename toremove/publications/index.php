<?php

if (array_key_exists('embed_format',$_GET)){
	$embed_format = (int) ($_GET['embed_format']);
}
else{
	 $embed_format = 0;
}
	require_once "../common.php";

dbConnect();

$type = (int)($_GET['type']);
$author = (int)($_GET['author']);
$year = (int)($_GET['year']);

if ($embed_format == 0){
	outputHeader(4, "Publications");
	echo "<h2 style=\"margin-bottom: 10px\">Publications</h2>";

	?>

	<form method="get" action="index.php">
	<table class="filter">
	<tr>
		<td class="prompt">
		Type:
		</td>
		<td>
		<select name="type">
			<option value="">All</option>
			<option value="1"<?php if ($type == 1) echo " selected"; ?>>Conference</option>
			<option value="2"<?php if ($type == 2) echo " selected"; ?>>Journal</option>
			<option value="3"<?php if ($type == 3) echo " selected"; ?>>Book</option>
			<option value="4"<?php if ($type == 4) echo " selected"; ?>>Book Chapter</option>
			<option value="5"<?php if ($type == 5) echo " selected"; ?>>Tech Report</option>
			<option value="6"<?php if ($type == 6) echo " selected"; ?>>M.Sc. Thesis</option>
			<option value="7"<?php if ($type == 7) echo " selected"; ?>>Ph.D. Thesis</option>
		</select>
		</td>
		<td class="prompt">
		Author:
		</td>
		<td>
		<select name="author">
			<option value="">All</option>
			<?php
			
			$query = mysql_query("SELECT DISTINCT `person`.`Id`, `person`.`FirstName`, `person`.`LastName` FROM `person`, `publicationperson` WHERE `person`.`Id`=`publicationperson`.`PersonId` ORDER BY `person`.`LastName` ASC");
			
			if (mysql_num_rows($query) > 0)
			{
				while ($row = mysql_fetch_assoc($query))
				{
					echo "<option value=\"" . $row['Id'] . "\"";
					
					if ($row['Id'] == $author)
					{
						echo " selected";
					}
					
					echo ">";
					
					if ($row['FirstName'] && $row['LastName'])
					{
						echo htmlspecialchars(stripslashes($row['LastName'])) . ", " . htmlspecialchars(stripslashes(substr($row['FirstName'], 0, 1))) . ".";
					}
					else
					{
						echo htmlspecialchars(stripslashes($row['FirstName']));
					}
					
					echo "</option>";
				}
			}
			
			?>
		</select>
		</td>
		<td class="prompt">
		Year:
		</td>
		<td>
		<select name="year">
			<option value="">All</option>
			<?php
			
			$query = mysql_query("SELECT DISTINCT `Year` FROM `publication` ORDER BY `Year` DESC");
			
			if (mysql_num_rows($query) > 0)
			{
				while ($row = mysql_fetch_assoc($query))
				{
					$val = (int)($row['Year']);
					
					echo "<option value=\"" . $val . "\"";
					
					if ($val == $year)
					{
						echo " selected";
					}
					
					echo ">" . $val . "</option>";
				}
			}
			
			?>
		</select>
		</td>
		<td class="filtersubmit">
			<input type="submit" value="Filter" />
		</td>
	</tr>
	</table>
	</form>

<?php
}//end if embed format == 0
// filters
$queryText = "SELECT `publication`.* FROM ";

$tableList = array();
$whereList = array();

$tableList[] = "`publication`";

if ($type > 0)
{
	$whereList[] = "`Type`='$type'";
}

if ($author > 0)
{
	$whereList[] = "`publicationperson`.`PersonId`='$author'";
	$whereList[] = "`publicationperson`.`PublicationId`=`publication`.`Id`";
	
	$tableList[] = "`publicationperson`";
}

if ($year > 0)
{
	$whereList[] = "`Year`='$year'";
}

$queryText .= implode(", ", $tableList);

if (sizeof($whereList))
{
	$queryText .= " WHERE " . implode(" AND ", $whereList);
}

$queryText .= " ORDER BY `publication`.`Year` DESC, `publication`.`Title` ASC";

$query = mysql_query($queryText);

$currentYear = 0;

if (@mysql_num_rows($query) > 0)
{
	echo "<div style=\"width: 75%\">";
	
	while ($row = mysql_fetch_assoc($query))
	{	
		// if we are starting to output a new year
		if ($row['Year'] != $currentYear)
		{
			outputStrikethrough("<h4>" . $row['Year'] . "</h4>");
			$currentYear = $row['Year'];
		}
		
		outputPublicationWithTitle($row);
	}
	
	echo "</div>";
}
else
{
	echo "<p>Sorry, no publications were found that match your criteria.</p>";
}

if ($embed_format == 0){
	outputFooter();
}

?>
