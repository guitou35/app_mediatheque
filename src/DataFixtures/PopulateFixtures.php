<?php

namespace App\DataFixtures;

use App\Entity\Adresse;
use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Entity\Personne;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PopulateFixtures extends Fixture implements FixtureGroupInterface
{

    private $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        $auteurs = [];
        for ($i = 0; $i < 7; $i++) {
            $auteurs[$i] = new Auteur();
            $auteurs[$i]->setNom($faker->lastName);
            $auteurs[$i]->setPrenom($faker->firstName);
            $auteurs[$i]->setDescription($faker->sentence(9, true));
            $manager->persist($auteurs[$i]);
        }

        $genre = [];
        for ($i = 0; $i < 7; $i++) {
            $genre[$i] = new Genre();
            $genre[$i]->setNom($faker->sentence(1, true));
            $manager->persist($genre[$i]);
        }

        $livres = [];
        for ($i = 0; $i < 20; $i++) {
            $livres[$i] = new Livre();
            $livres[$i]->setTitre($faker->sentence(4, true));
            $livres[$i]->setDescription($faker->sentence(12, true));
            $livres[$i]->setAuteur($auteurs[$i % 3]);
            $livres[$i]->setGenre($genre[$i % 3]);
            $livres[$i]->setStatut('dispo');
            $livres[$i]->setDateParution($faker->dateTime);

            $manager->persist($livres[$i]);
        }

        $adresse = [];
        for($i = 0; $i < 8; $i++){
            $adresse[$i] = new Adresse();
            $adresse[$i]->setVille($faker->city);
            $adresse[$i]->setRue($faker->address);
            $adresse[$i]->setCodePostal($faker->numberBetween(10000,99999));
            $manager->persist($adresse[$i]);
        }

        $personne = [];
        for($i = 0; $i < 20; $i++){
            $personne[$i] = new Personne();
            $personne[$i]->setNom($faker->lastName);
            $personne[$i]->setPrenom($faker->firstName);
            $personne[$i]->setCompteActived(0);
            $personne[$i]->setPassword($this->passwordHasher->hashPassword($personne[$i],'Test12345'));
            $personne[$i]->setDateNaissance($faker->dateTimeBetween("-30 years","-10 years"));
            $personne[$i]->setRoles(["ROLE_USER"]);
            $personne[$i]->setAdresse($adresse[$i % 3]);
            $personne[$i]->setEmail($faker->email);
            $manager->persist($personne[$i]);
        }

        $manager->flush();

    }

    public static function getGroups(): array
    {
        return ['populate'];
    }
}