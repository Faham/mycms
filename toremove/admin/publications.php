<?php

require_once "common.php";

dbConnect();

outputHeader(3);

$message = "";

// process form submissions
if ($_POST)
{
	$type = (int)($_POST['TYPE']);
	$title = sanitize($_POST['TITLE']);
	$journal = sanitize($_POST['JOURNAL']);
	$abstract = sanitize($_POST['ABSTRACT']);
	$school = sanitize($_POST['SCHOOL']);
	$techNumber = sanitize($_POST['NUMBER']);
	$year = sanitize($_POST['YEAR']);
	$toAppear = sanitize($_POST['TOAPPEAR']);
	$volume = sanitize($_POST['VOLUME']);
	$issue = sanitize($_POST['ISSUE']);
	$series = sanitize($_POST['SERIES']);
	$location = sanitize($_POST['LOCATION']);
	$pages = sanitize($_POST['PAGES']);
	$doi = sanitize($_POST['DOI']);
	$isbn = sanitize($_POST['ISBN']);
	$edition = sanitize($_POST['EDITION']);
	$chapter = sanitize($_POST['CHAPTER']);
	$additionalInfo = sanitize($_POST['ADDITIONALINFO']);
	
	$saveEdit = (int)($_POST['SAVEEDIT']);
	
	// the only valid values for $toAppear are 0 and 1
	if ($toAppear != 1)
		$toAppear = 0;
	
	if ($title)
	{
		if ($saveEdit > 0)
		{
			mysql_query("UPDATE `publication` SET `Type`='$type', `Title`='$title', `Journal`='$journal', `Abstract`='$abstract', `School`='$school', `TechNumber`='$techNumber', `Year`='$year', `ToAppear`='$toAppear', `VolumeNum`='$volume', `IssueNum`='$issue', `Series`='$series', `Location`='$location', `Pages`='$pages', `DOI`='$doi', `ISBN`='$isbn', `Chapter`='$chapter', `AdditionalInfo`='$additionalInfo' WHERE `Id`='$saveEdit' LIMIT 1");
			
			$publicationId = $saveEdit;
			
			$message = "Your changes have been saved to the database.";
		}
		else
		{
			mysql_query("INSERT INTO `publication` (`Type`, `Title`, `Journal`, `Abstract`, `School`, `TechNumber`, `Year`, `ToAppear`, `VolumeNum`, `IssueNum`, `Series`, `Location`, `Pages`, `DOI`, `ISBN`, `Edition`, `Chapter`, `AdditionalInfo`) VALUES ('$type', '$title', '$journal', '$abstract', '$school', '$techNumber', '$year', '$toAppear', '$volume', '$issue', '$series', '$location', '$pages', '$doi', '$isbn', '$edition', '$chapter', '$additionalInfo')");
			
			$publicationId = (int)(@mysql_insert_id());
			
			$message = "Your data has been saved to the database.";
		}
	}
	
	if ($publicationId > 0)
	{
		// first clear the list of authors for this publication
		mysql_query("DELETE FROM `publicationperson` WHERE `PublicationId`='$publicationId'");
		
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
				mysql_query("INSERT INTO `publicationperson` (`PublicationId`, `PersonId`, `Order`) VALUES ('$publicationId', '" . $idList[$i] . "', '" . ($i + 1) . "')");
			}
		}
		
		// handle pdf uploads
		$pdf = $_FILES['PDF'];
		
		if (! $pdf['error'])
		{
			// check the upload type
			if ($pdf['type'] == "application/pdf")
			{
				$fileName = $publicationId . "-" . str_replace(" ", "-", $pdf['name']);
				
				// the next line looks messed because backslashes need to be escaped...
				$dest = UPLOAD_DIR . $fileName;
				$link = "uploads/" . $fileName;
				
				if (file_exists($dest) == false)
				{
					move_uploaded_file($pdf['tmp_name'], $dest);
					mysql_query("UPDATE `publication` SET `PDFLink`='$link' WHERE `Id`='$publicationId' LIMIT 1");
				}
			}
		}
		
		// handle video uploads
		$video = $_FILES['VIDEO'];
		
		if (! $video['error'])
		{
			// check the upload type
			if (substr($video['type'], 0, 6) == "video/")
			{
				$fileName = $publicationId . "-" . str_replace(" ", "-", $video['name']);
				
				// the next line looks messed because backslashes need to be escaped...
				$dest = UPLOAD_DIR . $fileName;
				$link = "uploads/" . $fileName;
				
				if (file_exists($dest) == false)
				{
					move_uploaded_file($video['tmp_name'], $dest);
					mysql_query("UPDATE `publication` SET `SecondaryLink`='$link' WHERE `Id`='$publicationId' LIMIT 1");
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
				$maxQuery = mysql_query("SELECT MAX(`Id`) AS `MaxId` FROM `publicationimage`");
				
				if (mysql_num_rows($maxQuery) == 1)
				{
					$maxRow = mysql_fetch_assoc($maxQuery);
					$newId = (int)($maxRow['MaxId']);
				}
				
				// move to the next id
				$newId++;
				
				$dest = UPLOAD_DIR . "publication-" . $newId . ".jpg";
				$thumbDest = UPLOAD_DIR . "publication-" . $newId . "-thumb.jpg";
				
				$maxWidth = 794;
				$maxHeight = 794;
				$thumbMaxWidth = 40;
				$thumbMaxHeight = 40;
				
				if ($newId > 0 && file_exists($dest) == false && file_exists($thumbDest) == false)
				{
					resizeImage($image['type'], $image['tmp_name'], $dest, $maxWidth, $maxHeight);
					thumbnail($image['type'], $image['tmp_name'], $thumbDest, $thumbMaxWidth, $thumbMaxHeight, false);
					
					// before inserting the new image into the database, first delete old images
					$imageQuery = mysql_query("SELECT * FROM `publicationimage` WHERE `PublicationId`='$publicationId'");
					
					if (@mysql_num_rows($imageQuery) > 0)
					{
						while ($imageRow = mysql_fetch_assoc($imageQuery))
						{
							$imagePath = UPLOAD_DIR . "publication-" . $imageRow['Id'] . ".jpg";
							$thumbPath = UPLOAD_DIR . "publication-" . $imageRow['Id'] . "-thumb.jpg";
							
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
					
					mysql_query("DELETE FROM `publicationimage` WHERE `PublicationId`='$publicationId'");
					mysql_query("INSERT INTO `publicationimage` (`Id`, `PublicationId`) VALUES ('$newId', '$publicationId')");
				}
			}
		}
	}
}

// process deletions
$delete = (int)($_GET['delete']);

if ($delete > 0)
{
	mysql_query("DELETE FROM `publication` WHERE `Id`='$delete' LIMIT 1");
	mysql_query("DELETE FROM `publicationperson` WHERE `PublicationId`='$delete'");
	
	// delete associated image(s)
	$imageQuery = mysql_query("SELECT * FROM `publicationimage` WHERE `PublicationId`='$delete'");
	
	if (@mysql_num_rows($imageQuery) > 0)
	{
		while ($imageRow = mysql_fetch_assoc($imageQuery))
		{
			$imagePath = UPLOAD_DIR . "publication-" . $imageRow['Id'] . ".jpg";
			$thumbPath = UPLOAD_DIR . "publication-" . $imageRow['Id'] . "-thumb.jpg";
			
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
	
	mysql_query("DELETE FROM `publicationimage` WHERE `PublicationId`='$delete'");
	
	$message = "The publication has been deleted.";
}


// load data for edits
$edit = false;
$editId = (int)($_GET['edit']);

if ($editId > 0)
{
	$editQuery = mysql_query("SELECT * FROM `publication` WHERE `Id`='" . $editId . "' LIMIT 1");
	
	if (mysql_num_rows($editQuery) > 0)
	{
		$editRow = mysql_fetch_assoc($editQuery);
		$edit = true;
		
		$personQuery = mysql_query("SELECT `person`.* FROM `publicationperson`, `person` WHERE `publicationperson`.`PublicationId`='$editId' AND `publicationperson`.`PersonId`=`person`.`Id` ORDER BY `publicationperson`.`Order` ASC");
		
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
	}
}

if ($message)
{
	echo "<div class=\"message\">$message</div>";
}


if ($edit)
{
	echo "<h2>Edit Publication</h2>";
}
else
{
	echo "<h2>Add a Publication</h2>";
}

?>

<form name="publication" method="post" action="publications.php" enctype="multipart/form-data">
<table cellspacing="10" width="550">
<tr>
	<td class="prompt">Type:</td>
	<td colspan="2">
	<label for="type1"><input name="TYPE" type="radio" value="1" onchange="changeType()" id="type1"<?php if ($edit == false || $editRow['Type'] == 1) echo ' checked="checked"'; ?>>Conference</label><br />
	<label for="type2"><input name="TYPE" type="radio" value="2" onchange="changeType()" id="type2"<?php if ($edit && $editRow['Type'] == 2) echo ' checked="checked"'; ?>>Journal</label><br />
	<label for="type3"><input name="TYPE" type="radio" value="3" onchange="changeType()" id="type3"<?php if ($edit && $editRow['Type'] == 3) echo ' checked="checked"'; ?>>Book</label><br />
	<label for="type4"><input name="TYPE" type="radio" value="4" onchange="changeType()" id="type4"<?php if ($edit && $editRow['Type'] == 4) echo ' checked="checked"'; ?>>Book Chapter</label><br />
	<label for="type5"><input name="TYPE" type="radio" value="5" onchange="changeType()" id="type5"<?php if ($edit && $editRow['Type'] == 5) echo ' checked="checked"'; ?>>Tech Report</label><br />
	<label for="type6"><input name="TYPE" type="radio" value="6" onchange="changeType()" id="type6"<?php if ($edit && $editRow['Type'] == 6) echo ' checked="checked"'; ?>>M.Sc. Thesis</label><br />
	<label for="type7"><input name="TYPE" type="radio" value="7" onchange="changeType()" id="type7"<?php if ($edit && $editRow['Type'] == 7) echo ' checked="checked"'; ?>>Ph.D. Dissertation</label>
	</td>
</tr>
<tr>
	<td class="prompt">Authors:</td>
	<td colspan="2"><span id="authorList"><?php if ($edit && (count($nameList) > 0)) echo implode(", ", $nameList); ?></span> [<a href="#" onclick="blur(); editAuthors(); return false"><b>edit</b></a>]</td>
</tr>
<tr>
	<td class="prompt">Title:</td>
	<td colspan="2"><input type="text" class="text" name="TITLE" id="firstText" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Title'])); ?>" /></td>
</tr>
<tr>
	<td class="prompt">Venue:</td>
	<td colspan="2"><input type="text" class="text" name="JOURNAL" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Journal'])); ?>" /></td>
</tr>
<tr>
	<td class="prompt">Abstract:</td>
	<td colspan="2"><textarea class="text" rows="5" name="ABSTRACT"><?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Abstract'])); ?></textarea></td>
</tr>
<tr id="schoolrow">
	<td class="prompt">School:</td>
	<td colspan="2"><input type="text" class="text" name="SCHOOL" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['School'])); ?>" /></td>
</tr>
<tr id="numberrow">
	<td class="prompt">Number:</td>
	<td colspan="2"><input type="text" class="text" name="NUMBER" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['TechNumber'])); ?>" /></td>
