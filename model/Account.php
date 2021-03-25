<?php

class Account
{
    public int $id;
    public int $userId;
    public string $type;
    public ?string $password;
    public ?string $googleId;
    public ?int $ldapId;
    public ?string $secretId;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getGoogleId(): string
    {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     */
    public function setGoogleId(string $googleId): void
    {
        $this->googleId = $googleId;
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

    /**
     * @return string|null
     */
    public function getSecretId(): ?string
    {
        return $this->secretId;
    }

    /**
     * @param string|null $secretId
     */
    public function setSecretId(?string $secretId): void
    {
        $this->secretId = $secretId;
    }






}