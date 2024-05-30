<?php

namespace App\Entity;

use App\Repository\TricksPhotoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TricksPhotoRepository::class)]
class TricksPhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $path = null;

    #[ORM\Column]
    private ?bool $isFirst = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'tricksPhotos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?tricks $tricks = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function isFirst(): ?bool
    {
        return $this->isFirst;
    }

    public function setFirst(bool $isFirst): static
    {
        $this->isFirst = $isFirst;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTricks(): ?tricks
    {
        return $this->tricks;
    }

    public function setTricks(?tricks $tricks): static
    {
        $this->tricks = $tricks;

        return $this;
    }
}
