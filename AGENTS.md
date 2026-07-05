# AGENTS.md

Projet : plugin Moodle local_nexus.

Objectif :
Créer un portail Nexus pour Moodle, servant de hub Flux Croisés.

Contraintes :
- Plugin Moodle local installé dans /local/nexus.
- Respecter les APIs Moodle.
- Utiliser Mustache pour les templates.
- Éviter le HTML directement dans les fichiers PHP.
- Préserver les URLs propres via .htaccess.
- Ne pas modifier le cœur Moodle.
- Langue de l’interface : français.

État actuel :
- Dashboard Nexus.
- Catalogue applications.
- Fiches applications par slug.
- CRUD admin simple des applications.
- Données stockées dans local_nexus_applications.

Prochaine priorité :
Refactoriser proprement la page catalogue et les fiches d’applications, puis ajouter les champs version, statut, catégorie, couleur, showdock, showhomepage.
