<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use OpenApi\Attributes as OA;

class ApiLoginController extends AbstractController
{
    
#[Route('api/login', name: 'api_login', methods: 'POST')]
#[OA\Post(
    path: "/api/login",
    summary: "Connecter un utilisateur",
    requestBody: new OA\RequestBody(
        required: true,
        description: "Données de l’utilisateur pour se connecter",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(property: "username", type: "string", example: "adresse@email.com"),
                new OA\Property(property: "password", type: "string", example: "Mot de passe")
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: "Connexion réussie",
            content: new OA\JsonContent(
                type: "object",
                properties: [
                    new OA\Property(property: "user", type: "string", example: "Nom d'utilisateur"),
                    new OA\Property(property: "apiToken", type: "string", example: "31a023e212f116124a36af14ea0c1c3806eb9378"),
                    new OA\Property(property: "roles", type: "array", items: new OA\Items(type: "string", example: "ROLE_USER"))
                ]
            )
        )
    ]
)]
public function login(#[CurrentUser] ?User $user): JsonResponse
{
    if (null === $user) {
        return new JsonResponse(['message' => 'Missing credentials'], Response::HTTP_UNAUTHORIZED);
    }

    return new JsonResponse([
        'user'  => $user->getUserIdentifier(),
        'apiToken' => $user->getApiToken(),
        'roles' => $user->getRoles(),
    ]);
}

}
