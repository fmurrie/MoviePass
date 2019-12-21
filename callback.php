<?php
require_once "FacebookConfig.php";
require "Config/Autoload.php";
require "Config/Config.php";

use Config\Autoload as Autoload;
use Config\Router 	as Router;
use Config\Request 	as Request;
  
Autoload::start();

try
{
  $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e)
{
  // When Graph returns an error
  exit;
}
catch(Facebook\Exceptions\FacebookSDKException $e)
{
  // When validation fails or other local issues
  exit;
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
  } else {
    header('HTTP/1.0 400 Bad Request');
  }
  exit;
}

// Logged in
try
{
    //Return a FacebookResponse object
    $response=$fb->get("/me?fields=id,name,email,first_name,last_name,picture,gender",$accessToken->getValue());
}
catch(Facebook\Exceptions\FacebookResponseException $e)
{
    exit;
}
catch(Facebook\Exceptions\FacebookSDKException $e)
{
    exit;
}

/*Here is what I want to do by clicking log in facebook*/
use Controllers\UserController as UserController;
$accountController=new UserController();
$fbUserData=$response->getGraphUser()->asArray();
$fbUserData["password"]=$accessToken->getValue();
$accountController->loginWithFacebook($fbUserData);

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $fb->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId($app_id); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    exit;
  }
}
?>