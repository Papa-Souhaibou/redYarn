<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\GroupeCompetence;
use App\Repository\CompetenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GroupeCompetenceController extends AbstractController
{
    private $manager;
    private $validator;
    public function __construct(EntityManagerInterface $manager,ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function setGrpeCompetence($id,Request $request,GroupeCompetence $groupeCompetence,SerializerInterface $serializer,CompetenceRepository $competenceRepository)
    {
        $jsonContent = $request->getContent();
        $contentTab = $serializer->decode($jsonContent,"json");
        $competences = !empty($contentTab["competences"]) ? $contentTab["competences"]:[];
        unset($contentTab["competences"]);
        $groupe = $serializer->denormalize($contentTab,"App\Entity\GroupeCompetence");
        $values = !empty($this->validator->validate($groupe)) ? $this->validator->validate($groupe):[];
        $status = Response::HTTP_BAD_REQUEST;
        if(!count($values))
        {
            $values = $this->setCompetences($groupeCompetence,$competences,$competenceRepository);
            $groupeCompetence->setLibelle($values->getLibelle())
                ->setDescriptif($values->getDescriptif());
            $this->manager->flush();
            $values = $groupeCompetence;
            $status = Response::HTTP_OK;
        }
        return $this->json($values,$status);
    }

    private function setCompetences($groupeCompetence,$competences,$competenceRepository)
    {
        if (!empty($competences))
        {
            $match = "#\d+#";
            foreach ($competences as $competence)
            {
                preg_match($match,$competence,$iri);
                $idCompetence = (int)$iri[0];
                $action = substr(strstr($competence,"?"),1);
                $skill = $competenceRepository->findOneBy(["id" => $idCompetence]);
                if ($action == "action=delete")
                {
                    $groupeCompetence->removeCompetence($skill);
                    $skill->removeGroupeCompetence($groupeCompetence);
                }
                elseif ($action == "action=add")
                    $groupeCompetence->addCompetence($skill);
            }
        }
        return $groupeCompetence;
    }
}
