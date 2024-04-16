<?php

namespace App\Service\User\User;

use App\Entity\User;
use App\Helper\NotificationError;
use App\Service\User\User\Storage\UserStorage;
use App\Service\User\User\Validation\UserForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class UserService
{
    private UserStorage $userStorage;
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    )
    {
        $this->userStorage = new UserStorage($entityManager);
    }

    public function list(NotificationError $notificationError): ?array
    {
        return $this->userStorage->list($notificationError);
    }

    public function create(NotificationError $notificationError, array $data): bool
    {
        $isUserValid = UserForm::create($notificationError, $data);
        if (!$isUserValid) {
            return false;
        }
        $hasEmail = $this->entityManager->getRepository(User::class)->findBy(["email" => $data['email']]);
        if($hasEmail){
            $notificationError
                ->setStatusCode(Response::HTTP_CONFLICT)
                ->setErrors(["error" => "user already exists"]);
            return false;
        }
        return $this->userStorage->create($notificationError, $data);
    }

    public function update(NotificationError $notificationError, int $id, array $data): bool
    {
        $isUserValid = UserForm::update($notificationError, $data);
        if (!$isUserValid) {
            return false;
        }
        $hasUser = $this->entityManager->getRepository(User::class)->findBy(["id" => $id]);
        if (!$hasUser) {
            $notificationError
                ->setStatusCode(Response::HTTP_NOT_FOUND)
                ->setErrors(["error" => "user not found"]);
            return false;
        }
        return $this->userStorage->update($notificationError, $id, $data);
    }

    public function delete(NotificationError $notificationError, string $id): bool
    {
        return $this->userStorage->delete($notificationError, $id);
    }

    public function find(NotificationError $notificationError, string $id): ?array
    {
        return $this->userStorage->find($notificationError, $id);
    }
}