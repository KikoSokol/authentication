<?php
require_once "service/LdapLoginService.php";
require_once "service/Service.php";
require_once "model/LdapData.php";
require_once "model/Account.php";
require_once "model/User.php";

header('Content-type: application/json');

session_start();

$ldapService = new LdapLoginService();
$service = new Service();

$ldapconfig['host'] = 'ldap.stuba.sk';//CHANGE THIS TO THE CORRECT LDAP SERVER
$ldapconfig['port'] = '389';
$ldapconfig['basedn'] = 'ou=People, DC=stuba, DC=sk';//CHANGE THIS TO THE CORRECT BASE DN
$ldapconfig['usersdn'] = 'cn=users';//CHANGE THIS TO THE CORRECT USER OU/CN
$ds=ldap_connect($ldapconfig['host'], $ldapconfig['port']);

ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
ldap_set_option($ds, LDAP_OPT_NETWORK_TIMEOUT, 10);

$operation = "";

if(isset($_GET["operation"]))
    $operation = isset($_GET["operation"]);

switch($operation)
{
    case "loginLdap":
        $json = file_get_contents('php://input');
        $loginData = json_decode($json);
        $dn="uid=".$loginData->login.",".$ldapconfig['basedn'];
        $ldapData = verifyLdapLogin($loginData->login,$loginData->password,$ds,$dn);
        login($ldapService,$service,$ldapData);
        break;
    default:


}


function getResponseWithLdapUser($isUserLogin, $user, $error)
{
    return array(
        "isUserLogin" => $isUserLogin,
        "user" => $user,
        "error" => $error
    );
}


function verifyLdapLogin($login,$password, $ds, $dn)
{
    if($bind=ldap_bind($ds, $dn, $password))
    {
        $sr = ldap_search($ds, 'ou=People, DC=stuba, DC=sk', 'uid=' . $login, ['givenname', 'sn', 'mail',"uisid"] );
        $data = ldap_get_entries($ds,$sr);
        $name = $data[0]['givenname'][0];
        $surname = $data[0]['sn'][0];
        $email = $data[0]['mail'][0];
        $ldapId = $data[0]["uisid"][0];
        return new LdapData($name,$surname,$email,intval($ldapId));
    }
    else
        false;

}

function login($ldapService,$service, $loginData)
{
    if($loginData == false)
    {
        $response = getResponseWithLdapUser(false, null,"Boli zadané zle prihlasovacie údaje");
        echo json_encode($response);
        return;
    }

    $accountId = $ldapService->login($loginData->name, $loginData->surname, $loginData->email, $loginData->ldapId);

    if($accountId == -1)
    {
        $response = getResponseWithLdapUser(false,null,"Došlo ku chybe. Prihlasenie sa nepodarilo");
        echo json_encode($response);
        return;
    }

    $user = $service->getUserByAccountId($accountId);
    doLoginUser($service,$accountId);
    $response = getResponseWithLdapUser(true,$user,"Úspešné prihlasenie");
    echo json_encode($response);
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





