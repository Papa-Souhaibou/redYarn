<?php

namespace App\Controller;

use App\Repository\GroupeCompetenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReferentielController extends AbstractController
{
    private const NOT_FOUND_RESOURCE = "Resource not found";
    /**
     * @Route(path="api/admin/referentiels/{idRef<\d+>}/grpecompetences/{idGrpe<\d+>}",methods={"GET"})
     */
    public function getCompetencesInReferentiel($idRef,$idGrpe,GroupeCompetenceRepository $groupeCompetenceRepository)
    {
        $grpe = $groupeCompetenceRepository->findCompetenceInReferentiel($idRef,$idGrpe);
        if ($grpe)
            return $this->json($grpe,Response::HTTP_OK);
        return $this->json(["message"=>self::NOT_FOUND_RESOURCE],Response::HTTP_NOT_FOUND);
    }
}
