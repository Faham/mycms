<?php

// default timezone
date_default_timezone_set('America/Regina');

// global constants
define("BASE_URL", "/hci/");
define("BASE_DIR", "/hci/www/html/");
define("UPLOAD_DIR", BASE_DIR . "uploads/");

// create a connection to the database
// print an error message in case of failure
function dbConnect()
{
	if (!mysql_connect('localhost', 'root', '2501'))
	{
		die('Database connection failure');
	}
	
	if (!mysql_select_db('hci'))
	{
		die('Database selection failure');
	}
	
	mysql_query("SET NAMES 'utf8'");
}

// output a header
// the supplied integer parameter will add a "selected" effect to the menu
function outputHeader($selectedTab = 0, $title = "", $metaDesc = "")
{
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en">
<head>
	<title><?php
	
	echo "Interaction Lab";
	
	if ($title)
	{
		echo " | " . $title;
	}
	
	?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><?php
    if ($metaDesc) echo "\n\t<meta name=\"description\" content=\"$metaDesc\" />\n"; ?>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/main.css" type="text/css" media="all" />
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/print.css" type="text/css" media="print" />
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/js/main.js"></script>
	<link rel="shortcut icon" href="<?php echo BASE_URL; ?>images/favicon.png" />
	<!--[if lte IE 6]>
		<link type="text/css" rel="stylesheet" href="<?php echo BASE_URL; ?>/css/ie.css" media="all" />
	<![endif]-->
	<style type="text/css">
	<?php
	
	$imageList = array();
	$imageList[] = "header-graphic-green.gif";
	$imageList[] = "header-graphic-green-2.gif";
	$imageList[] = "header-graphic-green-3.gif";
	$imageList[] = "header-graphic-green-4.gif";
	
	// pick a random image out of the array
	$image = BASE_URL . "images/" . $imageList[rand(0, sizeof($imageList) - 1)];
	
	echo "#graphic {\n";
	echo "\t\tbackground-image: url($image);\n";
	echo "\t}\n";
	
	?>
	</style>
</head>
<body onload="doLoad()">
<div id="container">
	<div id="top"></div>
	<div id="header">
		<div id="logo">
			<a href="<?php echo BASE_URL; ?>"><img src="<?php echo BASE_URL; ?>images/logo-green.gif" width="261" height="61" alt="the interaction lab" border="0" /></a>
		</div>
		<div id="graphic">
			
		</div>
	</div>
	
	<ul id="menu">
		<li><a href="<?php echo BASE_URL; ?>"<?php if ($selectedTab == 1) echo ' class="selected"'; ?>>Home</a></li>
		<li><a href="<?php echo BASE_URL; ?>people/"<?php if ($selectedTab == 2) echo ' class="selected"'; ?>>People</a></li>
		<li><a href="<?php echo BASE_URL; ?>research/"<?php if ($selectedTab == 3) echo ' class="selected"'; ?>>Research</a></li>
		<li><a href="<?php echo BASE_URL; ?>publications/"<?php if ($selectedTab == 4) echo ' class="selected"'; ?>>Publications</a></li>
		<li><a href="<?php echo BASE_URL; ?>classinfo/"<?php if ($selectedTab == 5) echo ' class="selected"'; ?>>Classes</a></li>
		<li><a href="<?php echo BASE_URL; ?>software/"<?php if ($selectedTab == 6) echo ' class="selected"'; ?>>Software</a></li>
		<li><a href="<?php echo BASE_URL; ?>contact/"<?php if ($selectedTab == 7) echo ' class="selected"'; ?>>Contact</a></li>
		<li style="margin-left: 281px"><a href="https://papyrus.usask.ca/trac/hci/">Trac</a></li>
	</ul>
	
	<div id="content">
<?php
}

// output the footer
function outputFooter()
{?>
	</div>
</div>
<div id="footer">
	<div id="innerfooter">&copy; <?php echo date("Y"); ?> The Interaction Lab, University of Saskatchewan</div>
</div>
</body>
</html><?php
}

// this is used to generate slighly fancier headers
// it adds the styles neccessary to essentially draw a strikethrough, except the
// strikethrough only goes through the background and not the text
function outputStrikethrough($input)
{
	echo '<div class="strike">';
	echo '<div class="line"></div>';
	echo $input;
	echo "</div>\n";
}

