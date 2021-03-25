<?php

require_once "repository/Repository.php";
require_once "repository/RepositoryFa.php";
require_once "model/Account.php";
require_once "model/User.php";
//require_once "model/TwoFactoryAuthentication.php";

class CustomLoginService
{
    private Repository $repository;
    private RepositoryFa $fa;


    public function __construct()
    {
        $this->repository = new Repository();
        $this->fa = new RepositoryFa();
    }


    function createNewUser($name, $surname, $email, $password, $checkPassword, $secretId)
    {
        $name = trim($name," ");
        $surname = trim($surname, " ");
        $email = trim($email," ");
        $password = trim($password," ");
        $secretId = trim($secretId," ");

        $isMatchingPassword = $this->repository->checkMatchingPassword($password,$checkPassword);
        if(!$isMatchingPassword)
            return -1;

        $userWithThisEmail = $this->repository->getUserByEmail($email);

        if($userWithThisEmail == false)
        {
            return $this->addNewUserWithAccount($name,$surname,$email,$password,$secretId);
        }

        $userAccount = $this->repository->getUserCustomAccountByUserId($userWithThisEmail->id);

        if ($userAccount != false)
            return false;

        return $this->createNewAccountForUser($userWithThisEmail->id,$password,$secretId);

    }

    private function addNewUserWithAccount($name,$surname,$email,$password,$secredId)
    {

        $newUserId = $this->repository->addNewUser($name,$surname,$email);
        if($newUserId == -1)
            return -1;

        return $this->createNewAccountForUser($newUserId,$password,$secredId);

    }

    private function createNewAccountForUser($userId,$password,$secredId)
    {
        return $this->repository->createNewCustomAccountForUser($userId,$password,$secredId);

    }


    function login($email,$password)
    {
        $email = trim($email," ");
        $password = trim($password," ");

        $user = $this->repository->getUserByEmail($email);

        if($user == false)
            return false;

        $userAccount = $this->repository->verificationCustomAccountOfUser($user->id,$password);

        if($userAccount == false)
            return false;

        return $userAccount;
    }

    function getSecretCodeForRegistration()
    {
        return $this->fa->getStart();
    }

    function verifyRegistrationCode($secretCode,$code)
    {
        return $this->fa->verifyForRegistration($secretCode,$code);
    }

    function verifyLoginAccount($account,$code)
    {
        return $this->fa->verify($account->secretId, $code);
    }


}