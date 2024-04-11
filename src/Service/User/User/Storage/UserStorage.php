<?php

namespace App\Service\User\User\Storage;

use App\Helper\NotificationError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class UserStorage
{


    public function __construct(
        private array $users = []
    )
    {
    }

    public function list(NotificationError $notificationError): ?array
    {
        try {
            if (count($this->users) <= 0) {
                $notificationError
                    ->setStatusCode(Response::HTTP_NOT_FOUND)
                    ->setErrors(["error" => "user not found"]);
                return null;
            }
            return $this->users;
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return null;
        }
    }

    public function create(NotificationError $notificationError, array $data): bool
    {
        try {
            $data["id"] = Uuid::v4()->toRfc4122();
            $this->users[] = $data;
            return true;
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return false;
        }
    }

    public function update(NotificationError $notificationError, array $data): bool
    {
        try {
            $userUpdate = $data;
            return true;
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return false;
        }
    }

    public function delete(NotificationError $notificationError, string $id): bool
    {
        try {
            $indexUser = array_search($id, $this->users);
            if (!$indexUser) {
                return false;
            }
            unset($this->users[$indexUser]);
            return true;
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return false;
        }
    }

    public function listOne(NotificationError $notificationError, string $id): ?array
    {
        try {
            $indexUser = array_search($id, $this->users);
            if (!$indexUser) {
                return null;
            }
            return $this->users[$indexUser];
        } catch (\Exception) {
            $notificationError
                ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                ->setErrors(["error" => "internal server error"]);
            return null;
        }
    }
}