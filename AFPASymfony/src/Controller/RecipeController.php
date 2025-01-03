<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use App\Form\RecipeType;
use App\Entity\Recipe;
use App\Entity\User;
use Gedmo\Sluggable\Util\Urlizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Filesystem\Filesystem;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class RecipeController extends AbstractController
{
    const RECIPES_BY_PAGE = 12;

    #[Route(['/recipe', '/home'], name: 'recipe')]
    public function recipeRedirect()
    {
        return $this->redirect('/recipe/search/p:0');
    }

    #[Route(['/recipe/search/{parameters}'], name: 'recipeSpec',  defaults: ['parameters' => ''])]
    public function recipe(string $parameters, RecipeRepository $recipeRepository): Response
    {
        preg_match_all('/([a-zA-Z0-9\-_%]+)(?::([a-zA-Z0-9\-_%]+(?:%[0-9A-Fa-f]{2})*))?/', $parameters, $matches);
        $parametersArr = array_combine($matches[1], $matches[2]);

        $query = $recipeRepository->createQueryBuilder("recipe");
        $query->where('true = true');

        $pageIndex = array_key_exists("p", $parametersArr) ? intval($parametersArr["p"]) : 0;
        if (array_key_exists("user", $parametersArr)) {
            $twig = 'userRecipe.html.twig';
            $query = $recipeRepository->AddWhereAuthorIsUser($query, $parametersArr['user']);
        } else {
            $twig = 'recipe.html.twig';
            $query = $recipeRepository->AddWhereRecipeNotPrivate($query);
        }


        if (array_key_exists("td", $parametersArr)) {
            $query = $recipeRepository->AddWhereAroundTotalDuration($query, intval($parametersArr['td']));
        }


        if (array_key_exists("n", $parametersArr)) {
            $query = $recipeRepository->AddWhereTitleLikeString($query, urldecode($parametersArr['n']));
        }

        if (array_key_exists("i", $parametersArr)) {
            $query = $recipeRepository->AddWhereContainsIngredient($query, urldecode($parametersArr['i']));
        }

        if (array_key_exists("c", $parametersArr)) {
            $query = $recipeRepository->AddWhereIsCategory($query, urldecode($parametersArr['c']));
        }

        if (array_key_exists("f", $parametersArr)) {
            $query = $recipeRepository->AddOnlyFavorite($this->getUser(), $query);
            $twig = 'userFavoriteRecipe.html.twig';
        }

        $querySize = clone $query;
        $paginationSize = (int)ceil($recipeRepository->GetSize($querySize) / self::RECIPES_BY_PAGE - 1);
        if ($paginationSize == 0) {
            $paginationSize = 1;
        }

        $query->setMaxResults(self::RECIPES_BY_PAGE)
            ->setFirstResult(self::RECIPES_BY_PAGE * $pageIndex);

        $exec = $query->getQuery()->execute();
        //dump($query->getQuery()->execute());

        //print($query->getQuery()->getSql());
        return $this->render($twig, [
            'currentPage' => $pageIndex,
            'paginationSize' => $paginationSize,
            'recipesList' => $exec,
        ]);
    }

    #[Route('/recipe/show/{id}', name: 'show_recipe', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/showRecipe.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recipe/{action}Favorites/user:{userId};rid:{recipeId}', name: 'add_favorite_recipe', methods: ['GET'])]
    public function addFavorites(string $action, int $userId, int $recipeId, RecipeRepository $recipeRepository, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($userId);

        $favorites = $user->getFavorites();
        if ($action === "remove") {
            if (in_array($recipeId, $favorites)) {
                $key = array_search($recipeId, $favorites);
                unset($favorites[$key]);
            }
        } else {
            $favorites[] = $recipeId;
        }

        $user->setFavorites($favorites);

        $recipe = $recipeRepository->find($recipeId);
        $entityManager->persist($recipe);
        $entityManager->flush();

        return $this->redirect('/recipe/show/' . $recipeId);
    }

    #[Route('/recipe/new', name: 'new_recipe', methods: ['GET', 'POST'])]
    public function NewRecipe(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        $errors = "";
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/Upload/Image';
                $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFileName) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );

                $recipe->setImageName($newFilename);
                $entityManager->persist($recipe);
                $entityManager->flush();

                return $this->redirectToRoute('home');
            } else {
                $this->addFlash('error', "The rexcipe needs an image");
                return $this->render('recipe/formCreateRecipe.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        foreach ($form->getErrors(true) as $error) {
            $errors = $error->getMessage();
            break;
        }

        $this->addFlash('error', $errors);
        return $this->render('recipe/formCreateRecipe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recipe/modify/{id}', name: 'modify_recipe', methods: ['GET', 'POST'])]
    public function ModifyRecipe(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
            if ($uploadedFile) {
                $destination = $this->getParameter('kernel.project_dir') . '/public/Upload/Image';

                $originalFileName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFileName) . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );

                $path = $destination . '/' . $recipe->getImageName();
                $fileSystem = new Filesystem();
                $fileSystem->remove($path);
                $recipe->setImageName($newFilename);
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        $errors = "";
        foreach ($form->getErrors(true) as $error) {
            $errors = $error->getMessage();
            break;
        }

        $this->addFlash('error', $errors);
        return $this->render('recipe/formUpdateRecipe.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recipe/remove/{id}', name: 'remove_recipe', methods: ['GET', 'POST'])]
    public function RemoveRecipe(int $id, EntityManagerInterface $entityManager): Response
    {
        $recipe = $entityManager->getRepository(Recipe::class)->find($id);
        if ($recipe) {
            $destination = $this->getParameter('kernel.project_dir') . '/public/Upload/Image';
            $path = $destination . '/' . $recipe->getImageName();
            $fileSystem = new Filesystem();
            $fileSystem->remove($path);

            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirect('/recipe/search/user:' . $this->getUser()->getId());
    }
}
