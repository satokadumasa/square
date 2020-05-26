<?php
class RtcSessionController extends \strangerfw\core\controller\BaseController{
  public function __construct($uri, $url = null) {
    $conf = \strangerfw\core\Config::get('database.config');
    $database = $conf['default_database'];
    parent::__construct($database, $uri, $url);
    $this->controller_class_name = str_replace('Controller', '', get_class($this));;
  }

  public function index() {
    $rtc_sessions = new RtcSession($this->dbh);
    $limit = 10;
    $offset = 10 * (isset($this->request['page']) ? $this->request['page'] - 1 : 0);

    $data = $rtc_sessions->contain([
              'User' => [
                'UserInfo',
              ]
            ])
            ->select([
              'RtcSession' => [
                'user_id',
                'spd',
                'status',
              ],
              'User' => [
                'username',
                'role_id'
              ],
              'UserInfo' => [
                'first_name',
                'last_name',
                'address'
              ]
            ])
            ->where('RtcSession.id', '>', 0)->limit($limit)->offset($offset)->find('all');

    $ref = isset($this->request['page']) && ($this->request['page'] - 1 > 0) ? ($this->request['page'] - 1) : 0;
    $next = isset($this->request['page']) && ($this->request['page'] > 0) ? $this->request['page'] + 1 : 2;

    $this->set('Title', 'RtcSession List');
    $this->set('data', $data);
    $this->set('RtcSession', $data);
    $this->set('ref', $ref);
    $this->set('next', $next);
  }

  public function detail() {
    $data = null;
    $id = $this->request['id'];

    $rtc_sessions = new RtcSession($this->dbh);
    $data = $rtc_sessions->where('RtcSession.id', '=', $id)->find('first');
    $this->set('Title', 'RtcSession Ditail');
    $this->set('RtcSession', $data['RtcSession']);
    $this->set('data', $data);
  }

  public function create() {
    $this->debug->log("RtcSessionController::create()");
    $rtc_sessions = new RtcSession($this->dbh);
    $form = $rtc_sessions->createForm();
    $this->set('Title', 'RtcSession Create');
    $this->set('RtcSession', $form['RtcSession']);
  }

  public function save(){
    $this->debug->log("RtcSessionController::save()");
    try {
      $this->dbh->beginTransaction();
      $rtc_sessions = new RtcSession($this->dbh);
      $rtc_sessions->save($this->request);
      $this->dbh->commit();
      $url = BASE_URL . 'RtcSession' . '/show/' . $rtc_sessions->primary_key_value . '/';
      $this->redirect($url);
    } catch (\Exception $e) {
      $this->debug->log("RtcSessionController::create() error:" . $e->getMessage());
      $this->set('Title', 'RtcSession Save Error');
      $this->set('error_message', '保存ができませんでした。');
    }
  }

  public function edit() {
    $this->debug->log("RtcSessionController::edit()");
    try {
      $data = null;
      $id = $this->request['id'];

      $rtc_sessions = new RtcSession($this->dbh);
      $data = $rtc_sessions->where('RtcSession.id', '=', $id)->find('first');
      $this->set('Title', 'RtcSession Edit');
      $this->set('RtcSession', $data['RtcSession']);
      $this->set('data', $data);
    } catch (\Exception $e) {
      $this->debug->log("RtcSessionController::edit() error:" . $e->getMessage());
    }
  }

  public function delete() {
    try {
      $this->dbh->beginTransaction();
      $rtc_sessions = new RtcSession($this->dbh);
      $rtc_sessions->delete($this->request['id']);
      $this->dbh->commit();
      $url = BASE_URL . 'RtcSession' . '/index/';
    } catch (\Exception $e) {
      $this->debug->log("UsersController::delete() error:" . $e->getMessage());
    }
  }


}