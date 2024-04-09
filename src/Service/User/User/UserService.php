<?php

namespace App\Service\User\User;

use App\Helper\NotificationError;
use App\Service\User\User\Storage\UserStorage;
use App\Service\User\User\Validation\UserForm;
use Symfony\Component\HttpFoundation\Response;

final readonly class UserService
{
    public function __construct(
        private UserStorage $userStorage
    )
    {

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
        return $this->userStorage->create($notificationError, $data);
    }

    public function update(NotificationError $notificationError, string $id, array $data): bool
    {
        if ($id != 1) {
            $notificationError
                ->setStatusCode(Response::HTTP_NOT_FOUND)
                ->setErrors(["error" => "user not found"]);
            return false;
        }
        $isUserValid = UserForm::update($notificationError, $data);
        if (!$isUserValid) {
            return false;
        }
        return $this->userStorage->update($notificationError, $data);
    }

    public function delete(NotificationError $notificationError, string $id): bool
    {
        return $this->userStorage->delete($notificationError, $id);
    }

    public function find(NotificationError $notificationError, string $id): ?array
    {
        $user = $this->userStorage->listOne($notificationError, $id);
        if (!$user) {
            $notificationError
                ->setStatusCode(Response::HTTP_NOT_FOUND)
                ->setErrors(["error" => "user not found"]);
            return null;
        }
        return $user;
    }
}