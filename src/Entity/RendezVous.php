<?php

namespace App\Entity;
use App\Entity\Consultation;
use App\Form\ConsulationType;
use App\Repository\ConsultationRepository;
use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DateTime\DateTime;
use App\Entity\referencedColumnName;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank(message: " titre doit etre non vide")]

    private ?\DateTimeInterface $time = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: " titre doit etre non vide")]

   
    private ?\DateTimeInterface $date_rendezvous = null;

    #[ORM\ManyToOne(inversedBy: 'rendezVouses')]
    private ?User $User = null;

    #[ORM\Column(type :"boolean")]
    private ?bool $Confirmation = null;
   

 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDateRendezvous(): ?\DateTimeInterface
    {
        return $this->date_rendezvous;
      
    }

    public function setDateRendezvous(\DateTimeInterface $date_rendezvous): self
    {
        $this->date_rendezvous = $date_rendezvous;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
      public function __ToString()
{
    return (String)$this->getDateRendezvous();
} 
public function __ToString1()
{
    return (String)$this->getTime();
}

public function isConfirmation(): ?bool
{
    return $this->Confirmation;
}

public function setConfirmation(bool $Confirmation): self
{
    $this->Confirmation = $Confirmation;

    return $this;
} 

}
