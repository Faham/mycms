<?php

require_once "common.php";

dbConnect();

outputHeader(2);

$message = "";

// process form submissions
if ($_POST)
{
	$name = sanitize($_POST['NAME']);
	$summary = sanitize($_POST['SUMMARY']);
	$past = (int)($_POST['PAST']);
	
	$saveEdit = (int)($_POST['SAVEEDIT']);
	
	if ($name && $summary)
	{
		if ($saveEdit > 0)
		{
			mysql_query("UPDATE `project` SET `Name`='$name', `Summary`='$summary', `Past`='$past' WHERE `Id`='$saveEdit' LIMIT 1");
			
			$projectId = $saveEdit;
			
			$message = "Your changes have been saved to the database.";
		}
		else
		{
			mysql_query("INSERT INTO `project` (`Name`, `Summary`, `Past`) VALUES ('$name', '$summary', '$past')");
			
			$projectId = (int)(@mysql_insert_id());
			
			$message = "Your data has been saved to the database.";
		}
		
		if ($projectId > 0)
		{
			// first clear the list of authors for this publication
			mysql_query("DELETE FROM `projectperson` WHERE `ProjectId`='$projectId'");
			
			$idListPost = $_POST['IDLIST'];
			
			$idListRaw = explode("|", $idListPost);

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
			
			if (count($idList) > 0)
			{
				for ($i=0; $i<count($idList); $i++)
				{
					mysql_query("INSERT INTO `projectperson` (`ProjectId`, `PersonId`, `Order`) VALUES ('$projectId', '" . $idList[$i] . "', '" . ($i + 1) . "')");
				}
			}
			
			// first clear the list of publications for this project
			mysql_query("DELETE FROM `projectpublication` WHERE `ProjectId`='$projectId'");
			
			$publicationList = $_POST['PUBLICATIONLIST'];
			
			$i = 1;
			
			foreach ($publicationList as $publication)
			{
				$publication = (int)($publication);
				if ($publication > 0)
				{
					mysql_query("INSERT INTO `projectpublication` (`ProjectId`, `PublicationId`, `Order`) VALUES ('$projectId', '$publication', '" . ($i++) . "')");
				}
			}
			
			// handle image uploads
			$image = $_FILES['IMAGE'];
			
			if (! $image['error'])
			{
				// check the upload type
				if ($image['type'] == "image/jpeg" || $image['type'] == "image/pjpeg" || $image['type'] == "image/png" || $image['type'] == "image/gif")
				{
					$maxQuery = mysql_query("SELECT MAX(`Id`) AS `MaxId` FROM `projectimage`");
					
					if (mysql_num_rows($maxQuery) == 1)
					{
						$maxRow = mysql_fetch_assoc($maxQuery);
						$newId = (int)($maxRow['MaxId']);
					}
					
					// move to the next id
					$newId++;
					
					$dest = UPLOAD_DIR . "project-" . $newId . ".jpg";
					$thumbDest = UPLOAD_DIR . "project-" . $newId . "-thumb.jpg";
					
					$maxWidth = 794;
					$maxHeight = 794;
					$thumbMaxWidth = 40;
					$thumbMaxHeight = 40;
					
					if ($newId > 0 && file_exists($dest) == false && file_exists($thumbDest) == false)
					{
						resizeImage($image['type'], $image['tmp_name'], $dest, $maxWidth, $maxHeight);
						thumbnail($image['type'], $image['tmp_name'], $thumbDest, $thumbMaxWidth, $thumbMaxHeight, false);
						
						// before inserting the new image into the database, first delete old images
						$imageQuery = mysql_query("SELECT * FROM `projectimage` WHERE `ProjectId`='$projectId'");
						
						if (@mysql_num_rows($imageQuery) > 0)
						{
							while ($imageRow = mysql_fetch_assoc($imageQuery))
							{
								$imagePath = UPLOAD_DIR . "project-" . $imageRow['Id'] . ".jpg";
								$thumbPath = UPLOAD_DIR . "project-" . $imageRow['Id'] . "-thumb.jpg";
								
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
						
						mysql_query("DELETE FROM `projectimage` WHERE `ProjectId`='$projectId'");
						mysql_query("INSERT INTO `projectimage` (`Id`, `ProjectId`) VALUES ('$newId', '$projectId')");
					}
				}
			}
		}
	}
	
	// handle reordering of projects
	$projectList = $_POST['ORDERLIST'];
	
	$i = 1;
	
	if (sizeof($projectList) > 0)
	{
		foreach ($projectList as $project)
		{
			$project = (int)($project);
			if ($project > 0)
			{
				mysql_query("UPDATE `project` SET `Order`='" . ($i++) . "' WHERE `Id`='$project' LIMIT 1");
			}
		}
	}
}

// process deletions
$delete = (int)($_GET['delete']);

if ($delete > 0)
{
	mysql_query("DELETE FROM `project` WHERE `Id`='$delete' LIMIT 1");
	mysql_query("DELETE FROM `projectperson` WHERE `ProjectId`='$delete'");
	
	// delete associated image(s)
	$imageQuery = mysql_query("SELECT * FROM `projectimage` WHERE `ProjectId`='$delete'");
	
	if (@mysql_num_rows($imageQuery) > 0)
	{
		while ($imageRow = mysql_fetch_assoc($imageQuery))
		{
			$imagePath = UPLOAD_DIR . "project-" . $imageRow['Id'] . ".jpg";
			$thumbPath = UPLOAD_DIR . "project-" . $imageRow['Id'] . "-thumb.jpg";
			
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
	
	mysql_query("DELETE FROM `projectimage` WHERE `ProjectId`='$delete'");
	
	$message = "The project has been deleted.";
}


// load data for edits
$edit = false;
$editId = (int)($_GET['edit']);

if ($editId > 0)
{
	$editQuery = mysql_query("SELECT * FROM `project` WHERE `Id`='" . $editId . "' LIMIT 1");
	
	if (mysql_num_rows($editQuery) > 0)
	{
		$editRow = mysql_fetch_assoc($editQuery);
		$edit = true;
		
		$personQuery = mysql_query("SELECT `person`.* FROM `projectperson`, `person` WHERE `projectperson`.`ProjectId`='$editId' AND `projectperson`.`PersonId`=`person`.`Id` ORDER BY `projectperson`.`Order` ASC");
		
		$idList = array();
		$nameList = array();
		
		if (mysql_num_rows($personQuery) > 0)
		{
			while ($personRow = mysql_fetch_assoc($personQuery))
			{
				$idList[] = $personRow['Id'];
				if ($personRow['FirstName'] && $personRow['LastName'])
				{
					$nameList[] = htmlspecialchars(stripslashes($personRow['LastName'])) . ", " . htmlspecialchars(stripslashes(substr($personRow['FirstName'], 0, 1))) . ".";
				}
				else
				{
					$nameList[] = htmlspecialchars(stripslashes($personRow['FirstName']));
				}
			}
		}
	} else {
		$editId = 0;
	}
}

?>

<script type="text/javascript" src="js/core.js"></script>
<script type="text/javascript" src="js/events.js"></script>
<script type="text/javascript" src="js/css.js"></script>
<script type="text/javascript" src="js/coordinates.js"></script>
<script type="text/javascript" src="js/drag.js"></script>
<script type="text/javascript" src="js/dragsort.js"></script>
<script type="text/javascript" src="js/cookies.js"></script>

<?php

if ($message)
{
	echo "<div class=\"message\">$message</div>";
}


if ($edit)
{
	echo "<h2>Edit Project</h2>";
}
else
{
	echo "<h2>Add a Project</h2>";
}

?>

<form method="post" action="research.php" enctype="multipart/form-data">
<table cellspacing="10" width="550">
<tr>
	<td class="prompt">Name:</td>
	<td><input type="text" class="text" name="NAME" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Name'])); ?>" id="firstText"/></td>
</tr>
<tr>
	<td class="prompt">Participants:</td>
	<td><span id="authorList"><?php if ($edit && (count($nameList) > 0)) echo implode(", ", $nameList); ?></span> [<a href="#" onclick="blur(); editAuthors(); return false"><b>edit</b></a>]</td>
</tr>
<tr>
	<td class="prompt">Description:</td>
	<td>
	<textarea rows="10" name="SUMMARY"><?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Summary'])); ?></textarea>
	</td>
</tr>
<tr>
	<td class="prompt">Image:</td>
	<td><table cellspacing="0" cellpadding="0"><tr><?php
	
	$imageExists = false;
	
	if ($edit)
	{
		$imageQuery = mysql_query("SELECT * FROM `projectimage` WHERE `ProjectId`='$editId' ORDER BY `Id` DESC LIMIT 1");
		
		if (mysql_num_rows($imageQuery) == 1)
		{
			$imageRow = mysql_fetch_assoc($imageQuery);
			
			$imagePath = UPLOAD_DIR . "project-" . $imageRow['Id'] . "-thumb.jpg";
			
			if (file_exists($imagePath))
			{
				echo "<td style=\"padding-right: 7px\">" . returnBorderedImage("../uploads/project-" . $imageRow['Id'] . "-thumb.jpg") . "</td>";
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
<tr>
	<td class="prompt">Publications:</td>
	<td>
	<ul id="sortable2">
	<li id="pubcopy"><select name="PUBLICATIONLIST[]">
	<option value=""></option>
	<?php
	
	if ($edit && $editId > 0)
	{
		$pubEdit = mysql_query("SELECT * FROM `projectpublication` WHERE `ProjectId`='$editId' ORDER BY `Order` ASC");
		
		if (mysql_num_rows($pubEdit) > 0)
		{
			$pubEditRow = mysql_fetch_assoc($pubEdit);
			$hasPubs = true;
		}
	}
	
	$pubQuery = mysql_query("SELECT * FROM `publication` ORDER BY `Title`");
	
	if (mysql_num_rows($pubQuery) > 0)
	{
		while ($pubRow = mysql_fetch_assoc($pubQuery))
		{
			echo "<option value=\"" . $pubRow['Id'] . "\"";
			
			if ($hasPubs)
			{
				if ($pubEditRow['PublicationId'] == $pubRow['Id'])
				{
					echo " selected";
				}
			}
			
			echo ">" . shortenTitle(stripslashes(htmlspecialchars($pubRow['Title']))) . " (" . stripslashes(htmlspecialchars($pubRow['Year'])) . ")</option>";
		}
	}
	
	?>
	</select></li>
	<?php
	
	if ($hasPubs && mysql_num_rows($pubEdit) > 1)
	{
		while ($pubEditRow = mysql_fetch_assoc($pubEdit))
		{
			echo '<li><select name="PUBLICATIONLIST[]">';
			echo '<option value=""></option>';
			
			$pubQuery = mysql_query("SELECT * FROM `publication` ORDER BY `Title`");
	
			if (mysql_num_rows($pubQuery) > 0)
			{
				while ($pubRow = mysql_fetch_assoc($pubQuery))
				{
					echo "<option value=\"" . $pubRow['Id'] . "\"";
					
					if ($pubEditRow['PublicationId'] == $pubRow['Id'])
					{
						echo " selected";
					}
					
					echo ">" . shortenTitle(stripslashes(htmlspecialchars($pubRow['Title']))) . " (" . stripslashes(htmlspecialchars($pubRow['Year'])) . ")</option>";
				}
			}
			
			echo '</select></li>';
		}
	}
	
	?>
	</ul>
	<div id="pubadd"><a href="#" onclick="addPubRow(); return false">Add</a></div>
	</td>
</tr>
<tr>
	<td class="prompt">Past:</td>
	<td><input type="checkbox" name="PAST" value="1"<?php if ($edit && $editRow['Past']) echo " checked"; ?> /></td>
</tr>
<tr>
	<td></td>
	<td>
	<input type="hidden" name="IDLIST" id="idList" value="<?php if ($edit) echo implode("|", $idList); ?>" />
	<input type="hidden" name="SAVEEDIT" value="<?php if ($edit) echo $editId; ?>" />
	<input type="submit" value="Submit" />
	</td>
</tr>
</table>
</form>

<br />

<h2>Projects</h2>

<form method="post" action="research.php">

<ul id="sortable3">
<?php

$query = mysql_query("SELECT * FROM `project` ORDER BY `Past` ASC, `ORDER` ASC");

$i = 1;

if (mysql_num_rows($query) > 0)
{
	while ($row = mysql_fetch_assoc($query))
	{
		echo '<li><table cellspacing="0" cellpadding="2" width="525">';
		
		echo "<tr class=\"pubrow\">";
		
		echo "<td><input type=\"hidden\" name=\"ORDERLIST[]\" value=\"" . $row['Id'] . "\" />" . htmlspecialchars(stripslashes($row['Name'])) . "</td>";
		echo "<td class=\"edit\"><a href=\"research.php?edit=" . $row['Id'] . "\">edit</a></td>";
		echo "<td class=\"delete\"><a href=\"research.php?delete=" . $row['Id'] . "\">delete</a></td>";
		echo "</tr>";
		
		echo '</table></li>';
		
		$i++;
	}
}

?>
</ul>

<input type="submit" value="Save Order" />

</form>

<?php

outputFooter();

?>