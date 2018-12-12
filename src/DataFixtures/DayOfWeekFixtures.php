<?php
/**
 * Created by PhpStorm.
 * User: lucile
 * Date: 11/12/18
 * Time: 15:14
 */

namespace App\DataFixtures;

use App\Entity\DaysOfWeek;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DayOfWeekFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1; $i <=15; $i++) {
            $dayOfWeek=new DaysOfWeek();
            $faker  =  Faker\Factory::create('fr_FR');
            $dayOfWeek->setName($faker->company);
            $manager->persist($dayOfWeek);
        }

        $manager->flush();
    }
}
