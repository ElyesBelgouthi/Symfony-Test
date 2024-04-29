# Projet Symfony 6

Introduction : Ce projet est basé sur Symfony 6 et présente un exemple 
de mise en œuvre d'une application web de blog. 
L'objectif est de permettre aux utilisateurs de consulter des articles, 
d'en créer de nouveaux,
et de les modifier ou supprimer s'ils sont l'auteur de l'article.

## Installation

1. **Cloner le dépôt :**

    ```bash
    git clone https://github.com/ElyesBelgouthi/Symfony-Test.git
    ```

2. **Installer les dépendances via Composer :**

    ```bash
    cd Symfony-Test
    composer install
    ```

3. **Configurer la base de données :**

    - Copier le fichier `.env` et le renommer en `.env.local`.
    - Configurer les paramètres de connexion à la base de données dans le fichier `.env.local`.
    - Créer la base de données :

        ```bash
        php bin/console doctrine:database:create
        ```

    - Appliquer les migrations :

        ```bash
        php bin/console doctrine:migrations:migrate
        ```

4. **Démarrer le serveur Symfony :**

    ```bash
    symfony server:start
    ```

5. **Accéder à l'application :**

    Ouvrez votre navigateur web et accédez à l'URL suivante : [https://127.0.0.1:8000](https://127.0.0.1:8000)

## Utilisation

Une fois l'application démarrée, vous pouvez explorer ses fonctionnalités via l'interface utilisateur.
