<?php

/**
 * @file
 * Contains Drupal\GDPR\Form\ConsentForm.
 */

namespace Drupal\GDPR\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\GDPR\Service\DatabaseService;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\GDPR\Form
 */
class ConsentForm extends FormBase {

  protected $connection;

  /**
   * @inheritDoc
   */
  public function __construct(DatabaseService $connection)
  {
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_form';
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container) {
    return new static(
        $container->get('GDPR.database')
      );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#required'=> TRUE,
      '#attributes'=>array(
          'placeholder'=>'Enter your name'
      )
    );
    $form['email'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Email'),
      '#required'=> TRUE,
      '#attributes'=>array(
          'placeholder'=>'Enter your e-mail'
      )
    );
    $form['consent'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('Consent'),
      '#required'=> TRUE,
      '#options' => ['Agree' => t('Agree')],
    );
    $form['submit'] = [
      '#type' => 'submit',
      '#weight' => '100',
      '#attributes' => ['class' => ['btn btn-success']],
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->connection->insertData('gdpr_consent_table',[
      'name' => $form_state->getValue('name'),
      'email' => $form_state->getValue('email'),
    ]);
  }

}
