<?php
namespace Drupal\mymodule\Controller;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MailboxController class
 * used to show credentials of mailboxlayer
 */
class MailboxController implements ContainerInjectionInterface{
    protected $config;
    /**
     * @inheritDoc
     *
     * @param ConfigFactory $config
     */
    public function __construct(ConfigFactory $config)
    {
        $this->config=$config;
    }

    /**
     * @inheritDoc
     *
     * @param ContainerInterface $container
     */
    public static function create(ContainerInterface $container) {
        return new static(
          $container->get('config.factory')
        );
      }

    /**
     * Shows the Client ID from mymodule.settings
     */
    public function showCredentials() {
        $access_key = $this->config->get('mymodule.settings')->get('config');
        return array (
            '#markup' => '<b>Client ID:</b> '.$access_key
        );
    }
}