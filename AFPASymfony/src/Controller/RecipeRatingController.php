<?php

namespace App\Controller;

use App\Entity\RecipeRating;
use App\Entity\Recipe;
use App\Form\RecipeRatingType;
use App\Repository\RecipeRatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recipe/rating')]
final class RecipeRatingController extends AbstractController
{
    #[Route(name: 'app_recipe_rating_index', methods: ['GET'])]
    public function index(RecipeRatingRepository $recipeRatingRepository): Response
    {
        return $this->render('recipe_rating/index.html.twig', [
            'recipe_ratings' => $recipeRatingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_rating_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {

            $recipeId = $request->request->get('Recipe');
            $recipe = $entityManager->getRepository(Recipe::class)->find($recipeId);
            if (!$this->getUser()->HaveAlreadyRateThisRecipe($recipe)) {
                $rate = $request->request->get('rate');
                $author = $this->getUser();
                $recipeRating = new RecipeRating();
                $recipeRating->setRate($rate);
                $recipeRating->setRecipe($recipe);
                $recipeRating->setAuthor($author);
                $entityManager->persist($recipeRating);
                $entityManager->flush();
            }
        }

        return $this->redirect('/recipe/show/' . $request->request->get('Recipe'));
    }

    #[Route('/{id}', name: 'app_recipe_rating_show', methods: ['GET'])]
    public function show(RecipeRating $recipeRating): Response
    {
        return $this->render('recipe_rating/show.html.twig', [
            'recipe_rating' => $recipeRating,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_rating_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RecipeRating $recipeRating, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecipeRatingType::class, $recipeRating);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_rating_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe_rating/edit.html.twig', [
            'recipe_rating' => $recipeRating,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_rating_delete', methods: ['POST'])]
    public function delete(Request $request, RecipeRating $recipeRating, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $recipeRating->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recipeRating);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_rating_index', [], Response::HTTP_SEE_OTHER);
    }
}
