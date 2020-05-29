<?php
class Migrate20200528224619CreateTableChat extends \strangerfw\core\migrate\BaseMigrate {
  private $dbh = null;
  public function __construct($default_database) {
    parent::__construct($default_database);
  }

  public function up() {
    $sql = <<<EOM
CREATE TABLE chats (
  id int(9) NOT NULL AUTO_INCREMENT,
  user_id int(8) NOT NULL,
  body text NOT NULL,
  created_at datetime NOT NULL,
  modified_at datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY index_chats_id (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
EOM;
    parent::up($sql);
  }

  public function down(){
    $sql = <<<EOM
DROP TABLE chats;
EOM;
    parent::down($sql);
  } 
}