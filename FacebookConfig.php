<?php
if(!session_id())
    session_start();

require_once "Facebook/autoload.php";

//API data
$app_id = "581586509325467";
$app_secret = "2b8cfcc554c68d0e04188696f678dffe";

$permissions = ['email']; //Optional permissions'
$callbackUrl = "http://localhost/MoviePass/callback.php";
$fb = new Facebook\Facebook([
	'app_id' => $app_id, 
	'app_secret' => $app_secret,
	'default_graph_version' => 'v3.2',
	]);
$helper = $fb->getRedirectLoginHelper();
$loginUrl = $helper->getLoginUrl($callbackUrl, $permissions);
?>