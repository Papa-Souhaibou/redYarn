<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;

class TagDataPersister implements ContextAwareDataPersisterInterface
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function supports($data, array $context = []): bool
    {
        return  $data instanceof Tag;
    }

    public function persist($data, array $context = [])
    {
        if (isset($context["collection_operation_name"]))
        {
            $this->manager->persist($data);
        }
        $this->manager->flush();
    }

    public function remove($data, array $context = [])
    {
        $grpeTags = $data->getGroupeTags();
        $data->setIsDeleted(true);
        foreach ($grpeTags as $grpeTag)
        {
            $grpeTag->removeTag($data);
            $data->removeGroupeTag($grpeTag);
        }
        $this->manager->flush();
    }
}