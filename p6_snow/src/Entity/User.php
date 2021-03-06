<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username", message="Ce pseudo existe déjà")
 * @UniqueEntity("email", message="Cet email existe déjà")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 3,max = 55,minMessage = "Prénom doit avoir un minimum de caractères 3 ", maxMessage = "Prénom doit avoir un maximum de caractères 55")
     * @Assert\Regex(pattern="/^[^0-9][a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ^'\x22][^'\x22&)(]+$/", message="Le prénom avec uniquement des lettres de l'alphabet")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 3,max = 55,minMessage = "Nom doit avoir un minimum de caractères 3 ", maxMessage = "Nom doit avoir un maximum de caractères 55")
     * @Assert\Regex(pattern="/^[^0-9][a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ^'\x22][^'\x22&)(]+$/", message="Le nom avec uniquement des lettres de l'alphabet")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email( message = "L'email n'est pas valide.")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $role;

    /**
     * @ORM\Column(type="boolean", length=4)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 3,max = 40,minMessage = "Pseudo doit avoir un minimum de caractères 3 ", maxMessage = "Pseudo doit avoir un mximum de caractères 40")
     * @Assert\Regex(pattern="/^[^0-9][a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ^'\x22][^'\x22&)(]+$/", message="Le nom avec uniquement des lettres de l'alphabet")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 4,minMessage = "Mot de passe minimum de caractères 4 ")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Confirmation de mot de passe non correcte")
     * @Assert\Length(min = 4,minMessage = "Mot de passe minimum de caractères 4 ")
     */
    private $repeatPassword;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Figure", mappedBy="editor")
     */
    private $figures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", orphanRemoval=true)
     * @Assert\Length(min = 50,minMessage = "Un minimum de 50 caractères requis")
     */
    private $comments;

    public function __construct()
    {
        $this->figures = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return 0 !== $this->status;
    }

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
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return $this
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;
        return $this; // permet l'enchaînement d'autres méthodes setXyz
    }

    /**
     * @return string|null
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     * @return $this
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPicture(): ?string
    {
        return $this->picture;
    }

    /**
     * @param string|null $picture
     * @return $this
     */
    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;
        return $this;
    }

    /**
     * @return array
     */
    public function getRole(): array
    {
        return [$this->role];
    }

    /**
     * @param string $role
     * @return $this
     */
    public function setRole(string $role): self
    {
        if ($role === null) {
            $this->role = ["ROLE_USER"];
        } else $this->role = $role;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }


    /**
     * @return string|null
     */
    public function getRepeatPassword(): ?string
    {
        return $this->repeatPassword;
    }

    /**
     * @param string $repeatPassword
     * @return $this
     */
    public function setRepeatPassword(string $repeatPassword): self
    {
        $this->repeatPassword = $repeatPassword;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles() // hérite de Userinterface
    {
        return [$this->role];
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Figure[]
     */
    public function getFigures(): Collection
    {
        return $this->figures;
    }

    public function addFigures(Figure $figures): self
    {
        if (!$this->figures->contains($figures)) {
            $this->figures[] = $figures;
            $figures->setEditor($this);
        }

        return $this;
    }

    public function removeFigures(Figure $figures): self
    {
        if ($this->figures->contains($figures)) {
            $this->figures->removeElement($figures);
            // set the owning side to null (unless alreadfigures changed)
            if ($figures->getEditor() === $this) {
                $figures->setEditor(null);
            }
        }

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
            $comment->setAuthor($this);
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
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }


}
