<?php

namespace App\Controller;

use App\Form\RecipeType;
use App\Entity\Recipe;
use Gedmo\Sluggable\Util\Urlizer;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Filesystem\Filesystem;

class RecipeController extends AbstractController
{
    const RECIPES_BY_PAGE = 12;

    #[Route(['/', '/recipe/p:{p}'], name: 'home',  defaults: ['p' => 0])]
    public function home(int $p, RecipeRepository $recipeRepository): Response
    {
        $query = $recipeRepository->createQueryBuilder("recipe")
            ->setMaxResults(self::RECIPES_BY_PAGE)
            ->setFirstResult(self::RECIPES_BY_PAGE * $p)
            ->getQuery();
        return $this->ShowRecipes($query->execute(), $p, $recipeRepository);
    }

    #[Route('/recipe/td:{totalDuration}p:{p}', name: 'homeTotalDuration',  defaults: ['p' => 0])]
    public function homeDuration(int $totalDuration, int $p, RecipeRepository $recipeRepository): Response
    {
        return $this->ShowRecipes($recipeRepository->findAllWithTotalDurationAround($totalDuration, self::RECIPES_BY_PAGE, self::RECIPES_BY_PAGE * $p,), $p, $recipeRepository);
    }

    #[Route('/recipe/name:{name}p:{p}', name: 'homeName',  defaults: ['p' => 0])]
    public function homeName(string $name, int $p, RecipeRepository $recipeRepository): Response
    {
        return $this->ShowRecipes($recipeRepository->findAllLikeString($name, self::RECIPES_BY_PAGE, self::RECIPES_BY_PAGE * $p,), $p, $recipeRepository);
    }

    public function ShowRecipes(array $recipesList, int $currentPage = 0, RecipeRepository $recipeRepository): Response
    {
        $paginationSize = (int)ceil($recipeRepository->GetTableSize() / self::RECIPES_BY_PAGE - 1);
        if ($paginationSize == 0) {
            $paginationSize = 1;
        }
        
        return $this->render('home.html.twig', [
            'currentPage' => $currentPage,
            'paginationSize' => $paginationSize,
            'recipesList' => $recipesList,
        ]);
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

        return $this->redirectToRoute('home');
    }

    #[Route('/recipe/{id}', name: 'show_recipe', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/showRecipe.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}
