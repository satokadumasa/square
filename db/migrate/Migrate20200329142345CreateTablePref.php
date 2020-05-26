<?php
class Migrate20200329142345CreateTablePref extends \strangerfw\core\migrate\BaseMigrate {
  private $dbh = null;
  public function __construct($default_database) {
    parent::__construct($default_database);
  }

  public function up() {
    $sql = <<<EOM
CREATE TABLE prefs (
  id int(9) NOT NULL AUTO_INCREMENT,
  pref_id int(8) NOT NULL,
  name varchar(64) NOT NULL,
  kana varchar(64) NOT NULL,
  created_at datetime NOT NULL,
  modified_at datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY index_prefs_id (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
EOM;
    parent::up($sql);
  }

  public function down(){
    $sql = <<<EOM
DROP TABLE prefs;
EOM;
    parent::down($sql);
  } 
}