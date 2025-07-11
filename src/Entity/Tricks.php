<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Repository\TricksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: TricksRepository::class)]
class Tricks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Comments>
     */
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'tricks', cascade: ['persist'], orphanRemoval: true)]
    private Collection $comments;

    /**
     * @var Collection<int, TricksPhoto>
     */
    #[ORM\OneToMany(targetEntity: TricksPhoto::class, mappedBy: 'tricks', orphanRemoval: true)]
    private Collection $tricksPhotos;

    /**
     * @var Collection<int, TricksVideo>
     */
    #[ORM\OneToMany(targetEntity: TricksVideo::class, mappedBy: 'tricks', orphanRemoval: true)]
    private Collection $tricksVideos;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $chapo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;


    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->comments = new ArrayCollection();
        $this->tricksPhotos = new ArrayCollection();
        $this->tricksVideos = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }


    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTricks($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTricks() === $this) {
                $comment->setTricks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TricksPhoto>
     */
    public function getTricksPhotos(): Collection
    {
        return $this->tricksPhotos;
    }

    public function addTricksPhoto(TricksPhoto $tricksPhoto): static
    {
        if (!$this->tricksPhotos->contains($tricksPhoto)) {
            $this->tricksPhotos->add($tricksPhoto);
            $tricksPhoto->setTricks($this);
        }

        return $this;
    }

    public function removeTricksPhoto(TricksPhoto $tricksPhoto): static
    {
        if ($this->tricksPhotos->removeElement($tricksPhoto)) {
            // set the owning side to null (unless already changed)
            if ($tricksPhoto->getTricks() === $this) {
                $tricksPhoto->setTricks(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TricksVideo>
     */
    public function getTricksVideos(): Collection
    {
        return $this->tricksVideos;
    }

    public function addTricksVideo(TricksVideo $tricksVideo): static
    {
        if (!$this->tricksVideos->contains($tricksVideo)) {
            $this->tricksVideos->add($tricksVideo);
            $tricksVideo->setTricks($this);
        }

        return $this;
    }

    public function removeTricksVideo(TricksVideo $tricksVideo): static
    {
        if ($this->tricksVideos->removeElement($tricksVideo)) {
            // set the owning side to null (unless already changed)
            if ($tricksVideo->getTricks() === $this) {
                $tricksVideo->setTricks(null);
            }
        }

        return $this;
    }

    public function getChapo(): ?string
    {
        return $this->chapo;
    }

    public function setChapo(string $chapo): static
    {
        $this->chapo = $chapo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUri()
    {
        $name = strtolower($this->name);
        $name = str_replace(' ', '-', $name);
        return $name;
    }
}
