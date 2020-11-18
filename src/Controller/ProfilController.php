<?php

namespace App\Controller;

use App\Repository\AdminRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProfilController extends AbstractController
{
    private $profilRepository;
    private $manager;
    private $serializer;

    public function __construct(ProfilRepository $profilRepository,EntityManagerInterface $manager,SerializerInterface $serializer)
    {
        $this->profilRepository = $profilRepository;
        $this->manager = $manager;
        $this->serializer = $serializer;
    }

    public function get_profils()
    {
        $profils = $this->profilRepository->findAll();
        return $this->json($profils,Response::HTTP_NO_CONTENT);
    }

    public function add_profil(Request $request, ValidatorInterface $validator)
    {
        dd($request->getContent());
        $requestContent = $request->getContent();
        $profil = $this->serializer->deserialize($requestContent,"App\Entity\Profil","json");
        $libelle = strtoupper($profil->getLibelle());
        $same_profil = $this->profilRepository->findOneBy(["libelle" => $libelle]);
        $profil->setLibelle($libelle);
        $errors = isset($same_profil) ? ["message" => "Ce profil existe dejà."]:$validator->validate($profil);
        if(isset($errors))
        {
            return $this->json($errors,Response::HTTP_BAD_REQUEST);
        }
        $this->manager->persist($profil);
        $this->manager->flush();
        return  $this->json($profil,Response::HTTP_CREATED);

    }
}
