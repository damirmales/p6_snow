<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class FigureFixtures extends Fixture //implements DependentFixtureInterface
{

    public function load(ObjectManager $entityManager)
    {
        $faker = Factory::create('fr-FR');

        $editor = [];
        $users = $entityManager->getRepository(User::class)->findAll();
        foreach ($users as $user) {
            array_push($editor, $user);
        }

        $groupFig = ['slide', 'grab', 'rotation', 'flip'];

        for ($i = 0; $i < 4; $i++) {
            $titre = $faker->word;
            $description = $faker->sentence(10);

            $fig = new Figure();
            $fig->setTitle($titre)
                ->setSlug($fig->getTitle())
                ->setFigGroup($groupFig[array_rand($groupFig)])
                ->setFeatureImage($i . ".jpg")
                ->setDescription($description)
                ->setEditor($editor[array_rand($editor)])
                ->setCreateDate(DateTime::createFromFormat('j-M-Y', '15-Feb-2009'));
            $entityManager->persist($fig);
        }
        $entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    /* public function getDependencies()
     {
         return array(
             UserFixtures::class,
         );
     }*/
}
