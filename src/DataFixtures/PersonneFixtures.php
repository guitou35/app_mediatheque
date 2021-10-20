<?php

namespace App\DataFixtures;

use App\Entity\Adresse;
use App\Entity\Personne;
use App\Entity\Role;
use App\Repository\AdresseRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PersonneFixtures extends Fixture implements FixtureGroupInterface
{
    private $passwordHasher;


    public function __construct(UserPasswordHasherInterface $passwordHasher){
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        // set the password for the Admin
        $password = 'Admin';

        $adresse = new Adresse();
        $adresse->setCodePostal(9000);
        $adresse->setRue('route de Rennes');
        $adresse->setVille('Rennes');
        $manager->persist($adresse);

        $admin = new Personne();
        $admin->setCompteActived(1);
        $admin->setDateNaissance( new \DateTime('now'));
        $admin->setNom('Admin');
        $admin->setEmail('admin@test.fr');
        $admin->setPassword($password);
        $admin->setPrenom('admin');
        $admin->setRoles(['ROLE_SUPER_ADMIN']);
        $admin->setAdresse($adresse);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $password));

        $manager->persist($admin);
        $manager->flush();

    }


    public static function getGroups(): array
    {
        return ['admin'];
    }
}
