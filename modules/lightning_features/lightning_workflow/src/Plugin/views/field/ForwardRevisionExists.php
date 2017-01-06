<?php

namespace Drupal\lightning_workflow\Plugin\views\field;

use Drupal\Core\Entity\ContentEntityInterface;
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

    return $this->isPublished($current) && !$this->isPublished($latest)
      ? $this->t('Yes')
      : $this->t('No');
  }

  /**
   * Checks if a content entity is published.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity to check. It must have a status key.
   *
   * @return mixed
   *   The value of the entity's status key.
   *
   * @TODO Kill this in Drupal 8.3 in favor of EntityPublishedInterface.
   */
  protected function isPublished(ContentEntityInterface $entity) {
    $status_key = $entity->getEntityType()->getKey('status');

    /** @var \Drupal\Core\Field\FieldItemInterface $item */
    $item = $entity->get($status_key)->first();

    return $item->get($item::mainPropertyName())->getValue();
  }

}
