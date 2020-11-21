<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{
    private $manager;
    private $request;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    public function supports($data, array $context = []): bool
    {
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {

    }

    public function remove($data, array $context = [])
    {
        $data->setIsDeleted(true);
        $this->manager->flush();
        return $data;
    }
}