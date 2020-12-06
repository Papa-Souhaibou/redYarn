<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Repository\FormateurRepository;
use App\Service\CreateUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormateurController extends AbstractController
{
    private $avatarName = "avatar";
    private $entityNameSpace = "App\Entity\Formateur";

    public function addTeacher(Request $request, FormateurRepository $formateurRepository, CreateUserInterface $createUser,
                               EntityManagerInterface $manager)
    {
        $profil = $formateurRepository->findOneBy(["libelle" => "FORMATEUR"]);
        $result = $createUser->createUserContent($request,$this->avatarName,$this->entityNameSpace,$profil);
        $status = Response::HTTP_BAD_REQUEST;
        if ($result instanceof Formateur)
        {
            $manager->persist($result);
            $manager->flush();
            $avatarResource = $createUser->getAvatarResource();
            if ($avatarResource)
                fclose($avatarResource);
            $status = Response::HTTP_CREATED;
        }
        return $this->json($result,$status);
    }
}
