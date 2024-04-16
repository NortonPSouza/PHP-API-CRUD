<?php

namespace App\Service\User\User\Storage;

use App\Entity\User;
use App\Helper\NotificationError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final readonly class UserStorage
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    public function list(NotificationError $notificationError): ?array
    {
        /** @var User[] $userEntity */
        $usersEntity = $this->entityManager->getRepository(User::class)->findAll();
        try {
            if (!$usersEntity) {
                $notificationError
                    ->setStatusCode(Response::HTTP_NOT_FOUND)
                    ->setErrors(["error" => "user not found"]);
                return null;
            }
            return array_map(function (User $userEntity){
                return [
                    "id" => $userEntity->getId(),
                    "name" => $userEntity->getName(),
                    "email" => $userEntity->getEmail(),
                    "city" => $userEntity->getCity()
                ];
            }, $usersEntity);
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return null;
        }
    }

    public function create(NotificationError $notificationError, array $data): bool
    {
        $password = password_hash($data["password"],PASSWORD_DEFAULT);
        try {
            $userEntity = new User();
            $userEntity->setName($data["name"]);
            $userEntity->setEmail($data["email"]);
            $userEntity->setPassword($password);
            $userEntity->setCity($data["city"]);

            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
            return true;
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return false;
        }
    }

    public function update(NotificationError $notificationError, int $id, array $data): bool
    {
        /** @var User $userEntity */
        $userEntity = $this->entityManager->getRepository(User::class)->find($id);
        try {
            $userEntity->setName($data["name"]);
            $userEntity->setCity($data["city"]);
            $userEntity->setPassword($data["password"]);

            $this->entityManager->persist($userEntity);
            $this->entityManager->flush();
            return true;
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return false;
        }
    }

    public function delete(NotificationError $notificationError, int $id): bool
    {
        try {
            /** @var User $userEntity */
            $userEntity = $this->entityManager->getRepository(User::class)->find($id);
            if (!$userEntity) {
                $notificationError
                    ->setStatusCode(Response::HTTP_NOT_FOUND)
                    ->setErrors(["error" => "user not found"]);
                return false;
            }
            $this->entityManager->remove($userEntity);
            $this->entityManager->flush();
            return true;
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return false;
        }
    }

    public function find(NotificationError $notificationError, int $id): ?array
    {
        try {
            /** @var User $userEntity */
            $userEntity = $this->entityManager->getRepository(User::class)->find($id);
            if (!$userEntity) {
                $notificationError
                    ->setStatusCode(Response::HTTP_NOT_FOUND)
                    ->setErrors(["error" => "user not found"]);
                return null;
            }
            return [
                "id" => $userEntity->getId(),
                "name" => $userEntity->getName(),
                "email" => $userEntity->getEmail(),
                "city" => $userEntity->getCity()
            ];
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return null;
        }
    }
}