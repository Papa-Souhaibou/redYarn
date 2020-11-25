<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Niveau;
use Doctrine\ORM\EntityManagerInterface;

class NiveauDataPersister implements ContextAwareDataPersisterInterface
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Niveau;
    }

    public function persist($data, array $context = [])
    {
        if (isset($context["collection_operation_name"]))
            $this->manager->persist($data);
        $this->manager->flush();
    }

    public function remove($data, array $context = [])
    {
        $data->setIsDeleted(true);
        $this->manager->flush();
        return $data;
    }
}