<?php

abstract class baseModel{

    static $instance = null;

    protected static function get_DB($filter = null) { //remember singleton?
        if( self::$instance === null) { //first time
            self::$instance = new my_db_mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);;
        }

        return self::$instance;
    }
}

?>