// outputs a title and calls outputPublication to do the work
function outputPublicationWithTitle($row)
{
	echo "<table class=\"publication\">\n";
	echo "<tr>\n";
	echo "<td class=\"publeft\" valign=\"top\">\n";
	
	if ($row['PDFLink'])
	{
		echo "<a href=\"";
		
		if (substr($row['PDFLink'], 0, 4) == "http")
		{
			echo htmlspecialchars(stripslashes($row['PDFLink']));
		}
		else
		{
			echo BASE_URL . htmlspecialchars(stripslashes($row['PDFLink']));
		}
		
		echo "\"><img src=\"" . BASE_URL . "images/pdf.png\" width=\"17\" border=\"0\" align=\"top\"></a>\n";
	}
	
	if ($row['SecondaryLink'])
	{
		echo "<a href=\"";
		
		if (substr($row['SecondaryLink'], 0, 4) == "http")
		{
			echo htmlspecialchars(stripslashes($row['SecondaryLink']));
		}
		else
		{
			echo BASE_URL . htmlspecialchars(stripslashes($row['SecondaryLink']));
		}
		
		echo "\">";
		
		if (strpos($row['SecondaryLink'], ".mov"))
		{
			echo "<img src=\"" . BASE_URL . "images/mov.png\" width=\"17\" style=\"margin-left: 1px\" border=\"0\">";
		}
		else
		{
			echo "<img src=\"" . BASE_URL . "images/wmv.png\" width=\"17\" style=\"margin-left: 1px\" border=\"0\">";
		}
		
		echo "</a>\n";
	}
	
	echo "</td>\n";
	echo "<td class=\"pubright\">\n";
	echo "<a href=\"" . BASE_URL . "publications/view.php?id=" . $row['Id'] . "\" class=\"pub\">" . htmlspecialchars(stripslashes($row['Title'])) . "</a><br />\n";
	
	outputPublication($row);
	
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}

// output a publication - used in several places: publication list, people pages, project pages
function outputPublication($row)
{
	$id = (int)($row['Id']);
	
	$authorQuery = mysql_query("SELECT `person`.* FROM `publicationperson`, `person` WHERE `publicationperson`.`PublicationId`='$id' AND `publicationperson`.`PersonId`=`person`.`Id` ORDER BY `publicationperson`.`Order` ASC");
	
	if (mysql_num_rows($authorQuery) > 0)
	{
		$arrayBuild = array();
		
		while ($authorRow = mysql_fetch_assoc($authorQuery))
		{
			if ($authorRow['FirstName'] && $authorRow['LastName'])
			{
				$arrayBuild[] = htmlspecialchars(stripslashes($authorRow['LastName'])) . ", " . htmlspecialchars(stripslashes(substr($authorRow['FirstName'], 0, 1))) . ".";
			}
			else
			{
				$arrayBuild[] = htmlspecialchars(stripslashes($authorRow['FirstName']));
			}
		}
		
		echo implode(", ", $arrayBuild);
	}
	
	if ($row['Year'])
	{
		echo " (" . htmlspecialchars(stripslashes($row['Year'])) . ")";
	}
	
	if ($row['Type'] == 6)
	{
		echo " <i>M.Sc. Thesis</i>";
	}
	
	if ($row['Type'] == 7)
	{
		echo " <i>Ph.D. Dissertation</i>";
	}
	
	if ($row['Journal'])
	{
		echo ", <i>" . stripslashes(htmlspecialchars($row['Journal'])) . "</i>";
	}
	
	if ($row['VolumeNum'] || $row['IssueNum'] || $row['Series'] || $row['ISBN'] || $row['Edition'] || 
			$row['Pages'] || $row['Location'] || $row['TechNumber'] || $row['School'] || $row['ToAppear'] || $row['AdditionalInfo'])
	{
		echo ",";
	}
	else
	{
		echo ".";
	}
	
	if ($row['VolumeNum'])
	{
		echo " vol. " . stripslashes(htmlspecialchars($row['VolumeNum']));
	}
	
	if ($row['IssueNum'])
	{
		echo " no. " . stripslashes(htmlspecialchars($row['IssueNum']));
	}
	
	if ($row['VolumeNum'] || ($row['IssueNum']))
	{
		echo ",";
	}
	
	if ($row['Series'])
	{
		echo " " . stripslashes(htmlspecialchars($row['Series'])) . ".";
	}
	
	if ($row['ISBN'])
	{
		echo " ISBN " . stripslashes(htmlspecialchars($row['ISBN'])) . ".";
	}
	
	if ($row['Edition'])
	{
		echo " " . stripslashes(htmlspecialchars($row['Edition'])) . " edition.";
	}
	
	if ($row['Chapter'])
	{
		echo " Chapter " . stripslashes(htmlspecialchars($row['Chapter'])) . ".";
	}
	
	if ($row['Location'])
	{
		echo " " . stripslashes(htmlspecialchars($row['Location'])) . ".";
	}
	
	if ($row['Pages'])
	{
		echo " " . stripslashes(htmlspecialchars($row['Pages'])) . ".";
	}
	
	if ($row['TechNumber'])
	{
		echo " <i>Technical Report " . stripslashes(htmlspecialchars($row['TechNumber'])) . "</i>,";
	}
	
	if ($row['School'])
	{
		echo " <i>" . stripslashes(htmlspecialchars($row['School'])) . "</i>.";
	}
	
	if ($row['ToAppear'])
	{
		echo " To appear.";
	}
	
	if ($row['AdditionalInfo'])
	{
		echo " " . stripslashes(htmlspecialchars($row['AdditionalInfo'])) . ".";
	}
	
	if ($row['DOI'])
	{
		echo " &lt;<a href=\"http://dx.doi.org/" . stripslashes(htmlspecialchars($row['DOI'])) . "\">doi:" . stripslashes(htmlspecialchars($row['DOI'])) . "</a>&gt;";
	}
	
	echo "\n";
}

