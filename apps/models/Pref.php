<?php
class Pref extends \strangerfw\core\model\BaseModel {
  public $table_name  = 'prefs';
  public $model_name  = 'Pref';
  public $model_class_name  = 'Pref';

  //  Relation
  public $belongthTo = null;
  public $has = null;
  public $has_many_and_belongs_to = null;

  public function __construct(&$dbh) {
    parent::__construct($dbh);
  }
}
