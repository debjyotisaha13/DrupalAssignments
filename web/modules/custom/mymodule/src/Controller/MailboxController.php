<?php
namespace Drupal\mymodule\Controller;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MailboxController implements ContainerInjectionInterface{
    protected $config;
    public function __construct(ConfigFactory $config)
    {
        $this->config=$config;
    }
    public static function create(ContainerInterface $container) {
        return new static(
          $container->get('config.factory')
        );
      }
    public function showCredentials() {
        $access_key = $this->config->get('mymodule.settings')->get('config');
        return array (
            '#markup' => '<b>Client ID:<b> '.$access_key
        );
    }
}