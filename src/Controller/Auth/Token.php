<?php

namespace App\Controller\Auth;

use App\Helper\NotificationError;
use App\Service\Auth\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/auth/v1")]
final class Token extends AbstractController
{
    public function __construct(
        private readonly AuthService $authService
    )
    {
    }

    #[Route("/token", methods: ["POST"])]
    public function token(Request $request): Response
    {
        $notificationError = new NotificationError();
        $data = [
            "email" => (string) $request->get("email"),
            "password" => (string) $request->get("password")
        ];
        $token = $this->authService->token($notificationError, $data);
        if(!$token) {
            return new JsonResponse($notificationError->getErrors(), $notificationError->getStatusCode());
        }
        return new JsonResponse(["token" => $token], Response::HTTP_OK);
    }

}