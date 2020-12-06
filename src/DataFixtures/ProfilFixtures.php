<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $profils = ["ADMIN","APPRENANT","FORMATEUR","CM"];
        foreach ($profils as $role)
        {
            $profil = new Profil();
            $profil->setLibelle("$role");
            $manager->persist($profil);
            $this->addReference($role,$profil);
        }
        $manager->flush();
    }
}
