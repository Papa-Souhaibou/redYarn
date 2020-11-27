<?php

namespace App\DataFixtures;

use App\Entity\Promo;
use App\Repository\AdminRepository;
use App\Repository\GroupeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PromoFixtures extends Fixture implements FixtureGroupInterface
{
    private $adminRepository;
    private $grpeRepository;
    public function __construct(AdminRepository $adminRepository,GroupeRepository $groupeRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->grpeRepository = $groupeRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $rows = 10;
        $creators = $this->adminRepository->findAll();
        $groupes = $this->grpeRepository->findBy(["type" => "principal"]);
        for ($i = 0; $i < $rows; $i++)
        {
            $promo = new Promo();
            $fetchCreator = array_rand($creators,1);
            $groupe = array_rand($groupes,1);
            $promo->setIsDeleted(true)
                ->setDescription($faker->paragraph(1))
                ->setCreator($creators[$fetchCreator])
                ->setTitle($faker->paragraph(1))
                ->setReferenceAgate($faker->paragraph(1))
                ->setLocation($faker->address)
                ->setPrevisionalEndDate($faker->dateTime("+1y"))
                ->setStartedAt($faker->dateTime("-1y"))
                ->setLanguage($faker->lastName)
                ->addGroupe($groupes[$groupe]);
            $manager->persist($promo);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return array(
            "promo"
        );
    }
}
