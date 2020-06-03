<?php

class MeetingRtcSessionController extends \strangerfw\core\controller\BaseController
{

  public function __construct($uri, $url = null)
  {
    $conf = \strangerfw\core\Config::get('database.config');
    $database = $conf['default_database'];
    parent::__construct($database, $uri, $url);
    $this->debug->log("MeetingRtcSessionController::__construct()");
    $this->controller_class_name = str_replace('Controller', '', get_class($this));
    ;
  }

  public function index()
  {
    $meeting_rtc_sessions = new \MeetingRtcSession($this->dbh);
    $limit = 10;
    $offset = 10 * (isset($this->request['page']) ? $this->request['page'] - 1 : 0);

    $data = $meeting_rtc_sessions->where('MeetingRtcSession.id', '>', 0)
      ->limit($limit)
      ->offset($offset)
      ->find('all');

    $ref = isset($this->request['page']) && ($this->request['page'] - 1 > 0) ? ($this->request['page'] - 1) : 0;
    $next = isset($this->request['page']) && ($this->request['page'] > 0) ? $this->request['page'] + 1 : 2;

    $this->set('Title', 'MeetingRtcSession List');
    $this->set('data', $data);
    $this->set('MeetingRtcSession', $data);
    $this->set('ref', $ref);
    $this->set('next', $next);
  }

  public function show()
  {
    $data = null;
    $id = $this->request['id'];

    $meeting_rtc_sessions = new \MeetingRtcSession($this->dbh);
    $data = $meeting_rtc_sessions->where('MeetingRtcSession.id', '=', $id)->find('first');
    $this->set('Title', 'MeetingRtcSession Ditail');
    $this->set('MeetingRtcSession', $data['MeetingRtcSession']);
    $this->set('data', $data);
  }

  public function create()
  {
    $this->debug->log("MeetingRtcSessionController::create()");
    $meeting_rtc_sessions = new \MeetingRtcSession($this->dbh);
    $form = $meeting_rtc_sessions->createForm();
    $this->set('Title', 'MeetingRtcSession Create');
    $this->set('MeetingRtcSession', $form['MeetingRtcSession']);
  }

  public function save()
  {
    $this->debug->log("MeetingRtcSessionController::save()");
    try {
      $this->dbh->beginTransaction();
      $meeting_rtc_sessions = new \MeetingRtcSession($this->dbh);
      $meeting_rtc_sessions->save($this->request);
      $this->dbh->commit();
      $url = BASE_URL . 'MeetingRtcSession' . '/show/' . $meeting_rtc_sessions->primary_key_value . '/';
      $this->redirect($url);
    } catch (\Exception $e) {
      $this->debug->log("MeetingRtcSessionController::create() error:" . $e->getMessage());
      $this->set('Title', 'MeetingRtcSession Save Error');
      $this->set('error_message', '保存ができませんでした。');
    }
  }

  public function edit()
  {
    $this->debug->log("MeetingRtcSessionController::edit()");
    try {
      $data = null;
      $id = $this->request['id'];

      $meeting_rtc_sessions = new \MeetingRtcSession($this->dbh);
      $data = $meeting_rtc_sessions->where('MeetingRtcSession.id', '=', $id)->find('first');
      $this->set('Title', 'MeetingRtcSession Edit');
      $this->set('MeetingRtcSession', $data['MeetingRtcSession']);
      $this->set('data', $data);
    } catch (\Exception $e) {
      $this->debug->log("MeetingRtcSessionController::edit() error:" . $e->getMessage());
    }
  }

  public function registsdp()
  {
    $this->debug->log("MeetingRtcSessionController::registsdp() request:" . print_r($this->request, true));
    $session = \strangerfw\core\Session::get();
    try {
      $this->dbh->beginTransaction();
      $meeting = new \Meeting($this->dbh);
      $session = \strangerfw\core\Session::get();

      $data = $meeting->contain([
        'MeetingRtcSession' => [
          'RtcSession'
        ]
      ])
        ->select([
        'Meeting' => [
          'title',
          'meeting_hash_key',
          'password',
          'last_connected_at'
        ],
        'MeetingRtcSession' => [
          'meeting_id',
          'rtc_session_id'
        ],
        'RtcSession' => [
          'id',
          'sdp',
          'status'
        ]
      ])
        ->where('Meeting.id', '=', $this->request['meeting_id'])
        ->where('RtcSession.user_id', '=', $session['Auth']['User']['id'])
        ->find('first');

      // $this->debug->log("MeetingRtcSessionController::registsdp() MeetingRtcSession:" . print_r($data['MeetingRtcSession'], true));
      // $this->debug->log("MeetingRtcSessionController::registsdp() RtcSession:" . print_r($data['RtcSession'], true));
      $data['RtcSession'] = [
        'id' => $data['RtcSession']['id'] ? $data['RtcSession']['id'] : null,
        'user_id' => $session['Auth']['User']['id'],
        'sdp' => $this->request['sdp'],
        'status' => 1
      ];
      $data['MeetingRtcSession'] = [
        'user_id' => $session['Auth']['User']['id'],
        'meeting_id' => $this->request['meeting_id']
      ];
      $request = [
        'status' => 'Success'
      ];

      // $this->debug->log("MeetingRtcSessionController::registsdp() data:" . print_r($data, true));
      $meeting_rtc_session = new \MeetingRtcSession($this->dbh);
      $meeting_rtc_session->registsdp($data);
      // $meeting->registsdp($data);

      // \UserService::registCoTurnUser(DOMAIN_NAME, $this->request['username'], $this->request['password'], $return);

      $result = [
        'status' => true
      ];
      $this->debug->log("MeetingRtcSessionController::registsdp() execute commit");
      $this->dbh->commit();
      $this->debug->log("MeetingRtcSessionController::registsdp() commited");
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      exit();
    } catch (\Exception $e) {
      $result = [
        'status' => false
      ];
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      exit();
    }
  }

