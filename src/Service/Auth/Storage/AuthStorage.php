<?php

namespace App\Service\Auth\Storage;

use App\Entity\User;
use App\Helper\NotificationError;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Response;

final readonly class AuthStorage
{
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function token(NotificationError $notificationError, array $data): ?string
    {
        /** @var User $userEntity */
        $userEntity = $this->entityManager->getRepository(User::class)->findOneBy([
            "email" => $data["email"],
        ]);
        $passwordChecked = password_verify($data["password"], $userEntity->getPassword());
        if (!$userEntity->getEmail() || !$passwordChecked) {
            $notificationError
                ->setStatusCode(Response::HTTP_UNAUTHORIZED)
                ->setErrors(
                    [
                        "message" => "Invalid email or password"
                    ]
                );
            return null;
        }
        try {
            $payload = [
                "email" => $data["email"],
                "exp" => time() + $_ENV["JWT_EXPIRES"]
            ];
            return JWT::encode($payload, $_ENV["JWT_SECRET"], $_ENV["JWT_ENCODER"]);
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return null;
        }
    }
}