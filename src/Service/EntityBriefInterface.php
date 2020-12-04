<?php


namespace App\Service;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Formateur;
use Symfony\Component\Serializer\SerializerInterface;

class EntityBriefInterface
{
    private $avatarResource;
    private $serializer;
    private $validator;

    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function createBrief($request,$creator)
    {
        $content = $request->request->all();
        $avatar = $request->files->get("avatar");
        $brief = $this->serializer->denormalize($content,"App\Entity\Brief",null,["groups" => ['brief:write']]);
        if ($avatar)
            $this->avatarResource = fopen($avatar->getRealPath(),"rb");
        $brief->setAvatar($this->avatarResource);
        $errors = $this->validator->validate($brief,[ "groups"=>['brief_validation'] ]);
        if(empty($errors))
            $errors = $brief;
        if ($creator instanceof Formateur)
            $brief->setCreator($creator);
        return $errors;
    }

    public function getAvatarResource()
    {
        return $this->avatarResource;
    }
}