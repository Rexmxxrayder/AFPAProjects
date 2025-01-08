<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/user', name: 'users', methods: ['GET'])]
    public function getUsers(UserRepository $ur): JsonResponse
    {
        $users = $ur->findAll();
        return $this->json($users);
    }

    #[Route('/user', name: 'add_user', methods: ['POST'])]
    public function addUser(Request $request, SerializerInterface $serializer, UserPasswordHasherInterface $uphi, EntityManagerInterface $em): JsonResponse
    {
        $jsonData = $request->getContent();
        $newUser = $serializer->deserialize($jsonData, User::class, 'json');
        $password = $request->getPayload()->get("password");
        $newUser->setPassword($uphi->hashPassword($newUser, $password));
        $em->persist($newUser);
        $em->flush();
        return new JsonResponse(['message' => 'User Created'], 200);
    }

    #[Route('/user/{id}', name: 'get_user', methods: ['GET'])]
    public function getThisUser(int $id, UserRepository $for): JsonResponse
    {
        $user = $for->find($id);
        if(!$user){
            return new JsonResponse(['error' => 'User not found', Response::HTTP_NOT_FOUND]);
        }

        return $this->json($user);
    }

    #[Route('/user/{id}', name: 'update_user', methods: ['PUT'])]

    public function updateUser(int $id, UserRepository $for, Request $request, UserPasswordHasherInterface $uphi, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $user = $for->find($id);
        if(!$user){
            return new JsonResponse(['error' => 'User not found', Response::HTTP_NOT_FOUND]);
        }

        $serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);
        $password = $request->getPayload()->get("password");
        $user->setPassword($uphi->hashPassword($user, $password));
        $em->flush();
        return $this->json($user);
    }

    #[Route('/user/{id}', name: 'delete_user', methods: ['DELETE'])]

    public function deleteUser(int $id, UserRepository $for, EntityManagerInterface $em): JsonResponse
    {
        $user = $for->find($id);
        if(!$user){
            return new JsonResponse(['error' => 'User not found', Response::HTTP_NOT_FOUND]);
        }

        $em->remove($user);
        $em->flush();
        return new JsonResponse(['message' => 'User Deleted'], 200);
    }
}
