<?php

namespace App\Service;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserInterface
{
    private $avatarRessource = null;
    private $encoder;
    private $validator;
    private $serializer;

    public function __construct(UserPasswordEncoderInterface $encoder,ValidatorInterface $validator,SerializerInterface $serializer)
    {
        $this->encoder = $encoder;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    private function createUser($entity,$avatar,$encoder,$profil)
    {
        $password = $entity->getPassword();
        $entity->setPassword($encoder->encodePassword($entity,$password))
            ->setIsDeleted(false)
            ->setProfil($profil);
        if(isset($avatar))
        {
            $avatarResource = fopen($avatar->getRealPath(),"rb");
            $entity->setAvatar($avatarResource);
            $this->avatarRessource = $avatarResource;
        }
        return $entity;
    }

    public function createUserContent($request,$avatarName,$entityNameSpace,$profil=null)
    {
        $content = $request->request->all();
        $avatar = $request->files->get($avatarName);
        $entity = $this->serializer->denormalize($content,$entityNameSpace);
        if($profil == null)
        {
            $entity->setProfil($entity->getProfil());
        }else{
            $entity->setProfil($profil);
        }
        $entity->setIsDeleted(false);
        $errors =  $this->validator->validate($entity);
        if (!count($errors))
        {
            $entity = $this->createUser($entity,$avatar,$this->encoder,$profil);
            return $entity;
        }
        return $errors;
    }

    public function getAvatarResource()
    {
        return $this->avatarRessource;
    }
}