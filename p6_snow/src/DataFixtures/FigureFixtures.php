<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\Media;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
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

        for ($i = 0; $i < 11; $i++) {

            $user = new User();
            $user->setFirstname("toto$i")
                ->setLastname("titi")
                ->setEmail("tto$i@ff.fr")
                ->setRole("ROLE_USER")
                ->setStatus(1)
                ->setPicture("https://via.placeholder.com/150")
                ->setToken("46446545465jjj")
                ->setUsername("toto$i")
                ->setPassword($this->encoder->encodePassword($user, "toto"));
            $entityManager->persist($user);

            $fig = new Figure();

            $fig->setTitle("Figure $i")
                ->setSlug("slug-fig-$i")
                ->setFigGroup($group[array_rand($group)])
                ->setFeatureImage("https://via.placeholder.com/150x150")
                ->setDescription("Description de la figure $i")
                ->setEditor($user)
                ->setCreateDate(DateTime::createFromFormat('j-M-Y', '15-Feb-2009'));

            $entityManager->persist($fig);

          
            $img = new Media();
            $img->setTitle("Media $i")
                ->setType("photo")
                ->setUrl("https://via.placeholder.com/150")
                ->setFigure($fig
                )
                ->setCreateDate(DateTime::createFromFormat('j-M-Y', '15-Feb-2009'));

            $entityManager->persist($img);


            $entityManager->flush();

        }

    }
}
