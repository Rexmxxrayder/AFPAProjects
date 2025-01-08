<?php

namespace App\Controller;

use App\Entity\FoundObject;
use App\Entity\User;
use DateInterval;
use DateTime;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class SearchController extends AbstractController
{
    #[Route('/search/foundObject/localisation', name: 'search_foundObject_localisation')]
    public function searchFoundObjectByLocalisation(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $localisation = $request->getPayload()->get('localisation');
        $station = $request->getPayload()->get('station');
        $query =  $em->getRepository(FoundObject::class)->createQueryBuilder("foundObject");
        $query->where("foundObject.localisation = :localisation")
            ->andWhere("foundObject.station = :station")
            ->setParameter("localisation", $localisation)
            ->setParameter("station", $station);
        // dump($query->getQuery()->getSQL());     
        // dump($query->getQuery()->execute());     
        $foundObjects = $query->getQuery()->execute();
        return $this->json($foundObjects, 200, [], ['groups' => 'FoundObject:read']);
    }

    #[Route('/search/foundObject/reportdate', name: 'search_foundObject_date')]
    public function searchFoundObjectByDate(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $startDate = DateTime::createFromFormat("Y-m-d", $request->getPayload()->get('startDate'));
        $endDate = DateTime::createFromFormat("Y-m-d", $request->getPayload()->get('endDate'));
        $query =  $em->getRepository(FoundObject::class)->createQueryBuilder("foundObject");
        $query->where("foundObject.reportDate > :startDate AND foundObject.reportDate < :endDate")
            ->setParameter("startDate", $startDate)
            ->setParameter("endDate", $endDate);
        //dump($query->getQuery()->getSQL());     
        //dump($query->getQuery()->execute());     
        $foundObjects = $query->getQuery()->execute();
        return $this->json($foundObjects, 200, [], ['groups' => 'FoundObject:read']);
    }

    #[Route('/search/foundObject/status', name: 'search_foundObject_status')]
    public function searchFoundObjectByStatus(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $status = $request->getPayload()->get('status');
        $query =  $em->getRepository(FoundObject::class)->createQueryBuilder("foundObject");
        $query->where("foundObject.status = :status")
            ->setParameter("status", $status);
        //dump($query->getQuery()->getSQL());     
        //dump($query->getQuery()->execute());     
        $foundObjects = $query->getQuery()->execute();
        return $this->json($foundObjects, 200, [], ['groups' => 'FoundObject:read']);
    }

    #[Route('/search/user/name', name: 'search_user_name')]
    public function searchUserByName(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $surname = $request->getPayload()->get('surname');
        $firstName = $request->getPayload()->get('firstName');
        $query =  $em->getRepository(User::class)->createQueryBuilder("user");
        $query->where('1 = 1');
        if ($surname) {
            $query->andWhere("user.surname = :surname")
                ->setParameter("surname", $surname);
        }

        if ($firstName) {
            $query->andWhere("user.firstName = :firstName")
                ->setParameter("firstName", $firstName);
        }

        dump($query->getQuery()->getSQL());     
        dump($query->getQuery()->execute());     
        $users = $query->getQuery()->execute();
        return $this->json($users);
    }

    #[Route('/search/user/email', name: 'search_user_email')]
    public function searchUserByEmail(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $email = $request->getPayload()->get('email');
        $query =  $em->getRepository(User::class)->createQueryBuilder("user");
        $query->where("user.email = :email")
                ->setParameter("email", $email);
        dump($query->getQuery()->getSQL());     
        dump($query->getQuery()->execute());     
        $users = $query->getQuery()->execute();
        return $this->json($users);
    }

    #[Route('/search/user/phoneNumber', name: 'search_user_phoneNumber')]
    public function searchUserByPhoneNumber(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $phoneNumber = $request->getPayload()->get('phoneNumber');
        $query =  $em->getRepository(User::class)->createQueryBuilder("user");
        $query->where("user.phoneNumber = :phoneNumber")
                ->setParameter("phoneNumber", $phoneNumber);
        dump($query->getQuery()->getSQL());     
        dump($query->getQuery()->execute());     
        $users = $query->getQuery()->execute();
        return $this->json($users);
    }
}
