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
            for ($i = 0; $i <= 15; $i++) {
                $schedule = new Schedule();
                $openingAM = new \DateTime('08:00');
                $schedule->setOpeningAM($openingAM);
                $closingAM = new \DateTime('12:00');
                $schedule->setClosingAM($closingAM);
                $openingPM = new \DateTime('14:00');
                $schedule->setOpeningPM($openingPM);
                $closingAM = new \DateTime('18:00');
                $schedule->setClosingPM($closingAM);
                $schedule->setDay(
                    $this->getReference('dayOfWeek_' . $a)
                );
                $schedule->setCompany(
                    $this->getReference('company_' . $i)
                );
                $schedule->setAssociation(
                    $this->getReference('association_' . $i)
                );
                $manager->persist($schedule);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            DayOfWeekFixtures::class,
            AssociationFixtures::class,
            CompanyFixtures::class,
        );
    }
}
