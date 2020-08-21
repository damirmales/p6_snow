<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FigureRepository")
 * @UniqueEntity(
 *     fields={"title"},
 *     message="Ce titre est déjà utilisé.")
 * @ORM\HasLifecycleCallbacks()
 */
class Figure
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="4",minMessage="Le titre doit contenir au moins 4 caractères")
     * @Assert\Regex(pattern="/^[^0-9][a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ^'\x22][^'\x22&)(]+$/",
     *      message="Le titre avec uniquement des lettres de l'alphabet",
     *     )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $featureImage;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min="50", minMessage="Doit contenir une description de 50 caractères minimum")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $figGroup;

    /**
     * @ORM\Column(type="datetime")
     */
    private $CreateDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="figures", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $editor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="figure", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="figure", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="figure", orphanRemoval=true)
     */
    private $videos;

    /**
     * Figure constructor.
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function defineSlug() // linked to HasLifecycleCallbacks
    {
        $trimStr = $this->trimSpecialChars($this->getTitle());
        $replaceSpace = (str_replace(' ', '-', $trimStr));
        $this->setSlug('snowboard' . '-' . 'figure' . '-' . $replaceSpace);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFeatureImage(): ?string
    {
        return $this->featureImage;
    }

    /**
     * @param string|null $featureImage
     * @return $this
     */
    public function setFeatureImage(?string $featureImage): self
    {
        $this->featureImage = $featureImage;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFigGroup(): ?string
    {
        return $this->figGroup;
    }

    /**
     * @param string $figGroup
     * @return $this
     */
    public function setFigGroup(string $figGroup): self
    {
        $this->figGroup = $figGroup;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->CreateDate;
    }

    /**
     * @param \DateTimeInterface $CreateDate
     * @return $this
     */
    public function setCreateDate(\DateTimeInterface $CreateDate): self
    {
        $this->CreateDate = $CreateDate;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    /**
     * @param \DateTimeInterface|null $updateDate
     * @return $this
     */
    public function setUpdateDate(?\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEditor()
    {
        return $this->editor;
    }

    /**
     * @param $editor
     * @return $this
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;
        return $this;
    }


    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setFigure($this);
        }
        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getFigure() === $this) {
                $comment->setFigure(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    /**
     * @param Photo $photo
     * @return $this
     */
    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setFigure($this);
        }
        return $this;
    }

    /**
     * @param Photo $photo
     * @return $this
     */
    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getFigure() === $this) {
                $photo->setFigure(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    /**
     * @param Video $video
     * @return $this
     */
    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setFigure($this);
        }
        return $this;
    }

    /**
     * @param Video $video
     * @return $this
     */
    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getFigure() === $this) {
                $video->setFigure(null);
            }
        }
        return $this;
    }

    /**
     * @param $text
     * @return string|string[]|null
     */
    public function trimSpecialChars($text)
    {
        $char = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N',
            '/&/' => '',
        );
        return preg_replace(array_keys($char), array_values($char), $text);
    }

}
