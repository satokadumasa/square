<?php
// require_once dirname(dirname(dirname(__FILE__))) . "/config/config.php";
class MenuHelper{
  public $error_log;
  public $info_log;
  public $debug;

  private $auth = null;
  public function __construct($auth) {
    $this->error_log = new \strangerfw\utils\Logger('ERROR');
    $this->info_log = new \strangerfw\utils\Logger('INFO');
    $this->debug = new \strangerfw\utils\Logger('DEBUG');
    $this->auth = $auth;
  }

  public function site_menu($auth, $option = 'nologin'){
    $log_out_str = "<a href='".DOCUMENT_ROOT."logout' class='dropdown-item'>Logout</a>";
    $user_edit = "<a href='".DOCUMENT_ROOT."User/edit/".$this->auth['User']['id']." class='dropdown-item'>UserEdit</a>";
    if (isset($this->auth['User']['UserInfo'])) {
      $user_info_edit = '<a href="'.DOCUMENT_ROOT.'UserInfo/edit/'.$this->auth['User']['id'].'" class="dropdown-item">UserInfo</a><br>' ;
    }
    else {
      $user_info_edit = '<a href="'.DOCUMENT_ROOT.'UserInfo/create/" class="dropdown-item">UserInfo</a>';
    }

    $regist_url = '<a href="'.DOCUMENT_ROOT.'User/create/">利用者登録</a>';
    $login = '<a href="'.DOCUMENT_ROOT.'login">Login<br></a>';
    // if (isset($auth[]))
    if ($option == 'logined') {
      $site_menu = <<<EOF
    <div class="btn-group">
      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
       System Menu
      </button>
      <div class="dropdown-menu">
          $log_out_str
          $user_edit
          $user_info_edit
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#">その他リンク</a>
      </div><!-- /.dropdown-menu -->
    </div><!-- /.btn-group -->
EOF;

    } else {
      $site_menu = <<<EOF
    <div class="btn-group">
      <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
       System Menu
      </button>
      <div class="dropdown-menu">
          $login
          $regist_url
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#">その他リンク</a>
      </div><!-- /.dropdown-menu -->
    </div><!-- /.btn-group -->
EOF;
    }
    return $site_menu;
  }
}