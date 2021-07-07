<?php

namespace Drupal\mymodule\Plugin\WebformHandler;

use Drupal\Core\Form\FormStateInterface;
use Drupal\webform\Plugin\WebformHandlerBase;
use Drupal\Component\Utility\Html;
use Drupal\webform\WebformSubmissionInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Webform validate handler.
 *
 * @WebformHandler(
 *   id = "my_module_custom_validator",
 *   label = @Translation("Mailbox Email Validator"),
 *   category = @Translation("Settings"),
 *   description = @Translation("Form alter to validate it."),
 *   cardinality = \Drupal\webform\Plugin\WebformHandlerInterface::CARDINALITY_SINGLE,
 *   results = \Drupal\webform\Plugin\WebformHandlerInterface::RESULTS_PROCESSED,
 *   submission = \Drupal\webform\Plugin\WebformHandlerInterface::SUBMISSION_OPTIONAL,
 * )
 */
class MyWebformHandler extends WebformHandlerBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state, WebformSubmissionInterface $webform_submission) {
    $this->validateEmail($form_state);
  }

  private function validateEmail(FormStateInterface $formState) {
    $value = !empty($formState->getValue('email')) ? Html::escape($formState->getValue('email')) : NULL;
    if (empty($value) || is_array($value)) {
      return;
    }
    else{
      $access_key = \Drupal::config('mymodule.settings')->get('config');

      $ch = curl_init('http://apilayer.net/api/check?access_key='.$access_key.'&email='.$value.'');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $json = curl_exec($ch);
      curl_close($ch);
      $validationResult = json_decode($json, true);

      if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        $formState->setErrorByName('email', $this->t('Invalid email format.'));
      }
      else{
        if ($validationResult['format_valid'] && $validationResult['smtp_check']) {
          $formState->setValue('email', $value);
        }
        else{
          $formState->setErrorByName('email', $this->t('Enter a valid email.'));
        }
      }
    }
  }
}