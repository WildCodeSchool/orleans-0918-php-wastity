<?php
/**
 * Created by PhpStorm.
 * User: wilder20
 * Date: 28/11/18
 * Time: 16:38
 */

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\Offer;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OfferFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i <=15; $i++) {
            $offer=new Offer();
            $faker  =  Faker\Factory::create('fr_FR');
            $offer->setPicture($faker->imageUrl($width = 320, $height = 240));
            $offer->setWeight(rand(0, 10));
            $startDate=new \DateTime();
            $endDate=new \DateTime('now + 15days');
            $offer->setStart($startDate);
            $offer->setEnd($endDate);
            $offer->setDescription($faker->text);
            $offer->setComplementary($faker->text);
            $offer->setCompany(
                $this->getReference('company_'.$i)
            );
            $updatedAt = new \DateTime('now');
            $offer->setUpdatedAt($updatedAt);
            $manager->persist($offer);
        }
        
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            CompanyFixtures::class,
        );
    }
}
