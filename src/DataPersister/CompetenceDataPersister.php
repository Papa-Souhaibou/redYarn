<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Competence;
use Doctrine\ORM\EntityManagerInterface;

class CompetenceDataPersister implements ContextAwareDataPersisterInterface
{
    private  $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function supports($data, array $context = []): bool
    {
        return  $data instanceof  Competence;
    }

    public function persist($data, array $context = [])
    {
        if (isset($context["collection_operation_name"]))
            $this->manager->persist($data);
        $this->manager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        $data->setIsDeleted(true);
        $groupes = $data->getGroupeCompetences();
        array_map(function ($groupe,$data){
            $groupe->removeCompetence($data);
            $data->removeGroupeCompetence($groupe);
        },$groupes,$data);
        $niveaux = $data->getNiveaux();
        array_map(function ($niveau,$data){
            $data->removeNiveau($niveau);
            $niveau->setIsDeleted(true);
        },$niveaux,$data);
        $this->manager->flush();
        return $data;
    }
}