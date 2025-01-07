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

class UserController extends AbstractController
{
    #[Route('/user', name: 'users', methods: ['GET'])]
    public function getUsers(UserRepository $ur): JsonResponse
    {
        $users = $ur->findAll();
        return $this->json($users);
    }

    #[Route('/user', name: 'add_user', methods: ['POST'])]
    public function addUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonData = $request->getContent();
        $newUser = $serializer->deserialize($jsonData, User::class, 'json');
        $em->persist($newUser);
        $em->flush();
        return new JsonResponse(['message' => 'User Created'], 200);
    }

    #[Route('/user/{email}', name: 'get_user', methods: ['GET'])]
    public function getThisUser(string $email, UserRepository $for): JsonResponse
    {
        $user = $for->findOneBy(['email' => $email]);
        if(!$user){
            return new JsonResponse(['error' => 'User not found', Response::HTTP_NOT_FOUND]);
        }

        return $this->json($user);
    }

    #[Route('/User/{email}', name: 'update_user', methods: ['PUT'])]

    public function updateUser(string $email, UserRepository $for, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $user = $for->findOneBy(['email' => $email]);
        if(!$user){
            return new JsonResponse(['error' => 'User not found', Response::HTTP_NOT_FOUND]);
        }

        $serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);
        $em->flush();
        return $this->json($user);
    }

    #[Route('/User/{email}', name: 'delete_user', methods: ['DELETE'])]

    public function deleteUser(string $email, UserRepository $for, EntityManagerInterface $em): JsonResponse
    {
        $user = $for->findOneBy(['email' => $email]);
        if(!$user){
            return new JsonResponse(['error' => 'User not found', Response::HTTP_NOT_FOUND]);
        }

        $em->remove($user);
        $em->flush();
        return new JsonResponse(['message' => 'User Deleted'], 200);
    }
}
