<?php

namespace Drupal\banner\Plugin\Block;

use Drupal\banner\Form\CarouselForm;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an 'Active Users' Block.
 *
 * @Block(
 *   id = "page_banner",
 *   admin_label = @Translation("Page Banner"),
 *   category = @Translation("Banner"),
 * )
 */
class Banner extends BlockBase implements ContainerFactoryPluginInterface {

  protected $configFactory;
  /**
   * @inheritDoc
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }
  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('config.factory')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->configFactory->getEditable('banner.settings');
    $images = $config->get('image');
    $url = [];
    foreach($images as $id){
      $files = File::load($id);
      $url[] = $files->createFileUrl();
    }
    return [
      '#theme' => 'banner_template',
      '#urls' => $url
    ];
  }
}