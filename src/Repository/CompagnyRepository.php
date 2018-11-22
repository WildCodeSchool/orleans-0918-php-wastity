<?php
/**
 * Created by PhpStorm.
 * User: lucile
 * Date: 21/11/18
 * Time: 14:07
 */

namespace App\Repository;

use App\Entity\Compagny;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Compagny|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compagny|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compagny[]    findAll()
 * @method Compagny[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompagnyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Compagny::class);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
