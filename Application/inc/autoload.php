<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

require_once("../inc/config.php");
require_once("../inc/MegaDB.php");
require_once("../inc/my_db_mysqli.php");

spl_autoload_register(function($class_name) {

    if( strpos($class_name, 'Model')!==false ) {
        //usersModel
        $path = sprintf('../mvc/models/%s.php', $class_name);
    }elseif( strpos($class_name, 'Controller')!==false ) {
        $path = sprintf('../mvc/controllers/%s.php', $class_name);
    }else{
        $path = sprintf('../inc/%s.php', $class_name);
    }

    if(file_exists($path)){

      require_once($path);

    }

});

require_once("../inc/user_functions.php");

?>
