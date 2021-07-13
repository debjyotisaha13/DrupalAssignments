<?php

/**
 * @file
 * Contains Drupal\banner\Form\CarouselForm.
 */

namespace Drupal\banner\Form;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\banner\Form
 */
class CarouselForm extends ConfigFormBase{

  protected $config;

  public function __construct(ConfigFactory $config){
    $this->config = $config;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container) {
    return new static(
        $container->get('config.factory')
      );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'banner.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'carousel_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['image'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Image'),
      '#multiple' => TRUE,
      'required' => TRUE,
      '#attributes'=>[
        'placeholder'=>'Enter your banner Image'
      ],
      '#upload_validators' => [
        'file_validate_extensions' => ['gif png jpg jpeg'],
      ],
      '#upload_location' => 'public://banner-image',
    ];
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
    $files=$form_state->getValue('image');
    foreach ($files as $id) {
        $file = File::load($id);
        $file->setPermanent();
        $file->save();
    }
    $this->config->getEditable('banner.settings')
      ->set('image', $files)
      ->save();
    parent::submitForm($form, $form_state);
  }

}
