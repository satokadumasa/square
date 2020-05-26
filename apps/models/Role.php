<?php
class Role extends \strangerfw\core\model\BaseModel {
  public $table_name  = 'roles';
  public $model_name  = 'Role';
  public $model_class_name  = 'Role';

  //  Relation
  public $belongthTo = null;
  public $has = [
      'User' => [
          'JOIN_COND' => 'INNER',
          'FOREIGN_KEY' => 'role_id'
      ],
  ];
  public $has_many_and_belongs_to = null;

  public function __construct(&$dbh) {
    parent::__construct($dbh);
  }
}
