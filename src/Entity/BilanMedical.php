<?php

namespace App\Entity;

use App\Repository\BilanMedicalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\DossierMedical;
use App\Entity\referencedColumnName;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\component\HttpFoundation\File\File ;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: BilanMedicalRepository::class)]
#[Vich\Uploadable]
class BilanMedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("BilanMedical")]
    public ?int $id = null;

   
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"Veuillez Entrer Les Informations Demandées")]
    #[Assert\Length(min:"3", minMessage: "Entrer un Antecedent au mini de 3 caracteres")]
    #[Groups("BilanMedical")]
    private ?string $Antecedents = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Veuillez Entrer Les Informations Demandées")]
    #[Assert\Length(min:"1", minMessage: "Entrer une correcte taille ")]
    #[Groups("BilanMedical")]
    private ?string $Taille = null;
       
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Veuillez Entrer Les Informations Demandées")]
    #[Assert\Length(min:"1", minMessage: "Entrer un correct poids")]
    #[Groups("BilanMedical")]
    private ?string $Poids = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"Veuillez Entrer Les Informations Demandées")]
    #[Assert\Length(min:"3", minMessage: "Entrer une correcte examen biologique ")]
    #[Groups("BilanMedical")]
    private ?string $ExamensBiologiques = null;


    #[ORM\ManyToOne(inversedBy: 'bilanMedicalal')]
    #[ORM\JoinColumn(onDelete: "CASCADE")] 
    private ?DossierMedical $dossierMedical = null;

    #[ORM\JoinColumn(onDelete: "CASCADE")] 
    #[ORM\ManyToOne(inversedBy: 'BilanMedical')]
    private ?User $user = null;

    
         
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAntecedents(): ?string
    {
        return $this->Antecedents;
    }

    public function setAntecedents(string $Antecedents): self
    {
        $this->Antecedents = $Antecedents;

        return $this;
    }

    public function getTaille(): ?string
    {
        return $this->Taille;
    }

    public function setTaille(string $Taille): self
    {
        $this->Taille = $Taille;

        return $this;
    }

    public function getPoids(): ?string
    {
        return $this->Poids;
    }

    public function setPoids(string $Poids): self
    {
        $this->Poids = $Poids;

        return $this;
    }

    public function getExamensBiologiques(): ?string
    {
        return $this->ExamensBiologiques;
    }

    public function setExamensBiologiques(string $ExamensBiologiques): self
    {
        $this->ExamensBiologiques = $ExamensBiologiques;

        return $this;
    }
    
    /**
     * @return File |null
     */


    public function getDossierMedical(): ?DossierMedical
    {
        return $this->dossierMedical;
    }

    public function setDossierMedical(?DossierMedical $dossierMedical): self
    {
        $this->dossierMedical = $dossierMedical;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

  
}
