# Projet final de PHP avancé - Clément Darne

## ToDo

- ~~Choisir un nom de blog~~
--> "Blog" :)

- une partie "Admin" (/admin/...)  
  - Barre de menu pour les accès à
    - Nom du site (avec un lien sur la homepage: partie Utilisateur)
    - Liste des Post
    - Liste des Catégories
    - Liste des Commentaires
  - Post (depuis la liste des Post)
    - Liste des Post
    - ~~Créer un nouveau Post~~
      - Pouvoir ajouter des catégories au Post
      - Dates automatiques
    - Éditer un Post
    - ~~Supprimer un Post~~
  - Category (depuis la liste des categories)
    - Liste des catégories
    - Créer une catégorie
    - Éditer une catégorie
    - Supprimer une catégorie
  - Commentaire (depuis la liste des Commentaires)
    - Liste des commentaires
    - Valider un commentaire (Modération)
    - Supprimer un commentaire
  
- une partie "Utilisateur" (/...)
  - ~~Barre de menu (header) pour les accès à :~~
    - ~~Nom du site (avec un lien sur la homepage)~~
    - ~~Partie Admin~~
  - Post
    - Lister les 5 derniers Post (si la date de publication est valide) (ceci est la page d'accueil / homepage)
    - Afficher un Post (si la date de publication est valide) (via le `slug`)
    - Pagination sur la liste des Post (possible avec [knpPaginator](https://github.com/KnpLabs/KnpPaginatorBundle))
  - Commentaire
    - Ajouter un commentaire à un Post
    - Afficher les commentaires associés à un Post (si le commentaire est valide)
  - Sidebar (sur page homepage ou toutes les pages "partie utilisateur") (voir `render(controller())` => [lien](https://symfony.com/doc/current/templates.html#embedding-controllers))
    - Afficher dans une sidebar les 5 derniers commentaires valides (si le commentaire est valide)
    - Afficher les catégories (si au moins un post avec cette catégorie existe) avec le nombre de Post
