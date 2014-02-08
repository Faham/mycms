<?php

require_once "common.php";

dbConnect();

$idListGet = $_GET['idList'];

$idListPost = $_POST['IDLIST'];

if ($idListPost)
{
	$idListRaw = explode("|", $idListPost);
}
else
{
	$idListRaw = explode("|", $idListGet);
}

$idList = array();

$j = 0;

for ($i=0; $i<count($idListRaw); $i++)
{
	$temp = (int)($idListRaw[$i]);
	if ($temp > 0)
	{
		$idList[$j] = $temp;
		$j++;
	}
}

if ($_POST)
{
	$existing = (int)($_POST['EXISTINGNAME']);
	
	// if they added an author from the select box
	if ($existing > 0)
	{
		// make sure its not already in the array
		if (! in_array($existing, $idList))
		{
			$idList[] = $existing;
		}
	}
	
	$firstName = sanitize($_POST['FIRSTNAME']);
	$lastName = sanitize($_POST['LASTNAME']);
	
	// if they added an author using the text boxes
	if ($firstName)
	{
		// check to see if it exists first
		$checkQuery = mysql_query("SELECT * FROM `person` WHERE `FirstName`='$firstName' AND `LastName`='$lastName' LIMIT 1");
		
		if (mysql_num_rows($checkQuery) == 1)
		{
			$rowId = mysql_result($checkQuery, 0, "Id");
			
			// make sure its not already in the array
			if (! in_array($rowId, $idList))
			{
				$idList[] = $rowId;
			}
		}
		else
		{
			mysql_query("INSERT INTO `person` (`FirstName`, `LastName`) VALUES ('$firstName', '$lastName')");
		
			$id = mysql_insert_id();
			
			if ($id > 0)
			{
				$idList[] = $id;
			}
		}
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>Authors</title>
	<link rel="stylesheet" href="../hci.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="admin.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="authors.css" type="text/css" media="screen" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript" src="js/core.js"></script>
	<script type="text/javascript" src="js/events.js"></script>
	<script type="text/javascript" src="js/css.js"></script>
	<script type="text/javascript" src="js/coordinates.js"></script>
	<script type="text/javascript" src="js/drag.js"></script>
	<script type="text/javascript" src="js/dragsort.js"></script>
	<script type="text/javascript" src="js/cookies.js"></script>
	<script type="text/javascript">
	
	var dragsort = ToolMan.dragsort()
	var junkdrawer = ToolMan.junkdrawer()

	window.onload = function() {
		if ($("sortable"))
		{
			dragsort.makeListSortable($("sortable"), saveOrder);
		}
	}
	
	</script>
</head>

<body style="margin-top: 17px; text-align: left">

<?php

$nameList = array();

if (sizeof($idList))
{

?>

<table cellspacing="5" width="100%">
<tr>
	<td><h2 style="margin-bottom: 0">Authors</h2></td>
</tr>
<tr>
	<td>
	<ul id="sortable">
		<?php
		
		foreach ($idList as $id)
		{
			$query = mysql_query("SELECT * FROM `person` WHERE `Id`='$id' LIMIT 1");
			
			if (mysql_num_rows($query) > 0)
			{
				$row = mysql_fetch_assoc($query);
				
				echo "<li itemId=\"" . $row['Id'] . "\" id=\"" . $row['Id'] . "\"><div class=\"left\">";
				
				if ($row['FirstName'] && $row['LastName'])
				{
					echo htmlspecialchars(stripslashes($row['LastName'])) . ", " . htmlspecialchars(stripslashes(substr($row['FirstName'], 0, 1))) . ".";
					$nameList[] = htmlspecialchars(stripslashes($row['LastName'])) . ", " . htmlspecialchars(stripslashes(substr($row['FirstName'], 0, 1))) . ".";
				}
				else if ($row['FirstName'])
				{
					echo htmlspecialchars(stripslashes($row['FirstName']));
					$nameList[] = htmlspecialchars(stripslashes($row['FirstName']));
				}
				
				echo "</div>";
				echo "<div class=\"right\">";
				echo "<a onclick=\"removeAuthor(" . $row['Id'] . ")\"></a>";
				echo "</div>";
				echo "</li>";
			}
		}
		
		?>
	</ul>
	</td>
</tr>
</table>

<?php

}

?>

<form method="post" action="editauthors.php">
<table cellspacing="5" width="100%">
<tr>
	<td colspan="2"><h2>Add an existing author</h2></td>
</tr>
<tr>
	<td class="prompt">Name:</td>
	<td><select name="EXISTINGNAME" style="width: 150px">
	<option value=""></option>
	<?php
	
	$query = mysql_query("SELECT * FROM `person` ORDER BY `LastName` ASC");
	
	if (mysql_num_rows($query) > 0)
	{
		while ($row = mysql_fetch_assoc($query))
		{
			echo "<option value=\"" . $row['Id'] . "\">";
			
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
	
	?></select>
	</td>
</tr>
<tr>
	<td></td>
	<td><input type="hidden" name="IDLIST" id="idList1" value="<?php echo implode("|", $idList); ?>" /><input type="submit" value="Add" /></td>
</tr>
</table>
</form>

<form method="post" action="editauthors.php">
<table cellspacing="5" width="100%">
<tr>
	<td colspan="2"><h2 style="margin-top: 0px">Add a new author</h2></td>
</tr>
<tr>
	<td class="prompt">First name:</td>
	<td><input type="text" name="FIRSTNAME" /></td>
</tr>
<tr>
	<td class="prompt">Last name:</td>
	<td><input type="text" name="LASTNAME" /></td>
</tr>
<tr>
	<td></td>
	<td><input type="hidden" name="IDLIST" id="idList2" value="<?php echo implode("|", $idList); ?>" /><input type="submit" value="Add" /></td>
</tr>
</table>
</form>

<input type="hidden" name="NAMELIST" id="nameList" value="<?php echo implode(", ", $nameList); ?>" />

<script type="text/javascript">

<?php

// output the list of names for use by JS
echo "nameList = [];\n";
$i = 0;

foreach ($nameList as $name)
{
	echo "nameList[" . $idList[$i++] . "] = \"" . $name . "\";\n";
}

?>

</script>

<div class="saveandclose">
	<input type="button" value="Save Changes and Close Window" onclick="saveAndClose()" />
</div>

</body>

</html>
