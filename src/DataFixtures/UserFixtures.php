<?php
/**
 * Created by PhpStorm.
 * User: lucile
 * Date: 11/12/18
 * Time: 16:10
 */

namespace App\DataFixtures;

use App\Entity\User;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 15; $i++) {
            $user = new User();
            $faker = Faker\Factory::create('fr_FR');
            $user->setEmail($faker->email);
            $user->setRoles($faker->text);
            $user->setPassword($faker->password);
            $user->setFirstname($faker->text);
            $user->setLastname($faker->text);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
