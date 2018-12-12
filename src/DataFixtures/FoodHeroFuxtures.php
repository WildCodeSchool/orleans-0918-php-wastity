<?php
/**
 * Created by PhpStorm.
 * User: lucile
 * Date: 11/12/18
 * Time: 15:15
 */

namespace App\DataFixtures;

use App\Entity\FoodHero;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FoodHeroFuxtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i <=15; $i++) {
            $foodHero = new FoodHero();
            $faker  =  Faker\Factory::create('fr_FR');
            $endDate = new \DateTime('now + 30min');
            $foodHero->setUpdatedAt($endDate);
            $foodHero->setPhone($faker->phoneNumber);
            $foodHero->setProfileImage($faker->imageUrl($width = 320, $height = 240));
            $foodHero->setUser(rand(0, 10));
            $manager->persist($foodHero);
        }
        $manager->flush();
    }
}
