<?php

require_once "repository/Repository.php";
require_once "repository/RepositoryFa.php";
require_once "service/CustomLoginService.php";

$r = new Repository();


$name = "Kristián";
$surname = "Sokol";
$email = "kikosokol@gmail.com";
$password = "hesielko";

//$userId = $r->addNewUser($name,$surname,$email);
//
//if($userId == -1)
//    echo "kriste pane nefunguje pridanie usera <br>";
//else
//{
//    $correct = $r->checkMatchingPassword($password,$password);
//    echo "heslo je spravne   " . $correct;
//
//    $accountId = $r->createNewCustomAccountForUser($userId,$password);
//    if($accountId == -1)
//    {
//        echo "bože dačo nefunguje pri pridani accountu";
//    }
//}


//var_dump($r->verificationCustomAccountOfUser(1,$password));
//var_dump($r->getUserByEmail($email));
//var_dump($r->getAllAccessOfUserByUserId(1));

//var_dump($r->getCoutnGoogleAccount());


//$u = password_hash($password, PASSWORD_DEFAULT);
//echo $u . "<br>";
//echo password_verify($password, $u). "<br>";
//echo password_hash($password, PASSWORD_DEFAULT);


//var_dump($r->getUserByEmail("ahoj"));


$customService = new CustomLoginService();

//$customService->createNewUser("Janko","Hrasko","janko@hrasko.sk","123456789","123456789","123456789");

//echo $r->createNewCustomAccountForUser(1,"123456789","123456789");
//
//$f = $customService->getSecretCodeForRegistration();
//echo $f->secredId;
//echo '<br /><img src="'.$f->qrCodeUrl.'" />';








