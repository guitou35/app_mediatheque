<?php

namespace App\DataFixtures;

use App\Entity\Adresse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AdresseFixtures extends Fixture
{
    const FIRST_ADDRESS = 'adresse';

    public function load(ObjectManager $manager): void
    {
        $adresse = new Adresse();
        $adresse->setCodePostal(9000);
        $adresse->setRue('route de Rennes');
        $adresse->setVille('Rennes');
        $manager->persist($adresse);
        $this->setReference(self::FIRST_ADDRESS, $adresse);

        $manager->flush();
    }
}
