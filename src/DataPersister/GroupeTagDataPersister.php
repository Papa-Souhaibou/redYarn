<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\GroupeTag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;

class GroupeTagDataPersister implements ContextAwareDataPersisterInterface
{
    private $manager;
    private  $tagRepository;
    public function __construct(EntityManagerInterface $manager, TagRepository $tagRepository)
    {
        $this->manager = $manager;
        $this->tagRepository = $tagRepository;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof  GroupeTag;
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
        $data->setIsDeleted(true);
        $tags = $data->getTags();
        foreach ($tags as $tag)
        {
            $data->removeTag($tag);
            $tag->removeGroupeTag($data);
        }
        $this->manager->flush();
        return $data;
    }
}