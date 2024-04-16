<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpFoundation\Response;

abstract class TokenVerify
{
    public static function verify(NotificationError $notificationError, ?string $token): ?array
    {
        if (!$token) {
            $notificationError
                ->setErrors(["error" => "token is required"])
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
            return null;
        }
        try {
            $decodeToken = JWT::decode($token, new Key($_ENV["JWT_SECRET"], $_ENV["JWT_ENCODER"]));
            return (array)$decodeToken;
        } catch (\Exception $exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_UNAUTHORIZED)
                ->setErrors([
                    "error" => $exception->getMessage()
                ]);
            return null;
        }
    }
}