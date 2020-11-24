<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Profil;
use Doctrine\ORM\EntityManagerInterface;

class ProfilDataPersister implements ContextAwareDataPersisterInterface
{
    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }


    public function supports($data, array $context = []): bool
    {
        return $data instanceof Profil;
    }

    public function persist($data, array $context = [])
    {
        if(isset($context["collection_operation_name"])){
            $data->setIsDeleted(false);
            $this->manager->persist($data);
        }
        $this->manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $users = $data->getUsers()->getValues();
        $data->setIsDeleted(true);
        array_map( function ($user){
            $user->setIsDeleted(true);
            return $user;
        },$users);
        $this->manager->flush();
        return $data;
    }
}