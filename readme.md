
# Test Sitraka Taram



PARTIE 1 : 

Étapes de migration de Drupal 9 vers Drupal 11
 1ere ETAPE : Analyse avant la migrationer
Lister tous les modules/themes contrib et custom utilisés

Vérifier leur compatibilité avec Drupal 11 (sur Drupal.org ou via composer)

Identifier les modules obsolètes, non maintenus ou remplacés

Sauvegarder la base, fichiers, configurations

2nd ETAPE :
Mettre à jour Drupal 9 vers la dernière version stable, 9.5 par exemple
Mettre le site en mode maintenance :

utilisation du module Upgrade Status pour checker les modules incompatibles
Mettre à jour tous les modules vers leurs dernières versions compatibles D9 (aussi les modules custom)

PLAN de Rollback : 
Faire du backup d'abord => vendor/bin/drush sql-dump > drupal9_backup.sql
tar -czf site-files-backup.tar.gz web/sites/default/files
git checkout -b migration-d10
git add .
git commit -m "Backup commit before Drupal 10 migration"
En cas d'echec, remettre la base 
vendor/bin/drush sql-drop -y   # Supprime l’actuelle base 
vendor/bin/drush sql:dump < drupal9_backup.sql



Corriger les dépréciations signalées dans les logs
Migration vers Drupal 10 (étape intermédiaire)
Mise à jour des dépendances avec Composer
composer require 'drupal/core-recommended:^10' 'drupal/core-composer-scaffold:^10' 'drupal/core-project-message:^10' --update-with-dependencies
vendor/bin/drush updb -y


Faire le test en drupal 10, tester les fonctionnalités, visiter toutes les pages, corriger les problemes

Preparation de la migration de drupal 10 vers drupal 11




## Prérequis
- Drupal 9+ installé avec PHP 8.x

Installation drupal 9 
composer  create-project drupal/recommended-project:9.5 taram_migration

Installation module nécéssaire pour la migration : 
composer require drupal/twig_tweak
composer require drupal/migrate_plus
composer require drupal/migrate_upgrade
composer require drupal/default_content

installer aussi drush
composer require --dev drush/drush


module admin_toolbar pour navigation rapide : 
composer require drupal/admin_toolbar

j'ai utilisé devel aussi, pour la génération rapide de contenu fictif 
composer require drupal/devel

- Module Node activé
- Champ personnalisé `field_score_seo` sur le type de contenu "article" (se trouve dans test_sitraka_taram.install)


Liste des 5 derniers articles :   ici -> /last-articles
Fait dans web\modules\custom\test_sitraka_taram\src\Plugin\Block\LastArticlesBlock.php
fichier twig : web\modules\custom\test_sitraka_taram\templates\last-articles-block.html.twig

## Installation
1. Copier `test_sitraka_taram` dans `modules/custom/`
2. Activer le module :
   `vendor/bin/drush en test_sitraka_taram`


## Fonctions
- Bloc affichant les derniers articles avec score SEO

D'abord on va utiliser Devel pour generer des contenus
vendor/bin/drush en devel_generate -y
$ vendor/bin/drush  devel-generate-content --bundles=article 20 (generer 20 articles)


- Dashboard `/admin/test-sitraka-taram/dashboard`
- Commande Drush `drush test_sitraka_taram:backup` pour purger cache + backup DB (c'est fait ici :
 web\modules\custom\test_sitraka_taram\src\Command\TestSitrakaTaramCommands.php)
- Purge automatique du cache via `hook_cron`



PROCESS DU MIGRATION : 

desactiver upgrade_status. => vendor/bin/drush pmu upgrade_status
Mise à jour complete => /c/wamp64/bin/php/composer.phar update drupal/* --with-all-dependencies

MIGRATION VERS D10
/c/wamp64/bin/php/composer.phar  require 'drupal/core-recommended:^10' 'drupal/core-composer-scaffold:^10' 'drupal/core-project-message:^10' --update-with-dependencies

$ vendor/bin/drush updb  => update de la base de donnée





