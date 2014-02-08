<?php

require_once "common.php";

dbConnect();

outputHeader(1);

$message = "";

// process form submissions
if ($_POST)
{
	$firstName = sanitize($_POST['FIRSTNAME']);
	$middleInitial = sanitize($_POST['MIDDLEINITIAL']);
	$lastName = sanitize($_POST['LASTNAME']);
	$affiliation = sanitize($_POST['AFFILIATION']);
	$email = sanitize($_POST['EMAIL']);
	$bio = sanitize($_POST['BIO']);
	$group = (int)($_POST['GROUP']);
	$publicationPref = (int)($_POST['PUBLICATIONPREF']);
	$projectPref = (int)($_POST['PROJECTPREF']);
	
	$saveEdit = (int)($_POST['SAVEEDIT']);
	
	if ($firstName || $lastName)
	{
		if ($saveEdit == 0)
		{
			// check to see if this person already exists in the database
			$checkQuery = mysql_query("SELECT `Id` FROM `person` WHERE `FirstName`='$firstName' AND `LastName`='$lastName' LIMIT 1");
			
			// if the person already exists, pretend that they clicked edit
			if (mysql_num_rows($checkQuery) == 1)
			{
				$saveEdit = (int)(mysql_result($checkQuery, 0, "Id"));
			}
		}
		
		if ($saveEdit > 0)
		{
			mysql_query("UPDATE `person` SET `FirstName`='$firstName', `MiddleInitial`='$middleInitial', `LastName`='$lastName', `Affiliation`='$affiliation', `Email`='$email', `Bio`='$bio', `GroupId`='$group', `PublicationPref`='$publicationPref', `ProjectPref`='$projectPref' WHERE `Id`='$saveEdit' LIMIT 1");
			
			$personId = $saveEdit;
			
			$message = "Your changes have been saved to the database.";
		}
		else
		{
			mysql_query("INSERT INTO `person` (`FirstName`, `MiddleInitial`, `LastName`, `Affiliation`, `Email`, `Bio`, `GroupId`, `PublicationPref`, `ProjectPref`) VALUES ('$firstName', '$middleInitial', '$lastName', '$affiliation', '$email', '$bio', '$group', '$publicationPref', '$projectPref')");
			
			$personId = (int)(@mysql_insert_id());
			
			$message = "Your data has been saved to the database.";
		}
		
	}
	
	// handle profile publications
	mysql_query("DELETE FROM `profilepublication` WHERE `PersonId`='$personId'");
	
	if ($publicationPref == 1)
	{
		$customPub = array();
		
		if (isset($_POST['CUSTOMPUB0']))
			$customPub[0] = (int)($_POST['CUSTOMPUB0']);
		
		if (isset($_POST['CUSTOMPUB1']))
			$customPub[1] = (int)($_POST['CUSTOMPUB1']);
		
		if (isset($_POST['CUSTOMPUB2']))
			$customPub[2] = (int)($_POST['CUSTOMPUB2']);
		
		if (isset($_POST['CUSTOMPUB3']))
			$customPub[3] = (int)($_POST['CUSTOMPUB3']);
		
		if (isset($_POST['CUSTOMPUB4']))
			$customPub[4] = (int)($_POST['CUSTOMPUB4']);
		
		$order = 1;
		
		foreach ($customPub as $pub)
		{
			if ($pub > 0)
			{
				mysql_query("INSERT INTO `profilepublication` (`PersonId`, `PublicationId`, `Order`) VALUES ('$personId', '$pub', '$order')");
				
				$order++;
			}
		}
	}
	
	// handle profile projects
	mysql_query("DELETE FROM `profileproject` WHERE `PersonId`='$personId'");
	
	if ($projectPref == 1)
	{
		$customPro = array();
		
		if (isset($_POST['CUSTOMPRO0']))
			$customPro[0] = (int)($_POST['CUSTOMPRO0']);
		
		if (isset($_POST['CUSTOMPRO1']))
			$customPro[1] = (int)($_POST['CUSTOMPRO1']);
		
		if (isset($_POST['CUSTOMPRO2']))
			$customPro[2] = (int)($_POST['CUSTOMPRO2']);
		
		if (isset($_POST['CUSTOMPRO3']))
			$customPro[3] = (int)($_POST['CUSTOMPRO3']);
		
		if (isset($_POST['CUSTOMPRO4']))
			$customPro[4] = (int)($_POST['CUSTOMPRO4']);
		
		$order = 1;
		
		foreach ($customPro as $pro)
		{
			if ($pro > 0)
			{
				mysql_query("INSERT INTO `profileproject` (`PersonId`, `ProjectId`, `Order`) VALUES ('$personId', '$pro', '$order')");
				
				$order++;
			}
		}
	}
	
	// handle image uploads
	$image = $_FILES['IMAGE'];
	
	if (! $image['error'])
	{
		// check the upload type
		if ($image['type'] == "image/jpeg" || $image['type'] == "image/pjpeg" || $image['type'] == "image/png" || $image['type'] == "image/gif")
		{
			$maxQuery = mysql_query("SELECT MAX(`Id`) AS `MaxId` FROM `personimage`");
			
			if (mysql_num_rows($maxQuery) == 1)
			{
				$maxRow = mysql_fetch_assoc($maxQuery);
				$newId = (int)($maxRow['MaxId']);
			}
			
			// move to the next id
			$newId++;
			
			$dest = UPLOAD_DIR . "person-" . $newId . ".jpg";
			$thumbDest = UPLOAD_DIR . "person-" . $newId . "-thumb.jpg";
			
			$maxWidth = 200;
			$maxHeight = 300;
			$thumbMaxWidth = 40;
			$thumbMaxHeight = 40;
			
			if ($newId > 0 && file_exists($dest) == false && file_exists($thumbDest) == false)
			{
				resizeImage($image['type'], $image['tmp_name'], $dest, $maxWidth, $maxHeight);
				thumbnail($image['type'], $image['tmp_name'], $thumbDest, $thumbMaxWidth, $thumbMaxHeight, false);
				
				// before inserting the new image into the database, first delete old images
				$imageQuery = mysql_query("SELECT * FROM `personimage` WHERE `PersonId`='$personId'");
				
				if (@mysql_num_rows($imageQuery) > 0)
				{
					while ($imageRow = mysql_fetch_assoc($imageQuery))
					{
						$imagePath = UPLOAD_DIR . "person-" . $imageRow['Id'] . ".jpg";
						$thumbPath = UPLOAD_DIR . "person-" . $imageRow['Id'] . "-thumb.jpg";
						
						if (is_file($imagePath))
						{
							@unlink($imagePath);
						}
						
						if (is_file($thumbPath))
						{
							@unlink($thumbPath);
						}
					}
				}
				
				mysql_query("DELETE FROM `personimage` WHERE `PersonId`='$personId'");
				mysql_query("INSERT INTO `personimage` (`Id`, `PersonId`) VALUES ('$newId', '$personId')");
			}
		}
	}
}