// output a BibTeX entry - used on publication pages
function outputBibTeX($row)
{
	$id = (int)($row['Id']);
	
	echo "<table cellspacing=\"0\" cellpadding=\"0\">";
	echo "<tr>";
	echo "<td colspan=\"3\">";
	
	if ($row['Type'] == 1)
	{
		echo "@inproceedings";
	}
	else if ($row['Type'] == 2)
	{
		echo "@article";
	}
	else if ($row['Type'] == 3)
	{
		echo "@book";
	}
	else if ($row['Type'] == 4)
	{
		echo "@inbook";
	}
	else if ($row['Type'] == 5)
	{
		echo "@techreport";
	}
	else if ($row['Type'] == 6)
	{
		echo "@mastersthesis";
	}
	else if ($row['Type'] == 7)
	{
		echo "@phdthesis";
	}
	
	echo " {";
	
	if ($row['PDFLink'])
	{
		$tag = basename($row['PDFLink']);
		$tag = substr($tag, 0, -4);
		echo htmlspecialchars(stripslashes($tag)) . ",";
	}
	
	echo "</td>";
	echo "</tr>";
	
	$output = "";
	
	$authorQuery = mysql_query("SELECT `person`.* FROM `publicationperson`, `person` WHERE `publicationperson`.`PublicationId`='$id' AND `publicationperson`.`PersonId`=`person`.`Id` ORDER BY `publicationperson`.`Order` ASC");
	
	if (mysql_num_rows($authorQuery) > 0)
	{
		$arrayBuild = array();
		
		while ($authorRow = mysql_fetch_assoc($authorQuery))
		{
			if ($authorRow['FirstName'] && $authorRow['LastName'])
			{
				$arrayBuild[] = htmlspecialchars(stripslashes($authorRow['FirstName'])) . " " . htmlspecialchars(stripslashes($authorRow['LastName']));
			}
			else
			{
				$arrayBuild[] = htmlspecialchars(stripslashes($authorRow['FirstName']));
			}
		}
		
		if (sizeof($arrayBuild) > 0)
		{
			$output .= "<tr><td class=\"bibtexleft\">author</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . implode(" and ", $arrayBuild) . "},</td></tr>";
		}
	}
	
	if ($row['Title'])
	{
		$output .= "<tr><td class=\"bibtexleft\">title</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['Title'])) . "},</td></tr>";
	}
	
	if ($row['Journal'])
	{
		$output .= "<tr><td class=\"bibtexleft\">booktitle</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['Journal'])) . "},</td></tr>";
	}
	
	if ($row['Year'])
	{
		$output .= "<tr><td class=\"bibtexleft\">year</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['Year'])) . "},</td></tr>";
	}
	
	if ($row['VolumeNum'])
	{
		$output .= "<tr><td class=\"bibtexleft\">volume</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['VolumeNum'])) . "},</td></tr>";
	}
	
	if ($row['IssueNum'])
	{
		$output .= "<tr><td class=\"bibtexleft\">number</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['IssueNum'])) . "},</td></tr>";
	}
	
	if ($row['Series'])
	{
		$output .= "<tr><td class=\"bibtexleft\">series</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['Series'])) . "},</td></tr>";
	}
	
	if ($row['Location'])
	{
		$output .= "<tr><td class=\"bibtexleft\">address</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['Location'])) . "},</td></tr>";
	}
	
	if ($row['TechNumber'])
	{
		$output .= "<tr><td class=\"bibtexleft\">number</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['TechNumber'])) . "},</td></tr>";
	}
	
	if ($row['School'])
	{
		$output .= "<tr><td class=\"bibtexleft\">school</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['School'])) . "},</td></tr>";
	}
	
	if ($row['Pages'])
	{
		$output .= "<tr><td class=\"bibtexleft\">pages</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['Pages'])) . "},</td></tr>";
	}
	
	if ($row['Edition'])
	{
		$output .= "<tr><td class=\"bibtexleft\">edition</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['Edition'])) . "},</td></tr>";
	}
	
	if ($row['Chapter'])
	{
		$output .= "<tr><td class=\"bibtexleft\">chapter</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['Chapter'])) . "},</td></tr>";
	}
	
	if ($row['AdditionalInfo'])
	{
		$output .= "<tr><td class=\"bibtexleft\">note</td><td class=\"bibtexmiddle\">=</td><td class=\"bibtexright\"> {" . htmlspecialchars(stripslashes($row['AdditionalInfo'])) . "},</td></tr>";
	}
	
	$output = substr($output, 0, -11);
	
	echo $output;
	
	echo "<tr>";
	echo "<td colspan=\"3\">";
	echo "}";
	echo "</td>";
	echo "</tr>";
	echo "</table>\n";
}

