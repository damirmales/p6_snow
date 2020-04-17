<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $userID = [];

        for ($i = 0; $i < 3; $i++) {
            $prenom = $faker->firstName();
            $nom = $faker->lastName;
            $email = $faker->email;
            $pseudo = $faker->userName;

            $user = new User();
            array_push($userID, $user->getId());

            $user->setFirstname($prenom)
                ->setLastname($nom)
                ->setEmail($email)
                ->setRole("ROLE_USER")
                ->setStatus(1)
                ->setPicture("default-avatar.png")
                ->setToken(0)
                ->setUsername($pseudo)
                ->setPassword($this->encoder->encodePassword($user, "trick"));
            $manager->persist($user);
           
        }

        $manager->flush();
    }

}
