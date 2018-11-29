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
            $offer->setWeight(rand(0, 10));
            $startDate=$endDate=\DateTime::createFromFormat(
                'j-m-Y H:i:s',
                rand(1, 28).'-'.rand(1, 12).'-2018 '.rand(17, 20).':'.rand(0, 59).':00'
            );
            $duration=[15,30,45];
            $endDate->modify('+'.$duration[array_rand($duration)].' minutes');
            $offer->setStart($startDate);
            $offer->setEnd($endDate);
            $offer->setDescription($faker->text);
            $offer->setComplementary($faker->text);
            $offer->setPicture('https://lardoisier.com/media/coffret-sandwich.jpg');
            
            $manager->persist($offer);
        }
        
        $manager->flush();
    }
}
