<?php

namespace Drupal\test_sitraka_taram\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Provides a block with last N articles with SEO score.
 *
 * @Block(
 *   id = "last_articles_block",
 *   admin_label = @Translation("Last articles with SEO score")
 * )
 */
class LastArticlesBlock extends BlockBase {

  public function build() {
    $limit = $this->configuration['limit'] ?? 5;

    $nids = \Drupal::entityQuery('node')
      ->accessCheck(TRUE) 
      ->condition('type', 'article')
      ->condition('status', 1)
      ->sort('created', 'DESC')
      ->range(0, $limit)
      ->execute();


    $nodes = Node::loadMultiple($nids);

    return [
      '#theme' => 'last_articles_block',
      '#articles' => $nodes,
    ];
  }

  public function defaultConfiguration() {
    return ['limit' => 5];
  }

  public function blockForm($form, FormStateInterface $form_state) {
    $form['limit'] = [
      '#type' => 'number',
      '#title' => $this->t('Nombre d’articles à afficher'),
      '#default_value' => $this->configuration['limit'],
      '#min' => 1,
      '#max' => 20,
    ];
    return $form;
  }

  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['limit'] = $form_state->getValue('limit');
  }
}
