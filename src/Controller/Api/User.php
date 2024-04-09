<?php

namespace App\Controller\Api;

use App\Helper\NotificationError;
use App\Service\User\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/v1/user")]
final class User extends AbstractController
{
    public function __construct(
        private readonly UserService $userService
    )
    {

    }

    #[Route("", methods: ["GET"])]
    public function list(Request $request): Response
    {
        $notificationError = new NotificationError();
        $users = $this->userService->list($notificationError);
        if (!$users) {
            return new JsonResponse($notificationError->getErrors(), $notificationError->getStatusCode());
        }
        return new JsonResponse($users, Response::HTTP_OK);
    }

    #[Route("", methods: ["POST"])]
    public function create(Request $request): Response
    {
        $notificationError = new NotificationError();
        $data = [
            "name" => (string) $request->get("name"),
            "email" => (string) $request->get("email"),
            "password" => (string) $request->get("password"),
            "city" => (string) $request->get("city"),
        ];
        $wasCreated = $this->userService->create($notificationError, $data);
        if (!$wasCreated) {
            return new JsonResponse($notificationError->getErrors(), $notificationError->getStatusCode());
        }
        return new Response("", Response::HTTP_CREATED);
    }

    #[Route("/{id}", methods: ["PUT"])]
    public function update(Request $request, string $id): Response
    {
        $notificationError = new NotificationError();
        $data = [
            "name" => (string) $request->get("name"),
            "city" => (string) $request->get("city"),
        ];
        $wasUpdated = $this->userService->update($notificationError, $id, $data);
        if (!$wasUpdated) {
            return new JsonResponse($notificationError->getErrors(), $notificationError->getStatusCode());
        }
        return new Response("", Response::HTTP_OK);
    }

    #[Route("/{id}", methods: ["GET"])]
    public function find(Request $request, string $id): Response
    {
        $notificationError = new NotificationError();
        $user = $this->userService->find($notificationError, $id);
        if (!$user) {
            return new JsonResponse($notificationError->getErrors(), $notificationError->getStatusCode());
        }
        return new JsonResponse($user, Response::HTTP_OK);
    }

    #[Route("/{id}", methods: ["DELETE"])]
    public function delete(Request $request, string $id): Response
    {
        $notificationError = new NotificationError();
        $wasDeleted = $this->userService->delete($notificationError, $id);
        if (!$wasDeleted) {
            return new JsonResponse($notificationError->getErrors(), $notificationError->getStatusCode());
        }
        return new Response("", Response::HTTP_OK);
    }
}