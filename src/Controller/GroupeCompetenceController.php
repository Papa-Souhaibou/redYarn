<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeCompetenceController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function addGrpeCompetence(Request $request,SerializerInterface $serializer,ValidatorInterface $validator)
    {
        $requestContent = $request->getContent();
        $grpeCompetenceTab = $serializer->decode($requestContent,"json");
        $competenceTab = !empty($grpeCompetenceTab["competences"]) ? $grpeCompetenceTab["competences"] : null;
        unset($grpeCompetenceTab["competences"]);
        $grpeCompetence = $serializer->denormalize($grpeCompetenceTab,"App\Entity\GroupeCompetence");
        $grpeCompetenceErrors = $validator->validate($grpeCompetence);
        if(isset($competenceTab))
        {
            $competences = $serializer->denormalize($competenceTab,"App\Entity\Competence[]");
            $competenceErrors = $validator->validate($competences);
            if(empty($grpeCompetenceErrors) && empty($competenceErrors))
            {
                $grpeCompetence = $this->addCompetenceToGroup($grpeCompetence,$competences);
                $this->manager->persist($grpeCompetence);
                $this->manager->flush();
                return $this->json($grpeCompetence,Response::HTTP_CREATED);
            }
        }
    }

    private function addCompetenceToGroup($groupeCompetence,$competences)
    {
        foreach ($competences as $competence)
        {
            $groupeCompetence->addCompetence($competence);
        }
        return $groupeCompetence;
    }
}
