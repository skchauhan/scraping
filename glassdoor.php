<?php 
set_time_limit(0);
include 'vendor/autoload.php';
include 'config.php';
use Goutte\Client;
$client = new Client();

/*https://www.glassdoor.co.in/Job/jobs.htm?sc.keyword=ios%20developer&locT=N&locId=1&locKeyword=United%20States&jobType=fulltime&fromAge=-1&radius=31&cityId=-1&minRating=0.0&industryId=-1&sgocId=-1&companyId=-1&employerSizes=0&applicationType=0&remoteWorkType=0*/


$siteUrl = "https://www.glassdoor.co.in/Job/jobs.htm?sc.keyword=ios%20developer&locT=N&locId=1&locKeyword=United%20States&jobType=fulltime&fromAge=-1&radius=31&cityId=-1&minRating=0.0&industryId=-1&sgocId=-1&companyId=-1&employerSizes=0&applicationType=0&remoteWorkType=0";

$crawler = $client->request('GET', $siteUrl);

$crawler->filter('li.jl')->each(function ($node) {

	$strKeyword = '-'; $strCompanyName = '-'; $strJobLink = '-'; $strLocation = '-'; $strTime = '-'; $strSalary = '-'; $strCity = ''; $strState = '';

	if($node->filter('.jobContainer .jobHeader + a')->extract('_text')) {
		$strKeyword = $node->filter('.jobContainer .jobHeader + a')->extract('_text')[0];
	}

	if($node->filter('div.jobHeader .jobLink')->extract(['_text', 'href'])) {
		$arrCompany = $node->filter('div.jobHeader .jobLink')->extract(['_text', 'href'])[0];
		if(!empty($arrCompany[0])) {
			$strCompanyName = $arrCompany[0];
		}

		if(!empty($arrCompany[1])) {
			$strJobLink = $arrCompany[1];
		}
	}

	if($node->filter('.jobContainer .subtle.loc')->extract('_text')) {
		$strLocation = $node->filter('.jobContainer .subtle.loc')->extract('_text')[0];
		$arrLocation = explode(',', $strLocation);
		if(!empty($arrLocation[0])) {
			$strCity = $arrLocation[0];
		}

		if(!empty($arrLocation[1])) {
			$strState = $arrLocation[1];
		}
	}

	if($node->filter('.jobLabel.nowrap .minor')->extract('_text')) {
		$strTime = $node->filter('.jobLabel.nowrap .minor')->extract('_text')[0];
	}

	if($node->filter('.salaryText')->extract('_text')) {
		$strSalary = $node->filter('.salaryText')->extract('_text')[0];
	}	

	$strDate = convertDtoDate($strTime);

	// echo $strLocation.'<hr>';
	echo $strKeyword.' | '.$strCompanyName.' | '.$strJobLink.' | '.$strLocation.' | '.$strTime.' | '.$strSalary.' | '.$strCity.' | '.$strState.'<hr>';
	
	/*global $conn;
	$sql = "INSERT INTO `job_posts` (`keyword`, `title`, `description`, `company_name`, `location`, `website_name`, `link`, `date`) VALUES ('php', '$strKeyword', '', '$strCompanyName', '$strLocation', 'glassdoor', '$strJobLink', '$strDate')";
	$conn->query($sql) or die(mysqli_error($conn));	*/
});
