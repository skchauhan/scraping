<?php 
  	error_reporting(E_ALL);

  	ini_set("display_errors", "1");

	include 'config.php';

	// global $wpdb;

	require_once('./ganon.php');

	 $start = 1;
	 $total_page = 1;

	for ( $i=$start; $i <= $total_page; $i++ ) {
		$html = file_get_dom("https://oilprice.com/Latest-Energy-News/World-News/");
		$bssnsQry = [];

	    foreach($html('.categoryArticle') as $element)
	    {
	    	foreach($element('a') as $aa)
	        {
	        	$bssnsQry['link'] = $aa->href;	
	        }

        	foreach($element('a img') as $aa)
	        {
	            $bssnsQry['image'] = $aa->src;
	        }

	        foreach($element('.categoryArticle__content .categoryArticle__title') as $aa)
	        {
	            $bssnsQry['title'] = $aa->getPlainText();
	        }

			foreach ($element('p.categoryArticle__meta') as $date) {
				$arrDatePublisher = explode(' | ', $date->getPlainText());
				$strPublishDate = $arrDatePublisher[0];
				$arrDateTime = explode(' at ', $strPublishDate);
				$postDate = date('Y-m-d H:i:s', strtotime($arrDateTime[0].' '.$arrDateTime[1]));
				$bssnsQry['datetime'] = $postDate;
			}			

			// Inner page scraping start
			$htmlText = '';        
        	$html = file_get_dom($bssnsQry['link']);

        	foreach ($html('.articleImageContainer img') as  $data) {
        		$bssnsQry['image'] = $data->src;
        	}
        	
			foreach ($html('#news-content') as  $data) {
				foreach ($data("p") as $caption) {
					if($caption->getPlainText() != 'By Julianne Geiger for Oilprice.com') {
						$htmlText .= '<p>'.$caption->getPlainText().'</p>';
					}
				}
			}
			// Inner page scraping end

	        $bssnsQry['content'] = $htmlText;

	        pre($bssnsQry);
	        echo "<hr>";
	    }
	}
?>