<?php
class CityController extends \strangerfw\core\controller\BaseController{
  public function __construct($uri, $url = null) {
    $conf = \strangerfw\core\Config::get('database.config');
    $database = $conf['default_database'];
    parent::__construct($database, $uri, $url);
    $this->controller_class_name = str_replace('Controller', '', get_class($this));;
  }

  public function index() {
    $cities = new City($this->dbh);
    $limit = 10 * (isset($this->request['page']) ? $this->request['page'] : 1);
    $offset = 10 * (isset($this->request['page']) ? $this->request['page'] - 1 : 0);

    $data = $cities->where('City.id', '>', 0)->limit($limit)->offset($offset)->find('all');

    $ref = isset($this->request['page']) ? $this->request['page'] : 0;
    $next = isset($this->request['page']) ? $this->request['page'] + 1 : 2;

    $this->set('Title', 'City List');
    $this->set('data', $data);
    $this->set('City', $data);
    $this->set('ref', $ref);
    $this->set('next', $next);
  }

  public function show() {
    $data = null;
    $id = $this->request['id'];

    $cities = new City($this->dbh);
    $data = $cities->where('City.id', '=', $id)->find('first');
    $this->set('Title', 'City Ditail');
    $this->set('City', $data['City']);
    $this->set('data', $data);
  }

  public function create() {
    $this->debug->log("CityController::create()");
    $cities = new City($this->dbh);
    $form = $cities->createForm();
    $this->set('Title', 'City Create');
    $this->set('City', $form['City']);
  }

  public function save(){
    $this->debug->log("CityController::save()");
    try {
      $this->dbh->beginTransaction();
      $cities = new City($this->dbh);
      $cities->save($this->request);
      $this->dbh->commit();
      $url = BASE_URL . 'City' . '/show/' . $cities->primary_key_value . '/';
      $this->redirect($url);
    } catch (\Exception $e) {
      $this->debug->log("CityController::create() error:" . $e->getMessage());
      $this->set('Title', 'City Save Error');
      $this->set('error_message', '保存ができませんでした。');
    }
  }

  public function edit() {
    $this->debug->log("CityController::edit()");
    try {
      $data = null;
      $id = $this->request['id'];

      $cities = new City($this->dbh);
      $data = $cities->where('City.id', '=', $id)->find('first');
      $this->set('Title', 'City Edit');
      $this->set('City', $data['City']);
      $this->set('data', $data);
    } catch (\Exception $e) {
      $this->debug->log("CityController::edit() error:" . $e->getMessage());
    }
  }

  public function delete() {
    try {
      $this->dbh->beginTransaction();
      $cities = new City($this->dbh);
      $cities->delete($this->request['id']);
      $this->dbh->commit();
      $url = BASE_URL . 'City' . '/index/';
    } catch (\Exception $e) {
      $this->debug->log("UsersController::delete() error:" . $e->getMessage());
    }
  }


}
