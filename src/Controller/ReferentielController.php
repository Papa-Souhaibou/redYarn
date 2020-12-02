<?php

namespace App\Controller;

use App\Entity\Referentiel;
use App\Repository\GroupeCompetenceRepository;
use App\Repository\ReferentielRepository;
use App\Service\EntityReferentielInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReferentielController extends AbstractController
{
    private const NOT_FOUND_RESOURCE = "Resource not found";
    private $referentielService;
    private $manager;
    private $grpeCompetenceRepo;
    public function __construct(EntityReferentielInterface $referentielService,EntityManagerInterface $manager,GroupeCompetenceRepository $grpeCompetenceRepo)
    {
        $this->referentielService = $referentielService;
        $this->manager = $manager;
        $this->grpeCompetenceRepo = $grpeCompetenceRepo;
    }


    public function getCompetencesInReferentiel($idRef,$idGrpe,GroupeCompetenceRepository $groupeCompetenceRepository)
    {
        $grpe = $groupeCompetenceRepository->findCompetenceInReferentiel($idRef,$idGrpe);
        if ($grpe)
            return $this->json($grpe,Response::HTTP_OK);
        return $this->json(["message"=>self::NOT_FOUND_RESOURCE],Response::HTTP_NOT_FOUND);
    }

    public function addReferentiel(Request $request)
    {
        $content = $request->request->all();
        $programme = $request->files->get("programme");
        $value = $this->referentielService->createreferentiel($content,$programme,$this->grpeCompetenceRepo);
        $status = Response::HTTP_BAD_REQUEST;
        if ($value instanceof Referentiel)
        {
            $this->manager->persist($value);
            $this->manager->flush();
            fclose($this->referentielService->getProgrammeRessource());
            $status = Response::HTTP_CREATED;
        }
        return $this->json($value,$status);
    }

    public function setReferentiel(Request $request,Referentiel  $referentiel)
    {
        $content = $request->request->all();
        $groupes = !empty($content["groupeCompetences"])?$content["groupeCompetences"]:[];
        $programme = $request->files->get("programme");
        $value = $this->referentielService->createreferentiel($content,$programme,$this->grpeCompetenceRepo);
        if ($value instanceof Referentiel)
        {
            $this->setGrpeCompetences($referentiel,$groupes);
            dd($referentiel->getGroupeCompetences()->getValues());
        }
    }

    private function setGrpeCompetences($referentiel,$groupes)
    {
        $pattern = "#\d+#";
        foreach ($groupes as $groupe)
        {
            preg_match($pattern,$groupe,$id);
            $id = (int)$id[0];
            $action = substr(strstr($groupe,"?"),1);
            $groupe = $this->grpeCompetenceRepo->findOneById($id);
            if($groupe)
            {
                if ($action == "action=add"){
                    $referentiel->addGroupeCompetence($groupe);
                    $groupe->removeReferentiel($referentiel);
                }elseif ($action == "action=delete"){
                    $referentiel->removeGroupeCompetence($groupe);
                }
            }
        }
        return $referentiel;
    }
}
