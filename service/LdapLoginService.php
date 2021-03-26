<?php
require_once "repository/Repository.php";
require_once "model/User.php";
require_once "model/Account.php";

class LdapLoginService
{

    private Repository $repository;

    public function __construct()
    {
        $this->repository = new Repository();
    }


    function login($name, $surname, $email, $ldapId)
    {
        $user = $this->repository->getUserByEmail($email);

        if($user == false)
        {
            return $this->createNewUserWithAccount($name,$surname,$email,$ldapId);
        }

        $userAccount = $this->repository->getUserLdapAccountByUserId($user->id);

        if($userAccount == false)
        {
            return $this->createNewAccountForUser($user->id,$ldapId);
        }

        return $userAccount->id;

    }

    function createNewUserWithAccount($name, $surname, $email, $ldapId)
    {
        $newUserId = $this->repository->addNewUser($name,$surname,$email);

        if($newUserId == -1)
            return -1;

        return $this->createNewAccountForUser($newUserId,$ldapId);
    }

    private function createNewAccountForUser($userId,$ldapId)
    {
        $ldapId = intval($ldapId);
        return $this->repository->createNewLdapAccountForUser($userId,$ldapId);
    }










}