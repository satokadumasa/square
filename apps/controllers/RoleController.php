<?php

class RoleController extends \strangerfw\core\controller\BaseController
{

  public function __construct($uri, $url = null)
  {
    $conf = \strangerfw\core\Config::get('database.config');
    $database = $conf['default_database'];
    parent::__construct($database, $uri, $url);
    $this->controller_class_name = str_replace('Controller', '', get_class($this));
    ;
  }

  public function index()
  {
    $roles = new Role($this->dbh);
    $limit = 10;
    $offset = 10 * (isset($this->request['page']) ? $this->request['page'] - 1 : 0);

    $data = $roles->where('Role.id', '>', 0)
      ->limit($limit)
      ->offset($offset)
      ->find('all');
    $ref = isset($this->request['page']) && ($this->request['page'] - 1 > 0) ? ($this->request['page'] - 1) : 0;
    $next = isset($this->request['page']) && ($this->request['page'] > 0) ? $this->request['page'] + 1 : 2;

    $this->set('Title', 'Role List');
    $this->set('data', $data);
    $this->set('Role', $data);
    $this->set('ref', $ref);
    $this->set('next', $next);
  }

  public function detail()
  {
    $data = null;
    $id = $this->request['id'];

    $roles = new Role($this->dbh);
    $data = $roles->where('Role.id', '=', $id)->find('first');
    $this->set('Title', 'Role Ditail');
    $this->set('Role', $data['Role']);
    $this->set('data', $data);
  }

  public function create()
  {
    $this->debug->log("RoleController::create()");
    $roles = new Role($this->dbh);
    $form = $roles->createForm();
    $this->set('Title', 'Role Create');
    $this->set('Role', $form['Role']);
  }

  public function save()
  {
    $this->debug->log("RoleController::save()");
    try {
      $this->dbh->beginTransaction();
      $roles = new Role($this->dbh);
      $roles->save($this->request);
      $this->dbh->commit();
      $url = BASE_URL . 'Role' . '/show/' . $roles->primary_key_value . '/';
      $this->redirect($url);
    } catch (\Exception $e) {
      $this->debug->log("RoleController::create() error:" . $e->getMessage());
      $this->set('Title', 'Role Save Error');
      $this->set('error_message', '保存ができませんでした。');
    }
  }

  public function edit()
  {
    $this->debug->log("RoleController::edit()");
    try {
      $data = null;
      $id = $this->request['id'];

      $roles = new Role($this->dbh);
      $data = $roles->where('Role.id', '=', $id)->find('first');
      $this->set('Title', 'Role Edit');
      $this->set('Role', $data['Role']);
      $this->set('data', $data);
    } catch (\Exception $e) {
      $this->debug->log("RoleController::edit() error:" . $e->getMessage());
    }
  }

  public function delete()
  {
    try {
      $this->dbh->beginTransaction();
      $roles = new Role($this->dbh);
      $roles->delete($this->request['id']);
      $this->dbh->commit();
      $url = BASE_URL . 'Role' . '/index/';
    } catch (\Exception $e) {
      $this->debug->log("UsersController::delete() error:" . $e->getMessage());
    }
  }
}
