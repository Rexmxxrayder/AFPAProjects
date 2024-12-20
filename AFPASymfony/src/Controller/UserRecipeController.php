<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserRecipeController extends AbstractController
{
    #[Route('/user/recipe', name: 'app_user_recipe')]
    public function index(): Response
    {
        return $this->render('user_recipe/index.html.twig', [
            'controller_name' => 'UserRecipeController',
        ]);
    }
}
