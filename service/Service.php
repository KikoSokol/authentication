<?php
require_once "repository/Repository.php";

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

}