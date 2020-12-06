<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use App\Entity\Cm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CmFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $profil = $this->getReference("CM");
        $times = 10;
        for ($i = 0; $i < $times; $i++)
        {
            $cm = new Cm();
            $cm->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setUsername($faker->userName)
                ->setEmail($faker->email)
                ->setProfil($profil)
                ->setIsDeleted(false)
                ->setPassword($this->encoder->encodePassword($cm,strtolower($profil->getLibelle())));
            $manager->persist($cm);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
