<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ApprenantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 */
class Apprenant extends User
{
    /**
     * @ORM\ManyToOne(targetEntity=ProfilSortie::class, inversedBy="apprenants")
     */
    private $profilSortie;

    public function getProfilSortie(): ?ProfilSortie
    {
        return $this->profilSortie;
    }

    public function setProfilSortie(?ProfilSortie $profilSortie): self
    {
        $this->profilSortie = $profilSortie;

        return $this;
    }
}
