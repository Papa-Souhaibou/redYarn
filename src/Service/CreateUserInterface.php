<?php

namespace App\Service;

class CreateUserInterface
{
    private $avatarRessource = null;

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

    public function createUserContent($request,$avatarName,$entityNameSpace,$serializer,$profil,$validator,$encoder)
    {
        $content = $request->request->all();
        $avatar = $request->files->get($avatarName);
        $entity = $serializer->denormalize($content,$entityNameSpace);
        $entity->setProfil($profil)
            ->setIsDeleted(false);
        $errors =  $validator->validate($entity);
        if (!count($errors))
        {
            $entity = $this->createUser($entity,$avatar,$encoder,$profil);
            return $entity;
        }
        return $errors;
    }

    public function getAvatarResource()
    {
        return $this->avatarRessource;
    }
}