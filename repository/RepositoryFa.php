<?php

require_once 'PHPGangsta/GoogleAuthenticator.php';
require_once 'model/TwoFactoryAuthentication.php';

class RepositoryFa
{
    private PHPGangsta_GoogleAuthenticator $ga;

    const SITE = 'sokol_z3';

    /**
     * RepositoryFa constructor.
     * @param $ga
     */
    public function __construct()
    {
        $this->ga = new PHPGangsta_GoogleAuthenticator();
    }

    function getStart()
    {
        $secretCode = $this->ga->createSecret();
        $qrCodeUrl = $this->ga->getQRCodeGoogleUrl(self::SITE,$secretCode);

        $fa = new TwoFactoryAuthentication($secretCode,$qrCodeUrl);

        return $fa;
    }

    function verifyForRegistration($secretCode,$code)
    {
        $result = $this->ga->verifyCode($secretCode,$code,1);

        return $result;
    }

    function verify($secretCode,$code)
    {
        return $this->ga->verifyCode($secretCode,$code);
    }


}