</tr>
<tr>
	<td class="prompt">Year:</td>
	<td width="90">
	<select name="YEAR" style="width: 75px">
	<?php
	
	$baseYear = 1995;
	$currentYear = (int)(date("Y"));
	
	for ($i = ($currentYear + 1); $i >= $baseYear; $i--)
	{
		echo "<option value=\"" . $i . "\"";
		
		if (($edit && $i == $editRow['Year']) || ($edit == false && $i == $currentYear))
		{
			echo " selected";
		}
		
		echo ">" . $i . "</option>";
	}
	
	?></select>
	</td>
	<td><label class="prompt"><input type="checkbox" name="TOAPPEAR" value="1" />To Appear</label></td>
</tr>
<tr id="volumerow">
	<td class="prompt">Volume:</td>
	<td colspan="2"><input type="text" class="text" name="VOLUME" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['VolumeNum'])); ?>" /></td>
</tr>
<tr id="issuerow">
	<td class="prompt">Issue:</td>
	<td colspan="2"><input type="text" class="text" name="ISSUE" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['IssueNum'])); ?>" /></td>
</tr>
<tr id="seriesrow">
	<td class="prompt">Series:</td>
	<td colspan="2"><input type="text" class="text" name="SERIES" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Series'])); ?>" /></td>
