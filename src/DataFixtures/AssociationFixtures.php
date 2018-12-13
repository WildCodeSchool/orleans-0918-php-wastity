<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker;
use App\Entity\Association;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AssociationFixtures extends Fixture implements DependentFixtureInterface
{
    private $streetAddress = [
        'Rue Roussy',
        'Boulevard Amiral Courbet',
        'Avenue de Paris',
        'Allée de la concorde',
        'Boulevard de Chateaudun',
        'Rue de la Bourie Blanche',
        'Rue de la gare',
        'Rue Emile Zola',
        'Rue Marcel Proust',
        'Boulevard Alexandre martin',
    ];
    
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i <=15; $i++) {
            $association=new Association();
            $faker  =  Faker\Factory::create('fr_FR');
            $fakerUS = Faker\Factory::create('en_US');
            $association->setName($fakerUS->company);
            $association->setAddress(rand(1, 9).' '.$this->streetAddress[array_rand($this->streetAddress)]);
            $association->setPostalCode('45000');
            $association->setCity('Orléans');
            $association->setMail($faker->email);
            $association->setPhone($faker->phoneNumber);
            $this->addReference('association_'.$i, $association);
            $association->setUser(
                $this->getReference('user_'.$i)
            );
            $manager->persist($association);
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
