<?php
class Meeting extends \strangerfw\core\model\BaseModel {
  public $table_name  = 'meetings';
  public $model_name  = 'Meeting';
  public $model_class_name  = 'Meeting';

  //  Relation
  public $belongthTo = [
    'User' => [
      'JOIN_COND' => 'LEFT',
      'CONDITIONS' => [
        'Meeting.user_id' => 'User.id'
      ]
    ]
  ];
  public $has = [
    'MeetingRtcSession' => [
      'JOIN_COND' => 'LEFT',
      'FOREIGN_KEY' => 'meeting_id'
    ],
  ];
  public $has_many_and_belongs_to = null;

  public function __construct(&$dbh) {
    parent::__construct($dbh);
  }

  public function save($form){
    if($form[$this->model_name]['password'] != $form[$this->model_name]['password_confirm']){
      throw new Exception("Error:パスワードが一致していません。", 1);
    }
    $form[$this->model_name]['password'] = md5($form[$this->model_name]['password'] . SALT);
    $form[$this->model_name]['meeting_hash_key'] = \strangerfw\utils\StringUtil::makeRandStr(16);
    unset($form[$this->model_name]['password_confirm']);
    $form[$this->model_name]['last_connected_at'] = date('Y-m-d H:i:s');
    parent::save($form);
    return $form;
  }
}
