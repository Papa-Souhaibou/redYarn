<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use App\Service\CreateUserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    private $avatarName = "avatar";
    private $entityNameSpace = "App\Entity\Admin";
    public function addUser(Request $request, ProfilRepository $profilRepository, CreateUserInterface $createUser, SerializerInterface $serializer,
                            ValidatorInterface $validator, UserPasswordEncoderInterface $encoder,
                            EntityManagerInterface $manager)
    {
        $profil = $profilRepository->findOneBy(["libelle" => "ADMIN"]);
        $result = $createUser->createUserContent($request,$this->avatarName,$this->entityNameSpace,$serializer,$profil,$validator,$encoder);
        $status = Response::HTTP_BAD_REQUEST;
        if ($result instanceof User)
        {
            $manager->persist($result);
            $manager->flush();
            fclose($createUser->getAvatarResource());
            $status = Response::HTTP_CREATED;
        }
        return $this->json($result,$status);
    }

    public function setUser($id,UserRepository $userRepository,Request $request)
    {
        $user = $userRepository->findOneBy(["id" => $id]);
        $profil  = $user->getProfil();
        dd($request);
    }
}
