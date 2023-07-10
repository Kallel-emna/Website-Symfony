<?php

namespace App\Entity;
use App\Entity\Consultation;
use App\Form\ConsulationType;
use App\Repository\ConsultationRepository;
use App\Repository\OrdonnanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\referencedColumnName;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
 

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: " titre doit etre non vide")]
    #[Assert\Length(min : 3,minMessage:" Entrer un titre au mini de 3 caracteres")]
    private ?string $nompatient = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: " titre doit etre non vide")]
    private ?string $medicament = null;

    #[ORM\ManyToOne(inversedBy: 'Ordonnance')]
    #[ORM\JoinColumn(onDelete: "CASCADE")]
    private ?Consultation $consultation = null;

    #[ORM\ManyToOne(inversedBy: 'NO')]
    private ?User $User = null;


    #[ORM\OneToMany(mappedBy: 'ordonnance', targetEntity: DossierMedical::class)]
    private Collection $dossierMedicals;   

    public function getId(): ?int
    {
        return $this->id;
    }
  
    

    public function getNompatient(): ?string
    {
        return $this->nompatient;
    }

    public function setNompatient(string $nompatient): self
    {
        $this->nompatient = $nompatient;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMedicament(): ?string
    {
        return $this->medicament;
    }

    public function setMedicament(string $medicament): self
    {
        $this->medicament = $medicament;

        return $this;
    }

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): self
    {
        $this->consultation = $consultation;

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
}