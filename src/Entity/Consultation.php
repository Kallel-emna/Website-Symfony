<?php

namespace App\Entity;
use App\Entity\Ordonnance;
use App\Entity\RendezVous;
use App\Repository\ConsultationRepository;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\referencedColumnName;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
 

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notes = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: " titre doit etre non vide")]
    #[Assert\Length(min : 2,minMessage:" Entrer un titre au mini de 2 caracteres")]
    private ?float $prix = null;

    #[ORM\OneToMany(mappedBy: 'consultation', targetEntity: Ordonnance::class)]
    private Collection $Ordonnance;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?RendezVous $id_rendezvous= null;

    public function __construct()
    {
        $this->Ordonnance = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
  

   
    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): self
    {
        $this->notes = $notes;

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

   

    public function setIdOrdonnance(?Ordonnance $id_ordonnance): self
    {
        $this->Ordonnance = $id_ordonnance;

        return $this;
    }

    /**
     * @return Collection<int, Ordonnance>
    */
    public function getOrdonnance(): Collection
    {
        return $this->Ordonnance;
    }

    public function addOrdonnance(Ordonnance $ordonnance): self
    {
        if (!$this->Ordonnance->contains($ordonnance)) {
            $this->Ordonnance->add($ordonnance);
            $ordonnance->setConsultation($this);
        }

        return $this;
    }

    public function removeOrdonnance(Ordonnance $ordonnance): self
    {
        if ($this->Ordonnance->removeElement($ordonnance)) {
            // set the owning side to null (unless already changed)
            if ($ordonnance->getConsultation() === $this) {
                $ordonnance->setConsultation(null);
            }
        }

        return $this;
    }

    public function getIdRendezvous(): ?RendezVous
    {
      
        return $this->id_rendezvous;
        
    }

    public function setIdRendezvous(?RendezVous $id_rendezvous): self
    {
        $this->id_rendezvous = $id_rendezvous;

        return $this;
    }

}