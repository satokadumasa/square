<?php

class UserHelper
{

  public function __construct()
  {
    $this->error_log = new \strangerfw\utils\Logger('ERROR');
    $this->info_log = new \strangerfw\utils\Logger('INFO');
    $this->debug = new \strangerfw\utils\Logger('DEBUG');
  }

  public function edit_link($data, $session, $options = null)
  {
    $this->debug->log("UserHelper::edit_link() START");
    $this->debug->log("UserHelper::edit_link() session:" . print_r($session, true));
    if ($this->check_permission($session, $data['User'])) {
      $link_url = DOCUMENT_ROOT . 'User/edit/' . $data['User']['id'];
      return "<a href='${link_url}'>EDIT</a>";
    } else {
      return "";
    }
  }

  public function delete_link($data, $session, $options = null)
  {
    $this->debug->log("UserHelper::delete_link() START");
    $this->debug->log("UserHelper::delete_link() session:" . print_r($session, true));
    if ($this->check_permission($session, $data['User'])) {
      $link_url = DOCUMENT_ROOT . 'User/delete/' . $data['User']['id'];
      return "<a href='${link_url}'>DELETE</a>";
    } else {
      return "";
    }
  }

  public function check_permission($session, $user)
  {
    $this->debug->log("UserHelper::check_permission() session:".print_r($session, true));
    $this->debug->log("UserHelper::check_permission() user:".print_r($user, true));
    if ($session['Auth']['User']['id'] == $user['id'] || $session['Auth']['User']['id'] == 1) {
      return true;
    }
    return false;
  }
}