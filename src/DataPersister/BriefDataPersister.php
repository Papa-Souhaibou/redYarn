<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Brief;
use Doctrine\ORM\EntityManagerInterface;

class BriefDataPersister implements ContextAwareDataPersisterInterface
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Brief;
    }

    public function persist($data, array $context = [])
    {
        if (isset($context["collection_operation_name"]) && $context["collection_operation_name"] == "duplicate_brief")
        {

        }
    }

    public function remove($data, array $context = [])
    {

    }
}