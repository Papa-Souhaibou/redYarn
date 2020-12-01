<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Repository\ApprenantRepository;
use App\Repository\GroupeRepository;
use App\Repository\PromoRepository;
use App\Service\EntityPromoInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class PromoController extends AbstractController
{
    private const GROUPE_NORMALISATION = "grpe_principal_in_promo:read";
    private const WAITING_STUDENT_NORMALISATION = "waiting_student:read";
    private const STUDENTS_IN_PROMO_GROUPE_NORMALISATION = 'get_students_in_grpe_promo:read';
    private $manager;
    private $avatarName = "avatar";
    private $entityPromo;
    private $serializer;
    private $groupeRepository;
    public function __construct(EntityManagerInterface $manager,EntityPromoInterface $entityPromo,
                                SerializerInterface $serializer,GroupeRepository $groupeRepository)
    {
        $this->manager = $manager;
        $this->entityPromo = $entityPromo;
        $this->serializer = $serializer;
        $this->groupeRepository = $groupeRepository;
    }

    public function addPromo(Request $request,TokenStorageInterface $tokenStorage)
    {
        $avatar = $request->files->get($this->avatarName);
        $result = $this->entityPromo->createPromo($request,$avatar);
        $status = Response::HTTP_BAD_REQUEST;
        if ($result instanceof Promo)
        {
            $creator = $tokenStorage->getToken()->getUser();
            $result->setCreator($creator);
            $this->manager->persist($result);
            $this->manager->flush();
            $status = Response::HTTP_CREATED;
            fclose($this->entityPromo->getRessource());
        }
        return $this->json($result,$status);
    }

    public function setPromo(Request $request,Promo $promo)
    {
        $avatar = $request->files->get($this->avatarName);
        $response = $this->entityPromo->createPromo($request,$avatar);
        $status = Response::HTTP_BAD_REQUEST;
        if ($response instanceof Promo)
        {
            $location = !empty($response->getLocation())? $response->getLocation(): $promo->getLocation();
            $avatar = !empty($response->getAvatar()) ? $response->getAvatar(): $promo->getAvatar();
            $fabrique = !empty($response->getFabrique()) ? $response->getFabrique() : $promo->getFabrique();
            $referentiel = !empty($response->getReferentiel()) ? $response->getReferentiel(): $promo->getReferentiel();
            $promo->setLanguage($response->getLanguage())
                ->setStartedAt($response->getStartedAt())
                ->setPrevisionalEndDate($response->getPrevisionalEndDate())
                ->setReferenceAgate($response->getReferenceAgate())
                ->setTitle($response->getTitle())
                ->setDescription($response->getDescription())
                ->setLocation($location)
                ->setAvatar($avatar)
                ->setFabrique($fabrique)
                ->setReferentiel($referentiel);
            $status = Response::HTTP_OK;
            $this->manager->flush();
            $response = $promo;
        }
        return $this->json($response,$status);
    }

    public function getGrpePrincipaux()
    {
        $groupes = $this->groupeRepository->findBy(["type"=>"principal"]);
        $groupes = $this->serializer->normalize($groupes,null,["groups"=>[self::GROUPE_NORMALISATION]]);
        return $this->json($groupes,Response::HTTP_OK);
    }

    public function getGrpePrincipalInPromo($id)
    {
        $groupe = $this->groupeRepository->findGrpeInPromo($id,"principal");
        $groupe = $this->serializer->normalize($groupe,null,["groups"=>[self::GROUPE_NORMALISATION]]);
        return $this->json($groupe,Response::HTTP_OK);
    }

    public function getPromoWaitingStudents()
    {
        $students = $this->groupeRepository->findWaitingStudents(true);
        $students = $this->serializer->normalize($students,null,["groups" => [self::WAITING_STUDENT_NORMALISATION]]);
        return $this->json($students,Response::HTTP_OK);
    }

    public function getWaitingStudentsInPromo($id)
    {
        $students = $this->groupeRepository->findWaitingStudentsInPromo((int)$id,true);
        $students = $this->serializer->normalize($students,null,["groups" => [self::WAITING_STUDENT_NORMALISATION]]);
        return $this->json($students,Response::HTTP_OK);
    }

    public function getStudentInPromoGrpe($idPromo,$idGrpe)
    {
        $students = $this->groupeRepository->findStudentInPromoGrpe((int)$idPromo,(int)$idGrpe);
        $students = $this->serializer->normalize($students,null,["groups" => [self::STUDENTS_IN_PROMO_GROUPE_NORMALISATION]]);
        return $this->json($students,Response::HTTP_OK);
    }

    public function setStudentsInPromo(Promo $promo,Request $request,ApprenantRepository $apprenantRepository)
    {
        $content = $request->getContent();
        $contents = $this->serializer->decode($content,"json");
        $students = !empty($contents["apprenants"])?$contents["apprenants"]:[];
        
    }

    public function setTeacherInPromo()
    {
        
    }

    public function setPromoGrpeStatus()
    {

    }
}
