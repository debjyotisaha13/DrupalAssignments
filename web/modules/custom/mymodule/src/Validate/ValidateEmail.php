<?php
namespace Drupal\mymodule\Validate;

use Drupal\Core\Form\FormStateInterface;

/**
 * Form API callback. Validate element value.
 */
class ValidateEmail {
    /**
     * Validates given element.
     *
     * @param array              $element      The form element to process.
     * @param FormStateInterface $formState    The form state.
     * @param array              $form The complete form structure.
     */
    public static function validate(array &$element, FormStateInterface $formState, array &$form) {
        $webformKey = $element['#webform_key'];
        $value = $formState->getValue($webformKey);
        if ($value === '' || is_array($value)) {
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
            $formState->setErrorByName('email', t('Invalid email format.'));
          }
          else{
            if ($validationResult['format_valid'] && $validationResult['smtp_check']) {
            $formState->setValue('email', $value);
            }
            else{
            $formState->setErrorByName('email', t('Enter a valid email.'));
            }
          }
        }
    }
}