<?php
require_once "repository/Repository.php";
require_once "model/CountOfLogin.php";

class Service
{

    private Repository $repository;


    public function __construct()
    {
        $this->repository = new Repository();
    }

    function getUserByAccountId($accountId)
    {
        $account = $this->repository->getAccountById($accountId);
        return $this->repository->getUserById($account->userId);
    }

    function getUserAccountById($accountId)
    {
        return $this->repository->getAccountById($accountId);
    }

    function addAccessForAccount($accountId)
    {
        return $this->repository->addAccess($accountId);
    }

    function getAllAccessOfUser($accountId)
    {
        $user = $this->getUserByAccountId($accountId);
        return $this->repository->getAllAccessOfUserByUserId($user->id);
    }

    function getCountOfLogin()
    {
        $custom = $this->repository->getCoutnCustomAccount()["countOfCustomAccounts"];//->countOfCustomAccounts;
        $ldap = $this->repository->getCoutnLdapAccount()["countOfLdapAccounts"];//->countOfLdapAccounts;
        $google = $this->repository->getCoutnGoogleAccount()["countOfGoogleAccounts"];//->countOfGoogleAccounts;

        $custom = intval($custom);
        $ldap = intval($ldap);
        $google = intval($google);


        $countOfLogin = new CountOfLogin();
        $countOfLogin->setCustomLogin($custom);
        $countOfLogin->setLdapLogin($ldap);
        $countOfLogin->setGoogleLogin($google);

        return $countOfLogin;

    }

}