<?php

namespace App\DataFixtures;

use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use App\Repository\ReferentielRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GroupeCompetenceFixtures extends Fixture implements  FixtureGroupInterface
{
    private $skillRepository;
    private $referentielRepository;
    public function __construct(CompetenceRepository $skillRepository,ReferentielRepository $referentielRepository)
    {
        $this->skillRepository = $skillRepository;
        $this->referentielRepository = $referentielRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $rows = 10;
        $skills = $this->skillRepository->findAll();
        $referentiels = $this->referentielRepository->findAll();
        for ($i = 0; $i < $rows; $i++)
        {
            $grpeCompetence = new GroupeCompetence();
            $random = random_int(0,count($skills)-1);
            $grpeCompetence->setLibelle($faker->paragraph(2))
                        ->setDescriptif($faker->paragraph(2))
                        ->addCompetence($skills[$random]);
            $random = random_int(0,count($referentiels)-1);
            $grpeCompetence->addReferentiel($referentiels[$random]);
            $manager->persist($grpeCompetence);
        }
        $manager->flush();
    }


    public static function getGroups(): array
    {
        return array(
            "createGrpecompetence",
            "grpecompetence"
        );
    }
}