// process deletions
$delete = (int)($_GET['delete']);

if ($delete > 0)
{
	mysql_query("DELETE FROM `person` WHERE `Id`='$delete' LIMIT 1");
	mysql_query("DELETE FROM `projectperson` WHERE `PersonId`='$delete'");
	mysql_query("DELETE FROM `publicationperson` WHERE `PersonId`='$delete'");
	mysql_query("DELETE FROM `personimage` WHERE `PersonId`='$delete'");
	
	// delete associated image(s)
	$imageQuery = mysql_query("SELECT * FROM `personimage` WHERE `PersonId`='$delete'");
	
	if (@mysql_num_rows($imageQuery) > 0)
	{
		while ($imageRow = mysql_fetch_assoc($imageQuery))
		{
			$imagePath = UPLOAD_DIR . "person-" . $imageRow['Id'] . ".jpg";
			$thumbPath = UPLOAD_DIR . "person-" . $imageRow['Id'] . "-thumb.jpg";
			
			if (is_file($imagePath))
			{
				@unlink($imagePath);
			}
			
			if (is_file($thumbPath))
			{
				@unlink($thumbPath);
			}
		}
	}
	
	$message = "The person has been deleted.";
}

// load data for edits
$edit = false;
$editId = (int)($_GET['edit']);

