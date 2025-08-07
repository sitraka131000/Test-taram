<?php

namespace Drupal\test_sitraka_taram\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;

class DashboardController extends ControllerBase {

  public function dashboard() {
    $nids = \Drupal::entityQuery('node')
          ->accessCheck(TRUE) 
      ->condition('type', 'article')
      ->sort('created', 'DESC')
      ->range(0, 5)
      ->execute();

    $nodes = Node::loadMultiple($nids);
    $rows = [];

    foreach ($nodes as $node) {
      $rows[] = [
        $node->toLink(),
        $node->getOwner()->getDisplayName(),
        \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'short'),
        $node->get('field_score_seo')->value ?? 'N/A',
        $node->isPublished() ? $this->t('Publié') : $this->t('Non publié'),
      ];
    }

    $header = [$this->t('Titre'), $this->t('Auteur'), $this->t('Date'), $this->t('Score SEO'), $this->t('Statut')];

    return [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#prefix' => '<div class="dashboard-actions">
        <a href="' . Url::fromRoute('system.performance_settings')->toString() . '" class="button button--primary">Purger le cache</a>
        <a href="' . Url::fromRoute('test_sitraka_taram.dashboard')->toString() . '" class="button">Vérifier SEO</a>
      </div>',
    ];
  }
}
