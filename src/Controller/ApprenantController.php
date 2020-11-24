<?php

namespace App\Controller;

use App\Entity\Apprenant;
use App\Repository\ApprenantRepository;
use App\Service\CreateUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApprenantController extends AbstractController
{
    private $avatarName = "avatar";
    private $entityNameSpace = "App\Entity\Apprenant";
    public function addStudent(Request $request, ApprenantRepository $apprenantRepository, CreateUserInterface $createUser,
                            EntityManagerInterface $manager)
    {
        $profil = $apprenantRepository->findOneBy(["libelle" => "APPRENANT"]);
        $result = $createUser->createUserContent($request,$this->avatarName,$this->entityNameSpace,$profil);
        $status = Response::HTTP_BAD_REQUEST;
        if ($result instanceof Apprenant)
        {
            $manager->persist($result);
            $manager->flush();
            fclose($createUser->getAvatarResource());
            $status = Response::HTTP_CREATED;
        }
        return $this->json($result,$status);
    }
}
