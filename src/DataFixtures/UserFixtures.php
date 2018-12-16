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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder=$passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 15; $i++) {
            $user = new User();
            $faker = Faker\Factory::create('fr_FR');
            $user->setEmail("user$i@gmail.com");
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'azerty'));
            $user->setFirstname($faker->text);
            $user->setLastname($faker->text);
            $this->addReference('user_'.$i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
