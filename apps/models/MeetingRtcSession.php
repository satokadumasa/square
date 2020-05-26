<?php
class MeetingRtcSession extends \strangerfw\core\model\BaseModel {
  public $table_name  = 'meeting_rtc_sessions';
  public $model_name  = 'MeetingRtcSession';
  public $model_class_name  = 'MeetingRtcSession';

  //  Relation
  public $belongthTo = [
    'User' => [
      'JOIN_COND' => 'LEFT',
      'CONDITIONS' => [
        'MeetingRtcSession.user_id' => 'User.id',
      ],
    ],
    'Meeting' => [
      'JOIN_COND' => 'LEFT',
      'CONDITIONS' => [
        'MeetingRtcSession.meeting_id' => 'Meeting.id'
      ]
    ],
    'RtcSession' => [
      'JOIN_COND' => 'LEFT',
      'CONDITIONS' => [
        'MeetingRtcSession.rtc_session_id' => 'RtcSession.id'
      ]
    ]
  ];

  public $has = null;
  public $has_many_and_belongs_to = null;

  public function __construct(&$dbh) {
    parent::__construct($dbh);
  }

  public function registsdp($form){
    $this->debug->log("MeetingRtcSession::registsdp() form:".print_r($form, true));
    try {
      $data = [];
      $data['RtcSession'] = $form['RtcSession'];
      $rtc_session = new \RtcSession($this->dbh);
      $rtc_session->save($data);
      $data = [];
      $data['MeetingRtcSession'] = $form['MeetingRtcSession'];
      $data['MeetingRtcSession']['rtc_session_id'] = $rtc_session->last_insert_id;
      parent::save($data);
    } catch (\PDOException $e) {
      $this->debug->log("MeetingRtcSession::registsdp() error:" . $e->getMessage());
      throw new \Exception($e->getMessage(), 1);
    } catch (\Exception $e) {
      $this->debug->log("MeetingRtcSession::registsdp() error:" . $e->getMessage());
      throw new \Exception($e->getMessage(), 1);
    }
  }
}
