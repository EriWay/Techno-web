<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'utilisateurs')]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Your name must be at least {{ limit }} characters long',
        maxMessage: 'Your name cannot be longer than {{ limit }} characters',
    )]
    private string $nom;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Your first name must be at least {{ limit }} characters long',
        maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
    )]
    private string $prenom;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private string $email;

    #[ORM\Column(type:'string')]
    #[Assert\NotBlank]
    private string $mot_de_passe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->nom;
    }

    public function setName(string $name): User
    {
        $this->nom = $name;
        return $this;
    }

    public function getFirstname(): string
    {
        return $this->prenom;
    }

    public function setFirstname(string $firstname): User
    {
        $this->prenom = $firstname;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getmotDePasse(): string
    {
        return $this->mot_de_passe;
    }
    public function setMotDePasse(string $motDePasse): User
    {
        $this->mot_de_passe = $motDePasse;
        return $this;
    }
    public function __toString(): string
    {
        return sprintf('%s %s', $this->nom, $this->prenom);
    }


}