<?php
class MeetingController extends \strangerfw\core\controller\BaseController{
  public function __construct($uri, $url = null) {
    $conf = \strangerfw\core\Config::get('database.config');
    $database = $conf['default_database'];
    parent::__construct($database, $uri, $url);
    $this->controller_class_name = str_replace('Controller', '', get_class($this));;
  }

  public function index() {
    $meeting = new Meeting($this->dbh);
    $limit = 10;
    $offset = 10 * (isset($this->request['page']) ? $this->request['page'] - 1 : 0);

    $data = $meeting->where('Meeting.id', '>', 0)->limit($limit)->offset($offset)->find('all');

    $ref = isset($this->request['page']) && ($this->request['page'] - 1 > 0) ? ($this->request['page'] - 1) : 0;
    $next = isset($this->request['page']) && ($this->request['page'] > 0) ? $this->request['page'] + 1 : 2;

    $this->set('Title', 'Meeting List');
    $this->set('data', $data);
    $this->set('Meeting', $data);
    $this->set('ref', $ref);
    $this->set('next', $next);
  }

  public function detail() {
    $data = null;
    $id = $this->request['id'];
    $this->debug->log("MeetingController::detail() request:".print_r($this->request, true));

    $meeting = new Meeting($this->dbh);
    try {
      $data = $meeting->contain([
        'MeetingRtcSession' => [
          'RtcSession',
        ],
      ])
      ->select([
        'Meeting' => [
          'id',
          'user_id',
          'title',
          'meeting_hash_key',
        ],
        'RtcSession' => [
          'sdp',
          'status',
        ]
      ])
      ->where('Meeting.id', '=', $id)->find('first');

      // $db = new SQLite3('turndb.db');
      // $sql = "UPDATE table1 SET column1=1";
      // $db->exec($sql);
      // $db->close();

      $this->debug->log("MeetingController::detail() data:".print_r($data, true));

      $this->set('Title', 'Meeting Room');
      $this->set('Meeting', $data['Meeting']);
      $this->set('data', $data);
    } catch (\Exception $e) {
      $this->debug->log("MeetingController::detail() error:" . $e->getMessage());
      $this->set('Title', 'Meeting Room');
      $this->set('Meeting', $data['Meeting']);
      $this->set('data', $data);
    }
  }

  public function create() {
    $this->debug->log("MeetingController::create()");
    $meetings = new Meeting($this->dbh);
    $form = $meetings->createForm();
    $form['Meeting']['user_id'] = \strangerfw\core\Session::get()['Auth']['User']['id'];
    $this->set('Title', 'Meeting Create');
    $this->set('Meeting', $form['Meeting']);
  }

  public function save(){
    $this->debug->log("MeetingController::save()");
    try {
      $this->dbh->beginTransaction();
      $meetings = new Meeting($this->dbh);
      $meetings->save($this->request);
      $this->dbh->commit();
      $url = BASE_URL . 'Meeting' . '/detail/' . $meetings->primary_key_value . '/';
      $this->redirect($url);
    } catch (\Exception $e) {
      $this->debug->log("MeetingController::create() error:" . $e->getMessage());
      $this->set('Title', 'Meeting Save Error');
      $this->set('error_message', 'ミーティング情報の保存ができませんでした。');
    }
  }

  public function edit() {
    $this->debug->log("MeetingController::edit()");
    try {
      $data = null;
      $id = $this->request['id'];

      $meetings = new Meeting($this->dbh);
      $data = $meetings->where('Meeting.id', '=', $id)->find('first');
      $this->set('Title', 'Meeting Edit');
      $this->set('Meeting', $data['Meeting']);
      $this->set('data', $data);
    } catch (\Exception $e) {
      $this->debug->log("MeetingController::edit() error:" . $e->getMessage());
    }
  }

  public function delete() {
    try {
      $this->dbh->beginTransaction();
      $meetings = new Meeting($this->dbh);
      $meetings->delete($this->request['id']);
      $this->dbh->commit();
      $url = BASE_URL . 'Meeting' . '/index/';
    } catch (\Exception $e) {
      $this->debug->log("UsersController::delete() error:" . $e->getMessage());
    }
  }


}