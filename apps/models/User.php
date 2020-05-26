<?php

class User extends \strangerfw\core\model\BaseModel
{

  public $table_name = 'users';

  public $model_name = 'User';

  public $model_class_name = 'User';

  // Relation
  public $belongthTo = [
    'Role' => [
      'JOIN_COND' => 'INNER',
      'CONDITIONS' => [
        'User.role_id' => 'Role.id'
      ]
    ]
  ];

  public $has = [
    'UserInfo' => [
      'JOIN_COND' => 'LEFT',
      'FOREIGN_KEY' => 'user_id'
    ],
    'Note' => [
      'JOIN_COND' => 'LEFT',
      'FOREIGN_KEY' => 'user_id'
    ],
    'Meeting' => [
      'JOIN_COND' => 'LEFT',
      'FOREIGN_KEY' => 'user_id'
    ],
    'MeetingRtcSession' => [
      'JOIN_COND' => 'LEFT',
      'FOREIGN_KEY' => 'user_id'
    ]
  ];

  public $has_many_and_belongs_to = null;

  public function __construct(&$dbh)
  {
    parent::__construct($dbh);
  }

  public function save($form)
  {
    $form[$this->model_name]['password'] = md5($form[$this->model_name]['password'] . SALT);
    $form[$this->model_name]['notified_at'] = null;
    if (! isset($form[$this->model_name]['role_id'])) {
      $form[$this->model_name]['role_id'] = USER_ROLE_ID;
    }
    $form[$this->model_name]['authentication_key'] = \strangerfw\utils\StringUtil::makeRandStr(16);
    unset($form[$this->model_name]['password_confirm']);
    $this->debug->log("User::save() form:" . print_r($form, true));
    parent::save($form);
    return $form;
  }

  public function update($form)
  {
    if (isset($form[$this->model_name]['password']) && isset($form[$this->model_name]['password_confirm'])) {
      unset($form[$this->model_name]['password_confirm']);
      $form[$this->model_name]['password'] = md5($form[$this->model_name]['password'] . SALT);
    }
    parent::save($form);
    return $form;
  }

  public function confirm($confirm_string)
  {
    $this->debug->log("User::confirm() confirm_string:${confirm_string}");
    $form = $this->where('User.authentication_key', '=', $confirm_string)->find('first');
    $form['User']['authentication_key'] = null;
    parent::save($form);
  }

  public function auth($form)
  {
    $form[$this->model_name]['password'] = md5($form[$this->model_name]['password'] . SALT);
    $data = $this->where('User.username', '=', $form[$this->model_name]['username'])
      ->where('User.password', '=', $form[$this->model_name]['password'])
      ->where('User.authentication_key', 'IS NULL', null)
      ->find('first');
    return $data;
  }

  public function check_permission($user, $request)
  {
    if ($user['Auth'][$this->primary_key] == $request[$this->model_class_name][$this->primary_key] || $user['Auth'][$this->primary_key] == 1) {
      return true;
    }
    return false;
  }
}
