<?php

namespace Drupal\test_sitraka_taram\Controller;

use Drupal\Core\Controller\ControllerBase;

class LastArticlesController extends ControllerBase {

  public function content() {
    // Crée une instance du bloc
    $block = \Drupal::service('plugin.manager.block')
      ->createInstance('last_articles_block', []);

    // Construit le rendu du bloc
    $render = $block->build();

    return $render;
  }
}
