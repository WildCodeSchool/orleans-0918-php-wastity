<?php

namespace App\Repository;

use App\Entity\Association;
use App\Entity\FoodHero;
use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Offer::class);
    }
    
    public function findAllBeforeEndDate(\DateTime $date): array
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.end > :date')
            ->andWhere('o.active = true')
            ->setParameter('date', $date)
            ->orderBy('o.end', 'ASC')
            ->getQuery();
        
        return $qb->execute();
    }
    
    public function findAllBeforeEndDateAssociation(\DateTime $date): array
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.end > :date')
            ->andWhere('o.association is null')
            ->andWhere('o.active = true')
            ->setParameter('date', $date)
            ->orderBy('o.end', 'ASC')
            ->getQuery();
        
        return $qb->execute();
    }
    
    public function findAllBeforeEndDateFoodhero(\DateTime $date): array
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.end > :date')
            ->andWhere('o.association is not null')
            ->andWhere('o.foodhero is null')
            ->andWhere('o.active = true')
            ->setParameter('date', $date)
            ->orderBy('o.end', 'ASC')
            ->getQuery();
        
        return $qb->execute();
    }

    public function findAcceptedByAssociationBeforeEndDate(\DateTime $date, Association $association): array
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.end > :date')
            ->andWhere('o.association = :association')
            ->andWhere('o.active = true')
            ->setParameters(['date' => $date, 'association' => $association->getId()])
            ->orderBy('o.end', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    public function findAcceptedByFoodHeroBeforeEndDate(\DateTime $date, FoodHero $foodHero): array
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.end > :date')
            ->join('o.status', 's')
            ->andWhere('o.foodhero = :foodhero')
            ->andWhere("s.constStatus = 'Delivered'")

            ->setParameters(['date' => $date, 'foodhero' => $foodHero->getId()])
            ->orderBy('o.end', 'ASC')
            ->getQuery();

        return $qb->execute();
    }

    public function findAcceptedByFoodHero(\DateTime $date, FoodHero $foodHero): array
    {
        $qb = $this->createQueryBuilder('o')
            ->where('o.end > :date')
            ->join('o.status', 's')
            ->andWhere('o.foodhero = :foodhero')
            ->andWhere("s.constStatus = 'WaitingForRecuperation'")
            ->orWhere("s.constStatus = 'WaitingForDelivery'")

            ->setParameters(['date' => $date, 'foodhero' => $foodHero ])
            ->orderBy('o.end', 'ASC')
            ->getQuery();
        
        return $qb->execute();
    }
}
