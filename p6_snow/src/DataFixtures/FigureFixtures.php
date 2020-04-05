<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Photo;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FigureFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $entityManager)
    {

        $editor = [10, 23, 53];
        $group = ['slide', 'grab', 'rotation', 'flip'];

        for ($i = 0; $i < 12; $i++) {

            $user = new User();
            $user->setFirstname("user$i")
                ->setLastname("titi")
                ->setEmail("user$i@ff.fr")
                ->setRole("ROLE_USER")
                ->setStatus(1)
                ->setPicture("https://via.placeholder.com/150")
                ->setToken(0)
                ->setUsername("user$i")
                ->setPassword($this->encoder->encodePassword($user, "toto"));
            $entityManager->persist($user);

            $fig = new Figure();

            $fig->setTitle("Figure $i")
                ->setSlug($this->getTitle())
                ->setFigGroup($group[array_rand($group)])
                ->setFeatureImage("https://via.placeholder.com/150x150")
                ->setDescription("Description de la figure $i")
                ->setEditor($user)
                ->setCreateDate(DateTime::createFromFormat('j-M-Y', '15-Feb-2009'));
            $entityManager->persist($fig);


            $img = new Photo();
            $img->setTitle("Media $i")
                ->setFilename("https://via.placeholder.com/150")
                ->setFigure($fig)
                ->setCreatedDate(DateTime::createFromFormat('j-M-Y', '15-Feb-2009'));
            $entityManager->persist($img);

            $entityManager->flush();

        }

    }
}
