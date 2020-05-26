<?php
class Migrate20200512102732CreateTableMeetingRtcSession extends \strangerfw\core\migrate\BaseMigrate {
  private $dbh = null;
  public function __construct($default_database) {
    parent::__construct($default_database);
  }

  public function up() {
    $sql = <<<EOM
CREATE TABLE meeting_rtc_sessions (
  id int(9) NOT NULL AUTO_INCREMENT,
  user_id int(10) NOT NULL,
  meeting_id int(8) NOT NULL,
  rtc_session_id int(8) NOT NULL,
  created_at datetime NOT NULL,
  modified_at datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY index_meeting_rtc_sessions_id (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
EOM;
    parent::up($sql);
  }

  public function down(){
    $sql = <<<EOM
DROP TABLE meeting_rtc_sessions;
EOM;
    parent::down($sql);
  } 
}