<?php

namespace App\Controller;

use App\Entity\Station;
use App\Repository\StationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StationController extends AbstractController
{
    #[Route('/station', name: 'stations', methods: ['GET'])]
    public function getStations(StationRepository $sr): JsonResponse
    {
        $stations = $sr->findAll();
        return $this->json($stations);
    }

    #[Route('/station', name: 'add_station', methods: ['POST'])]
    public function addStation(Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $jsonData = $request->getContent();
        $newStation = $serializer->deserialize($jsonData, Station::class, 'json');
        $em->persist($newStation);
        $em->flush();
        return new JsonResponse(['message' => 'Station Created'], 200);
    }

    #[Route('/station/{id}', name: 'get_station', methods: ['GET'])]
    public function getThisStation(string $id, StationRepository $for): JsonResponse
    {
        $station = $for->findOneBy(['id' => $id]);
        if(!$station){
            return new JsonResponse(['error' => 'Station not found', Response::HTTP_NOT_FOUND]);
        }

        return $this->json($station);
    }

    #[Route('/Station/{id}', name: 'update_station', methods: ['PUT'])]

    public function updateStation(string $id, StationRepository $for, Request $request, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        $station = $for->findOneBy(['id' => $id]);
        if(!$station){
            return new JsonResponse(['error' => 'Station not found', Response::HTTP_NOT_FOUND]);
        }

        $serializer->deserialize($request->getContent(), Station::class, 'json', ['object_to_populate' => $station]);
        $em->flush();
        return $this->json($station);
    }

    #[Route('/Station/{id}', name: 'delete_station', methods: ['DELETE'])]

    public function deleteStation(string $id, StationRepository $for, EntityManagerInterface $em): JsonResponse
    {
        $station = $for->findOneBy(['id' => $id]);
        if(!$station){
            return new JsonResponse(['error' => 'Station not found', Response::HTTP_NOT_FOUND]);
        }

        $em->remove($station);
        $em->flush();
        return new JsonResponse(['message' => 'Station Deleted'], 200);
    }
}
