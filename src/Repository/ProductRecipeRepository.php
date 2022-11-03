<?php

namespace App\Repository;

use App\Entity\ProductRecipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductRecipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductRecipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductRecipe[]    findAll()
 * @method ProductRecipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductRecipe::class);
    }
}