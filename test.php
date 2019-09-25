<?php 
print "<pre>";
$curl_handle=curl_init();
curl_setopt($curl_handle,CURLOPT_URL,'https://oilprice.com/Latest-Energy-News/World-News/');
curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
$buffer = curl_exec($curl_handle);
curl_close($curl_handle);
if (empty($buffer)){
  print "Nothing returned from url.<p>";
}
else{
  print $buffer;
}


die();
$html = file_get_contents('https://oilprice.com/Latest-Energy-News/World-News/');

/*** a new dom object ***/ 
   $dom = new domDocument; 
   
   /*** load the html into the object ***/ 
   $dom->loadHTML($html); 
   
   /*** the table by its tag name ***/ 
   $articles = $dom->getElementsByClassName('categoryArticle'); 

   print_r($articles);
   
   