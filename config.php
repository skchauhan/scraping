<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scrapping";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function pre($data) {
	if(is_array($data) || is_object($data)) {
		print"<pre>";
		print_r($data);
		print"</pre>";
	} else {
		echo "<pre>";
		echo $data;
		echo "</pre>";
	}
}

function getTotalPage() {
	global $conn;
	$totlPage = 0;
	$strQQ = "SELECT total_page FROM `page_counter` WHERE `website` = 'linkedin'";
	$strRR = $conn->query($strQQ) or die(mysqli_error($conn));
	if($strRR) {
		$row = 	mysqli_fetch_assoc($strRR);
		$totlPage = $row['total_page'];
	}
	return $totlPage;
}

function convertDtoDate($strTime) {
	$curDate = date('Y-m-d');
	if(!empty($strTime)) {				
		if(trim($strTime) == "30d+") {
			$strTime = '30 d';
		}
		$arrTime = explode(' ', trim($strTime));
		$intNum = $arrTime[0];
		$strTimeType = $arrTime[1];
		$newStrType = '';
		if($strTimeType == 'd') {
			$newStrType = 'day';
			$postDay = $intNum.' '.$newStrType;	
		} elseif($strTimeType == 'hr') {
			$postDay = '0 day';	
		} 
		return date('Y-m-d',(strtotime ( '-'.$postDay , strtotime ( $curDate) ) ));
	}
}


/*function insertIndeedPost($strKeyWord, $strDescription, $companyName, $jobLocation, $jobLink, $strDate) {
	global $conn;
	$sql = "INSERT INTO `job_posts` (`keyword`, `title`, `description`, `company_name`, `location`, `website_name`, `link`, `date`) VALUES ('php', '$strKeyWord', '".addslashes($strDescription)."', '$companyName', '$jobLocation', 'linkedin', '$jobLink', '$strDate')";
	$conn->query($sql) or die(mysqli_error($conn));
}*/