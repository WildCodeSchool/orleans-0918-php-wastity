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
    const CONST_STATUS = [
        'AssociationResearch',
        'FoodHeroResearch',
        'WaitingForRecuperation',
        'WaitingForDelivery',
        'Delivered',
    ];

    const COLORS = [
        '#E77471',
        '#000000',
        '#E95420',
        '#FFFF00',
        '#57a639',
    ];

    const STATUS_TEXT = [
        'En attente d\'association',
        'En attente de FoodHero',
        'En route vers l\'entreprise',
        'En route vers l\'association',
        'Livré',
    ];

    const CLASS_FONTAWESOME = [
        'fa-hands-helping',
        'fa-user-ninja',
        'fa-truck',
        'fa-box',
        'fa-box-check',
    ];

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $status = new Status();
            $this->addReference('status_'.$i, $status);
            $status->setConstStatus(self::CONST_STATUS[$i]);
            $status->setColor(self::COLORS[$i]);
            $status->setStatusText(self::STATUS_TEXT[$i]);
            $status->setClassFontAwesome(self::CLASS_FONTAWESOME[$i]);
            $manager->persist($status);
        }

        $manager->flush();
    }
}
