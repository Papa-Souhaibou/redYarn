<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $profil = $this->getReference("APPRENANT");
        $times = 10;
        for ($i = 0; $i < $times; $i++)
        {
            $apprenant = new Apprenant();
            $apprenant->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setUsername($faker->userName)
                ->setEmail($faker->email)
                ->setProfil($profil)
                ->setIsDeleted(false)
                ->setPassword($this->encoder->encodePassword($apprenant,strtolower($profil->getLibelle())));
            $manager->persist($apprenant);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return array(
            "apprenant"
        );
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
