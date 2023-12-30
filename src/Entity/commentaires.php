<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Repository\CommentaireRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
#[ORM\Table(name: 'commentaires')]
class Commentaire
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\ManyToOne(targetEntity: Meme::class, inversedBy: 'commentaires')]
    #[ORM\JoinColumn(name: 'meme_id', referencedColumnName: 'id')]
    private Meme $meme;

    #[ORM\Column(type: 'integer')]
    private int $userId;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $date;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Commentaire
    {
        $this->id = $id;
        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): Commentaire
    {
        $this->userId = $userId;
        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): Commentaire
    {
        $this->date = $date;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): Commentaire
    {
        $this->commentaire = $commentaire;
        return $this;
    }
}
