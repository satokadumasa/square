<?php
class RtcSession extends \strangerfw\core\model\BaseModel {
  public $table_name  = 'rtc_sessions';
  public $model_name  = 'RtcSession';
  public $model_class_name  = 'RtcSession';

  //  Relation
  public $belongthTo = [
    'User' => [
      'JOIN_COND' => 'LEFT',
      'CONDITIONS' => [
        'RtcSession.user_id' => 'User.id'
      ]
    ],
  ];
  public $has = null;
  public $has_many_and_belongs_to = null;

  public function __construct(&$dbh) {
    parent::__construct($dbh);
  }
}
