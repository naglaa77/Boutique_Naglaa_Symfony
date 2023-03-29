# Boutique_Naglaa_Symfony

Dans le cadre de ma formation en autodidate et pour me preparer au différents entretiens que je devrais passer dans ma recherche d'emploi je me suis lancé comme défi de réaliser les base de un simple projet qui est une application e-commerce simple développée en utilisant le framework Symfony 6 et le moteur de templates Twig. Le but de ce projet est de fournir une base solide pour la construction d'une boutique en ligne en utilisant des fonctionnalités telles que la gestion des produits, des catégories, des commandes et des utilisateurs.

## Configuration requise

- PHP 8.0 ou supérieur
- Composer
- MySQL 5.7 ou supérieur (ou une base de données compatible Doctrine)
- Symfony CLI (optionnel)

## Installation

1. Clonez ce dépôt :

`git clone https://github.com/naglaa77/Boutique_Naglaa_Symfony.git
cd Boutique_Naglaa_Symfony `

2.  Installez les dépendances PHP à l'aide de Composer :

        `composer install`

3.  Configurez votre base de données en modifiant le fichier .env:

`DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"`

4.  Générez les assets frontend :

        `yarn encore dev`

5.  Créez la base de données et le schéma :

        `php bin/console doctrine:database:create
        php bin/console doctrine:schema:create`

6.  (Optionnel) Chargez les fixtures pour peupler la base de données avec des données d'exemple :

        `php bin/console doctrine:fixtures:load`

## Lancement de l'application

Démarrez le serveur web intégré de Symfony :

        `php bin/console server:run`

Visitez l'application dans votre navigateur à l'adresse http://127.0.0.1:8000/.

## Fonctionnalités

- Gestion des produits
- Gestion des catégories
- Gestion des commandes
- Gestion des utilisateurs
- Panier
- Passage de commande
- Authentification et autorisation
- Interface d'administration

## Technologies utilisées

- Symfony 6
- Twig
- Doctrine ORM
- Bootstrap 5

## Contribution

Les contributions sont les bienvenues! N'hésitez pas à soumettre des problèmes ou des demandes de fonctionnalités via le système de suivi des problèmes GitHub. Vous pouvez également soumettre des pull requests pour contribuer directement au projet.