// output a citation - used on publication pages
function outputCitation($row)
{
	$id = (int)($row['Id']);
	
	$authorQuery = mysql_query("SELECT `person`.* FROM `publicationperson`, `person` WHERE `publicationperson`.`PublicationId`='$id' AND `publicationperson`.`PersonId`=`person`.`Id` ORDER BY `publicationperson`.`Order` ASC");
	
	if (mysql_num_rows($authorQuery) > 0)
	{
		$arrayBuild = array();
		
		while ($authorRow = mysql_fetch_assoc($authorQuery))
		{
			$name = "";
			
			if ($authorRow['LastName'] && $authorRow['FirstName'])
			{
				$name .= htmlspecialchars(stripslashes($authorRow['LastName'])) . ", " . htmlspecialchars(stripslashes(substr($authorRow['FirstName'], 0, 1))) . ".";
			}
			else if ($authorRow['FirstName'])
			{
				$name .= htmlspecialchars(stripslashes($authorRow['FirstName']));
			}
			
			if ($authorRow['MiddleInitial'])
			{
				$name .= htmlspecialchars(stripslashes($authorRow['MiddleInitial']));
				
				if (substr($name, -1) != ".")
				{
					$name .= ".";
				}
			}
			
			$arrayBuild[] = $name;
		}
		
		echo implode(", ", $arrayBuild);
	}
	
	if ($row['Year'])
	{
		echo " " . htmlspecialchars(stripslashes($row['Year'])) . ".";
	}
	
	if ($row['Title'])
	{
		echo " " . htmlspecialchars(stripslashes($row['Title'])) . ".";
	}
	
	if ($row['Type'] == 6)
	{
		echo " <i>M.Sc. Thesis</i>";
	}
	
	if ($row['Type'] == 7)
	{
		echo " <i>Ph.D. Dissertation</i>";
	}
	
	if ($row['Journal'])
	{
		echo " In <i>" . stripslashes(htmlspecialchars($row['Journal'])) . "</i>";
	}
	
	if ($row['VolumeNum'] || $row['IssueNum'] || $row['Series'] || $row['ISBN'] || $row['Edition'] || 
			$row['Pages'] || $row['Location'] || $row['TechNumber'] || $row['School'] || $row['ToAppear'] || $row['AdditionalInfo'])
	{
		echo ",";
	}
	else
	{
		echo ".";
	}
	
	if ($row['VolumeNum'])
	{
		echo " vol. " . stripslashes(htmlspecialchars($row['VolumeNum']));
	}
	
	if ($row['IssueNum'])
	{
		echo " no. " . stripslashes(htmlspecialchars($row['IssueNum']));
	}
	
	if ($row['VolumeNum'] || ($row['IssueNum']))
	{
		echo ",";
	}
	
	if ($row['Series'])
	{
		echo " " . stripslashes(htmlspecialchars($row['Series'])) . ".";
	}
	
	if ($row['ISBN'])
	{
		echo " ISBN " . stripslashes(htmlspecialchars($row['ISBN'])) . ".";
	}
	
	if ($row['Edition'])
	{
		echo " " . stripslashes(htmlspecialchars($row['Edition'])) . " edition.";
	}
	
	if ($row['Chapter'])
	{
		echo " Chapter " . stripslashes(htmlspecialchars($row['Chapter'])) . ".";
	}
	
	if ($row['Location'])
	{
		echo " " . stripslashes(htmlspecialchars($row['Location'])) . ".";
	}
	
	if ($row['Pages'])
	{
		echo " " . stripslashes(htmlspecialchars($row['Pages'])) . ".";
	}
	
	if ($row['TechNumber'])
	{
		echo " <i>Technical Report " . stripslashes(htmlspecialchars($row['TechNumber'])) . "</i>,";
	}
	
	if ($row['School'])
	{
		echo " <i>" . stripslashes(htmlspecialchars($row['School'])) . "</i>.";
	}
	
	if ($row['ToAppear'])
	{
		echo " To appear.";
	}
	
	if ($row['AdditionalInfo'])
	{
		echo " " . stripslashes(htmlspecialchars($row['AdditionalInfo'])) . ".";
	}
	
	if ($row['DOI'])
	{
		echo " DOI=<a href=\"http://dx.doi.org/". stripslashes(htmlspecialchars($row['DOI'])) . "\">" . stripslashes(htmlspecialchars($row['DOI'])) . "</a>.";
	}
	
	echo "\n";
}

