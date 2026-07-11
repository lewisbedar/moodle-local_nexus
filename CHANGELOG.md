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

## 0.5 - 2026-07-11

### Ajouté

- Service central `application_access_service` pour les décisions d’accès aux applications.
- Capacités Moodle dédiées pour l’accès adhérents, équipe/bureau et l’administration Nexus.
- Correspondance configurable avec les cohortes Adhérents et Bureau / Équipe.
- Tests PHPUnit couvrant les principales décisions d’accès.
- Premier moteur de widgets configurable pour composer la page d’accueil Nexus.
- Page d’administration “Composition de l’accueil”.
- Tests PHPUnit pour l’ordre, les widgets désactivés, les widgets indisponibles et les identifiants inconnus.

### Modifié

- Les URLs réelles des applications réservées ne sont plus transmises aux utilisateurs non autorisés.
- Les pages d’administration utilisent des capacités Nexus dédiées plutôt que `moodle/site:config`.
- La redirection “Nexus page d’accueil” est séparée des paramètres d’arrivée après connexion.
