<?php

namespace Drupal\absolute_links\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\StringFormatter;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'string_absolute_link' formatter.
 *
 * @FieldFormatter(
 *   id = "string_absolute_link",
 *   label = @Translation("Plain text (absolute)"),
 *   field_types = {
 *     "string",
 *     "uri",
 *   },
 *   quickedit = {
 *     "editor" = "plain_text"
 *   }
 * )
 */
class StringFormatterAbsolute extends StringFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    $options = parent::defaultSettings();

    $options['absolute'] = TRUE;
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['absolute'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Use absolute link'),
      '#default_value' => $this->getSetting('absolute'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->getSetting('absolute') ? $this->t('absolute') : $this->t('relative');
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEntityUrl(EntityInterface $entity) {
    // For the default revision, the 'revision' link template falls back to
    // 'canonical'.
    // @see \Drupal\Core\Entity\Entity::toUrl()
    $rel = $entity->getEntityType()->hasLinkTemplate('revision') ? 'revision' : 'canonical';
    return $entity->toUrl($rel, ['absolute' => $this->getSetting('absolute')]);
  }

}
