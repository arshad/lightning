<?php

namespace Drupal\lightning_workflow\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * A Views field to indicate if a content entity has forward revision(s).
 *
 * @ViewsField("forward_revision_exists")
 */
class ForwardRevisionExists extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $rel = 'latest_revision__' . $this->view->getBaseEntityType()->id();

    if (empty($this->view->relationship[$rel])) {
      return NULL;
    }

    /** @var ContentEntityInterface $current */
    $current = $values->_entity;
    /** @var ContentEntityInterface $latest */
    $latest = $values->_relationship_entities[$rel];

    return $latest->getRevisionId() > $current->getRevisionId()
      ? $this->t('Yes')
      : $this->t('No');
  }

}
