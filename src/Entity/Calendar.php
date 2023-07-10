<?php

namespace App\Entity;

use App\Entity\User;

use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\UserType;

use App\Repository\CalendarRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CalendarRepository::class)]
class Calendar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("calendar")]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Title doit etre non vide")]
    #[Assert\Regex("/^[a-z]|[1-9]|[A-Z]*$/")]
    #[Groups("calendar")]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: " Date de debut doit etre non vide")]
    #[Assert\GreaterThan('today')]
    #[Groups("calendar")]
    private ?\DateTimeInterface $start = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: " Date de fin doit etre non vide")]
    #[Assert\GreaterThan('today')]
    #[Groups("calendar")]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("calendar")]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups("calendar")]
    private ?bool $all_day = null;

    #[ORM\Column(length: 7)]
    private ?string $background_color = null;

    #[ORM\Column(length: 7)]
    private ?string $border_color = null;

    #[ORM\Column(length: 7)]
    private ?string $text_color = null;

    #[ORM\ManyToOne(inversedBy: 'calendars')]
    private ?User $User = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isAllDay(): ?bool
    {
        return $this->all_day;
    }

    public function setAllDay(bool $all_day): self
    {
        $this->all_day = $all_day;

        return $this;
    }

    public function getBackgroundColor(): ?string
    {
        return $this->background_color;
    }

    public function setBackgroundColor(string $background_color): self
    {
        $this->background_color = $background_color;

        return $this;
    }

    public function getBorderColor(): ?string
    {
        return $this->border_color;
    }

    public function setBorderColor(string $border_color): self
    {
        $this->border_color = $border_color;

        return $this;
    }

    public function getTextColor(): ?string
    {
        return $this->text_color;
    }

    public function setTextColor(string $text_color): self
    {
        $this->text_color = $text_color;

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