// output a table cell with project details
function outputProjectRowWithImage($row)
{
	echo "<tr>\n";
	echo "<td>\n";
	
	// project image
	$imageQuery = mysql_query("SELECT `Id` FROM `projectimage` WHERE `ProjectId`='" . $row['Id'] . "' LIMIT 1");
	
	if (@mysql_num_rows($imageQuery) == 1)
	{
		$imageRow = mysql_fetch_assoc($imageQuery);
		$imagePath = UPLOAD_DIR . "project-" . $imageRow['Id'] . "-thumb.jpg";
		
		if (file_exists($imagePath))
		{
			echo "<a href=\"" . BASE_URL . "research/view.php?id=" . $row['Id'] . "\">" . returnBorderedImage(BASE_URL . "uploads/project-" . $imageRow['Id'] . "-thumb.jpg") . "</a>\n";
		}
		else
		{
			echo "<a href=\"" . BASE_URL . "research/view.php?id=" . $row['Id'] . "\"><img src=\"" . BASE_URL . "images/noimage-50.gif\" width=\"40\" height=\"40\" class=\"thumbnail\" /></a>\n";
		}
	}
	else
	{
		echo "<a href=\"" . BASE_URL . "research/view.php?id=" . $row['Id'] . "\"><img src=\"" . BASE_URL . "images/noimage-50.gif\" width=\"40\" height=\"40\" class=\"thumbnail\" /></a>\n";
	}
	
	echo "</td>\n";
	echo "<td class=\"textsideproj\">\n";
	
	echo "<a href=\"" . BASE_URL . "research/view.php?id=" . $row['Id'] . "\" class=\"proj\">" . htmlspecialchars(stripslashes($row['Name'])) . "</a>\n";
	
	if ($row['Summary'])
	{
		echo "<br />";
		outputSummary(strip_tags(stripslashes($row['Summary'])), 100);
		echo "\n";
	}
	
	echo "</td>\n";
	echo "</tr>\n";
}

