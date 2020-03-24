<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordLost
{
    /**
     * @return mixed
     * @Assert\Email(message = "Le format '{{ value }}' n'est pas un email valide")
     */
    private $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }


}