if ($editId > 0)
{
	$editQuery = mysql_query("SELECT * FROM `person` WHERE `Id`='" . $editId . "' LIMIT 1");
	
	if (mysql_num_rows($editQuery) > 0)
	{
		$editRow = mysql_fetch_assoc($editQuery);
		$edit = true;
	}
}

if ($message)
{
	echo "<div class=\"message\">$message</div>";
}

?>

<?php

if ($edit)
{
	echo "<h2>Edit Person</h2>";
}
else
{
	echo "<h2>Add a Person</h2>";
}

?>

<form method="post" action="people.php" enctype="multipart/form-data">
<table cellspacing="10" width="641">
<tr>
	<td class="prompt">First Name:</td>
	<td><input type="text" class="text" name="FIRSTNAME" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['FirstName'])); ?>" id="firstText" /></td>
</tr>
<tr>
	<td class="prompt">Middle Initial:</td>
	<td><input type="text" class="text" name="MIDDLEINITIAL" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['MiddleInitial'])); ?>" /></td>
</tr>
<tr>
	<td class="prompt">Last Name:</td>
	<td><input type="text" class="text" name="LASTNAME" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['LastName'])); ?>" /></td>
</tr>
<tr>
	<td class="prompt">Affiliation:</td>
	<td><input type="text" class="text" name="AFFILIATION" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Affiliation'])); ?>" /></td>
</tr>
<tr>
	<td class="prompt">Email:</td>
	<td><input type="text" class="text" name="EMAIL" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Email'])); ?>" /></td>
</tr>
<tr>
	<td class="prompt">Bio:</td>
	<td>
	<textarea rows="8" name="BIO"><?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Bio'])); ?></textarea>
	</td>
</tr>
<tr>
	<td class="prompt">Group:</td>
	<td><select name="GROUP" style="min-width: 50%">
	<option value=""></option>
	<?php
	
	$query = mysql_query("SELECT * FROM `group`");

	if (mysql_num_rows($query) > 0)
	{
		while ($row = mysql_fetch_assoc($query))
		{
			echo "<option value=\"" . $row['Id'] . "\"";
			
			if ($row['Id'] == $editRow['GroupId'])
			{
				echo " selected";
			}
			
			echo ">" . $row['Name'] . "</option>";
		}
	}
	
	?>
	</select>
	</td>
</tr>
<tr>
	<td class="prompt">Image:</td>
	<td><table cellspacing="0" cellpadding="0"><tr><?php
	
	$imageExists = false;
	
	if ($edit)
	{
		$imageQuery = mysql_query("SELECT * FROM `personimage` WHERE `PersonId`='$editId' ORDER BY `Id` DESC LIMIT 1");
		
		if (mysql_num_rows($imageQuery) == 1)
		{
			$imageRow = mysql_fetch_assoc($imageQuery);
			
			$imagePath = UPLOAD_DIR . "person-" . $imageRow['Id'] . "-thumb.jpg";
			
			if (file_exists($imagePath))
			{
				echo "<td style=\"padding-right: 7px\">" . returnBorderedImage("/uploads/person-" . $imageRow['Id'] . "-thumb.jpg") . "</td>";
				$imageExists = true;
			}
		}
	}
	
	?><td style="padding-top: 1px"><input type="file" name="IMAGE" /><span class="smalltext"> (.jpg, .gif, or .png)</span><?php
	
	if ($imageExists)
	{
		echo "<div class=\"smalltext\">Uploading a new image will overwrite the current image.</div>";
	}
	
	?></td></tr></table></td>
