<?php
/**
 * Created by PhpStorm.
 * User: lucile
 * Date: 11/12/18
 * Time: 16:10
 */

namespace App\DataFixtures;

use App\Entity\Schedule;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ScheduleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 15; $i++) {
            $schedule = new Schedule();
            $schedule->setOpeningAM(rand(0, 12));
            $schedule->setClosingAM(rand(0, 12));
            $schedule->setOpeningPM(rand(0, 12));
            $schedule->setClosingPM(rand(0, 12));
            $schedule->setDay(rand(0, 30));
            $schedule->setCompany(rand(0, 10));
            $schedule->setAssociation(rand(0, 10));
            $manager->persist($schedule);
        }
        $manager->flush();
    }
}
