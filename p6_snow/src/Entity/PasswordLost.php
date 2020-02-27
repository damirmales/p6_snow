<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordLost
{
    /**
     * @return mixed
     * @Assert\Length(min = 3,max = 40,minMessage = "Minimum de caractères 3 ", maxMessage = "Maximum de caractères 40")
     */
    private $username;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }


}