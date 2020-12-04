<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Referentiel;
use Doctrine\ORM\EntityManagerInterface;

class ReferentielDataPersister implements ContextAwareDataPersisterInterface
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Referentiel;
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
        $grpeCompetences = $data->getGroupeCompetences()->getValues();
        foreach ($grpeCompetences as $grpeCompetence)
        {
            $grpeCompetence->removeReferentiel($data);
        }
        $this->manager->flush();
        return $data;
    }
}