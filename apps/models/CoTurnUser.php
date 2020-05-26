<?php

class CoTurnUser extends \strangerfw\core\model\BaseModel
{

  public $table_name = 'turnusers_lt';

  public $model_name = 'CoTurnUser';

  public $model_class_name = 'CoTurnUser';

  public $belongthTo = null;

  public $has = null;

  public $has_many_and_belongs_to = null;

  public function __construct(&$dbh)
  {
    parent::__construct($dbh);
  }

  public function save($form)
  {
    parent::save($form);
    return $form;
  }
}
