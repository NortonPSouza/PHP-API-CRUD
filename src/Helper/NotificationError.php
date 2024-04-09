<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Response;

final class NotificationError
{
    private int $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;
    private array $errors = [];

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): NotificationError
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setErrors(array $errors): NotificationError
    {
        $this->errors = $errors;
        return $this;
    }

}