<?php

namespace App\Controller;

use App\Entity\Competence;
use App\Repository\GroupeCompetenceRepository;
use App\Service\EntityCompetenceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CompetenceController extends AbstractController
{
    private $manager;
    private $competenceService;
    private $grpeCompetenceRepository;
    private $serializer;
    public function __construct(EntityManagerInterface $manager,EntityCompetenceInterface $competenceService,
                                GroupeCompetenceRepository $grpeCompetenceRepository,SerializerInterface $serializer)
    {
        $this->manager = $manager;
        $this->competenceService = $competenceService;
        $this->grpeCompetenceRepository = $grpeCompetenceRepository;
        $this->serializer = $serializer;
    }


    public function addCompetence(Request $request)
    {
        $requestContent = $request->getContent();
        $contentTab = $this->serializer->decode($requestContent,"json");
        $idGrpeCompetence = !empty($contentTab["groupeCompetences"]) ? (int)$contentTab["groupeCompetences"]: 0;
        $grpeCompetence = $this->grpeCompetenceRepository->findOneById($idGrpeCompetence);
        $result = $this->competenceService->createCompetence($contentTab,$grpeCompetence);
        $status = Response::HTTP_BAD_REQUEST;
        if ($result instanceof Competence)
        {
            $this->manager->persist($result);
            $this->manager->flush();
            $status = Response::HTTP_CREATED;
        }
        return $this->json($result,$status);
    }
}
