# moodle-local_nexus

**Nexus Core** est un plugin Moodle `local_nexus` installé dans `/local/nexus`.
Il transforme Moodle en portail applicatif Flux Croisés sans modifier le cœur Moodle.

## Fonctionnalités principales

- Tableau de bord Nexus (`/local/nexus/index.php`) avec Hero configurable, dock d’applications, formations récentes et actualités.
- Catalogue public des applications (`/local/nexus/catalog.php`) avec catégories.
- Fiches d’applications (`/local/nexus/application.php?slug=...`).
- Administration des applications, actualités et réglages du Hero depuis l’administration Moodle.
- Uploads via la File API Moodle pour les logos d’applications, images du Hero et images mises en avant des actualités.
- Contrôle d’accès centralisé pour les applications publiques, connectées, adhérents, équipe/bureau et administration.
- Composition administrable de l’accueil via un premier moteur de widgets (`hero`, `dock`, `applications`, `courses`, `news`).

## Styles Nexus

Nexus Core embarque ses propres styles via `styles.css`. Les styles Nexus ne doivent plus être collés manuellement dans Boost Union.
Boost Union reste utilisé pour la navigation, le logo, les couleurs globales Moodle et la largeur générale du site.

## Administration

Les pages d’administration nécessitent `moodle/site:config` :

- `/local/nexus/manage_apps.php` : gestion des applications.
- `/local/nexus/manage_news.php` : gestion des actualités.
- `/local/nexus/hero_settings.php` : réglages du Hero et option d’utilisation de Nexus comme page d’accueil/tableau de bord Moodle.

Les actions destructives passent par `sesskey` et confirmation Moodle.

## Contrôle d’accès

Nexus Core utilise des capacités Moodle dédiées :

- `local/nexus:viewmemberapps` : accès aux applications réservées aux adhérents.
- `local/nexus:viewstaffapps` : accès aux applications réservées à l’équipe ou au bureau.
- `local/nexus:manageapps` : gestion des applications.
- `local/nexus:managenews` : gestion des actualités.
- `local/nexus:managehero` : réglages du Hero et paramètres Nexus.

Les cohortes “Adhérents” et “Bureau / Équipe” sont configurables dans les réglages Nexus Core. Les identifiants de cohortes ne sont pas codés en dur.

Les URLs réelles des applications réservées ne sont pas transmises aux utilisateurs non autorisés.

## Widgets de la page d’accueil

La composition de l’accueil est configurée dans `/local/nexus/homepage_settings.php`.
La configuration est stockée dans la configuration du plugin Moodle, sans table CMS dédiée.

Chaque widget implémente `local_nexus\local\widget\widget_interface` et déclare :

- son identifiant technique ;
- son nom traduit ;
- sa disponibilité pour l’utilisateur courant ;
- ses données de template ;
- son template Mustache.

Pour ajouter un widget plus tard :

1. créer une classe dans `classes/local/widget/` ;
2. implémenter `widget_interface` ;
3. créer le template dans `templates/widgets/` ;
4. déclarer le widget dans `widget_registry` ;
5. ajouter les chaînes de langue nécessaires.

## Fichiers et médias

Les logos des applications peuvent être importés via la File API de Moodle depuis le formulaire d’édition Nexus Core. Le champ historique d’URL d’icône reste disponible comme solution de repli pour les applications existantes.

Les actualités acceptent une image mise en avant. Les fichiers sont servis par `local_nexus_pluginfile()` après authentification Moodle.

## Mise à jour

Après déploiement ou mise à jour :

1. lancer la mise à niveau Moodle pour appliquer `db/upgrade.php` si nécessaire ;
2. purger les caches Moodle pour recharger les chaînes de langue, templates Mustache, `styles.css` et `dock.js` ;
3. vérifier les pages publiques et les pages d’administration Nexus Core.
