<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Figure;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');
        $author = [];
        $users = $manager->getRepository(User::class)->findAll();
        $fig = [];
        $figures = $manager->getRepository(Figure::class)->findAll();

        foreach ($users as $user) {
            array_push($author, $user);
        }
        foreach ($figures as $figure) {
            array_push($fig, $figure);
        }

        for ($i = 0; $i < 2; $i++) {

            $content = $faker->sentence(10);
            $comment = new Comment();
            $comment->setContent($content)
                ->setFigure($fig[array_rand($fig)])
                ->setAuthor($author[array_rand($author)])
                ->setCreateDate(DateTime::createFromFormat('j-M-Y', '15-Feb-2009'));
            $manager->persist($comment);
        }

        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return array(
            FigureFixtures::class, UserFixtures::class
        );
    }

}
