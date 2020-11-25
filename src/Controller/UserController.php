<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProfilRepository;
use App\Service\CreateUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    private $avatarName = "avatar";
    private $entityNameSpace = "App\Entity\Admin";
    private $manager,
            $createUser;

    public function __construct(CreateUserInterface $createUser,
                                EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->createUser = $createUser;
    }

    public function addUser(Request $request,ProfilRepository $profilRepository)
    {
        $profil = $profilRepository->findOneBy(["libelle" => "ADMIN"]);
        $result = $this->createUser->createUserContent($request,$this->avatarName,$this->entityNameSpace,$profil);
        $status = Response::HTTP_BAD_REQUEST;
        if ($result instanceof User)
        {
            $this->manager->persist($result);
            $this->manager->flush();
            fclose($this->createUser->getAvatarResource());
            $status = Response::HTTP_CREATED;
        }
        return $this->json($result,$status);
    }

    public function setUser(User $user,Request $request)
    {
        $value = $this->createUser->createUserContent($request,$this->avatarName,$this->entityNameSpace);
        $status = Response::HTTP_BAD_REQUEST;
        if ($value instanceof User)
        {
            $user->setFirstname( !empty($value->getFirstname()) ? $value->getFirstname() : "")
                ->setLastname(!empty($value->getLastname()) ? $value->getLastname() : "")
                ->setPassword($value->getPassword())
                ->setEmail($value->getEmail())
                ->setAvatar(empty($value->getAvatar()) ? $value->getAvatar() : null)
                ->setUsername($value->getUsername());
            $this->manager->flush();
            fclose($this->createUser->getAvatarResource());
            $status = Response::HTTP_CREATED;
            $value = $user;
        }
        return $this->json($value,$status);
    }
}
