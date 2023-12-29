<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Repository\ImageRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\Table(name: 'images')]
class Image
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]

    private string $nom;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]

    private string $encodage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEncodage(): string
    {
        return $this->encodage;
    }
    public function setEncodage(string $encodage): void
    {
        $this->encodage = $encodage;
    }
}