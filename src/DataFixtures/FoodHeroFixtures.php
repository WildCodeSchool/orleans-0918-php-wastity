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
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FoodHeroFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i <=15; $i++) {
            $foodHero = new FoodHero();
            $faker  =  Faker\Factory::create('fr_FR');
            $endDate = new \DateTime('now + 30min');
            $foodHero->setUpdatedAt($endDate);
            $foodHero->setPhone($faker->phoneNumber);
            $foodHero->setProfileImage('');
            $foodHero->setUser(
                $this->getReference('user_'.$i)
            );
            $manager->persist($foodHero);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
