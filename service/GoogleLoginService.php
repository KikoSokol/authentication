<?php
require_once "repository/Repository.php";
require_once "model/User.php";
require_once "model/Account.php";


class GoogleLoginService
{
    private Repository $repository;


    public function __construct()
    {
        $this->repository = new Repository();
    }

    function login($name, $surname, $email, $googleId)
    {
        $user = $this->repository->getUserByEmail($email);

        if($user == false)
        {
            return $this->createNewUserWithAccount($name,$surname,$email,$googleId);
        }

        $userAccount = $this->repository->getUserGoogleAccountByUserId($user->id);

        if($userAccount == false)
        {
            return $this->createNewAccountForUser($user->id, $googleId);
        }

        return $userAccount->id;

    }

    function createNewUserWithAccount($name, $surname, $email, $googleId)
    {
        $newUserId = $this->repository->addNewUser($name,$surname,$email);

        if($newUserId == -1)
        {
            return -1;
        }

        return $this->createNewAccountForUser($newUserId,$googleId);

    }


    private function createNewAccountForUser($userId, $googleId)
    {
        return $this->repository->createNewGoogleAccountForUser($userId,$googleId);
    }

}