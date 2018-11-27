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

    // /**
    //  * @return FoodHero[] Returns an array of FoodHero objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FoodHero
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
