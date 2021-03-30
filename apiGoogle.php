<?php
session_start();
define('MYDIR','googleApi/');
require_once(MYDIR."vendor/autoload.php");
require_once ("service/GoogleLoginService.php");
require_once ("service/Service.php");

$redirect_uri = 'https://wt142.fei.stuba.sk/zd3ks97933/apiGoogle.php';

$client = new Google_Client();
$client->setAuthConfig('../../configs/credentials.json');
$client->setRedirectUri($redirect_uri);
$client->addScope("email");
$client->addScope("profile");

$service = new Google_Service_Oauth2($client);

$googleService = new GoogleLoginService();

$serviceApi = new Service();

if(isset($_GET['code'])){
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);
    $_SESSION['upload_token'] = $token;

    // redirect back to the example
    header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

// set the access token as part of the client
if (!empty($_SESSION['upload_token'])) {
    $client->setAccessToken($_SESSION['upload_token']);
    if ($client->isAccessTokenExpired()) {
        unset($_SESSION['upload_token']);
    }
} else {
    $authUrl = $client->createAuthUrl();
}

if ($client->getAccessToken()) {
    //Get user profile data from google
    $UserProfile = $service->userinfo->get();
    var_dump($client->getAccessToken());
    if(!empty($UserProfile)){
        var_dump($UserProfile['given_name']);
        var_dump($UserProfile['family_name']);
        var_dump($UserProfile['id']);
        var_dump($UserProfile['email']);
        login($googleService,$serviceApi,$UserProfile['given_name'],$UserProfile['family_name'],$UserProfile['email'],$UserProfile['id']);

    }else{
        header("Location: index.html");
    }
} else {
    $authUrl = $client->createAuthUrl();
    echo json_encode($authUrl);
//    $output = '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'"><button>google</button></a>';
}




function login($googleService,$service,$name, $surname, $email, $googleId)
{
    $accountId = $googleService->login($name, $surname, $email, $googleId);

    if($accountId == -1)
    {
        header("Location: index.html");
    }
    else
    {
        doLoginUser($service,$accountId);
        header("Location: html/home.html");
    }
}


function doLoginUser($service,$accountId)
{
    $_SESSION['accountId'] = $accountId;
    addAccessForUser($service,$accountId);
    if(isSetTmpAccount())
        unset($_SESSION['accountIdTmp']);

}

function addAccessForUser($service,$accountId)
{
    $service->addAccessForAccount($accountId);
}

function isSetTmpAccount()
{
    return isset($_SESSION['accountIdTmp']);
}


?>

