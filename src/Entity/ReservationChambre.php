<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ReservationChambreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationChambreRepository::class)]
class ReservationChambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_admission = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_sortie = null;

    #[ORM\ManyToOne(inversedBy: 'reservationChambres')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Chambre $chambre = null;

    #[ORM\ManyToOne(inversedBy: 'reservationChambres')]
    private ?User $patient = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAdmission(): ?\DateTimeInterface
    {
        return $this->date_admission;
    }

    public function setDateAdmission(\DateTimeInterface $date_admission): self
    {
        $this->date_admission = $date_admission;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->date_sortie;
    }

    public function setDateSortie(?\DateTimeInterface $date_sortie): self
    {
        $this->date_sortie = $date_sortie;

        return $this;
    }
    public function getNbJoursHospitalisation(): int
    {
        $dateAdmission = $this->getDateAdmission();
        $dateSortie = $this->getDateSortie();
        
        if (!$dateAdmission || !$dateSortie) {
            return 0;
        }
        
        $diff = $dateSortie->diff($dateAdmission);
        return $diff->days;
    }
    
    public function getChambre(): ?Chambre
    {
        return $this->chambre;
    }

    public function setChambre(?Chambre $chambre): self
    {
        if($this->getDateSortie()!=null){
            $this->chambre = null;
        }else{
            $this->chambre = $chambre;
        }
    
        return $this;
    }
    
    

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }
    public function addUser(User $user): self
{
    $this->patient = $user;
    return $this;
}
public function getUser(): ?User
{
    return $this->patient;
}

}
