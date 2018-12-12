<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Association;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AssociationFixtures extends Fixture
{
    private $streetAdress = [
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
        for ($i=1; $i <=15; $i++) {
            $association=new Association();
            $faker  =  Faker\Factory::create('fr_FR');
            $fakerUS = Faker\Factory::create('en_US');
            $association->setName($fakerUS->company);
            $association->setAdress(rand(1, 9).' '.$this->streetAdress[array_rand($this->streetAdress)]);
            $association->setPostalCode('45000');
            $association->setTown('Orléans');
            $association->setMail($faker->email);
            $association->setPhone($faker->phoneNumber);
            $association->setUser(rand(0, 10));
            $manager->persist($association);
        }

        $manager->flush();
    }
}
