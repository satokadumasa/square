<?php
class Chat extends \strangerfw\core\model\BaseModel {
  public $table_name  = 'chats';
  public $model_name  = 'Chat';
  public $model_class_name  = 'Chat';

  //  Relation
  public $belongthTo = null;
  public $has = null;
  public $has_many_and_belongs_to = null;

  public function __construct(&$dbh) {
    parent::__construct($dbh);
  }
}
