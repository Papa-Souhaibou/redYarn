<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Entity\Formateur;
use App\Entity\Promo;
use App\Repository\BriefRepository;
use App\Repository\PromoRepository;
use App\Service\EntityBriefInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class BriefController extends AbstractController
{
    private $briefService;
    private $manager;
    private $tokenStorage;
    private $serializer;
    private $briefRepo;
    private const RESOURCE_NOT_FOUND = "Resource Not Found.";
    public function __construct(EntityBriefInterface $briefService,EntityManagerInterface $manager,
                                TokenStorageInterface $tokenStorage,SerializerInterface $serializer,
                                BriefRepository $briefRepo)
    {
        $this->briefService = $briefService;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->serializer = $serializer;
        $this->briefRepo = $briefRepo;
    }

    public function addBrief(Request $request)
    {
        $creator = $this->tokenStorage->getToken()->getUser();
        $result = $this->briefService->createBrief($request,$creator);
        $status = Response::HTTP_BAD_REQUEST;
        if ($result instanceof Brief)
        {
            $this->manager->persist($result);
            $this->manager->flush();
            $status = Response::HTTP_CREATED;
            $result = $this->serializer->normalize($result,null,["groups"=>["brief:read"]]);
        }
        $resource = $this->briefService->getAvatarResource();
        if ($resource){
            fclose($resource);
        }
        return $this->json($result,$status);
    }

    public function duplicateBrief(Brief $brief)
    {
       $newBrief = clone $brief;
       $creator = $this->tokenStorage->getToken()->getUser();
       unset($brief);
       if ($creator instanceof  Formateur)
           $newBrief->setCreator($creator);
       $this->manager->persist($newBrief);
       $this->manager->flush();
       $newBrief = $this->serializer->normalize($newBrief,null,["groups"=>["brief:read"]]);
       return $this->json($newBrief,Response::HTTP_CREATED);
    }

    public function getBriefsInPromo($id)
    {
        $briefs = $this->briefRepo->fetchBriefsInPromo((int)$id);
        if ($briefs)
        {
            return $this->json($briefs,Response::HTTP_OK);
        }
        return  $this->json(["message"=>self::RESOURCE_NOT_FOUND],Response::HTTP_NOT_FOUND);
    }

    public function getTeacherValidBriefs($id)
    {
        $briefs = $this->briefRepo->fetchTeacherBriefs((int)$id,"valide");
        if ($briefs)
        {
            return $this->json($briefs,Response::HTTP_OK);
        }
        return $this->json(["message"=>self::RESOURCE_NOT_FOUND],Response::HTTP_NOT_FOUND);
    }

    public function getBriefInAPromo($idBrief,$idPromo,PromoRepository $repository)
    {
        $brief = $this->briefRepo->findOneById((int)$idBrief);
        $promo = $repository->findOneById((int)$idPromo);
        $brief = $this->briefRepo->findBriefInAPromo($brief,$promo);
        if ($brief)
        {
            return $this->json($brief,Response::HTTP_OK);
        }
        return $this->json(["message"=>self::RESOURCE_NOT_FOUND],Response::HTTP_NOT_FOUND);
    }
}
