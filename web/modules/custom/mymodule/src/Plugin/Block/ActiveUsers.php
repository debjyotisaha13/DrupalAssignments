<?php

namespace Drupal\mymodule\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an 'Active Users' Block.
 *
 * @Block(
 *   id = "active_users",
 *   admin_label = @Translation("Active Users block"),
 *   category = @Translation("Active Users"),
 * )
 */
class ActiveUsers extends BlockBase implements ContainerFactoryPluginInterface {

  protected $userStorage;
  /**
   * @inheritDoc
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UserStorageInterface $user_storage) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->userStorage = $user_storage;
  }
  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
        $configuration,
        $plugin_id,
        $plugin_definition,
        $container->get('entity_type.manager')->getStorage('user')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function build() {
    $query = $this->userStorage->getQuery()->condition('status','1');
    $uids = $query->execute();
    $users = $this->userStorage->loadMultiple($uids);
    return [
      '#theme' => 'mymodule_template',
      '#users' => $users
    ];
  }
}