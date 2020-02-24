<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class ResetPassword
{
    /**
     *
     */
    private $email;

    /**
     *
     */
    private $password;

    /**
     *
     */
    private $repeatPassword;


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
}
