<?php


class CountOfLogin
{
    public int $customLogin;
    public int $ldapLogin;
    public int $googleLogin;

    /**
     * @return int
     */
    public function getCustomLogin(): int
    {
        return $this->customLogin;
    }

    /**
     * @param int $customLogin
     */
    public function setCustomLogin(int $customLogin): void
    {
        $this->customLogin = $customLogin;
    }

    /**
     * @return int
     */
    public function getLdapLogin(): int
    {
        return $this->ldapLogin;
    }

    /**
     * @param int $ldapLogin
     */
    public function setLdapLogin(int $ldapLogin): void
    {
        $this->ldapLogin = $ldapLogin;
    }

    /**
     * @return int
     */
    public function getGoogleLogin(): int
    {
        return $this->googleLogin;
    }

    /**
     * @param int $googleLogin
     */
    public function setGoogleLogin(int $googleLogin): void
    {
        $this->googleLogin = $googleLogin;
    }


}