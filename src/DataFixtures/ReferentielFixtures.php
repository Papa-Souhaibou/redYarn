<?php

namespace App\DataFixtures;

use App\Entity\Referentiel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReferentielFixtures extends Fixture implements  FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $rows = 10;
        for ($i = 0; $i < $rows; $i++)
        {
            $referentiel = new  Referentiel();
            $referentiel->setLibelle($faker->paragraph(2))
                ->setCritereEvaluation($faker->paragraph(2))
                ->setCritereAdmission($faker->paragraph(2))
                ->setProgramme($faker->paragraph(2))
                ->setPresentation($faker->paragraph(2));
            $manager->persist($referentiel);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return array(
            "createGrpecompetence",
            "referentiel"
        );
    }
}
