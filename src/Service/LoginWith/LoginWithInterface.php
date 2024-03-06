<?php
namespace App\Service\LoginWith;
interface LoginWithInterface
{
    function getLoginUrl(): string;
}