</tr>
<tr id="locationrow">
	<td class="prompt">Location:</td>
	<td colspan="2"><input type="text" class="text" name="LOCATION" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Location'])); ?>" /></td>
</tr>
<tr id="pagesrow">
	<td class="prompt">Pages:</td>
	<td colspan="2"><input type="text" class="text" name="PAGES" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Pages'])); ?>" /></td>
</tr>
<tr>
	<td class="prompt">DOI Number:</td>
	<td colspan="2"><input type="text" class="text" name="DOI" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['DOI'])); ?>" /></td>
</tr>
<tr id="isbnrow">
	<td class="prompt">ISBN:</td>
	<td colspan="2"><input type="text" class="text" name="ISBN" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['ISBN'])); ?>" /></td>
</tr>
<tr id="editionrow">
	<td class="prompt">Edition:</td>
	<td colspan="2"><input type="text" class="text" name="EDITION" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Edition'])); ?>" /></td>
</tr>
<tr id="chapterrow">
	<td class="prompt">Chapter:</td>
	<td colspan="2"><input type="text" class="text" name="CHAPTER" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['Chapter'])); ?>" /></td>
</tr>
<tr>
	<td class="prompt">Additional Info:</td>
	<td colspan="2"><input type="text" class="text" name="ADDITIONALINFO" value="<?php if ($edit) echo stripslashes(htmlspecialchars($editRow['AdditionalInfo'])); ?>" /></td>
