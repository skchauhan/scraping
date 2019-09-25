<?php 
set_time_limit(0);

include 'vendor/autoload.php';
include 'config.php';

use Goutte\Client;

$client = new Client();
$crawler = $client->request('GET', 'https://www.linkedin.com/jobs/search/?geoId=104869687&keywords=php%20developer&location=Noida%2C%20Uttar%20Pradesh%2C%20India&redirect=false&position=1&pageNum=0');

$perpage = 25;
// Get the latest post in this category and display the titles
$intTotlJobPost = 0; $totlPage = 0;
if($crawler->filter('.results-context-header__job-count')->extract('_text')) {
	$intTotlJobPost = $crawler->filter('.results-context-header__job-count')->extract('_text')[0];
	$intTotlJobPost = preg_replace('/[,+]/', '', $intTotlJobPost);
	$totlPage = $intTotlJobPost/$perpage;
}

$strQ = "UPDATE `page_counter` SET `total_page` = '$totlPage' WHERE `website` = 'linkedin'";
$conn->query($strQ) or die(mysqli_error($conn));


//glassdoor jobs
$crawler = $client->request('GET', 'https://www.glassdoor.co.in/Job/us-ios-developer-jobs-SRCH_IL.0,2_IN1_KO3,16.htm?radius=31&jobType=fulltime');

if($crawler->filter('.jobsCount')->extract('_text')) {
	$strTotlCount = $crawler->filter('.jobsCount')->extract('_text')[0];
	$intTotlJobPost = str_replace(',', '', str_replace('Jobs', '', $strTotlCount));
	
}

$strQ = "UPDATE `page_counter` SET `total_page` = '$intTotlJobPost' WHERE `website` = 'glassdoor'";
$conn->query($strQ) or die(mysqli_error($conn));


/*https://www.glassdoor.co.in/Job/jobs.htm?suggestCount=0&suggestChosen=false&clickSource=searchBtn&typedKeyword=anroid+developer&sc.keyword=anroid+developer&locT=C&locId=4477468&jobType=
2921225*/
?>