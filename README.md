# App Mediatheque

## Préalable
Ce projet nécessite les logiciels suivants sur votre ordinateur : 
``composer, npm, Symfony CLI et Docker`` ainsi que d'avoir une base de données local.

Liens de téléchargement :

- https://symfony.com/download
- https://getcomposer.org/download/
- https://nodejs.org/en/download/

## Création d'un compte admin
Opération à réaliser avant l'installation en local

allez dans ``src/DataFixtures/PersonneFixtures.php`` puis remplir la ligne 27  ``$password =``


## Installation en local

1 - A la racine du projet excécuter la commande suivante : 
````bash
docker-compose up -d
````

2 - Lancer le serveur avec la commande
````bash
symfony serve -d
````
L'application est maintenant accéssible à l'adresse http://localhost:8000 généralement

3- Générer la BDD et les données de test avec les commandes
````bash
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load
````

4 - Pour une première installation veuillez lancer la commande dans le terminal: 
```bash
composer install
```
Cette commande va ainsi installer les dépendances du projet 

5- lancer la commande pour charger les fichier assets webpack-encore
````bash
npm install
npm run build
````


## Création d'un jeu de données pour les tests
La création du jeu de donnée se fait lors de la première installation
 
