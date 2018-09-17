<?php

abstract class MegaDB{

  protected $website_user_HOST,$website_user_USERNAME,$website_user_PASSWORD,$website_user_NAME;

  function __construct($website_user_HOST,$website_user_USERNAME,$website_user_PASSWORD,$website_user_NAME) {

      $this->website_user_HOST = $website_user_HOST;
      $this->website_user_USERNAME = $website_user_USERNAME;
      $this->website_user_PASSWORD = $website_user_PASSWORD;
      $this->website_user_NAME = $website_user_NAME;

      $this->connect();

  }

  //abstract public function connect();
  //abstract static public function select($table, $columns="*", $where=null, $order=null, $sina=FALSE);
  //abstract public function insert($location, $data);
  //abstract public function delete();
  //abstract public function update();
  public function close(){

    $this->conn::close();

  }

}

?>
