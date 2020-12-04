<?php

namespace App\Controller;

use App\Entity\Brief;
use App\Service\EntityBriefInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BriefController extends AbstractController
{
    private $briefService;
    private $manager;

    public function __construct(EntityBriefInterface $briefService,EntityManagerInterface $manager)
    {
        $this->briefService = $briefService;
        $this->manager = $manager;
    }

    public function addBrief(Request $request,TokenStorageInterface $tokenStorage)
    {
        $creator = $tokenStorage->getToken()->getUser();
        $result = $this->briefService->createBrief($request,$creator);
        $status = Response::HTTP_BAD_REQUEST;
        if ($result instanceof Brief)
        {
            $this->manager->persist($result);
            $this->manager->flush();
            $resource = $this->briefService->getAvatarResource();
            if ($resource)
                fclose($resource);
            $status = Response::HTTP_CREATED;
        }
        return $this->json($result,$status);
    }
}
