<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HorairesRepository")
 * @ApiResource
 */
class Horaires
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Salles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idsalle;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idcours;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $jour;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $heure;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdSalle(): ?Salles
    {
        return $this->idsalle;
    }

    public function setIdSalle(?Salles $id_salle): self
    {
        $this->idsalle = $id_salle;

        return $this;
    }

    public function getIdCours(): ?Cours
    {
        return $this->idcours;
    }

    public function setIdCours(?Cours $id_cours): self
    {
        $this->idcours = $id_cours;

        return $this;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getHeure(): ?string
    {
        return $this->heure;
    }

    public function setHeure(string $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function __toString()
    {
        $val = $this->idsalle->getId() . "," . $this->idcours->getId(). "," . $this->jour. "," . $this->heure;
        dump($val);
        return $val;
    }


}
