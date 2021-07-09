<?php
namespace Drupal\mymodule\Validate;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Client;

/**
 * Form API callback. Validate element value.
 */
class ValidateEmail implements ContainerInjectionInterface{

    protected $httpClient;
    protected $config;

    /**
     * {@inheritDoc}
     */
    public function __construct(Client $http_client, ConfigFactory $config)
    {
      $this->httpClient = $http_client;
      $this->config = $config;
    }

    /**
     * {@inheritDoc}
     */
    public static function create(ContainerInterface $container) {
      return new static(
        $container->get('http_client'),
        $container->get('config.factory')
      );
    }

    /**
     * Validates given element.
     *
     * @param array              $element      The form element to process.
     * @param FormStateInterface $formState    The form state.
     * @param array              $form The complete form structure.
     */
    public function validate(array &$element, FormStateInterface $formState, array &$form) {
        $webformKey = $element['#webform_key'];
        $value = $formState->getValue($webformKey);
        if ($value === '' || is_array($value)) {
            return;
        }

        else{
          $access_key = $this->config->get('mymodule.settings')->get('config');

          $request = $this->httpClient->get('http://apilayer.net/api/check?access_key='.$access_key.'&email='.$value.'');
          $validationResult = json_decode($request->getBody());

          if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $formState->setErrorByName('email', t('Invalid email format.'));
          }
          else{
            if ($validationResult->format_valid && $validationResult->smtp_check) {
            $formState->setValue('email', $value);
            }
            else{
            $formState->setErrorByName('email', t('Enter a valid email.'));
            }
          }
        }
    }
}