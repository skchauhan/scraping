<?php 
  	error_reporting(E_ALL);

  	ini_set("display_errors", "1");

	// include('wp-config.php');

print"<pre>";
	// global $wpdb;

	require_once('./ganon.php');

	 $start = 1;
	 $total_page = 1;

	for ( $i=$start; $i <= $total_page; $i++ ) {
		$html = file_get_dom("https://www.foxbusiness.com/category/oil?page=5");
		$bssnsQry = [];

	    foreach($html('.content article') as $element)
	    {
	    	foreach($element('.m a') as $aa)
	        {
	        	if (filter_var($aa->href, FILTER_VALIDATE_URL)) {
	        		$bssnsQry['post_type'] = "video";
	        	} else {
	        		$bssnsQry['post_type'] = "post";
	        	}

	            $bssnsQry['link'] = $aa->href;
	        }


	        if($bssnsQry['post_type'] == "post") {
	        	foreach($element('.m img') as $aa)
		        {
		            $bssnsQry['image'] = $aa->src;
		        }

		        foreach($element('.info h3') as $aa)
		        {
		            $bssnsQry['title'] = $aa->getPlainText();
		        }

		        /*foreach($element('.info p') as $aa)
		        {
		            $bssnsQry['content'] = $aa->getPlainText();
		        }*/

		        $title = addslashes($bssnsQry['title']);

				$htmlText = '';
				$postDate = '';
	        
	        	$html = file_get_dom("https://www.foxbusiness.com/". $bssnsQry['link']);
				foreach ($html('article .inner') as  $data) {

					foreach ($data(".caption h3 a") as $caption) {
						$htmlText .= '<h3>'.$caption->getPlainText() .'</h3>';
					}	

					foreach ($data('time') as $date) {
						$dateTime = str_replace('Published ', '', $date->getPlainText());
						$postDate = date('Y-m-d H:i:s', strtotime($dateTime));
					}

					foreach ($data(".article-content .article-text") as $articleText) {
						foreach ($articleText("p") as  $paragraph) {
							if($paragraph->getPlainText() != "Advertisement") {
								if($paragraph->getPlainText() != "Continue Reading Below") {
									if($paragraph->getPlainText() != "CLICK HERE TO GET THE FOX BUSINESS APP") {
										if($paragraph->getPlainText() != "OPEC CUTS MAKE WAY FOR SHALE") {
											$htmlText .= "<p>".$paragraph->getPlainText() . "</p>";
										}
									}
								}	
							}
						}
					}
				}		        

		        $bssnsQry['content'] = $htmlText;

		        print_r($bssnsQry);
		        echo "<hr>";


		        /*$checkExist = $wpdb->get_row("SELECT *  FROM `wp_posts` WHERE `post_title` = '$title' and post_status = 'publish' and post_type='post'", ARRAY_A);

		        if(empty($checkExist)) {

			        $my_post = array(
					  'post_title'    => wp_strip_all_tags( $bssnsQry['title'] ),
					  'post_content'  => $bssnsQry['content'],
					  'post_status'   => 'publish',
					  'post_author'   => 1,
					  'post_date' => $postDate,
					  'post_type' => 'post'
					);
					 
					// Insert the post into the database
					$post_id = wp_insert_post( $my_post );

					// Add Featured Image to Post
					$image_url        = $bssnsQry['image']; // Define the image URL here
					$image_name       = time().'wp-header-logo.png';
					$upload_dir       = wp_upload_dir(); // Set upload folder
					$image_data       = file_get_contents($image_url); // Get image data
					$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); // Generate unique name
					$filename         = basename( $unique_file_name ); // Create image file name

					// Check folder permission and define file location
					if( wp_mkdir_p( $upload_dir['path'] ) ) {
					    $file = $upload_dir['path'] . '/' . $filename;
					} else {
					    $file = $upload_dir['basedir'] . '/' . $filename;
					}

					// Create the image  file on the server
					file_put_contents( $file, $image_data );

					// Check image file type
					$wp_filetype = wp_check_filetype( $filename, null );

					// Set attachment data
					$attachment = array(
					    'post_mime_type' => $wp_filetype['type'],
					    'post_title'     => sanitize_file_name( $filename ),
					    'post_content'   => '',
					    'post_status'    => 'inherit'
					);

					// Create the attachment
					$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

					// Include image.php
					require_once(ABSPATH . 'wp-admin/includes/image.php');

					// Define attachment metadata
					$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

					// Assign metadata to attachment
					wp_update_attachment_metadata( $attach_id, $attach_data );

					// And finally assign featured image to post
					set_post_thumbnail( $post_id, $attach_id );

		        }*/
	        }        

	    }
	}
?>