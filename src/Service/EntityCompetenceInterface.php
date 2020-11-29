<?php


namespace App\Service;


use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class EntityCompetenceInterface
{
    private  $serializer,
             $validator;
    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    public function createCompetence($contentTab,$grpCompetence)
    {
        $levels = !empty($contentTab["niveaux"]) ? $contentTab["niveaux"] : [];
        unset($contentTab["niveaux"]);
        unset($contentTab["groupeCompetences"]);
        $competence = $this->serializer->denormalize($contentTab,"App\Entity\Competence");
        if (count($levels))
        {

            $levels = $this->serializer->denormalize($levels,"App\Entity\Niveau[]");
            foreach ($levels as $level)
            {
                $competence->addNiveau($level);
            }
            if ($grpCompetence)
                $competence->addGroupeCompetence($grpCompetence);
        }
        $errors = $this->validator->validate($competence);
        if ($errors)
            return $errors;
        return  $competence;
    }
}