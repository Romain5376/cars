<?php

namespace App\DataFixtures;

use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Voiture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $marque1 = new Marque();
        $marque1->setLibelle("Dazma");
        $manager->persist($marque1);

        $marque2 = new Marque();
        $marque2->setLibelle("Rubasu");
        $manager->persist($marque2);

        $marque3 = new Marque();
        $marque3->setLibelle("Tayoto");
        $manager->persist($marque3);

        $marque4 = new Marque();
        $marque4->setLibelle("Dahon");
        $manager->persist($marque4);

        $modele1 = new Modele();
        $modele1->setLibelle("Cygale")
        ->setImage("modele1.jpg")
        ->setPrixMoyen(20000)
        ->setMarque($marque2);
        $manager->persist($modele1);

        $modele2 = new Modele();
        $modele2->setLibelle("5-mx")
        ->setImage("modele2.jpg")
        ->setPrixMoyen(25000)
        ->setMarque($marque1);
        $manager->persist($modele2);

        $modele3 = new Modele();
        $modele3->setLibelle("Prasu")
        ->setImage("modele3.jpg")
        ->setPrixMoyen(50000)
        ->setMarque($marque3);
        $manager->persist($modele3);

        $modele4 = new Modele();
        $modele4->setLibelle("2000s")
        ->setImage("modele4.jpg")
        ->setPrixMoyen(30000)
        ->setMarque($marque4);
        $manager->persist($modele4);

        $modele5 = new Modele();
        $modele5->setLibelle("Zapreim")
        ->setImage("modele6.jpg")
        ->setPrixMoyen(40000)
        ->setMarque($marque1);
        $manager->persist($modele5);

        $modele6 = new Modele();
        $modele6->setLibelle("7-rx")
        ->setImage("modele1.jpg")
        ->setPrixMoyen(20000)
        ->setMarque($marque1);
        $manager->persist($modele6);

        $modeles = [$modele1, $modele2, $modele3, $modele4, $modele5, $modele6];

        $faker = \Faker\Factory::create('fr_FR');
        foreach($modeles as $m){
            $rand = rand(1,3);
            for($i=1;$i <= $rand; $i++){
                $voiture = new Voiture();
                $voiture->setImmatriculation($faker->regexify("[A-Z]{2}-[0-9]{3}-[A-Z]{2}"))
                    ->setNbPortes($faker->randomElement($array = array(3,5)))
                    ->setAnnee($faker->numberBetween($min=1990, $max=2020))
                    ->setModele($m);
                $manager->persist($voiture);
            }
        }

        $manager->flush();
    }
}
