# Les fixtures

## Intro

Les fixtures vont nous permettre de supprimer les entrées de notre bdd et d'en rajouter des nouvelles automatiquement dans notre base de données, c'est super utile quand on travaille à plusieurs sur une bdd, ça évite de long temps d'export/import et des voyages de fichiers qui ne sont souvent pas à jour.

Pour installer le bundle : ```composer require --dev doctrine/doctrine-fixtures-bundle```


## En pratique

Il faut dans la méthode load de ```AppFixtures.php``` rentrer toutes les données que l'on veut insérer, cela fonctionne avec les entités de symfony donc on set nos attributs et à la fin on persist et flush avec le manager

## Lancer les fixtures

Pour lancer les fixtures, la commande suivante *ATTENTION* cela va vider completement votre BDD: 

```php bin/console doctrine:fixtures:load```