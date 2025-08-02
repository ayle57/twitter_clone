<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[Vich\Uploadable()]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le contenu du post ne peut pas être vide.")]
    #[Assert\Length(
        min: 1,
        max: 1000,
        minMessage: "Le contenu doit contenir au moins {{ limit }} caractère.",
        maxMessage: "Le contenu ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $content = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La date de création est requise.")]
    #[Assert\Type(\DateTimeImmutable::class)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La date de mise à jour est requise.")]
    #[Assert\Type(\DateTimeImmutable::class)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Like>
     */
    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'post', cascade: ['remove'])]
    private Collection $likes;

    /**
     * @var Collection<int, Hashtag>
     */
    #[ORM\ManyToMany(targetEntity: Hashtag::class, inversedBy: 'posts')]
    #[ORM\JoinTable(name: 'post_hashtag')]
    private Collection $tags;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Le post doit avoir un auteur.")]
    private ?User $author = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $thumbnail = null;

    #[Vich\UploadableField(mapping: 'posts', fileNameProperty: 'thumbnail')]
    #[Assert\Image]
    private ?File $thumbnailFile = null;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
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

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setPost($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            if ($like->getPost() === $this) {
                $like->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hashtag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Hashtag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addPost($this); // synchronise les deux côtés
        }

        return $this;
    }

    public function removeTag(Hashtag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removePost($this); // synchronise les deux côtés
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(?File $thumbnailFile): static
    {
        $this->thumbnailFile = $thumbnailFile;
        return $this;
    }
}
