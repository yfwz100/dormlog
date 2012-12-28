<?php
namespace gtf;

/**
 * Gtf PDO Box. A connection resource wrapper for PDO connection.
 */
class PDOBox {

  private static $dbh;

  private function __construct() {
  }

  /**
   * Get connection from the box.
   *
   * @return the created database connection.
   */
  public static function get() {
    if (static::$dbh == null) {
      // read configuration from file 'db.config.php'.
      $db = include dirname(dirname(__FILE__)).'/db.config.php';
      static::$dbh = new \PDO($db['dsn'], $db['user'], $db['password']);
    }
    return static::$dbh;
  }
}
