<?php

namespace App\DataFixtures;

use App\Entity\Formateur;
use App\Repository\ProfilRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormateurFixtures extends Fixture implements FixtureGroupInterface,DependentFixtureInterface
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $profil = $this->getReference("FORMATEUR");
        $times = 10;
        for ($i = 0; $i < $times; $i++)
        {
            $apprenant = new Formateur();
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
            "formateur"
        );
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
