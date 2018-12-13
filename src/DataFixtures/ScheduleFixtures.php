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
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ScheduleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($a = 0; $a < 7; $a++) {
            for ($i = 0; $i <= 3; $i++) {
                $schedule = new Schedule();
                $openingAM = null;
                $schedule->setOpeningAM($openingAM);
                $closingAM = null;
                $schedule->setClosingAM($closingAM);
                $openingPM = null;
                $schedule->setOpeningPM($openingPM);
                $closingAM = null;
                $schedule->setClosingPM($closingAM);
                $schedule->setDay(
                    $this->getReference('dayOfWeek_' . $a)
                );
                $schedule->setCompany(
                    $this->getReference('company_' . $i)
                );
//                $schedule->setAssociation(
//                    $this->getReference('association_' . $i)
//                );
                $manager->persist($schedule);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            DayOfWeekFixtures::class,
//            AssociationFixtures::class,
            CompanyFixtures::class,
        );
    }
}
