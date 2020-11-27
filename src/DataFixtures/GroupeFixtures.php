<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GroupeFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $rows = 10;
        $status = [false,true];
        $types = ["secondaire","principal"];
        for ($i = 0; $i < $rows; $i++)
        {
            $groupe = new Groupe();

            $random = random_int(0,1);
            $groupe->setName($faker->paragraph(1))
                ->setIsDeleted($status[$random])
                ->setStattus($status[$random])
                ->setType($types[$random]);
            $manager->persist($groupe);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return array(
            "groupe"
        );
    }
}
