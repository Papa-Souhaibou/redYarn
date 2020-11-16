<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        /*$faker = Factory::create();
        $apprenant = new Apprenant();
        $profil = $this->getReference(ProfilFixtures::PROFIL_REFERENCE);
        $apprenant->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setUsername($faker->userName)
            ->setEmail($faker->email)
            ->setProfil($profil)
            ->setPassword($this->encoder->encodePassword($apprenant,"apprenant"));
        $manager->persist($apprenant);
        $manager->flush();*/
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class
        );
    }
}
