<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Repository\ProfilRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture implements FixtureGroupInterface
{
    private $encoder;
    private $profilRepository;
    public function __construct(UserPasswordEncoderInterface $encoder,ProfilRepository $profilRepository)
    {
        $this->encoder = $encoder;
        $this->profilRepository = $profilRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $times = 10;
        $profil = $this->profilRepository->findOneBy(["libelle" => "ADMIN"]);
        for ($i = 0; $i < $times; $i++)
        $apprenant = new Admin();
        #$profil = $this->getReference(ProfilFixtures::PROFIL_REFERENCE);
        $apprenant->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setUsername($faker->userName)
            ->setEmail($faker->email)
            ->setProfil($profil)
            ->setPassword($this->encoder->encodePassword($apprenant,strtolower($profil->getLibelle())));
        $manager->persist($apprenant);
        $manager->flush();
    }

    /*public function getDependencies()
    {
        return array(
            ProfilFixtures::class
        );
    }*/

    public static function getGroups(): array
    {
        return array(
            "admin"
        );
    }
}
