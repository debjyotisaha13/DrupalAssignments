<?php

namespace Drupal\GDPR\Service;

use Drupal\Core\Database\Connection;
/**
 * Inserts Data into the database
 */
class DatabaseService {
  protected $connection;
  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }
  public function insertData( $table, $data) {
    return $this->connection->insert($table)
      ->fields($data)
      ->execute();
  }
}