// output table cells with person
function outputPersonCell($row)
{
	echo "<td width=\"43\">\n";
	
	// project image
	$imageQuery = mysql_query("SELECT `Id` FROM `personimage` WHERE `PersonId`='" . $row['Id'] . "' ORDER BY `Id` DESC LIMIT 1");
	
	if (@mysql_num_rows($imageQuery) == 1)
	{
		$imageRow = mysql_fetch_assoc($imageQuery);
		$imagePath = UPLOAD_DIR . "person-" . $imageRow['Id'] . "-thumb.jpg";
		
		if (file_exists($imagePath))
		{
			echo "<a href=\"" . BASE_URL . "people/view.php?id=" . $row['Id'] . "\">" . returnBorderedImage(BASE_URL . "uploads/person-" . $imageRow['Id'] . "-thumb.jpg") . "</a>";
		}
		else
		{
			echo "<a href=\"" . BASE_URL . "people/view.php?id=" . $row['Id'] . "\">" . returnBorderedImage(BASE_URL . "images/noimage-40.gif") . "</a>";
		}
	}
	else
	{
		echo "<a href=\"" . BASE_URL . "people/view.php?id=" . $row['Id'] . "\">" . returnBorderedImage(BASE_URL . "images/noimage-40.gif") . "</a>";
	}
	
	echo "</td>\n";
	echo "<td width=\"45%\" class=\"textside\">\n";
	
	echo "<a href=\"" . BASE_URL . "people/view.php?id=" . $row['Id'] . "\" class=\"person\">" . htmlspecialchars(stripslashes($row['FirstName'])) . " " . htmlspecialchars(stripslashes($row['LastName'])) . "</a>";
	
	if ($row['Affiliation'])
	{
		echo "<br /><span class=\"affiliation\">" . htmlspecialchars(stripslashes($row['Affiliation'])) . "</span>";
	}
	
	echo "</td>\n";
}

// output text, adding paragraph tags if necessary
function paragraph($text)
{
	$text = trim($text);
	
	if (substr($text, 0, 3) != "<p>")
	{
		echo "<p>";
	}
	
	echo $text;
	
	if (substr($text, -4) != "</p>")
	{
		echo "</p>";
	}
	
	echo "\n";
}

// shorten a block of text to a certain length
function outputSummary($text, $maxLen)
{
	if (strlen($text) > $maxLen)
	{
		$textBits = explode(". ", $text);
		$textBits = array_reverse($textBits);
		$newText = "";
		
		while (strlen($newText) < $maxLen && sizeof($textBits) > 0)
		{
			$newText .= array_pop($textBits) . ". ";
		}
		
		echo $newText;
	}
	else
	{
		echo $text;
	}
}

function returnBorderedImage($src)
{
	// ie6 chokes on alpha pngs, so only give fancy png borders to non-ie6-browsers
	if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 6.0") === false)
	{
		return "<img src=\"" . BASE_URL . "images/rounded-border-shadow.png\" width=\"43\" height=\"43\" class=\"thumbnail\" style=\"background-image: url(" . $src . "); background-repeat: no-repeat\">";
	}
	else
	{
		return "<img src=\"" . $src . "\" width=\"40\" height=\"40\" class=\"thumbnail\">";
	}
}

function returnBorderedImageWithText($src, $alt)
{
	// ie6 chokes on alpha pngs, so only give fancy png borders to non-ie6-browsers
	if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE 6.0") === false)
	{
		return "<img src=\"" . BASE_URL . "images/rounded-border-shadow.png\" width=\"43\" height=\"43\" class=\"thumbnail\" alt=\"" . $alt . "\" style=\"background-image: url(" . $src . "); background-repeat: no-repeat\">";
	}
	else
	{
		return "<img src=\"" . $src . "\" width=\"40\" height=\"40\" class=\"thumbnail\" alt=\"" . $alt . "\">";
	}
}

?>
