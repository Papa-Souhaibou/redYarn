<?php


namespace App\Service;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Repository\ApprenantRepository;
use Symfony\Component\Serializer\SerializerInterface;

class EntityPromoInterface
{
    private $serializer;
    private $validator;
    private $ressource;
    private $apprenantRepository;

    public function __construct(SerializerInterface $serializer,ValidatorInterface $validator,ApprenantRepository $apprenantRepository)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->apprenantRepository = $apprenantRepository;
    }

    public function createPromo($request,$avatar)
    {
        $content = $request->request->all();
        $apprenants = !empty($content["apprenants"]) ? $content["apprenants"] : [];
        $promo = $this->serializer->denormalize($content,"App\Entity\Promo");
        if ($avatar)
        {
            $this->ressource = fopen($avatar->getRealPath(),"rb");
            $promo->setAvatar($this->ressource);
        }
        $errors = $this->validator->validate($promo) != null ? $this->validator->validate($promo): [];
        if (!count($errors))
        {
            return  $this->addStudents($promo,$apprenants);
        }
        return $errors;
    }

    private function addStudents($promo,$apprenants)
    {
        if (!empty($apprenants))
        {
            $grpePrincipal = $promo->getGroupes()[0];
            foreach ($apprenants as $apprenant)
            {
                $student = $this->apprenantRepository->findOneByEmail($apprenant);
                $grpePrincipal->addApprenant($student);
            }
            return $promo;
        }
    }

    public function getRessource()
    {
        return $this->ressource;
    }
}