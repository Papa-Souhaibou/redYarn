<?php

namespace App\DataFixtures;

use App\Entity\Niveau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NiveauFixtures extends Fixture implements  FixtureGroupInterface
{

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $rows = 10;
        for ($i = 0; $i < $rows; $i++)
        {
            $niveau = new Niveau();
            $niveau->setLibelle($faker->paragraph(2))
                ->setCritereEvaluation($faker->paragraph(2))
                ->setGroupeAction($faker->paragraph(2));
            $manager->persist($niveau);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return array(
            "createGrpecompetence",
            "niveau"
        );
    }
}
