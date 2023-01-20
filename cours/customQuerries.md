# Les customs querries

Dans certains cas, nous avons besoin de faire des requêtes en bdd plus complexes que celles proposé de base dans les repositories (comme le findAll ect), c'est tout à fait possible en rajoutant des méthodes dans les repositories : https://symfony.com/doc/current/doctrine.html#querying-for-objects-the-repository

## DQL (doctrine query langage)

Le DQL utilise les bases du langage sql mais avec une approche plus orientée entités en mettant les FQCN plutôt que les noms de tables.

## Query builder

Le query builder est un autre outils de doctrine pour créer des requêtes, il a une approche 100% orienté objet avec des methodes pour construire et lancer sa requête.

    - On lance la création de la requête avec : ```return $this->createQueryBuilder('m')``` Le m en argument de méthode correspond à l'alias données pour la durée de la requête, le queryBuilder "devine" que l'alias correspond, dans ce cas à movie car je suis dans le repository de movie
    - Pour lancer la requête il faut utilise dans l'ordre : 
        - ```->getQuery()```
        - ```->getResult()```

## SQL

Il est possible de faire du sql classique, je ne conseille pas cette méthode car nous utilisons doctrine et autant utiliser sa puissance jusqu'au bout. De plus les résultats de requêtes ressortent moins bien qu'avec le queryBuilder ou le DQL