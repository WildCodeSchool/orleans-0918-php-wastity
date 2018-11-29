<?php

namespace App\Repository;

use App\Entity\DaysOfWeek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DaysOfWeek|null find($id, $lockMode = null, $lockVersion = null)
 * @method DaysOfWeek|null findOneBy(array $criteria, array $orderBy = null)
 * @method DaysOfWeek[]    findAll()
 * @method DaysOfWeek[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DaysOfWeekRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DaysOfWeek::class);
    }

    // /**
    //  * @return DaysOfWeek[] Returns an array of DaysOfWeek objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DaysOfWeek
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
