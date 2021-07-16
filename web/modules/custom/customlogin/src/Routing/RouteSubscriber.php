<?php

namespace Drupal\customlogin\Routing;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase{
  protected $config;

  /**
   * @inheritDoc
   *
   * @param ConfigFactory $config
   */
  public function __construct(ConfigFactory $config) {
    $this->config = $config;
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $url = $this->config->get('customlogin.settings')->get('customurl');
    if ($route = $collection->get('user.login')) {
      $route->setPath($url);
    }
  }

}