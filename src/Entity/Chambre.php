<?php

namespace App\Entity;
use App\Entity\Exception;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ChambreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChambreRepository::class)]
class Chambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $etage = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[Assert\GreaterThanOrEqual(0)]
    #[ORM\Column]
    private ?int $capacite = null;

    #[ORM\ManyToOne(inversedBy: 'chambres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    #[ORM\OneToMany(mappedBy: 'chambre', targetEntity: ReservationChambre::class)]
    private Collection $reservationChambres;

    public function __construct()
    {
        $this->reservationChambres = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtage(): ?int
    {
        return $this->etage;
    }

    public function setEtage(int $etage): self
    {
        $this->etage = $etage;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return Collection<int, ReservationChambre>
     */
    public function getReservationChambres(): Collection
    {
        return $this->reservationChambres;
    }

   
}