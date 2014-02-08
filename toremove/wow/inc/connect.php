<?php
include_once("inc/config.php");

/*Comment code: meh!*/

$mysqli = new mysqli($sql_login["host"], $sql_login["user"], $sql_login["password"],$sql_login["database"]);

if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function terminate(){
global $mysqli;
$terminate = 0;

$query = "SELECT * FROM codes";
$result = $mysqli->query($query);
$n = $result->num_rows;

$query = "SELECT * FROM participant";
$result = $mysqli->query($query);
$p = $result->num_rows;

if($p >  $n*10){
	$terminate = 1;
}

return $terminate;
}

function get_winner(){
	global $mysqli;

	$query = "SELECT * FROM `codes` WHERE subject IS NOT NULL";	
	$result = $mysqli->query($query);
	$row = $result->num_rows;
	
	return $row;
}

function get_code($subjectid){
	global $mysqli;

	$query = "SELECT * FROM codes WHERE subject IS NULL";
	$result = $mysqli->query($query);
	$n = $result->num_rows;
	
	if($n>0){
		$row = $result->fetch_assoc();

		$query = "UPDATE codes, (SELECT * FROM `participant` WHERE subjectid = '".$subjectid."') p SET codes.subject = p.id WHERE codes.id =".$row["ID"];	
		$result = $mysqli->query($query);
		
		$query = "UPDATE counter SET winner = winner + 1 ";
		$result = $mysqli->query($query);
		
		$code = $row["code"];
	} else {
		$code = 0;
	}
	
	return $code;
}

function set_participation($subjectid){
	global $mysqli;
	
	$query = "INSERT INTO `participant` SET subjectid = '".$subjectid."'";
	$result = $mysqli->query($query);
	
	return;
}

function get_participation($subjectid){
	global $mysqli;
	
	$query = "SELECT subjectid FROM `participant` WHERE subjectid = '".$subjectid."'";
	
	$result = $mysqli->query($query);
	$row = $result->num_rows;
	
	return $row;
}

function get_total_participation(){
	global $mysqli;
	
	$query = "SELECT * FROM `participant`";
	
	$result = $mysqli->query($query);
	$row = $result->num_rows;
	
	return $row;
}

function lottery($subjectid){
	global $mysqli;
	
	$lottery_result = 0;

	
	$query = "SELECT * FROM `urn` WHERE prize = 1";	
	$result = $mysqli->query($query);
	$n = $result->num_rows;
	
	$query = "SELECT * FROM `participant` WHERE subjectid = '".$subjectid."'";
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	
	$subject = $row["ID"];

	if(!$n || ($subject % 10) == 1){
		$query = "UPDATE `urn` SET prize = 0" ;	
		$result = $mysqli->query($query);
	
		$query = "UPDATE `urn` SET prize = 1 WHERE ID =".rand(1,10) ;	
		$result = $mysqli->query($query);
	}
		
	$query = "SELECT * FROM `urn` WHERE prize = 1";	
	$result = $mysqli->query($query);	
	$row = $result->fetch_assoc();	
	$prize = $row["ID"];

	//echo "PRIZE: ".($prize % 10). "SUBJECT: ". ($subject % 10);
	
	if(($prize % 10) == ($subject % 10)){
		$lottery_result = 1;
	}

	return $lottery_result;
}

// Qualified users:
// Survery completion: 1
// Survey count: 1
// Participation: 0;
function qualification($subjectid){

	$status = subject_status($subjectid);

	$completed = $status["_completed"];
	$participated = $status["_participation"];
	$count = $status["_count"];
	
	$r = 0;

	if($completed){
		if($count == 1){
			if(!$participated){
				$r = 1;					
			} else {
				$r = 4; //error code: participated
			}
		} else {
			$r = 3; //error code: several datasets			
		}	
	} else {
		$r = 2; //error code: not complete		
	}
	
	return $r;
}

function subject_status($subjectid){
	global $survey;
		
	$param = "responses/?subjectid=".$subjectid;	
	$url = $survey . $param;	
	
	$data = GET($url);
	$data = json_decode($data);
	
	$completed = $data->responses[0]->_completed;
	$completion_time = $data->responses[0]->_completion_time;
	$participation = get_participation($subjectid);
	$count = $data->count;		

	$status = array(
		"_completed" => $completed,
		"_completion_time" => $completion_time,
		"_participation" => $participation,
		"_count" => $count,
	);	
		
	return $status;
}

function GET($url){
        global $api_key, $password;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $api_key.':'.$password);
        curl_setopt($curl, CURLOPT_SSLVERSION,3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;
                MSIE 5.01; Windows NT 5.0)");
        curl_setopt($curl, CURLOPT_URL, $url);
        $data = curl_exec($curl);
        curl_close($curl);
        return $data;
}
?>