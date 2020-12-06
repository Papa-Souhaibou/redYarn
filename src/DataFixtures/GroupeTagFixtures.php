<?php

namespace App\DataFixtures;

use App\Entity\GroupeTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GroupeTagFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $row = 10;
        $faker = Factory::create();
        for ($i = 0; $i < $row; $i++)
        {
            $tag = $this->getReference("Tag $i");
            $grpeTag = (new GroupeTag())
                ->setLibelle($faker->paragraph(1))
                ->setIsDeleted(false)
                ->addTag($tag);
            $manager->persist($grpeTag);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TagFixtures::class,
        );
    }
}
