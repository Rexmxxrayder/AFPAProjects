<?php

namespace App\Repository;

use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function AddWhereTitleLikeString(QueryBuilder $qb, string $string): QueryBuilder
    {
        $qb->andWhere("recipe.title LIKE :string")
            ->setParameter("string", '%' . $string . '%');
        return $qb;
    }

    public function AddWhereContainsIngredient(QueryBuilder $qb, string $string): QueryBuilder
    {
        $qb->andWhere("recipe.ingredients LIKE :string")
            ->setParameter("string", '%' . $string . '%');
        return $qb;
    }

    public function AddWhereIsCategory(QueryBuilder $qb, string $string): QueryBuilder
    {
        $qb->andWhere("recipe.category LIKE :string")
            ->setParameter("string", '%' . $string . '%');
        return $qb;
    }

    public function AddWhereAroundTotalDuration(QueryBuilder $qb, int $totalDuration): QueryBuilder
    {
        $high = $totalDuration + 5;
        $low = $totalDuration - 5 < 0 ? 0 : $totalDuration - 5;
        $qb->andWhere("recipe.preparationTime + recipe.cookingTime < :high AND recipe.preparationTime + recipe.cookingTime >= :low ")
            ->setParameter("high", $high)
            ->setParameter("low", $low);
        return $qb;
    }

    public function OrderByMostPopular(QueryBuilder $qb): QueryBuilder
    {
        $qb->addOrderBy('(AVG(rr.rate)) + (COUNT(rc))', 'DESC');
        return $qb;
    }

    public function AddWhereAuthorIsUser(QueryBuilder $qb, int $userId): QueryBuilder
    {
        $qb->andWhere("recipe.author = :userId")
            ->setParameter("userId", $userId);
        return $qb;
    }

    public function AddWhereRecipeNotPrivate(QueryBuilder $qb): QueryBuilder
    {
        $qb->andWhere('recipe.isPrivate != true');
        return $qb;
    }

    public function AddOnlyFavorite(User $user,QueryBuilder $qb): QueryBuilder
    {
        $arr = $user->getFavorites();
        if(empty($arr)){
            $arr[] = -1;
        }
        $qb->andWhere($qb->expr()->in('recipe.id', $arr));
        return $qb;
    }

    public function GetSize(QueryBuilder $qb) : int {
          //dump($qb->getQuery()->getResult());
        return count($qb->getQuery()->getResult());
    }
}
