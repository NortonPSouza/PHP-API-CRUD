<?php

namespace App\Service\Auth;

use App\Helper\NotificationError;
use App\Service\Auth\Storage\AuthStorage;
use App\Service\Auth\Validation\AuthForm;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{

    public function __construct(
        private readonly AuthStorage $authStorage
    )
    {
    }

    public function token(NotificationError $notificationError, array $data): ?string
    {
        $loginIsValid = AuthForm::token($notificationError, $data);
        if (!$loginIsValid) {
            return null;
        }
        return $this->authStorage->token($notificationError, $data);
    }
}