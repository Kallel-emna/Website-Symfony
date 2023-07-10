<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\referencedColumnName;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Assert\NotBlank(message: " Nom doit etre non vide")]
    #[Assert\Length(min : 4,minMessage:" Entrer un nom au mini de 4 caracteres")]
    #[ORM\Column(length: 255)]
    private ?string $nom_produit = null;

    #[Assert\NotBlank(message: " Quantité doit etre non vide")]
    #[ORM\Column]
    #[Assert\Positive]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(name: "categorie_id",referencedColumnName: "id")]
    private ?Categorie $Categorie = null;

    #[Assert\NotBlank(message: " Prix doit etre non vide")]
    #[ORM\Column]
    #[Assert\Positive]
    private ?int $prix = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): self
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function __toString()
    {
        return(string) $this->getNomProduit();
    }

    public function isLowQuantity(): bool
    {
      return $this->quantite < 3;
    }
}
