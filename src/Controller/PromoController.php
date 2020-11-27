<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Service\EntityPromoInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PromoController extends AbstractController
{
    private $manager;
    private $avatarName = "avatar";
    private $entityPromo;
    public function __construct(EntityManagerInterface $manager,EntityPromoInterface $entityPromo)
    {
        $this->manager = $manager;
        $this->entityPromo = $entityPromo;
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
            $promo->setLanguage($response->getLanguage())
                ->setStartedAt($response->getStartedAt())
                ->setPrevisionalEndDate($response->getPrevisionalEndDate())
                ->setReferenceAgate($response->getReferenceAgate())
                ->setLocation(!empty($response->getLocation())? $response->getLocation(): $promo->getLocation())
                ->setTitle($response->getTitle())
                ->setDescription($response->getDescription())
                ->setAvatar(!empty($response->getAvatar()) ? $response->getAvatar(): $promo->getAvatar())
                ->setFabrique(!empty(($response->getFabrique()) ? $response->getFabrique() : $promo->getFabrique()))
                ->setReferentiel(!empty($response->getReferentiel()) ? $response->getReferentiel(): $promo->getReferentiel());
            $status = Response::HTTP_OK;
            $this->manager->flush();
            $response = $promo;
        }
        return $this->json($response,$status);
    }

}
