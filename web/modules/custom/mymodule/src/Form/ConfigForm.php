<?php

/**
 * @file
 * Contains Drupal\mymodule\Form\ConfigForm.
 */

namespace Drupal\mymodule\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\mymodule\Form
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'mymodule.settings',
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
    $config = $this->config('mymodule.settings');
    $form['config'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Client ID/Secret Key'),
      '#default_value' => $config->get('config'),
      '#required'=> TRUE,
      '#attributes'=>array(
          'placeholder'=>'Enter your Client ID'
      )
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('config')) == 0) {
        $form_state->setErrorByName('config', $this->t('Enter a valid Client ID.'));
      }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('mymodule.settings')
      ->set('config', $form_state->getValue('config'))
      ->save();
  }

}
