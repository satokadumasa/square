<?php
class ChatController extends \strangerfw\core\controller\BaseController{
  public function __construct($uri, $url = null) {
    $conf = \strangerfw\core\Config::get('database.config');
    $database = $conf['default_database'];
    parent::__construct($database, $uri, $url);
    $this->controller_class_name = str_replace('Controller', '', get_class($this));;
  }

  public function index() {
    $chats = new Chat($this->dbh);
    $limit = 10;
    $offset = 10 * (isset($this->request['page']) ? $this->request['page'] - 1 : 0);

    $data = $chats->where('Chat.id', '>', 0)->limit($limit)->offset($offset)->find('all');

    $ref = isset($this->request['page']) && ($this->request['page'] - 1 > 0) ? ($this->request['page'] - 1) : 0;
    $next = isset($this->request['page']) && ($this->request['page'] > 0) ? $this->request['page'] + 1 : 2;

    $this->set('Title', 'Chat List');
    $this->set('data', $data);
    $this->set('Chat', $data);
    $this->set('ref', $ref);
    $this->set('next', $next);
  }

  public function show() {
    $data = null;
    $id = $this->request['id'];

    $chats = new Chat($this->dbh);
    $data = $chats->where('Chat.id', '=', $id)->find('first');
    $this->set('Title', 'Chat Ditail');
    $this->set('Chat', $data['Chat']);
    $this->set('data', $data);
  }

  public function create() {
    $this->debug->log("ChatController::create()");
    $chats = new Chat($this->dbh);
    $form = $chats->createForm();
    $this->set('Title', 'Chat Create');
    $this->set('Chat', $form['Chat']);
  }

  public function save(){
    $this->debug->log("ChatController::save()");
    try {
      $this->dbh->beginTransaction();
      $chats = new Chat($this->dbh);
      $chats->save($this->request);
      $this->dbh->commit();
      $url = BASE_URL . 'Chat' . '/show/' . $chats->primary_key_value . '/';
      $this->redirect($url);
    } catch (\Exception $e) {
      $this->debug->log("ChatController::create() error:" . $e->getMessage());
      $this->set('Title', 'Chat Save Error');
      $this->set('error_message', '保存ができませんでした。');
    }
  }

  public function edit() {
    $this->debug->log("ChatController::edit()");
    try {
      $data = null;
      $id = $this->request['id'];

      $chats = new Chat($this->dbh);
      $data = $chats->where('Chat.id', '=', $id)->find('first');
      $this->set('Title', 'Chat Edit');
      $this->set('Chat', $data['Chat']);
      $this->set('data', $data);
    } catch (\Exception $e) {
      $this->debug->log("ChatController::edit() error:" . $e->getMessage());
    }
  }

  public function delete() {
    try {
      $this->dbh->beginTransaction();
      $chats = new Chat($this->dbh);
      $chats->delete($this->request['id']);
      $this->dbh->commit();
      $url = BASE_URL . 'Chat' . '/index/';
    } catch (\Exception $e) {
      $this->debug->log("UsersController::delete() error:" . $e->getMessage());
    }
  }


}