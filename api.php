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
    case "customRegistration":
        $json = file_get_contents('php://input');
        $registrationData = json_decode($json);
        $registrationResponse = customRegistration($customService,$registrationData);
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

function customRegistration($customService,$registrationData)
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

    $_SESSION['accountId'] = $accountId;
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
        $_SESSION['accountId'] = $userAccount->id;
        $user = getResponseWithUser(true,$service->getUserByAccountId($userAccount->id));
        echo json_encode($user);
    }


}

function verifyCustomLogin($customService, $service,$code)
{
    if(!isSetAccount())
    {
        $response = getVerifyCustomLoginResponse(false,false);
        echo json_encode($response);
    }
    else
    {
        $account = $service->getUserAccountById($_SESSION['accountId']);
        $response = getVerifyCustomLoginResponse(true,$customService->verifyLoginAccount($account,$code));
        echo json_encode($response);
    }
}



function isSetAccount()
{
    return isset($_SESSION['accountId']);
}


