<?php
class Migrate20200512091513CreateTableMeeting extends \strangerfw\core\migrate\BaseMigrate {
  private $dbh = null;
  public function __construct($default_database) {
    parent::__construct($default_database);
  }

  public function up() {
    $sql = <<<EOM
CREATE TABLE meetings (
  id int(9) NOT NULL AUTO_INCREMENT,
  user_id int(10) NOT NULL,
  title varchar(128) NOT NULL,
  meeting_hash_key varchar(128),
  password varchar(128) NOT NULL,
  last_connected_at datetime NOT NULL,
  created_at datetime NOT NULL,
  modified_at datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY index_meetings_id (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
EOM;
    parent::up($sql);
  }

  public function down(){
    $sql = <<<EOM
DROP TABLE meetings;
EOM;
    parent::down($sql);
  } 
}