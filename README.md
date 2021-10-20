# App Mediatheque

## Préalable
Ce projet nécessite les logiciels suivants sur votre ordinateur : 
``composer, npm et Symfony CLI`` ainsi que d'avoir une base de données local.

Liens de téléchargement :

- https://symfony.com/download
- https://getcomposer.org/download/
- https://nodejs.org/en/download/

## Installation en local

1 - A la racine du projet veuillez remplir le fichier ``.env``
en remplaçant la variable ``DATABASE_URL`` par celle de votre BDD

2 - Pour une première installation veuillez lancer la commande dans le terminal: 
```bash
composer install
```
Cette commande va ainsi installer les dépendances du projet ainsi que de lancer les 
``migrations`` et la compilation des assets avec ``npm``

3 - Lancer le serveur avec la commande
````bash
symfony server:start
````
 L'application est maintenant accéssible à l'adresse http://localhost:8000

## Création d'un compte admin
allez dans ``src/DataFixtures/PersonneFixtures.php`` puis remplir la ligne 27  ``$password =``
Puis lancer la commande : 
````bash
php bin/console doctrine:fixtures:load --group=admin
````

## Création d'un jeu de données pour les tests
Lancer la commande suivante : 
````bash
php bin/console doctrine:fixtures:load --append --group=populate
````
 