</tr>
<?php

if ($edit)
{
	if ($editRow['PublicationPref'] != '1')
	{
		$pubQuery = mysql_query("SELECT `publication`.* FROM `publication`, `publicationperson` WHERE `publicationperson`.`PersonId`='$editId' AND `publicationperson`.`PublicationId`=`publication`.`Id` ORDER BY `publication`.`Year` DESC, `publication`.`Id` DESC LIMIT 5");
	}
	else
	{
		$pubQuery = mysql_query("SELECT `publication`.* FROM `publication`, `profilepublication` WHERE `profilepublication`.`PersonId`='$editId' AND `profilepublication`.`PublicationId`=`publication`.`Id` ORDER BY `profilepublication`.`Order` ASC LIMIT 5");
	}
	
	$countQuery = mysql_query("SELECT COUNT(`publication`.`Id`) AS `Count` FROM `publication`, `publicationperson` WHERE `publicationperson`.`PersonId`='$editId' AND `publicationperson`.`PublicationId`=`publication`.`Id`");
	
	$count = (int)(mysql_result($countQuery, 0, "Count"));
	
	if ($count > 0)
	{
?>
<tr>
	<td class="prompt">Publications:</td>
	<td>
	<label for="mode0"><input type="radio" name="PUBLICATIONPREF" id="mode0" value="0" onchange="publicationModeChange()"<?php if ($editRow['PublicationPref'] != '1') echo " checked"; ?> />Display 5 newest publications on profile</label><br />
	<label for="mode1"><input type="radio" name="PUBLICATIONPREF" id="mode1" value="1" onchange="publicationModeChange()"<?php if ($editRow['PublicationPref'] == '1') echo " checked"; ?> />Manually choose which publications are displayed</label><br />
	<?php
		
		$i = 0;
		$numRows = mysql_num_rows($pubQuery);
		
		while ($i < $numRows || $editRow['PublicationPref'] == '1' && $count >= 5 && $i < 5)
		{
			if ($i < $numRows)
				$pubRow = mysql_fetch_assoc($pubQuery);
			
			$listQuery = mysql_query("SELECT `publication`.* FROM `publication`, `publicationperson` WHERE `publicationperson`.`PersonId`='$editId' AND `publicationperson`.`PublicationId`=`publication`.`Id` ORDER BY `Title`");
			
			if (mysql_num_rows($listQuery) > 0)
			{	
				echo "<select name=\"CUSTOMPUB" . $i . "\" id=\"customPub" . $i . "\" style=\"margin: 5px 0 0 0\">";
				echo "<option value=\"\"></option>";
				
				while ($listRow = mysql_fetch_assoc($listQuery))
				{
					echo "<option value=\"" . $listRow['Id'] . "\"";
					
					if ($i < $numRows && $pubRow['Id'] == $listRow['Id'])
					{
						echo " selected";
					}
					
					echo ">" . shortenTitle(stripslashes(htmlspecialchars($listRow['Title']))) . " (" . stripslashes(htmlspecialchars($listRow['Year'])) . ")</option>";
				}
				
				echo "</select>";
				
				$i++;
			}		
		}
		
	?>
	</td>
</tr>
<?php
	}
	
	// projects
	
	if ($editRow['ProjectPref'] != '1')
	{
		$proQuery = mysql_query("SELECT `project`.* FROM `project`, `projectperson` WHERE `projectperson`.`PersonId`='$editId' AND `projectperson`.`ProjectId`=`project`.`Id` ORDER BY `project`.`Id` DESC LIMIT 5");
	}
	else
	{
		$proQuery = mysql_query("SELECT `project`.* FROM `project`, `profileproject` WHERE `profileproject`.`PersonId`='$editId' AND `profileproject`.`ProjectId`=`project`.`Id` ORDER BY `profileproject`.`Order` ASC LIMIT 5");
	}
	
	$countQuery = mysql_query("SELECT COUNT(`project`.`Id`) AS `Count` FROM `project`, `projectperson` WHERE `projectperson`.`PersonId`='$editId' AND `projectperson`.`ProjectId`=`project`.`Id`");
	
	$count = (int)(mysql_result($countQuery, 0, "Count"));
	
	if ($count > 0)
	{
?>
<tr>
	<td class="prompt">Projects:</td>
	<td>
	<label for="promode0"><input type="radio" name="PROJECTPREF" id="promode0" value="0" onchange="projectModeChange()"<?php if ($editRow['ProjectPref'] != '1') echo " checked"; ?> />Display 5 newest projects on profile</label><br />
	<label for="promode1"><input type="radio" name="PROJECTPREF" id="promode1" value="1" onchange="projectModeChange()"<?php if ($editRow['ProjectPref'] == '1') echo " checked"; ?> />Manually choose which projects are displayed</label><br />
	<?php
		
		$i = 0;
		$numRows = mysql_num_rows($proQuery);
		
		while ($i < $numRows || $editRow['ProjectPref'] == '1' && $count >= 5 && $i < 5)
		{
			if ($i < $numRows)
				$proRow = mysql_fetch_assoc($proQuery);
			
			$listQuery = mysql_query("SELECT `project`.* FROM `project`, `projectperson` WHERE `projectperson`.`PersonId`='$editId' AND `projectperson`.`ProjectId`=`project`.`Id` ORDER BY `Name`");
			
			if (mysql_num_rows($listQuery) > 0)
			{	
				echo "<select name=\"CUSTOMPRO" . $i . "\" id=\"customPro" . $i . "\" style=\"margin: 5px 0 0 0\">";
				echo "<option value=\"\"></option>";
				
				while ($listRow = mysql_fetch_assoc($listQuery))
				{
					echo "<option value=\"" . $listRow['Id'] . "\"";
					
					if ($i < $numRows && $proRow['Id'] == $listRow['Id'])
					{
						echo " selected";
					}
					
					echo ">" . shortenTitle(stripslashes(htmlspecialchars($listRow['Name']))) . "</option>";
				}
				
				echo "</select>";
				
				$i++;
			}		
		}
		
	?>
	</td>
</tr>
<?php
	}

}

