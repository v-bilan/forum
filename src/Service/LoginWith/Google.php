<?php

namespace App\Service\LoginWith;
use Google\Client;
class Google implements LoginWithInterface
{
    public function __construct(private Client $google)
    {
    }
    function getLoginUrl(): string
    {
        $this->google->addScope("email");
        $this->google->addScope("profile");
        return $this->google->createAuthUrl();
    }
}