<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("username", message="Ce pseudo existe déjà")
 * @UniqueEntity("email", message="Cet email existe déjà")
 */
class User implements \Symfony\Component\Security\Core\User\UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 3,max = 40,minMessage = "Minimum de caractères 3 ", maxMessage = "Maximum de caractères 40")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 3,max = 40,minMessage = "Minimum de caractères 3 ", maxMessage = "Maximum de caractères 40")
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
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min = 3,max = 40,minMessage = "Minimum de caractères 3 ", maxMessage = "Maximum de caractères 40")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Confirmation de mot de passe non correcte")
     * @Assert\Length(min = 3,max = 40,minMessage = "Minimum de caractères 3 ", maxMessage = "Maximum de caractères 40")
     */
    private $repeatPassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getRole(): array
    {
        return [$this->role];
    }

    public function setRole(string $role): self
    {
        if ($role === null) {
            $this->role = ["ROLE_USER"];

        } else $this->role = $role;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }


    public function getRepeatPassword(): ?string
    {
        return $this->repeatPassword;
    }

    public function setRepeatPassword(string $repeatPassword): self
    {
        $this->repeatPassword = $repeatPassword;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles() // herite de Userinterface
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
}
