<?php


class LdapData
{
    public string $name;
    public string $surname;
    public string $email;
    public int $ldapId;

    /**
     * LdapData constructor.
     * @param string $name
     * @param string $surname
     * @param string $email
     * @param int $ldapId
     */
    public function __construct(string $name, string $surname, string $email, int $ldapId)
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->ldapId = $ldapId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return int
     */
    public function getLdapId(): int
    {
        return $this->ldapId;
    }

    /**
     * @param int $ldapId
     */
    public function setLdapId(int $ldapId): void
    {
        $this->ldapId = $ldapId;
    }




}