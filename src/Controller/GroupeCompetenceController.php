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
        $grpeCompetenceArray = $serializer->decode($requestContent,"json");
        $competencesArray = !empty($grpeCompetenceArray["competences"]) ? $grpeCompetenceArray["competences"]:[];
        $grpeCompetenceArray["competences"] = [];
        $grpeCompetence = $serializer->denormalize($grpeCompetenceArray,"App\Entity\GroupeCompetence");
        $competences = $serializer->denormalize($competencesArray,"App\Entity\Competence[]");
        $grpeEerrors = $validator->validate($grpeCompetence);
        $competenceErrors = $validator->validate($competences);
        if(empty($grpeEerrors) && empty($competenceErrors))
        {
            $grpeCompetence = $this->addCompetenceToGroupe($competences,$this->manager,$grpeCompetence);
            $this->manager->persist($grpeCompetence);
            dd($grpeCompetence);
            $this->manager->flush();
            return $this->json($grpeCompetence,Response::HTTP_CREATED);
        }
    }

    private function addCompetenceToGroupe($competences,$manager,$grpeCompetence)
    {
        foreach ($competences as $competence)
        {
            $competence->addGroupeCompetence($grpeCompetence);
            $this->manager->persist($competence);
            $grpeCompetence->addCompetence($competence);
        }
        return $grpeCompetence;
    }
}
