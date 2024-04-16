<?php

namespace App\Service\Auth\Validation;
use App\Helper\NotificationError;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Response;

abstract class AuthForm
{

    public static function token(NotificationError $notificationError, array $data): bool
    {
        $validate =  v::allOf(
            v::key("email", v::stringType()->notEmpty()->setName("name")),
            v::key("password", v::stringType()->notEmpty()->setName("password"))
        );
        try{
            $validate->assert($data);
        }catch (\InvalidArgumentException){
            $notificationError
                ->setStatusCode(Response::HTTP_BAD_REQUEST)
                ->setErrors([
                    "error" => "invalid params"
                ]);
        }
        return $validate->validate($data);
    }
}