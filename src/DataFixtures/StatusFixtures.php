<?php
/**
 * Created by PhpStorm.
 * User: lucile
 * Date: 11/12/18
 * Time: 15:14
 */

namespace App\DataFixtures;

use App\Entity\DaysOfWeek;
use App\Entity\Status;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StatusFixtures extends Fixture
{
    const CONST_KEYS_AND_CLASS = [
        'AssociationResearch',
        'FoodHeroResearch',
        'WaitingForRecuperation',
        'WaitingForDelivery',
        'Delivered',
    ];

    const STATUS_TEXT = [
        'En attente d\'association',
        'En attente de FoodHero',
        'En route vers l\'entreprise',
        'En route vers l\'association',
        'Livré',
    ];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $status = new Status();
            $this->addReference('status_'.$i, $status);
            $status->setConstKey(self::CONST_KEYS_AND_CLASS[$i]);
            $status->setClassColorName(self::CONST_KEYS_AND_CLASS[$i]);
            $status->setStatusText(self::STATUS_TEXT[$i]);
            $manager->persist($status);
        }

        $manager->flush();
    }
}
