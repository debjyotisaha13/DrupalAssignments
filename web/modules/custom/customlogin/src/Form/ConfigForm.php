<?php

/**
 * @file
 * Contains Drupal\customlogin\Form\ConfigForm.
 */

namespace Drupal\customlogin\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\customlogin\Form
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'customlogin.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('customlogin.settings');
    $form['customurl'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Custom Login Url'),
      '#default_value' => $config->get('customurl'),
      '#required'=> TRUE,
      '#attributes'=>array(
          'placeholder'=>'Enter a Login Url'
      )
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('customlogin.settings')
      ->set('customurl', $form_state->getValue('customurl'))
      ->save();
  }

}
