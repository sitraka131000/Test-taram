<?php

namespace Drupal\test_sitraka_taram\Command;

use Drush\Commands\DrushCommands;

class TestSitrakaTaramCommands extends DrushCommands {

  /**
   * Sauvegarde la base et purge le cache.
   *
   * @command test_sitraka_taram:backup
   */
  public function backupDatabase() {
    $this->output()->writeln('Purge du cache...');
    drupal_flush_all_caches();
    $this->output()->writeln('Cache purgé.');

    $this->output()->writeln('Sauvegarde de la base...');
    exec('vendor/bin/drush sql:dump > drupal_backup.sql 2>&1', $output, $return_var);
    $this->output()->writeln($output);
    $this->output()->writeln("Code retour : $return_var");

    $this->output()->writeln('Sauvegarde terminée.');
  }
}