</tr>
<tr>
	<td class="prompt">Image:</td>
	<td colspan="2"><table cellspacing="0" cellpadding="0"><tr><?php
	
	$imageExists = false;
	
	if ($edit)
	{
		$imageQuery = mysql_query("SELECT * FROM `publicationimage` WHERE `PublicationId`='$editId' ORDER BY `Id` DESC LIMIT 1");
		
		if (mysql_num_rows($imageQuery) == 1)
		{
			$imageRow = mysql_fetch_assoc($imageQuery);
			
			$imagePath = UPLOAD_DIR . "publication-" . $imageRow['Id'] . "-thumb.jpg";
			
			if (file_exists($imagePath))
			{
				echo "<td style=\"padding-right: 7px\">" . returnBorderedImage("../uploads/publication-" . $imageRow['Id'] . "-thumb.jpg") . "</td>";
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
	<td class="prompt">PDF:</td>
	<td colspan="2"><?php
	
	$pdfExists = false;
	
	if ($edit)
	{
		if ($editRow['PDFLink'])
		{
			$path = BASE_DIR . $editRow['PDFLink'];
			
			if (file_exists($path))
			{
				echo "<div class=\"pdf\" style=\"margin-bottom: 3px\">" . basename($path) . "</div>";
				$pdfExists = true;
			}
		}
	}
	
	?><input type="file" name="PDF" /><?php
	
	if ($pdfExists)
	{
		echo "<br /><span class=\"smalltext\">Uploading a new pdf will overwrite the current pdf.</span>";
	}
	
	?></td>
</tr>
<tr>
	<td class="prompt">Video:</td>
	<td colspan="2"><?php
	
	$videoExists = false;
	
	if ($edit)
	{
		if ($editRow['SecondaryLink'])
		{
			$path = BASE_DIR . $editRow['SecondaryLink'];
			
			if (file_exists($path))
			{
				echo "<div class=\"mov\" style=\"margin-bottom: 3px\">" . basename($path) . "</div>";
				$videoExists = true;
			}
		}
	}
	
	?><input type="file" name="VIDEO" /><?php
	
	if ($videoExists)
	{
		echo "<br /><span class=\"smalltext\">Uploading a new video will overwrite the current video.</span>";
	}
	
	?></td>
</tr>
<tr>
	<td></td>
	<td colspan="2">
		<input type="hidden" name="IDLIST" id="idList" value="<?php if ($edit) echo implode("|", $idList); ?>" />
		<input type="hidden" name="SAVEEDIT" value="<?php if ($edit) echo $editId; ?>" />
		<input type="submit" value="Submit" />
	</td>
</tr>
</table>
</form>

<?php

$query = mysql_query("SELECT * FROM `publication` ORDER BY `Year` DESC, `Title` ASC");

if (mysql_num_rows($query) > 0)
{
	?>
	<h2 style="margin: 25px 0 0">Publications</h2>
	
	<table cellspacing="0" cellpadding="2" width="525" style="border-bottom: 1px solid rgb(235, 235, 235)">
	<?php
	
	$currentYear = 0;
	$i = 1;
	
	while ($row = mysql_fetch_assoc($query))
	{
		// if we are starting to output a new year
		if ($row['Year'] != $currentYear)
		{
			echo "<tr class=\"pubdate\">";
			
			if ($i == 1)
			{
				echo "<td colspan=\"3\" style=\"border-top: 0\">";
			}
			else
			{
				echo "<td colspan=\"3\">";
			}
			
			echo $row['Year'] . "</td></tr>\n";
			$currentYear = $row['Year'];
			$i = 1;
		}
		
		if ($i % 2)
			echo "<tr class=\"pubrow alternate\">";
		else
			echo "<tr class=\"pubrow\">";
		
		echo "<td>" . shortenTitle(htmlspecialchars(stripslashes($row['Title']))) . "</td>";
		echo "<td class=\"edit\"><a href=\"publications.php?edit=" . $row['Id'] . "\">edit</a></td>";
		echo "<td class=\"delete\"><a href=\"publications.php?delete=" . $row['Id'] . "\">delete</a></td>";
		echo "</tr>";
		
		$i++;
	}
	
	?>
	</table>
	<?php
}

?>

<script type="text/javascript">

changeType();

</script>

<?php

outputFooter();

?>
