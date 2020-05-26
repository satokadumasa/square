<?php
class UserController extends \strangerfw\core\controller\BaseController
{

  public $controller_class_name;

  protected $dbh_coturn = null;

  protected $dbConnect_coturn = null;

  public function __construct($uri, $url = null)
  {
    $conf = \strangerfw\core\Config::get('database.config');
    $database = $conf['default_database'];
    parent::__construct($database, $uri, $url);
    $this->debug->log('UsersController::__construct() CH-01:');
    // if(isset($this->request)){
    //   var_dump($this->request);
    //   $this->debug->log('UsersController::__construct() reqest:'.print_r($this->request, true));
    // }
    $this->debug->log('UsersController::__construct() CH-02:');

    $database = $conf['sqlite_database'];

    $this->controller_class_name = str_replace('Controller', '', get_class($this));
    $this->setAuthCheck([
      'create',
      'edit',
      'show',
      'save',
      'delete'
    ]);
    $this->role_ids = \strangerfw\core\Config::get('acc/users');
    $this->debug->log("UserController::__construct() end");
  }

  /**
   * ログイン画面
   */
  public function login()
  {
    $this->debug->log("UserController::login() START");
    $auths = new \User($this->dbh);
    $form = $auths->createForm();
    $this->set('Title', 'Auth Login');
    $this->set('User', $form['User']);
    $this->debug->log("UserController::login() END");
  }

  /**
   * ログアウト処理
   */
  public function logout()
  {
    session_destroy();
    $this->redirect(DOCUMENT_ROOT);
  }

  /**
   * ログイン処理
   */
  public function auth()
  {
    $this->debug->log('UsersController::auth()');
    $url = "";
    $data = null;
    try {
      if (\strangerfw\Authentication::auth($this->dbh, $this->request)) {
        $data = [
          'status' => 'Success'
        ];
        $this->debug->log('UsersController::auth() Success');
        // $url = DOCUMENT_ROOT;
      } else {
        $data = [
          'status' => 'False'
        ];
        $this->debug->log('UsersController::auth() False');
        throw new \Exception("", 1);
        // $url = DOCUMENT_ROOT . 'login/';
      }
      $this->debug->log('UsersController::auth() url:' . $url);
      echo json_encode($data, JSON_UNESCAPED_UNICODE);
      exit();
      // $this->redirect($url);
    } catch (\Exception $e) {
      echo json_encode($data, JSON_UNESCAPED_UNICODE);
      exit();
      // $this->redirect(DOCUMENT_ROOT . 'login/');
    }
  }

  /**
   */
  public function confirm()
  {
    $user = new \User($this->dbh);
    $user->confirm($this->request['confirm_string']);
    $this->set('Title', 'User Confirmed');
    $this->set('message', 'Welcom, Confirmed your redistration.');
    $this->set('User', $data['User']);
    $this->set('data', $data);
    $this->redirect(DOCUMENT_ROOT . 'login/');
    exit();
  }

  public function index()
  {
    $this->debug->log('UserController::index()');
    $users = new \User($this->dbh);
    $limit = 10;
    $offset = 10 * (isset($this->request['page']) ? $this->request['page'] - 1 : 0);

    $data = $users->contain([
      'UserInfo'
    ])
      ->select([
      'User' => [
        'id',
        'username',
        'role_id'
      ],
      'UserInfo' => [
        'first_name',
        'last_name',
        'address'
      ]
    ])
      ->find();

    $ref = isset($this->request['page']) && ($this->request['page'] - 1 > 0) ? ($this->request['page'] - 1) : 0;
    $next = isset($this->request['page']) && ($this->request['page'] > 0) ? $this->request['page'] + 1 : 2;

    $this->set('Title', 'User List');
    $this->set('data', $data);
    $this->set('User', $data);
    $this->set('Session', \strangerfw\core\Session::get());
    $this->set('ref', $ref);
    $this->set('next', $next);
  }

  public function detail()
  {
    $data = null;
    $id = $this->request['id'];

    $users = new \User($this->dbh);
    $data = $users->where('User.id', '=', $id)->find('first');
    $this->debug->log("UserComtroll::detail() data:" . print_r($data, true));
    $this->set('Title', 'User Ditail');
    $this->set('User', $data['User']);
    $this->set('data', $data);
  }

  public function create()
  {
    $users = new \User($this->dbh);
    $form = $users->createForm();
    $this->set('Title', 'User Create');
    $this->set('User', $form['User']);
  }

  public function save()
  {
    try {
      $this->debug->log("UserController::save() START");
      $this->dbh->beginTransaction();
      $users = new \User($this->dbh);

      $users->save($this->request);

      \UserService::registCoTurnUser(DOMAIN_NAME, $this->request['User']['username'], $this->request['User']['password'], $return);
      // $this->debug->log("UserController::save() return:" . print_r($return, true));

      $this->dbh->commit();
      $cmd = 'php ' . BIN_PATH . 'send_notify.php';
      $result = exec($cmd);
      $this->set('Title', 'User Save');
      $this->redirect('/');
    } catch (\Exception $e) {
      $this->debug->log("UserController::save() error:" . $e->getMessage());
      $this->set('Title', 'User Save Error');
      $this->set('error_message', '保存ができませんでした。');
    }
  }

  public function edit()
  {
    try {
      $data = null;
      $id = $this->request['id'];

      $users = new \User($this->dbh);
      $session = \strangerfw\core\Session::get();
      if (! $user->check_permission($session['Auth'], $this->request))
        $this->redirect(DOCUMENT_ROOT);
      if (! $user->check_permission($session['Auth'], $this->request))
        throw new \Exception("権限がありません。", 1);

      $data = $users->where('User.id', '=', $id)->find('first');
      $this->set('Title', 'User Edit');
      $this->set('User', $data['User']);
      $this->set('data', $dats);
    } catch (\Exception $e) {
      $this->debug->log("UserController::edit() error:" . $e->getMessage());
      $this->redirect(DOCUMENT_ROOT);
    }
  }

  public function update()
  {
    $session = \strangerfw\core\Session::get();
    try {
      $this->dbh->beginTransaction();
      $user = new \User($this->dbh);

      if (! isset($session['Auth'])) {
        throw new \Exception("権限がありません。", 1);
      }
      if (! $user->check_permission($session, $this->request)){
        throw new \Exception("権限がありません。", 1);
      }

      // if (isset($session['Auth']) && (isset($session['Auth'][$user->primary_key]) && $session['Auth'][$user->primary_key] != $this->request['User'][$user->primary_key])) {
      // throw new \Exception("権限がありません。", 1);
      // }

      $user->update($this->request);

      \UserService::registCoTurnUser(DOMAIN_NAME, $this->request['username'], $this->request['password'], $return);

      $this->dbh->commit();
      $this->redirect(DOCUMENT_ROOT . 'User/show/' . $this->request['User'][$users->primary_key] . '/');
      $this->set('Title', 'User Save Error');
    } catch (\Exception $e) {
      $this->debug->log("UserController::update() error:" . $e->getMessage());
      $this->set('Title', 'User Save Error');
      $this->set('error_message', '保存ができませんでした。');
      $this->redirect(DOCUMENT_ROOT);
    }
  }


  public function delete()
  {
    try {
      $this->dbh->beginTransaction();
      $users = new \User($this->dbh);
      $users->delete($this->request['id']);
      $this->dbh->commit();
      $url = BASE_URL . User . '/index/';
    } catch (\Exception $e) {
      $this->debug->log("UsersController::delete() error:" . $e->getMessage());
    }
  }
}
