<?php

namespace App\Entity;

use App\Repository\DossierMedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Ordonnance;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DossierMedicalRepository::class)]
class DossierMedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("DossierMedical")]
    public ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"Veuillez Entrer Les Informations Demandées")]
    #[Assert\Length(min:"3", minMessage: "Entrer une certificat correcte ")]
    #[Groups("DossierMedical")]
    private ?string $Certificat;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Veuillez Entrer Les Informations Demandées")]
    #[Assert\Length(min:"1", minMessage: "Entrer le groupe sanguin correct ")]
    #[Groups("DossierMedical")]
    private ?string $GroupeSanguin ;

    #[ORM\OneToMany(mappedBy: 'dossierMedical', targetEntity: BilanMedical::class)]
    private Collection $bilanMedicalal;

    #[ORM\ManyToOne(inversedBy: 'dossierMedicals')]
    #[ORM\JoinColumn(onDelete: "CASCADE")] 
    private ?Ordonnance $ordonnance ;

   
    public function __construct()
    {
        $this->bilanMedicalal = new ArrayCollection();
     
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCertificat(): ?string
    {
        return $this->Certificat;
    }

    public function setCertificat(string $Certificat): self
    {
        $this->Certificat = $Certificat;

        return $this;
    }

    public function getGroupeSanguin(): ?string
    {
        return $this->GroupeSanguin;
    }

    public function setGroupeSanguin(string $GroupeSanguin): self
    {
        $this->GroupeSanguin = $GroupeSanguin;

        return $this;
    }

    /**
     * @return Collection<int, BilanMedical>
     */
    public function getBilanMedicalal(): Collection
    {
        return $this->bilanMedicalal;
    }

    public function addBilanMedicalal(BilanMedical $bilanMedicalal): self
    {
        if (!$this->bilanMedicalal->contains($bilanMedicalal)) {
            $this->bilanMedicalal->add($bilanMedicalal);
            $bilanMedicalal->setDossierMedical($this);
        }

        return $this;
    }

    public function removeBilanMedicalal(BilanMedical $bilanMedicalal): self
    {
        if ($this->bilanMedicalal->removeElement($bilanMedicalal)) {
            // set the owning side to null (unless already changed)
            if ($bilanMedicalal->getDossierMedical() === $this) {
                $bilanMedicalal->setDossierMedical(null);
            }
        }

        return $this;
    }

    public function getOrdonnance(): ?Ordonnance
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?Ordonnance $ordonnance): self
    {
        $this->ordonnance = $ordonnance;

        return $this;
    }

    
}
