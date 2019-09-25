<?php 
set_time_limit(0);
include 'vendor/autoload.php';
include 'config.php';
use Goutte\Client;
$client = new Client();

$siteUrl = "https://www.ziprecruiter.com/candidate-preview/suggested-jobs";

$crawler = $client->request('GET', $siteUrl);

pre($crawler->filter('.preview_sell .headline')->eq(0)->extract('_text'));
