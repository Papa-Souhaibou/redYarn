<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Competence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;

class CompetenceDataPersister implements ContextAwareDataPersisterInterface
{
    private  $manager;
    private $competenceRepository;

    public function __construct(EntityManagerInterface $manager,CompetenceRepository $competenceRepository)
    {
        $this->manager = $manager;
        $this->competenceRepository = $competenceRepository;
    }

    public function supports($data, array $context = []): bool
    {
        return  $data instanceof  Competence;
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
        $groupes = $data->getGroupeCompetences();
        $niveaux = $data->getNiveaux();
        foreach ($groupes as $groupe)
        {
            $groupe->removeCompetence($data);
            $data->removeGroupeCompetence($groupe);
        }
        foreach ($niveaux as $niveau)
        {
            $niveau->setIsDeleted(true);
        }
        $this->manager->flush();
        return $data;
    }
}