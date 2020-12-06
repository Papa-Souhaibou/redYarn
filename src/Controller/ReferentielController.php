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
            $status = Response::HTTP_CREATED;
        }
        $programmeResource = $this->referentielService->getProgrammeRessource();
        if ($programmeResource){
            fclose($programmeResource);
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
            $libelle = $value->getLibelle()?$value->getLibelle():$referentiel->getLibelle();
            $presentation = $value->getPresentation() ? $value->getPresentation(): $referentiel->getPresentation();
            $critereAdmission = $value->getCritereAdmission()?$value->getCritereAdmission():$referentiel->getCritereAdmission();
            $critereEvaluation = $value->getCritereEvaluation()?$value->getCritereEvaluation():$referentiel->getCritereEvaluation();
            $programme = $value->getProgramme()?$value->getProgramme():$referentiel->getProgramme();
            $status = $value->getIsDeleted()?$value->getIsDeleted():$referentiel->getIsDeleted();
            $referentiel = $this->setGrpeCompetences($referentiel,$groupes);
            $referentiel->setPresentation($presentation)
                    ->setProgramme($programme)
                    ->setCritereAdmission($critereAdmission)
                    ->setCritereEvaluation($critereEvaluation)
                    ->setLibelle($libelle)
                    ->setIsDeleted($status);
            unset($value);
            $this->manager->flush();
            return $this->json($referentiel,Response::HTTP_OK);
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
                if ($action == "add"){
                    $referentiel->addGroupeCompetence($groupe);
                }elseif ($action == "delete"){
                    $referentiel->removeGroupeCompetence($groupe);
                }
            }
        }
        return $referentiel;
    }
}
