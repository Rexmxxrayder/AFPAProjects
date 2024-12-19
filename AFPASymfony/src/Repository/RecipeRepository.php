<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



    public function findAllLikeString(string $string, int $limit, int $offset = 0): array
    {
        $qb = $this->createQueryBuilder("recipe")
            ->where("recipe.title LIKE :string")
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setParameter("string", '%' . $string . '%');
        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllWithTotalDurationAround(int $totalDuration, int $limit, int $offset = 0): array
    {
        $high = $totalDuration + 5;
        $low = $totalDuration - 5 < 0 ? 0 : $totalDuration - 5;
        $qb = $this->createQueryBuilder("recipe")
            ->where("recipe.preparationTime + recipe.cookingTime < :high AND recipe.preparationTime + recipe.cookingTime >= :low ")
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setParameter("high", $high)
            ->setParameter("low", $low);
        $query = $qb->getQuery();
        dump($qb->getQuery()->GetSQL());
        return $query->execute();
    }

    // public function findAllLikeString(string $string): array
    // {
    //     $conn = $this->getEntityManager()->getConnection();
    //     $sql = "SELECT * FROM `recipe` WHERE recipe.title LIKE '%" . $string . "%'";
    //     $resultSet = $conn->executeQuery($sql);
    //     $result = $resultSet->fetchAllAssociative();
    //     return $result;
    // }

    public function GetTableSize(): int
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT COUNT(*) AS 'Size' FROM recipe";
        $resultSet = $conn->executeQuery($sql);
        $result = $resultSet->fetchAssociative();
        return $result['Size'];
    }
}
