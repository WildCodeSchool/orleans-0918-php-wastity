<?php
/**
 * Created by PhpStorm.
 * User: lucile
 * Date: 11/12/18
 * Time: 15:14
 */

namespace App\DataFixtures;

use App\Entity\DaysOfWeek;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DayOfWeekFixtures extends Fixture
{
    private $dayOfWeekArray = [
        'Lundi',
        'Mardi',
        'Mercredi',
        'Jeudi',
        'Vendredi',
        'Samedi',
        'Dimanche'
    ];

    public function load(ObjectManager $manager)
    {
        for ($i=0; $i <= 6; $i++) {
            $dayOfWeek=new DaysOfWeek();
            $dayOfWeek->setName($this->dayOfWeekArray[$i]);
            $this->addReference('dayOfWeek_'.$i, $dayOfWeek);
            $manager->persist($dayOfWeek);
        }
        $manager->flush();
    }
}
