<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

#[Route('/api/users')]
class ApiRoleUserController extends AbstractController
{
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer
    ) {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    #[Route('', name: 'api_users_get', methods: ['GET'])]
    #[OA\Get(
        path: "/api/users",
        summary: "Récupérer tous les utilisateurs",
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des utilisateurs",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "username", type: "string", example: "user1"),
                            new OA\Property(property: "roles", type: "array", items: new OA\Items(type: "string"), example: ["ROLE_USER"]),
                        ]
                    )
                )
            )
        ]
    )]
    public function getAllUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $responseData = [];
        foreach ($users as $user) {
            $responseData[] = [
                'id' => $user->getId(),
                'username' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ];
        }

        return new JsonResponse($responseData, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_user_update_role', methods: ['PATCH'])]
    #[OA\Patch(
        path: "/api/users/{id}",
        summary: "Mettre à jour le rôle d'un utilisateur",
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "role", type: "string", example: "ROLE_ADMIN")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Utilisateur mis à jour",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer", example: 1),
                        new OA\Property(property: "username", type: "string", example: "user1"),
                        new OA\Property(property: "roles", type: "array", items: new OA\Items(type: "string"), example: ["ROLE_ADMIN"]),
                    ]
                )
            ),
            new OA\Response(response: 400, description: "Requête invalide"),
            new OA\Response(response: 404, description: "Utilisateur non trouvé")
        ]
    )]
    public function updateUserRole(int $id, Request $request): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['role'])) {
            $allowedRoles = ['ROLE_USER', 'ROLE_ADMIN','ROLE_DEBUTANT','ROLE_INTER','ROLE_AVANCE'];
            if (!in_array($data['role'], $allowedRoles)) {
                return $this->json(['message' => 'Rôle invalide'], Response::HTTP_BAD_REQUEST);
            }

            $user->setRoles([$data['role']]);
            $this->entityManager->flush();

            return $this->json(
                $this->serializer->serialize($user, 'json', ['groups' => 'user:read']),
                Response::HTTP_OK,
                [],
                ['json' => true]
            );
        }

        return $this->json(['message' => 'Aucune modification effectuée'], Response::HTTP_BAD_REQUEST);
    }
}