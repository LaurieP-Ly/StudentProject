<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReservationsRepository")
 */
class Reservations
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $EmailClient;

    /**
     * @ORM\Column(type="integer")
     */
    private $idHoraire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailClient(): ?string
    {
        return $this->EmailClient;
    }

    public function setEmailClient(string $EmailClient): self
    {
        $this->EmailClient = $EmailClient;

        return $this;
    }

    public function getIdHoraire(): ?int
    {
        return $this->idHoraire;
    }

    public function setIdHoraire(int $idHoraire): self
    {
        $this->idHoraire = $idHoraire;

        return $this;
    }
}
