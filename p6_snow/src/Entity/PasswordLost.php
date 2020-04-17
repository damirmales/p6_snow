<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordLost
{
    /**
     * @return mixed
     * @Assert\Email(message = "L'email '{{ value }}' n'est pas valide")
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