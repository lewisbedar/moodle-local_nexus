# Changelog

## 0.4 - 2026-07-11

### Ajouté

- Tableau de bord Nexus avec Hero configurable, dock d’applications, formations récentes et actualités.
- Gestion des applications, actualités et réglages du Hero depuis l’administration Moodle.
- Uploads via File API pour logos d’applications, visuels du Hero et images mises en avant des actualités.
- Champs d’applications : version, statut, catégorie, couleur, affichage dock et affichage page d’accueil.
- Styles Nexus embarqués dans `local/nexus/styles.css`.

### Modifié

- Catalogue et fiches d’applications rendus via templates Mustache.
- Page d’accueil Nexus renommée “Tableau de bord”.
- Dock d’applications stabilisé en ligne horizontale avec navigation si plus de cinq applications.

### Sécurité / stabilité

- Pages d’administration protégées par `moodle/site:config`.
- Actions destructives protégées par `sesskey` et confirmation Moodle.
- Migrations XMLDB ajoutées pour les nouveaux champs et la table des actualités.
