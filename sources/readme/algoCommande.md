# Créer une commande pour automatiser le remplacement des images érronées 

## La techno necessaire 

- Pour récupérer les images des films : l'api OMDB
- il nous faudrait un service / composants qui permet de tester des urls pour identifier les images "mortes"
- Il nous faut pouvoir tout simplement créer une commande symfony


## Baby steps

- En apprendre un peu plus sur le fonctionnement de l'api
- réfléchir à un moyen de mettre en oeuvre l'api dans notre application
  - Je vais avoir besoin d'un composant pour "fetcher" l'api
  - En apprendre un peu plus sur le composant : http-client
- Créer un **Service pour l'api**
- Rendre le service paramétrable via le .env
- Ecrire la/les methodes dans le service pour utiliser l'api

- Passons à la logique que l'on veut mettre dans notre commande personnalisée
  - récupérer tous les films
  - boucler sur les films
  - vérifier l'url des films à l'aide de l'http-client
  - je vais chercher dans l'api un poster correspond au titre du film
  - je le/les remplace
  - flush
  - Rajouter tout ce micmac dans une commande personnalisé
  - afficher combien d'images étaient mortes et ont été remplacé
- YOUPIIII YOUPAAA
