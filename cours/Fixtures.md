# Les fixtures

## Intro

Les fixtures vont nous permettre de supprimer les entrées de notre bdd et d'en rajouter des nouvelles automatiquement dans notre base de données, c'est super utile quand on travaille à plusieurs sur une bdd, ça évite de long temps d'export/import et des voyages de fichiers qui ne sont souvent pas à jour.

Pour installer le bundle : ```composer require --dev doctrine/doctrine-fixtures-bundle```


## En pratique

Il faut dans la méthode load de ```AppFixtures.php``` rentrer toutes les données que l'on veut insérer, cela fonctionne avec les entités de symfony donc on set nos attributs et à la fin on persist et flush avec le manager

## Lancer les fixtures

Pour lancer les fixtures, la commande suivante *ATTENTION* cela va vider completement votre BDD: 

```php bin/console doctrine:fixtures:load```

## Utiliser un faker

L'utilisation des fixtures seuls manque de vérasité dans les données, l'utilisation d'un faker pour completer nos fixtures est la solution idéale

```composer require fakerphp/faker```

Pour utiliser le faker, il faut en instancier un avec la langue des données souhaité : 
``` $faker = Faker\Factory::create("fr_FR");```

Vu qu'on utilise un ORM, on a la possibilité d'utiliser un populator, il permet de "deviner" les données à rajouter par rapport à nos entités
par ex:  
```   $populator->addEntity(Movie::class, 5);
        $populator->execute();
```

Le problème est que cette génération n'est pas toujours pertinante du coup nous devons quand même mettre le nez dedans.
Il est possible de modifier les columns avec des fonctions : 
```php     
$populator->addEntity(Movie::class, 5,[
    // En troisième argument de la méthode addEntity, il est possible de mettre un tableau avec les columns à formatter manuellement
    // Pour les formater manuellement il faut en valeur de tableau faire passer une fonction, vu que nos fonctions seront appeller uniquement ici, j'utilise des fonctions anonymes
    "duration" => function() use ($faker){
        return $faker->numberBetween(10,240);
    }
]);
```

## Provider

Un provider est un "fournisseur" de fausses données pour le faker, il en utilise une multitude et il est possible pour nous d'en rajouter si nous le souhaitons :

```php
  $faker->addProvider(new AppProvider());
```

```AppProvider``` Correspond à une classe qui contient des données statiques mise à disposition pour le faker (allez voir la classe pour plus de détails)
