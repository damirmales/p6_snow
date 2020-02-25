<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class ResetPassword
{

    // private $email;

    /**
     * @Assert\Length(min=5, minMessage = "Il faut au minimum 5 caractères")
     *
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passe doivent être identiques")
     */
    private $repeatPassword;


    /*  public function getEmail(): ?string
      {
          return $this->email;
      }

      public function setEmail(string $email): self
      {
          $this->email = $email;

          return $this;
      }
  */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getRepeatPassword(): ?string
    {
        return $this->repeatPassword;
    }


    public function setRepeatPassword(string $repeatPassword)
    {
        $this->repeatPassword = $repeatPassword;

        return $this;
    }
}
