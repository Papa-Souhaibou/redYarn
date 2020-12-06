<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $row = 10;
        $faker = Factory::create();
        for ($i = 0; $i < $row; $i++)
        {
            $tag = (new Tag())
                ->setLibelle("Tag $i")
                ->setLibelle(false)
                ->setDescriptif($faker->paragraph());
            $manager->persist($tag);
            $this->addReference("Tag $i",$tag);
        }
        $manager->flush();
    }
}
