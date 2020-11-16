<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
    public const PROFIL_REFERENCE = "user_profil";
    public function load(ObjectManager $manager)
    {
        $profil = new Profil();
        $profil->setLibelle("FORMATEUR");
        $manager->persist($profil);
        $manager->flush();
        $this->addReference(self::PROFIL_REFERENCE,$profil);
    }
}
