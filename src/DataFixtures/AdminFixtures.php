<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Repository\ProfilRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
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
        $profil = $this->getReference("ADMIN");
        for ($i = 0; $i < $times; $i++)
        {
            $apprenant = new Admin();
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


    public static function getGroups(): array
    {
        return array(
            "admin"
        );
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
