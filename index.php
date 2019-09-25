<?php 
set_time_limit(0);
include 'vendor/autoload.php';
include 'config.php';

$strD = "DELETE FROM `job_posts`";
$strQD = $conn->query($strD) or die(mysqli_error($conn));

use Goutte\Client;

$client = new Client();

$totlPage = 1;//getTotalPage();

$perpage = 1;
if($totlPage > 0) {
	$j=0;
	for ($i = 0; $i <= $totlPage; $i++) {
		$start = $i*$perpage;

		$strQu = "UPDATE `page_counter` SET `scraped_page` = '$i' WHERE `website` = 'linkedin'";
		$conn->query($strQu) or die(mysqli_error($conn));

		$siteUrl = 'https://www.linkedin.com/jobs-guest/jobs/api/jobPostings/jobs?keywords=laravel&location=Noida%2C%20Uttar%20Pradesh%2C%20India&trk=guest_job_search_jobs-search-bar_search-submit&redirect=false&position=1&pageNum=0&start='.$start;
		
		$crawler = $client->request('GET', $siteUrl);

		$crawler->filter('li.result-card')->each(function ($node) {

			$companyName = '-';	$strKeyWord = '-'; $jobLink = '-'; $jobLocation = '-'; $strDescription = '-'; $strDate = '-'; $strCity = '-'; $strState = '-'; $strCountry = '-';

			if($node->filter(".result-card__subtitle.job-result-card__subtitle")->extract('_text')) {
				$companyName = $node->filter(".result-card__subtitle.job-result-card__subtitle")->extract('_text')[0];
			}

			//keywords & link
			if($node->filter(".result-card__full-card-link")->eq(0)->extract(['_text', 'href'])) {
				$arrKeywords = $node->filter(".result-card__full-card-link")->eq(0)->extract(['_text', 'href'])[0];
				$strKeyWord = $arrKeywords[0];
				$jobLink = $arrKeywords[1];
			}

			//location
			if($node->filter(".job-result-card__location")->extract('_text')) {
				$jobLocation = $node->filter(".job-result-card__location")->extract('_text')[0];
				$arrLocation = explode(',', $jobLocation);
				if(!empty($arrLocation[0])) {
					$strCity = $arrLocation[0];
				}

				if(!empty($arrLocation[1])) {
					$strState = $arrLocation[1];
				}
				if (!empty($arrLocation[2])) {
					$strCountry = $arrLocation[2];					
				}
			}

			//Description
			if($node->filter(".job-result-card__snippet")->extract('_text')) {
				$strDescription = $node->filter(".job-result-card__snippet")->extract('_text')[0];
			}

			//Date
			if($node->filter("time")->extract('datetime')) {
				$strDate = $node->filter("time")->extract('datetime')[0];
			}

			echo $companyName.' - '.$strKeyWord.' - '.$jobLink.' - '.$jobLocation.' - '.$strDescription.' - '.$strDate.' - <br> '.$strCity.' - '.$strState.' - '.$strCountry. '<hr>';
/*			global $conn;
			$sql = "INSERT INTO `job_posts` (`keyword`, `title`, `description`, `company_name`, `location`, `website_name`, `link`, `date`) VALUES ('php', '$strKeyWord', '".addslashes($strDescription)."', '$companyName', '$jobLocation', 'linkedin', '$jobLink', '$strDate')";
			$conn->query($sql) or die(mysqli_error($conn));*/
		});
	}	
}

?>