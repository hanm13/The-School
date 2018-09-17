<?php

class userModel extends usersModel{

  private $last_modified_date = '';
  public $user_id = -1;

  function __construct($user_id){

    if (isset($user_id)){

      $this->user_id = $user_id;

    }

  }

  function updateInfo($key,$newvalue){

    $newvalue = my_db_mysqli::escape($newvalue);

    $user_info = $this->getUserInfoByKey("id",$this->user_id);

    if($user_info[$key] != $newvalue){

      $query = "
        UPDATE website_administrators
        SET $key = '$newvalue'
        WHERE id = '$this->user_id';
        ";

      $website_mysql_connection->query($query);

      }


    }

    function isAdmin(){

      if($this->user_info['Role'] == ROLE_MANAGER || $this->user_info['Role'] == ROLE_OWNER){

        return true;

      }

      return false;

    }

    function isOwner(){

      if($this->user_info['Role'] == ROLE_OWNER){

        return true;

      }

      return false;

    }

}

 ?>
