<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use App\Repository\NiveauRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompetencesFixtures extends Fixture implements FixtureGroupInterface
{
    private $levelRepository;

    public function __construct(NiveauRepository $levelRepository)
    {
        $this->levelRepository = $levelRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $times = 10;
        $levels = $this->levelRepository->findAll();
        for ($i = 0; $i < $times; $i++)
        {
            $competence = new Competence();
            $competence->setLibelle($faker->paragraph);
            $random = random_int(0, count($levels)-1);
            $competence->addNiveau($levels[$random]);
            $manager->persist($competence);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return array(
            "createGrpecompetence",
            "competence"
        );
    }
}
