<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use App\Repository\ProfilRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements FixtureGroupInterface
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
        //$profil = $this->getReference(ProfilFixtures::PROFIL_REFERENCE);
        $times = 7;
        for ($i = 0; $i < $times; $i++)
        {
            $apprenant = new Apprenant();
            $profil = $this->profilRepository->findOneBy(["libelle" => "APPRENANT"]);
            $apprenant->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setUsername($faker->userName)
                ->setEmail($faker->email)
                ->setProfil($profil)
                ->setPassword($this->encoder->encodePassword($apprenant,strtolower($profil->getLibelle())));
            $manager->persist($apprenant);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        /*return array(
            ProfilFixtures::class
        );*/
    }

    public static function getGroups(): array
    {
        return array(
            "apprenant"
        );
    }
}
