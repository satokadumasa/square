<?php
class UserInfo extends \strangerfw\core\model\BaseModel {
  public $table_name  = 'user_infos';
  public $model_name  = 'UserInfo';
  public $model_class_name  = 'UserInfo';

  //  Relation
  public $belongthTo = [
      'User' => [
          'JOIN_COND' => 'LEFT',
          'CONDITIONS' => [
              'UserInfo.user_id' => 'User.id',
          ],
      ],
      'Pref' => [
          'JOIN_COND' => 'LEFT',
          'CONDITIONS' => [
              'UserInfo.pref_id' => 'Pref.pref_id',
          ],
      ],
      'City' => [
          'JOIN_COND' => 'LEFT',
          'CONDITIONS' => [
              'UserInfo.pref_id' => 'City.pref_id',
          ],
      ],
  ];
  public $has = null;
  public $has_many_and_belongs_to = null;

  public function __construct(&$dbh) {
    parent::__construct($dbh);
  }
}
