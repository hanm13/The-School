<?php


class noaccessController {

  function __construct(){

    $this->usersModel = new usersModel();
    $this->coursesModel = new coursesModel();
    $this->studentsModel = new studentsModel();

  }

  function noAction(){

    View::render('noaccess', $data);

  }

}

 ?>
