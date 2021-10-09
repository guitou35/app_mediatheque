<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $DateReservation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DateEmprunt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DateRetour;

    /**
     * @ORM\ManyToOne(targetEntity=Personne::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $personne;

    /**
     * @ORM\ManyToOne(targetEntity=Livre::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $livre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->DateReservation;
    }

    public function setDateReservation(\DateTimeInterface $DateReservation): self
    {
        $this->DateReservation = $DateReservation;

        return $this;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->DateEmprunt;
    }

    public function setDateEmprunt(?\DateTimeInterface $DateEmprunt): self
    {
        $this->DateEmprunt = $DateEmprunt;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->DateRetour;
    }

    public function setDateRetour(?\DateTimeInterface $DateRetour): self
    {
        $this->DateRetour = $DateRetour;

        return $this;
    }

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(?Personne $personne): self
    {
        $this->personne = $personne;

        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }
}
