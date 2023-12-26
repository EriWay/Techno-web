<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'publications')]
class Publication
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 255,
        minMessage: 'The title must be at least {{ limit }} characters long',
        maxMessage: 'The title cannot be longer than {{ limit }} characters',
    )]
    private string $title;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private string $description;

    #[ORM\Column(type: 'string', nullable: true)]
    private string|null $image;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'publications')]
    #[ORM\JoinColumn(name: 'utilisateur_id', referencedColumnName: 'id')]
    private User $auteur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Publication
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Publication
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Publication
    {
        $this->description = $description;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): Publication
    {
        $this->image = $image;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): Publication
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getAuteur(): User
    {
        return $this->auteur;
    }

    public function setAuteur(User $auteur): Publication
    {
        $this->auteur = $auteur;
        return $this;
    }
}
