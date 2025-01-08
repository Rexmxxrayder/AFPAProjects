<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function test(Request $request, JWTTokenManagerInterface  $jwtManager): JsonResponse
    {
        $token = $request->headers->get('Authorization');
        $token = substr($token, 7);
        print($token . ' ');
        print(mb_strlen($token) . ' ');
        $goodtoken = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzYzMzE5NjQsImV4cCI6MTczNjMzNTU2NCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiYWxpY2UuZHVwb250QGV4YW1wbGUuY29tIn0.A-sya4VT1aJhrbRhKA0qAxhJRps3ph0gJr4nuMh9g6BnavI3CRtEqUCfK77VZt4npX_SSYwBaJsnfg9gFBBU63xNDO-OxISq8ebheWOKriy5kT6bFthd8LyddJSL4GDFKkpgVREM4qNyGIXcKXumsOnW50R-bEooY9CXnysXbVvU0HKoRLIXu65Q8NOEXTeoFmOMYrmUU8hNrhc2K9PXo-p4uwFmibmWleJ_AANL0Cgg7wAmmnTEPjwaD2kufUpcQ7Wh_LimRkEWyH6Mzw4SMRAVFAfuZJR8oqz0doTTxpRL1DsrMfF3krsYdmSQt_D3uA3j6fTDgKmAULJz21JiFZ2TqFgc90N7pevqZjf8foTH-LPM6ddc5FYHkAK4khiwbdIlElg4q0seN4SVrUfM73CQwoIpuEA20IRfsPcrhNURzGA9IxcCApVgxYXZXdW-VGzkLNns1ChoGcFsW2F5ECh5cD4-zy7UTLU8JC1zTfqdU-Z8XszadrKnkGA4qTezkUqKsIj0S5E7A2IFjoKRF9sX3Njz0VXsQdiT2Oj2mq43KAKI7SfQIs-D1zPtM8hOMs26W0JwEgl35iKC6z84CFop3JPDkU7qycam5pmU2RuBdMeVpL5mdEMCFIxhLsOEhHJT9v1F1XRJycGdAxNMwXSKiFYcYLyr2wR-7iQSJLE";
        print($token == $goodtoken ? "true" : "false");
        if ($token) {
            try {
                $jwtManager->parse($token);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Token invalide ou expiré'], 401);
            }
        } else {
            return new JsonResponse(['message' => 'Jeton manquant'], 401);
        }

        return new JsonResponse(['message' => 'Jeton présent'], 401);
    }

    #[Route('/test2', name: 'test2')]
    public function test2(Request $request, JWTTokenManagerInterface  $jwtManager): JsonResponse
    {
        $token = "";
        //$token .= "Bearer ";
        $token .= "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE3MzYzMzE5NjQsImV4cCI6MTczNjMzNTU2NCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiYWxpY2UuZHVwb250QGV4YW1wbGUuY29tIn0.A-sya4VT1aJhrbRhKA0qAxhJRps3ph0gJr4nuMh9g6BnavI3CRtEqUCfK77VZt4npX_SSYwBaJsnfg9gFBBU63xNDO-OxISq8ebheWOKriy5kT6bFthd8LyddJSL4GDFKkpgVREM4qNyGIXcKXumsOnW50R-bEooY9CXnysXbVvU0HKoRLIXu65Q8NOEXTeoFmOMYrmUU8hNrhc2K9PXo-p4uwFmibmWleJ_AANL0Cgg7wAmmnTEPjwaD2kufUpcQ7Wh_LimRkEWyH6Mzw4SMRAVFAfuZJR8oqz0doTTxpRL1DsrMfF3krsYdmSQt_D3uA3j6fTDgKmAULJz21JiFZ2TqFgc90N7pevqZjf8foTH-LPM6ddc5FYHkAK4khiwbdIlElg4q0seN4SVrUfM73CQwoIpuEA20IRfsPcrhNURzGA9IxcCApVgxYXZXdW-VGzkLNns1ChoGcFsW2F5ECh5cD4-zy7UTLU8JC1zTfqdU-Z8XszadrKnkGA4qTezkUqKsIj0S5E7A2IFjoKRF9sX3Njz0VXsQdiT2Oj2mq43KAKI7SfQIs-D1zPtM8hOMs26W0JwEgl35iKC6z84CFop3JPDkU7qycam5pmU2RuBdMeVpL5mdEMCFIxhLsOEhHJT9v1F1XRJycGdAxNMwXSKiFYcYLyr2wR-7iQSJLE";
        print(mb_strlen($token . ' '));
        print($token . ' ');
        if ($token) {
            try {
                $jwtManager->parse($token);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Token invalide ou expiré'], 401);
            }
        } else {
            return new JsonResponse(['message' => 'Jeton manquant'], 401);
        }

        return new JsonResponse(['message' => 'Jeton présent'], 401);
    }

    #[Route('/createtoken', name: 'createtoken')]
    public function createtoken(UserRepository $ur, JWTTokenManagerInterface  $jwtManager): JsonResponse
    {
        $user = $ur->findOneBy(["email" => 'alice.dupont@example.com']);
        $token = $jwtManager->create($user);
        print($token . ' ');
        print(mb_strlen($token . ' '));
        if ($token) {
            try {
                $jwtManager->parse($token); 
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Token invalide ou expiré'], 401);
            }
        } else {
            return new JsonResponse(['message' => 'Jeton manquant'], 401);
        }
        if ($token) {
            try {
                $jwtManager->parse($token);
            } catch (\Exception $e) {
                return new JsonResponse(['message' => 'Token invalide ou expiré'], 401);
            }
        } else {
            return new JsonResponse(['message' => 'Jeton manquant'], 401);
        }
        return new JsonResponse(['message' => 'Jeton présent'], 401);
    }
}
