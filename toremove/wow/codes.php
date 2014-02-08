<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>WoW Character Relationship Survey</title>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8" /> 
	<link rel="stylesheet" type="text/css" href="css/style.css">
	
	<!-- source: 
	Soul of the Aspects header: http://us.media2.battle.net/cms/blog_header/1H0NJPRDIXQF1334076756014.jpg
	Soul of the Aspects breathing fire: http://wow.zamimg.com/uploads/screenshots/normal/306587.jpg 
	Soul of the Aspects icon: http://wow.zamimg.com/images/wow/icons/large/inv_pet_chromaticdragon.jpg
	Mojo icon : http://wow.zamimg.com/images/wow/icons/large/spell_shaman_hex.jpg
	-->	
</head>

<body>
<?php
include_once "inc/connect.php";

$coin1 = "frog";
$coin2 = "frog";
$coin3 = "frog";

$message = "";
$link = 0;

$subjectid = $_GET["subjectid"];

$q = qualification($subjectid); 
$t = terminate();

//$q = 1;
if(!$t){
if($q == 1){
	set_participation($subjectid);
	
	if(lottery($subjectid) || get_total_participation() <= 3){
		$coin1 = "dragon";
		$coin2 = "dragon";
		$coin3 = "dragon";
		
		$message = "Congratulations, you are a winner! <br />Your code is: <span style='color:#FF0000'>". get_code($subjectid)."</span>";
		$link = 1;
	} else {
		$coin1 = "dragon";
		$coin2 = "dragon";
		$message =  "Sorry, you have not won. Thanks for participating.";
	}
} else {
	$link = 2;
				
	switch ($q){
		case 2:
			$message =  'First, complete our survey: <a href="https://fluidsurveys.usask.ca/s/wow-survey">get started!</a>';
			break;
		case 3:
			$message =  'Sorry, you completed more than one survey for your avatar.';
			break;
		case 4:
			$message =  'Sorry, you already participated.';
			break;
		default:
			$message =  "Sorry, you did't qualify for participation.";
	}
}
} else {
	echo "Our survey is closed.";
}

$counter_winner = get_winner(); 
$total_participation = get_total_participation();

$mysqli->close();

?>

<div class="wrapper" id="stylized">
	<a href="http://www.usask.ca" target="_blank" style="display:block;"><img src="images/UofS_FCR_SM_RGB.png" style="height:30%;width:30%;margin:10px; float:right" alt="University of Saskatchewan"/></a>
<div class="header">
		
			<h1>Learn about your relationship to your character</h1>
			<h2>and get the chance to win a Soul of the Aspects!</h2>
</div>
<div>	
	<h2><?php if($counter_winner > 0){echo "Others have already won pets! ";} echo $counter_winner; ?> out of <?php echo $total_participation ?> respondents have won. <br />
	Let's see if you are a lucky winner:</h2>
</div>

<div id="win">
<div class="box">
<div class="coins">
	<div class="dragon">
		<img src="images/<?php echo $coin1 ?>.png" alt="" class="first"/>
		<img src="images/<?php echo $coin2 ?>.png" alt=""/>
		<img src="images/<?php echo $coin3 ?>.png" alt=""/>
	</div>
</div>
<div class="draw">
<b>
 <?php echo $message ?>
</b>
</div>
</div>
</div>


<ol>
<?php

if($link == 1){
echo '<li>Copy your code, or write it down.</li>
	<li>Visit the <a href="https://us.battle.net/login/en/?ref=https://us.battle.net/account/management/redemption/redeem.html&app=bam&cr=true" target="_blank">Pet Store</a> to redeem your code.</li>
	<li>Check out the <a href="index.php?#results">meaning</a> of your IAT results.</li>
	<li>Post your results to the forum, and let them know that you won a pet.</li>';
} else if($link == 2){

}
else {
echo '<li>Check out the <a href="index.php?#results">meaning</a> of your IAT results.</li>
	<li>Post your results to the forum.</li>';
}
?>
	
</ol>
<h2>Tell me about my IAT results!</h2>
<p>One goal of this study is to determine, if it is possible to measure the relationship between a player and their character using the IAT. The results reported back to you describe where you fit on a spectrum. We are studying three ways of characterizing relationship between players and their characters: autonomy, competence, and relatedness.</p>

<p><a href="index.php?#results">Detailed description of the results</a></p>

<a href="http://hci.usask.ca" target="_blank"><img src="images/logo-green.gif" style="height:45%;width:45%;margin:0px 0 10px 10px; float:left" alt="the interaction lab"/></a>
<a href="http://www.usask.ca" target="_blank"><img src="images/PREF_FC_SM_RGB.png" style="height:40%;width:40%;;margin:0px 0 10px 15px" alt="University of Saskatchewan"/></a>
</div>
</body>

</html>