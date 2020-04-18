<?php

namespace App\DataFixtures;

use App\Entity\Figure;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class FigureFixtures extends Fixture implements DependentFixtureInterface
{


    public function load(ObjectManager $manager)
    {
        $groupFig = ['slide', 'grab', 'rotation', 'flip'];
        $editor = [];
        $titres = ["50-50box", "back180", "backboard", "front180", "frontside360", "frontsideshifty", "indygrab", "noseroll", "ollie", "press"];
        $descriptions = ["Un 50/50 ce qui signifie que vous glissez dans l’axe de la box ou du rail",
            "180 back ou mais lorsque tu est a 90° c'est le nose de la board qui vien toucher ce coller a la neige ",
            "Lorsque vous lancez des virages sur votre snowboard, commencez toujours par tourner la tête",
            "Pour une rotation dite \"ouverte\" (180° frontside), votre regard devra être porté dans le sens de la glisse",
            "On arrive les jambes bien fléchies, les épaules dans l’axe de la board, le regard pointé vers le bout du kicker",
            "La main arrière vient graber la carre frontside entre les pieds. Sur un saut droit c’est un Indy Grab",
            "Vous allez combiner la technique d'un Switch Frontside 360​à contre-rotation, avec les mouvements de haute pression",
            "Appui leger sur le nose ( avant de la board ) l'arriere est en l'air",
            "Le Ollie est une impulsion  avec déformation de la planche qui permet de faire un saut",
            "Les press sont les appuis sur les spatules, on peut les faire dans l’axe ou en travers, ce sont de bons tricks pour apprendre à bien maitriser sa board"];

        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            array_push($editor, $user);
        }
        for ($i = 0; $i < 10; $i++) {
            $trick = new Figure();
            $trick->setTitle($titres[$i])
                ->setSlug('slug')
                ->setFeatureImage($i . ".jpg")
                ->setDescription($descriptions[$i])
                ->setCreateDate(new DateTime())
                ->setUpdateDate(new DateTime());


            $manager->persist($trick);

            $trick->setFigGroup($groupFig[array_rand($groupFig)]);
            $trick->setEditor($editor[array_rand($editor)]);
        }
        $manager->flush();


    }

    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return array(
            UserFixtures::class,
        );
    }
}
