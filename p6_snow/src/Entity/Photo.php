<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @UniqueEntity(
 *     fields={"title"},
 *     message="Ce titre est déjà utilisé.")
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=55, unique=true)
     * @Assert\Length(min="4",max="15",minMessage="Le titre doit contenir au moins 4 caractères,
     * maxMessage=""Le titre doit contenir au max 15 caractères")
     * @Assert\Regex(pattern="/^[^0-9].[a-zA-Z^'\x22].[^'\x22]+$/", message="Le titre avec uniquement des lettres de l'alphabet")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Figure", inversedBy="photos")
     * @ORM\JoinColumn(name="figure_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $figure;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var ?File
     */
    protected $file;

    /**
     * @return int|null
     */
    public function getId(): ?int
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
     * @return \DateTimeInterface|null
     */
    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTimeInterface $createdDate
     * @return $this
     */
    public function setCreatedDate(\DateTimeInterface $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @return Figure|null
     */
    public function getFigure(): ?Figure
    {
        return $this->figure;
    }

    /**
     * @param Figure|null $figure
     * @return $this
     */
    public function setFigure(?Figure $figure): self
    {
        $this->figure = $figure;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return $this
     */
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }
}
