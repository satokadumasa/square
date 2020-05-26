<?php
class City extends \strangerfw\core\model\BaseModel {
  public $table_name  = 'cities';
  public $model_name  = 'City';
  public $model_class_name  = 'City';

  //  Relation
  public $belongthTo = [
      'Pref' => [
          'JOIN_COND' => 'INNER',
          'CONDITIONS' => [
              'Pref.pref_id' => 'City.pref_id',
          ],
      ],
  ];
  public $has = null;
  public $has_many_and_belongs_to = null;

  public function __construct(&$dbh) {
    parent::__construct($dbh);
  }
}
