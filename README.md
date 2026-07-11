# moodle-local_nexus

**Nexus Core** est un plugin Moodle `local_nexus` installé dans `/local/nexus`.
Il transforme Moodle en portail applicatif Flux Croisés sans modifier le cœur Moodle.

## Fonctionnalités principales

- Tableau de bord Nexus (`/local/nexus/index.php`) avec Hero configurable, dock d’applications, formations récentes et actualités.
- Catalogue public des applications (`/local/nexus/catalog.php`) avec catégories.
- Fiches d’applications (`/local/nexus/application.php?slug=...`).
- Administration des applications, actualités et réglages du Hero depuis l’administration Moodle.
- Uploads via la File API Moodle pour les logos d’applications, images du Hero et images mises en avant des actualités.

## Styles Nexus

Nexus Core embarque ses propres styles via `styles.css`. Les styles Nexus ne doivent plus être collés manuellement dans Boost Union.
Boost Union reste utilisé pour la navigation, le logo, les couleurs globales Moodle et la largeur générale du site.

## Administration

Les pages d’administration nécessitent `moodle/site:config` :

- `/local/nexus/manage_apps.php` : gestion des applications.
- `/local/nexus/manage_news.php` : gestion des actualités.
- `/local/nexus/hero_settings.php` : réglages du Hero et option d’utilisation de Nexus comme page d’accueil/tableau de bord Moodle.

Les actions destructives passent par `sesskey` et confirmation Moodle.

## Fichiers et médias

Les logos des applications peuvent être importés via la File API de Moodle depuis le formulaire d’édition Nexus Core. Le champ historique d’URL d’icône reste disponible comme solution de repli pour les applications existantes.

Les actualités acceptent une image mise en avant. Les fichiers sont servis par `local_nexus_pluginfile()` après authentification Moodle.

## Mise à jour

Après déploiement ou mise à jour :

1. lancer la mise à niveau Moodle pour appliquer `db/upgrade.php` si nécessaire ;
2. purger les caches Moodle pour recharger les chaînes de langue, templates Mustache, `styles.css` et `dock.js` ;
3. vérifier les pages publiques et les pages d’administration Nexus Core.
