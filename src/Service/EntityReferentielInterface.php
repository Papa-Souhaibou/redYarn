<?php


namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityReferentielInterface
{
    private $validator;
    private $serializer;
    private $programmeRessource;

    public function getProgrammeRessource()
    {
        return $this->programmeRessource;
    }

    public function __construct(ValidatorInterface $validator, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function createreferentiel($content,$programme,$grpeCompetenceRepo)
    {
        $groupeCompetences = isset($content["groupeCompetences"])? $content["groupeCompetences"] : [];
        unset($content["groupeCompetences"]);
        $referentiel = $this->serializer->denormalize($content,"App\Entity\Referentiel");

        $referentiel = $this->addGrpeCompetence($referentiel,$groupeCompetences,$grpeCompetenceRepo);
        $errors = $this->validator->validate($referentiel);
        if (!count($errors))
        {
            $referentiel = $this->addProgramme($referentiel,$programme);
            return $referentiel;
        }
        return $errors;
    }

    private function addProgramme($referentiel,$programme)
    {
        if ($programme)
        {
            $this->programmeRessource = fopen($programme->getRealPath(),"rb");
            $referentiel->setProgramme($this->programmeRessource);
        }
        return $referentiel;
    }

    private function addGrpeCompetence($referentiel,$grpeCompetences,$grpeCompetenceRepo)
    {
        $match = "#\d+#";
        foreach ($grpeCompetences as $grpeCompetence)
        {
            $id = (int)$grpeCompetence;
            $groupe = $grpeCompetenceRepo->findOneById($id);
            if ($groupe)
                $referentiel->addGroupeCompetence($groupe);
        }
        return $referentiel;
    }
}