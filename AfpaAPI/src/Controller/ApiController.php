<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Author;
use Symfony\Component\Serializer\Serializer;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/items', name: 'api_get_items', methods: ['GET'])]
    public function getItems(): JsonResponse
    {
        $items = [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
        ];

        return $this->json($items);
    }

    #[Route('/items', name: 'api_post_items', methods: ['POST'])]
    public function postItem(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $newItem = ['id' => 3, 'name' => $data['name']];

        return $this->json($newItem, 201);
    }

    #[Route('/authors', name: 'create_author', methods: ['POST'])]
    public function createAuthor(Request $request, Serializer $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonData = json_decode($request->getContent(), true);
        $author = $serializer->deserialize($jsonData, Author::Class, 'json');
        $em->persist($author);
        $em->flush();

        return new JsonResponse(['status' => 'Author created!'], 201);
    }

    #[Route('/items/{id}', name: 'api_put_items', methods: ['PUT'])]
    public function putItem(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $updatedItem = ['id' => $id, 'name' => $data['name']];

        return $this->json($updatedItem);
    }

    #[Route('/items/{id}', name: 'api_delete_items', methods: ['DELETE'])]
    public function deleteItem(int $id): JsonResponse
    {
        return $this->json(['message' => 'Item deleted', 204]);
    }
}