  public function remove()
  {
    $this->debug->log("MeetingRtcSessionController::remove() request:" . print_r($this->request, true));
    $session = \strangerfw\core\Session::get();
    try {
      $this->dbh->beginTransaction();
      $meeting_rtc_session = new \MeetingRtcSession($this->dbh);
      $session = \strangerfw\core\Session::get();

      $meeting_rtc_sessions = $meeting_rtc_session->contain([
        'RtcSession'
      ])
        ->select([
        'MeetingRtcSession' => [
          'id',
          'meeting_id',
          'rtc_session_id'
        ],
        'RtcSession' => [
          'user_id'
        ]
      ])
        ->where('MeetingRtcSession.meeting_id', '=', $this->request['meeting_id'])
        ->where('RtcSession.user_id', '=', $session['Auth']['User']['id'])
        ->find('all');

      $this->debug->log("MeetingRtcSessionController::remove() meeting_rtc_sessions:" . print_r($meeting_rtc_sessions, true));

      foreach ($meeting_rtc_sessions as $key => $datum) {
        $meeting_rtc_session = new \MeetingRtcSession($this->dbh);
        $rtc_session = new \RtcSession($this->dbh);
        $meeting_rtc_session
          ->where('MeetingRtcSession.meeting_id', '=', $datum['MeetingRtcSession']['meeting_id'])
          ->where('MeetingRtcSession.rtc_session_id', '=', $datum['MeetingRtcSession']['rtc_session_id'])->delete();
        $rtc_session->where('RtcSession.id', '=', $datum['MeetingRtcSession']['rtc_session_id'])
          ->where('RtcSession.user_id', '=', $datum['RtcSession']['user_id'])->delete();
      }

      $result = [
        'status' => true
      ];

      $this->dbh->commit();
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      exit();
    } catch (\Exception $e) {
      $this->debug->log("MeetingRtcSessionController::remove() error:" . $e->getMessage());
      $result = [
        'status' => false
      ];
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      exit();
    }
  }

  public function checksdp()
  {
    $this->debug->log("MeetingRtcSessionController::checksdp() START");
    $this->debug->log("MeetingRtcSessionController::checksdp() request:".print_r($this->request, true));
    $session = \strangerfw\core\Session::get();
    $result = [];
    try {
      $this->dbh->beginTransaction();
      $meeting = new \Meeting($this->dbh);
      $session = \strangerfw\core\Session::get();

      $data = $meeting->contain([
        'MeetingRtcSession' => [
          'RtcSession' => ['User'],
        ]
      ])
        ->select([
        'Meeting' => [
          'id',
          'title',
          'meeting_hash_key',
          'password',
          'last_connected_at'
        ],
        'MeetingRtcSession' => [
          'meeting_id',
          'rtc_session_id'
        ],
        'RtcSession' => [
          'sdp',
          'status'
        ],
        'User' => [
            'username'
        ],
      ])
        ->where('MeetingRtcSession.meeting_id', '=', $this->request['meeting_id'])
        ->where('RtcSession.user_id', '<>', $session['Auth']['User']['id'])
        ->find('first');

      if (isset($data['RtcSession']) && isset($data['RtcSession']['sdp'])) {
        $result = [
          'username' => $data['User']['username'],
          'status' => true,
          'sdp' => $data['RtcSession']['sdp']
        ];
      } else {
        $result = [
          'status' => false
        ];
      }
      $this->debug->log("MeetingRtcSessionController::checksdp() find RtcSession:" . print_r($result, true));
      $this->debug->log("MeetingRtcSessionController::checksdp() END");

      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      exit();
    } catch (\Exception $e) {
      $this->debug->log("MeetingRtcSessionController::checksdp() error:" . $e->getMessage());
      $this->set('Title', 'User Save Error');
      $this->set('error_message', '保存ができませんでした。');
      echo json_encode($request, JSON_UNESCAPED_UNICODE);
      exit();
    }
  }

  public function delete()
  {
    try {
      $this->dbh->beginTransaction();
      $meeting_rtc_sessions = new MeetingRtcSession($this->dbh);
      $meeting_rtc_sessions->delete($this->request['id']);
      $this->dbh->commit();
      $url = BASE_URL . 'MeetingRtcSession' . '/index/';
    } catch (\Exception $e) {
      $this->debug->log("UsersController::delete() error:" . $e->getMessage());
    }
  }
}