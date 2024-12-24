<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
{
    const RECIPES_BY_PAGE = 12;

    #[Route('/admin', name: 'app_admin')]
    public function adminDashboard(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/admin.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users,
        ]);
    }

    #[Route(['/admin/userRecipe/{parameters}'], name: 'app_admin_userRecipe',  defaults: ['parameters' => ''])]
    public function AdminUserRecipeDashboard(string $parameters, UserRepository $userRepository, RecipeRepository $recipeRepository): Response
    {
        preg_match_all('/([a-zA-Z0-9\-_]+)(?::([a-zA-Z0-9\-_]+))?(?=;|$)/', $parameters, $matches);
        $parametersArr = array_combine($matches[1], $matches[2]);

        $query = $recipeRepository->createQueryBuilder("recipe");
        $query->where('true = true');

        $pageIndex = array_key_exists("p", $parametersArr) ? intval($parametersArr["p"]) : 0;

        $user = $userRepository->find($parametersArr['user']);
        $query = $recipeRepository->AddWhereAuthorIsUser($query, $parametersArr['user']);

        if (array_key_exists("td", $parametersArr)) {
            $query = $recipeRepository->AddWhereAroundTotalDuration($query, intval($parametersArr['td']));
        }

        if (array_key_exists("n", $parametersArr)) {
            $query = $recipeRepository->AddWhereTitleLikeString($query, $parametersArr['n']);
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

        return $this->render('admin/adminUserRecipe.html.twig', [
            'currentPage' => $pageIndex,
            'paginationSize' => $paginationSize,
            'recipesList' => $exec,
            'user' => $user,
        ]);
    }

    #[Route('/admin/removeUser:{userId}', name: 'app_admin_removeUser')]
    public function AdminRemoveUser(int $userId,  EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $entityManager->remove($userRepository->find($userId));
        $entityManager->flush();

        return $this->redirect('/admin');
    }
}