?>
<tr>
	<td></td>
	<td>
	<input type="hidden" name="SAVEEDIT" value="<?php if ($edit) echo $editId; ?>" />
	<input type="submit" value="Submit" />
	</td>
</tr>
</table>
</form>

<h2>People</h2>

<table cellspacing="0" cellpadding="2" width="525">
<?php

$query = mysql_query("SELECT `person`.*, `group`.`Name` AS `GroupName` FROM `group`, `person` WHERE `person`.`GroupId`=`group`.`Id` ORDER BY `person`.`GroupId`, `person`.`FirstName`");

$i = 1;

if (@mysql_num_rows($query) > 0)
{
	while ($row = mysql_fetch_assoc($query))
	{
		if ($i % 2)
			echo "<tr class=\"pubrow alternate\">";
		else
			echo "<tr class=\"pubrow\">";
		
		echo "<td>" . htmlspecialchars(stripslashes($row['FirstName'])) . " " . htmlspecialchars(stripslashes($row['LastName'])) . "</td>";
		echo "<td>" . htmlspecialchars(stripslashes($row['GroupName'])) . "</td>";
		echo "<td class=\"edit\"><a href=\"people.php?edit=" . $row['Id'] . "\">edit</a></td>";
		echo "<td class=\"delete\"><a href=\"people.php?delete=" . $row['Id'] . "\">delete</a></td>";
		echo "</tr>";
		
		$i++;
	}
}

?>
</table>

<script type="text/javascript">

publicationModeChange();
projectModeChange();

</script>

<?php

outputFooter();

?>