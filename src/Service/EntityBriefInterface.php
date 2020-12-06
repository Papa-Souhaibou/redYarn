<?php


namespace App\Service;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Formateur;
use App\Entity\PromoBrief;
use App\Repository\GroupeRepository;
use Symfony\Component\Serializer\SerializerInterface;

class EntityBriefInterface
{
    private $avatarResource;
    private $serializer;
    private $validator;
    private $groupeRepo;

    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,GroupeRepository $groupeRepo)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->groupeRepo = $groupeRepo;
    }

    public function createBrief($request,$creator)
    {
        $content = $request->request->all();
        $avatar = $request->files->get("avatar");
        $groupes = isset($content["groupes"]) ? $content["groupes"] : [];
        $brief = $this->serializer->denormalize($content,"App\Entity\Brief",null,["groups" => ['brief:write']]);
        foreach ($groupes as $groupe)
        {
            $idGroupe = (int)$groupe["id"];
            $groupe = $this->groupeRepo->findOneById($idGroupe);
            if($groupe)
            {
                $promo = $groupe->getPromo();
                $promoBrief = (new PromoBrief())
                        ->setStatus("en cours")
                        ->setPromo($promo);
                $brief->addGroupe($groupe);
                $brief->addPromoBrief($promoBrief);
            }
        }
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