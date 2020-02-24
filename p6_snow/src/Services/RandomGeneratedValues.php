<?php


namespace App\Services;


class RandomGeneratedValues
{
    private $randomValue;

    public function generateRandomString()
    {

        $this->randomValue = sha1(random_bytes(12));


    }

    /**
     * @return mixed
     */
    public function getRandomValue()
    {
        return $this->randomValue;
    }

    /**
     * @param mixed $randomValue
     */
    public function setRandomValue($randomValue): void
    {
        $this->randomValue = $randomValue;
    }

}