<?php

class my_db_mysqli extends MegaDB{

  private $conn;

  function connect(){

    $this->conn = mysqli_connect(
      $this->website_user_HOST,
      $this->website_user_USERNAME,
      $this->website_user_PASSWORD,
      $this->website_user_NAME
      );

      if (!$this->conn) {
          error_log( "Error: Unable to connect to MySQL." . PHP_EOL);
          error_log( "Debugging errno: " . mysqli_connect_errno() . PHP_EOL);
          error_log( "Debugging error: " . mysqli_connect_error() . PHP_EOL);
          die;
      }

      return $this->conn;

  }

  static public function select($table, $columns="*", $where=null, $order=null, $sina=FALSE){

    $q = "SELECT ${columns} FROM `${table}`";
    if($where != null)
    {
      $q .= " WHERE ".$where;
    }
    if($order != null)
    {
      $q .= " ORDER BY ".$order;
    }

    return my_db_mysqli::query_to_array($q);

  }

  /* Example of Insert usage:

  $orders = [

    [
      "uid" => 1,
      "name" => "test",
      "info" => "test",
    ],
    [
      "uid" => 2,
      "name" => "test",
      "info" => "test",
    ],

  ];
  my_db_mysqli::insert("website_orders",$orders);

  */

  static public function insert($table_name, $rows_array){

    // Shmul's way :


    $q = " INSERT INTO ${table_name} ( ";

    $keys_arrays = array_keys($rows_array[0]);
    $q .= implode(', ', $keys_arrays) . ") VALUES ";

    // chen's  way :

    /*

    foreach ($rows_array[0] as $key => $value) {
       $q = $q . $key . ",";
    }

    $q = trim($q,',') . ") VALUES (";

    */

    foreach ($rows_array as $row) {

      $q .= "(";

      foreach ($keys_arrays as $field_name) {

        $value = my_db_mysqli::escape($row[$field_name]);
        $q .= "'{$value}',";
      }

      $q = trim($q, ',');

      $q .=  "),";
    }

    $q = trim($q,',');

    return my_db_mysqli::query($q);

  }

  static public function update($table_name, $rows_array,$where=null,$from=null,$joins=null){

    // Shmul's way :

    $keys_arrays = array_keys($rows_array);


    $q = " UPDATE ${table_name} SET ( ";

    foreach ($keys_arrays as $field_name) {

      $value = my_db_mysqli::escape($rows_array[$field_name]);
      $q .= $field_name . " = " . "'{$value}',";
    }

    $q = trim($q, ',');

    $q .=  "),";

    $q = trim($q,',');

    if($from && $joins != null){

      $q .= " FROM " . $from;

      foreach ($joins as $join => $value) {
        $q .= " " . $value;
      }

    }


    // Where check


    $q .= " WHERE 1 "; // to all

    if($where != null){

      $q .= " AND " . $where;

    }

    //echo $q;
    return my_db_mysqli::query($q);

  }

  static public function escape($string){

    global $website_mysql_connection;

    $string = $website_mysql_connection->conn->real_escape_string($string);

    return $string;

  }


  static public function query_to_array($query){

    global $website_mysql_connection;

    $results_assoc_array = [];

    $query_result = $website_mysql_connection->conn->query( $query );

    if ($website_mysql_connection->conn->error){

      die('Query returned error : '. $website_mysql_connection->conn->error);

    }

    while ( $row = $query_result->fetch_assoc()){

      array_push($results_assoc_array, $row);

    }

    return $results_assoc_array;

  }

  static public function last_id(){
    global $website_mysql_connection;
    return $website_mysql_connection->conn->insert_id;
  }

  static public function query($query){

    global $website_mysql_connection;

   $q = $website_mysql_connection->conn->query($query);

   if ($website_mysql_connection->conn->errno > 0){

     error_log('Query returned error : '. $website_mysql_connection->conn->error);

   }

    return $q;

  }

  static public function query_fetch($query){

    global $website_mysql_connection;

    $query_result = $website_mysql_connection->conn->query( $query );

    return $query_result->fetch_assoc();

  }

  // Can also be used(duplicated) as template for  useful function for any MVC model, Login SYSTEM / Admin system, etc

  static public function getTableInfoByKeyValue($from,$key, $value){

    if( $value == NULL){ return false;}
    if( $from == NULL){ return false;}

    $query = "
      SELECT *
      FROM $from
      WHERE $key = '$value';
      ";

    $array_result = my_db_mysqli::query_to_array($query);

    if (count($array_result) > 0){

      return $array_result[0];

    }

    return false;

  }

  static public function updateTableInfoByIDKeyValue($from,$id,$key,$newvalue){

      $newvalue = my_db_mysqli::escape($newvalue);

      $query = "
        UPDATE $from
        SET $key = '$newvalue'
        WHERE id = '$id';
        ";

      my_db_mysqli::query($query);

    }

    static public function updateTableInfoByKeyValue($from,$where,$key,$newvalue){

        $newvalue = my_db_mysqli::escape($newvalue);

        $query = "
          UPDATE $from
          SET $key = '$newvalue'
          $where;
          ";

        my_db_mysqli::query($query);

      }

}
$website_mysql_connection = new my_db_mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);

// getTableInfoByKeyValue usage example :
//var_dump(my_db_mysqli::getTableInfoByKeyValue("website_administrators","id",26));

// updateTableInfoByIDKeyValue usage example :

//var_dump(my_db_mysqli::updateTableInfoByIDKeyValue("website_administrators",26,"Name", "Chen Magled"));

// updateTableInfoByKeyValue usage example :

//var_dump(my_db_mysqli::updateTableInfoByKeyValue("website_administrators","WHERE id = 26","Name", "Chen Magleds"));

 ?>
