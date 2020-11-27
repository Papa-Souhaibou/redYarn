<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Promo;
use Doctrine\ORM\EntityManagerInterface;

class PromoDataPersister implements ContextAwareDataPersisterInterface
{
    private $manger;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manger = $manager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof  Promo;
    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
    }

    public function remove($data, array $context = [])
    {
        $data->setIsDeleted(true);
        $this->manger->flush();
    }
}