<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class ResetPassword
{

    /**
     * @Assert\Length(min=4, minMessage = "Il faut au minimum 4 caractères")
     *
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Les mots de passe doivent être identiques")
     */
    private $repeatPassword;


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
    public function setPassword(string $password)
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
    public function setRepeatPassword(string $repeatPassword)
    {
        $this->repeatPassword = $repeatPassword;

        return $this;
    }
}
