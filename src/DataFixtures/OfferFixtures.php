<?php
/**
 * Created by PhpStorm.
 * User: wilder20
 * Date: 28/11/18
 * Time: 16:38
 */

namespace App\DataFixtures;

use App\Entity\Offer;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class OfferFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i <=15; $i++) {
            $offer=new Offer();
            $faker  =  Faker\Factory::create('fr_FR');
            $offer->setPicture($faker->imageUrl($width = 320, $height = 240));
            $offer->setWeight(rand(0, 10));
            $startDate=new \DateTime();
            $endDate=new \DateTime('now + 30min');
            $offer->setStart($startDate);
            $offer->setEnd($endDate);
            $offer->setDescription($faker->text);
            $offer->setComplementary($faker->text);
            $offer->setAssociation(rand(0, 10));
            $offer->setCompany(rand(0, 10));
            $offer->setFoodHero(rand(0, 10));
            $offer->setUpdatedAt($endDate);
            $manager->persist($offer);
        }
        
        $manager->flush();
    }
}
