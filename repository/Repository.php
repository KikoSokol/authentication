<?php

require_once "database/Database.php";
require_once "model/User.php";
require_once "model/Account.php";
require_once "model/Access.php";


class Repository
{
    private $conn;
    const CUSTOM = 'CUSTOM';
    const LDAP = 'LDAP';
    const GOOGLE = 'GOOGLE';


    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConn();
    }

    function getHashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    function checkMatchingPassword($password, $checkPassword)
    {
        $password = trim($password," ");
        $checkPassword = trim($checkPassword," ");

        if($password != $checkPassword)
            return false;

        return true;

    }


    function addNewUser(string $name,string $surname,string $email)
    {
        $name = trim($name," ");
        $surname = trim($surname," ");
        $email = trim($email," ");

        try {
            $sql = "INSERT INTO `USER`(`name`, `surname`, `email`) VALUES (:name,:surname,:email)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("name",$name,PDO::PARAM_STR);
            $stmt->bindParam("surname",$surname,PDO::PARAM_STR);
            $stmt->bindParam("email",$email,PDO::PARAM_STR);
            $result = $stmt->execute();
            if($result)
                return $this->conn->lastInsertId();
        }
        catch (PDOException $e)
        {
//            echo $sql . "<br>" . $e->getMessage();
            return -1;
        }
        return -1;
    }

    function createNewCustomAccountForUser($userId,$password,$secretId)
    {
        $password = $this->getHashPassword(trim($password," "));
        try {
            $type = self::CUSTOM;
            $sql = "INSERT INTO `ACCOUNT`(`user_id`, `type`, `password`, `secret_id`) VALUES (:userId,:type,:password,:secretId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("userId",$userId,PDO::PARAM_INT);
            $stmt->bindParam("type",$type,PDO::PARAM_STR);
            $stmt->bindParam("password",$password,PDO::PARAM_STR);
            $stmt->bindParam("secretId",$secretId,PDO::PARAM_STR);
            $result = $stmt->execute();
            if($result)
                return $this->conn->lastInsertId();
        }
        catch (PDOException $e)
        {
//            echo $sql . "<br>" . $e->getMessage();
            return -1;
        }
        return -1;
    }

    function createNewGoogleAccountForUser($userId,$googleId)
    {
        try {
            $type = self::GOOGLE;
            $sql = "INSERT INTO `ACCOUNT`(`user_id`, `type`,`google_id`) VALUES (:userId,:type,:googleId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("userId",$userId,PDO::PARAM_INT);
            $stmt->bindParam("type",$type,PDO::PARAM_STR);
            $stmt->bindParam("googleId",$googleId,PDO::PARAM_STR);
            $result = $stmt->execute();
            if($result)
                return $this->conn->lastInsertId();
        }
        catch (PDOException $e)
        {
//            echo $sql . "<br>" . $e->getMessage();
            return -1;
        }
        return -1;
    }

    function createNewLdapAccountForUser($userId,$ldapId)
    {
        try {
            $type = self::LDAP;
            $sql = "INSERT INTO `ACCOUNT`(`user_id`, `type`,`ldap_id`) VALUES (:userId,:type,:ldapId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("userId",$userId,PDO::PARAM_INT);
            $stmt->bindParam("type",$type,PDO::PARAM_STR);
            $stmt->bindParam("ldapId",$ldapId,PDO::PARAM_INT);
            $result = $stmt->execute();
            if($result)
                return $this->conn->lastInsertId();
        }
        catch (PDOException $e)
        {
//            echo $sql . "<br>" . $e->getMessage();
            return -1;
        }
        return -1;
    }

    function getUserByEmail($email)
    {
        $sql = "SELECT USER.ID as id, USER.name as name, USER.surname, USER.email as email FROM USER WHERE USER.email = :email;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("email",$email, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS,"User");
        return $stmt->fetch();
    }

    function getUserById($userId)
    {
        $sql = "SELECT USER.ID as id, USER.name as name, USER.surname, USER.email as email FROM USER WHERE USER.ID = :userId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("userId",$userId, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS,"User");
        return $stmt->fetch();
    }

    function verificationCustomAccountOfUser($userId, $password)
    {
        $account =  $this->getUserCustomAccountByUserId($userId);

        if(!$account)
            return false;

        if(!password_verify($password, $account->getPassword()))
            return false;

        return $this->getAccountById($account->getId());
    }

    function getUserCustomAccountByUserId($userId)
    {
        $type = self::CUSTOM;
        $sql = "SELECT ACCOUNT.ID as id, ACCOUNT.user_id as userId, ACCOUNT.type as type, ACCOUNT.password as password, ACCOUNT.google_id as googleId, ACCOUNT.ldap_id as ldapId, ACCOUNT.secret_id AS secretId FROM ACCOUNT where ACCOUNT.user_id = :userId AND ACCOUNT.type = :type;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("userId",$userId,PDO::PARAM_INT);
        $stmt->bindParam("type",$type,PDO::PARAM_STR);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Account");
        return $stmt->fetch();
    }

    function getUserLdapAccountByUserId($userId)
    {
        $type = self::LDAP;
        $sql = "SELECT ACCOUNT.ID as id, ACCOUNT.user_id as userId, ACCOUNT.type as type, ACCOUNT.password as password, ACCOUNT.google_id as googleId, ACCOUNT.ldap_id as ldapId, ACCOUNT.secret_id AS secretId FROM ACCOUNT where ACCOUNT.user_id = :userId AND ACCOUNT.type = :type;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("userId",$userId,PDO::PARAM_INT);
        $stmt->bindParam("type",$type,PDO::PARAM_STR);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Account");
        return $stmt->fetch();
    }

    function getAccountById($accountId)
    {
        $sql = "SELECT ACCOUNT.ID as id, ACCOUNT.user_id as userId, ACCOUNT.type as type, ACCOUNT.password as password, ACCOUNT.google_id as googleId, ACCOUNT.ldap_id as ldapId, ACCOUNT.secret_id AS secretId FROM ACCOUNT where ACCOUNT.ID = :accountId;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("accountId",$accountId,PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS,"Account");
        return $stmt->fetch();
    }

    function addAccess($accountId)
    {
        try {
            $sql = "INSERT INTO `ACCESS`(`account_id`) VALUES (:accountId)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("accountId",$accountId,PDO::PARAM_INT);
            $result = $stmt->execute();
            if($result)
                return $this->conn->lastInsertId();
        }
        catch (PDOException $e)
        {
//            echo $sql . "<br>" . $e->getMessage();
            return -1;
        }
        return -1;
    }

    function getAllAccessOfUserByUserId($userId)
    {
        $sql = "SELECT AC.ID as id ,A.type as type, AC.timestamp as timestamp FROM ACCESS AC JOIN ACCOUNT A on AC.account_id = A.ID INNER JOIN USER U on A.user_id = U.ID where U.ID = :userId ORDER BY AC.timestamp DESC;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("userId",$userId,PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS,"Access");
    }

    function getCoutnCustomAccount()
    {
        $sql = "SELECT count(*) as countOfCustomAccounts from ACCOUNT where ACCOUNT.type = 'CUSTOM';";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getCoutnGoogleAccount()
    {
        $sql = "SELECT count(*) as countOfGoogleAccounts from ACCOUNT where ACCOUNT.type = 'GOOGLE';";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function getCoutnLdapAccount()
    {
        $sql = "SELECT count(*) as countOfLdapAccounts from ACCOUNT where ACCOUNT.type = 'LDAP';";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

//    function getAllAccessOfUserId($userId)
//    {
//        $sql = "SELECT ACCESS.ID as id, A.type as type, ACCESS.timestamp as timestamp FROM ACCESS INNER JOIN ACCOUNT A on ACCESS.account_id = A.ID INNER JOIN USER U on A.user_id = U.ID WHERE U.ID = :userId;";
//        $stmt = $this->conn->prepare($sql);
//        $stmt->bindParam("userId",$userId,PDO::PARAM_INT);
//        return $stmt->fetchAll(PDO::FETCH_CLASS,"Access");
//    }


}