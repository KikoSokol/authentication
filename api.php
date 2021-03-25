<?php

require_once "service/CustomLoginService.php";
require_once "service/Service.php";
require_once "model/Account.php";
require_once "model/User.php";


header('Content-type: application/json');

//session_id($_GET['PHPSESSID']);
session_start();

$service = new Service();
$customService = new CustomLoginService();


$operation = "";

$operation = "";

if(isset($_GET["operation"]))
    $operation = $_GET["operation"];


switch($operation)
{
    case "getQRCode":
        echo json_encode($customService->getSecretCodeForRegistration());
        break;
    case "getLoginUser":
        getLoginUser($service);
        break;
    case "logout":
        $result = session_destroy();
        echo json_encode($result);
        break;
    case "customRegistration":
        $json = file_get_contents('php://input');
        $registrationData = json_decode($json);
        $registrationResponse = customRegistration($customService,$service,$registrationData);
        echo json_encode($registrationResponse);
        break;
    case "customLogin":
        $json = file_get_contents('php://input');
        $loginData = json_decode($json);
        customLogin($customService,$service,$loginData);
        break;
    case "verifyCustomLogin":
        $json = file_get_contents('php://input');
        $code = json_decode($json);
        verifyCustomLogin($customService,$service,$code->code);
        break;
    case "userStats":
        getStats($service);
        break;
    case "canShowFa":
        echo(json_encode(isSetTmpAccount()));
        break;
    default:
//        var_dump(json_encode($customService->getSecretCodeForRegistration()));
//        $response = array(
//            "verifyCode" => false,
//            "code" => $customService->getSecretCodeForRegistration()
//        );
//        var_dump(json_encode($response));
//        var_dump(json_encode($customService->login("kikosokol@gmail.com","123456789")));
//        $userAccount = $customService->login("kikosokol@gmail.com","123456789");
//        var_dump(json_encode($service->getUserByAccountId($userAccount->id)));
//        $json = file_get_contents('php://input');
//        $loginData = json_decode($json);
//        var_dump(json_encode($loginData));
//        $_SESSION['accountId'] = 10;
//        var_dump(verifyCustomLogin($customService,$service,"319548"));
//        $_SESSION['accountId'] = 10;
//        var_dump(getStats($service));
//        var_dump($repository->getCoutnCustomAccount());




}












function getResponseRegistration($verifyCode, $successRegistration, $error)
{
    return array(
        "verifyCode" => $verifyCode,
        "successRegistration" => $successRegistration,
        "error" => $error,
    );
}

function getResponseWithUser($isUserLogin, $user)
{
    return array(
        "isUserLogin" => $isUserLogin,
        "user" => $user
    );
}

function getVerifyCustomLoginResponse($isLoginUser, $verifyCode)
{
    return array(
        "isLoginUser" => $isLoginUser,
        "verifyCode" => $verifyCode
    );
}

function getStatsResponse($isLoginUser, $user, $userAllAccess, $countOfLogin)
{
    return array(
        "isLoginUser" => $isLoginUser,
        "user" => $user,
        "userAllAccess" => $userAllAccess,
        "countOfLogin" => $countOfLogin
    );
}

function customRegistration($customService,$service,$registrationData)
{
    $verifyCode = $customService->verifyRegistrationCode($registrationData->secretId,$registrationData->code);
    if($verifyCode == false)
    {
        return getResponseRegistration(false,false,"Neplatný kód");

    }
    $accountId = $customService->createNewUser($registrationData->name,$registrationData->surname,$registrationData->email, $registrationData->password, $registrationData->checkPassword, $registrationData->secretId);
    if($accountId == false)
    {
        return getResponseRegistration(true,false,"Užívateľ s týmto emailom už existuje");

    }
    else if($accountId == -1)
    {
        return getResponseRegistration(true,false,"Došlo ku chybe. Registrácia neprebehla úspešne (Skontroluj správnosť údajov)");

    }

    doLoginUser($service,$accountId);//$_SESSION['accountId'] = $accountId;
    return getResponseRegistration(true,true,"Úspešne prihlasenie");

}

function getLoginUser($service)
{
    if(isSetAccount())
    {
        $userResponse = getResponseWithUser(true,$service->getUserByAccountId($_SESSION['accountId']));
        echo json_encode($userResponse);
    }
    else
    {
        $userResponse = getResponseWithUser(false,null);
        echo json_encode($userResponse);
    }
}

function customLogin($customService,$service,$loginData)
{
    $userAccount = $customService->login($loginData->email, $loginData->password);

    if($userAccount == false)
    {
        $user = getResponseWithUser(false,null);
        echo json_encode($user);
    }
    else
    {
        $_SESSION['accountIdTmp'] = $userAccount->id;
        $user = getResponseWithUser(true,$service->getUserByAccountId($userAccount->id));
        echo json_encode($user);
    }


}

function verifyCustomLogin($customService, $service,$code)
{
    if(!isSetTmpAccount())
    {
        $response = getVerifyCustomLoginResponse(false,false);
        echo json_encode($response);
    }
    else
    {
        $account = $service->getUserAccountById($_SESSION['accountIdTmp']);
        $verify = $customService->verifyLoginAccount($account,$code);
        $response = getVerifyCustomLoginResponse(true,$verify);
        if($verify)
        {
            doLoginUser($service,$_SESSION['accountIdTmp']);
        }

        echo json_encode($response);
    }
}

function getStats($service)
{
    if(isSetAccount())
    {
        $user = $service->getUserByAccountId($_SESSION['accountId']);
        $userAllAccess = $service->getAllAccessOfUser($_SESSION['accountId']);
        $countOfLogin = $service->getCountOfLogin();
        $response = getStatsResponse(true,$user,$userAllAccess,$countOfLogin);
        echo json_encode($response);
    }
    else
    {
        $response = getStatsResponse(false,null,null,null);
        echo json_encode($response);
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


function isSetAccount()
{
    return isset($_SESSION['accountId']);
}


function isSetTmpAccount()
{
    return isset($_SESSION['accountIdTmp']);
}


