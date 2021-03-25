<?php


class TwoFactoryAuthentication
{
    public string $secretId;
    public string $qrCodeUrl;


    public function __construct(string $secretId, string $qrCodeUrl)
    {
        $this->secretId = $secretId;
        $this->qrCodeUrl = $qrCodeUrl;
    }

    /**
     * @return string
     */
    public function getSecretId(): string
    {
        return $this->secretId;
    }

    /**
     * @param string $secretId
     */
    public function setSecretId(string $secretId): void
    {
        $this->secretId = $secretId;
    }

    /**
     * @return string
     */
    public function getQrCodeUrl(): string
    {
        return $this->qrCodeUrl;
    }

    /**
     * @param string $qrCodeUrl
     */
    public function setQrCodeUrl(string $qrCodeUrl): void
    {
        $this->qrCodeUrl = $qrCodeUrl;
    }




}