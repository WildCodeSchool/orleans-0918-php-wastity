<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    private $streetAddress = [
        'Rue Jules Lemaitre',
        'Boulevard Dupanloup',
        'Avenue de Paris',
        'Rue du bourdon blanc',
        'Quai du chatelet',
        'Rue du poirier',
        'Rue de la gare',
        'Rue Emile Zola',
        'Rue Saint Flou',
        'Boulevard Alexandre martin',
    ];
    
    private $companyType = [
      'boulangerie',
      'épicerie',
      'supermarché',
      'restaurant',
      'primeur',
      'magasin'
    ];
    
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i <=15; $i++) {
            $company=new Company();
            $faker  =  Faker\Factory::create('fr_FR');
            $fakerUS = Faker\Factory::create('en_US');
            $company->setType($this->companyType[array_rand($this->companyType)]);
            $company->setName($fakerUS->company);
            $company->setAddress(rand(1, 9).' '.$this->streetAddress[array_rand($this->streetAddress)]);
            $company->setPostalCode('45000');
            $company->setCity('Orléans');
            $company->setEmail($faker->email);
            $company->setPhone($faker->phoneNumber);
            $this->addReference('company_'.$i, $company);
            $company->setUser(
                $this->getReference('user_'.$i)
            );




            $manager->persist($company);
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
