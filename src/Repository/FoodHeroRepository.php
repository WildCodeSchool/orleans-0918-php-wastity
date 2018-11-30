<?php

namespace App\Repository;

use App\Entity\FoodHero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FoodHero|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodHero|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodHero[]    findAll()
 * @method FoodHero[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodHeroRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FoodHero::class);
    }
}
