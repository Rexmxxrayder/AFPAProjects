<?php

namespace App\Controller;

use App\Entity\FoundObject;
use App\Entity\Station;
use App\Repository\FoundObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FoundObjectController extends AbstractController
{
    #[Route('/found/object', name: 'found_objects', methods: ['GET'])]
    public function getObjects(FoundObjectRepository $for): JsonResponse
    {
        $foundObjects = $for->findAll();
        return $this->json($foundObjects, 200, [], ['groups' => 'FoundObject:read']);
    }

    #[Route('/found/object', name: 'add_found_object', methods: ['POST'])]
    public function addObject(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonData = $request->getContent();
        $newFoundObject = $serializer->deserialize($jsonData, FoundObject::class, 'json');
        $station 
        $em->persist($newFoundObject);
        $em->flush();
        return new JsonResponse(['message' => 'FoundObject Created'], 200);
    }

    #[Route('/found/object/{id}', name: 'get_found_object', methods: ['GET'])]
    public function getObject(int $id, FoundObjectRepository $for): JsonResponse
    {
        $foundObject = $for->find($id);
        if(!$foundObject){
            return new JsonResponse(['error' => 'FoundObject not found', Response::HTTP_NOT_FOUND]);
        }

        return $this->json($foundObject, 200, [], ['groups' => 'FoundObject:read']);
    }

    #[Route('/found/object/{id}', name: 'update_found_object', methods: ['PUT'])]

    public function updateObject(int $id, FoundObjectRepository $for, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $foundObject = $for->find($id);
        if(!$foundObject){
            return new JsonResponse(['error' => 'FoundObject not found', Response::HTTP_NOT_FOUND]);
        }
    
        $serializer->deserialize($request->getContent(), FoundObject::class, 'json', ['object_to_populate' => $foundObject]);
        $em->flush();
        return $this->json($foundObject, 200, [], ['groups' => 'FoundObject:read']);
    }

    #[Route('/found/object/{id}', name: 'delete_found_object', methods: ['DELETE'])]

    public function deleteObject(int $id, FoundObjectRepository $for, EntityManagerInterface $em): JsonResponse
    {
        $foundObject = $for->find($id);
        if(!$foundObject){
            return new JsonResponse(['error' => 'FoundObject not found', Response::HTTP_NOT_FOUND]);
        }

        $em->remove($foundObject);
        $em->flush();
        return new JsonResponse(['message' => 'FoundObject Deleted'], 200);
    }
}
