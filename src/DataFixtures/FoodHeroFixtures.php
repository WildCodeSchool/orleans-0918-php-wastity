<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\FoodHero;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FoodHeroFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i <=5; $i++) {
            $foodhero=new FoodHero();
            $faker  =  Faker\Factory::create('fr_FR');
            $foodhero->setPhone($faker->phoneNumber);
            $foodhero->setUser($this->getReference($id));

            $manager->persist($foodhero);
        }

        $manager->flush();
    }
}
