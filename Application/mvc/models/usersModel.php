<?php
class usersModel extends baseModel {

  function getUserInfoByKey($key, $value){

    if( $value == NULL){ return false;}

    $query = "
      SELECT *
      FROM website_administrators
      WHERE $key = '$value';
      ";

    $array_result = my_db_mysqli::query_to_array($query);

    if (count($array_result) > 0){

      return $array_result[0];

    }

    return false;

  }

  function updateUserInfo($userID,$key,$newvalue){

      $newvalue = my_db_mysqli::escape($newvalue);

      $query = "
        UPDATE website_administrators
        SET $key = '$newvalue'
        WHERE id = '$userID';
        ";

      my_db_mysqli::query($query);

      }

  function getStudentCourses($course_id){

    $q = " SELECT s.Name AS student_name,
                  s.Email AS student_phone,
                  s.Image AS student_image,

                FROM website_courses_to_students c2s
                  INNER JOIN website_courses c ON (
                    c.id = c2s.hid
                  )
                  INNER JOIN website_students s ON (
                      s.id = '$course_id'
                  )
                  WHERE s.id = c2s.uid;

              ";

    $array_result = my_db_mysqli::query_to_array($q);

    $array_result_normal = $array_result;

  }

  function getCourseStudents($student_id){

    $q = " SELECT c.Name AS course__name,
                  c.Description AS course_desc,
                  c.Image AS course_image,

                FROM website_courses_to_students c2s
                  INNER JOIN website_courses c ON (
                    c.id = c2s.hid
                  )
                  INNER JOIN website_students s ON (
                      s.id = '$student_id'
                  )
                  WHERE s.id = c2s.uid;

              ";

    $array_result = my_db_mysqli::query_to_array($q);

    $array_result_normal = $array_result;

  }

  function getUsersObj(){
    $query = "
      SELECT *
      FROM website_administrators;";

    $array_result = my_db_mysqli::query_to_array($query);

    return $array_result;

  }

  function getRegisteredUsersAmt(){

    $query = "SELECT COUNT(*) as total FROM website_administrators";

    $result = my_db_mysqli::query_fetch($query);

    return $result['total'];


  }

  function registerAdmin($register_array){
    $query = " INSERT INTO website_administrators ( Name,Phone ,Email, Image, Role, Password )
    VALUES( '{$register_array["Name"]}','{$register_array["Phone"]}','{$register_array["Email"]}','{$register_array["Image"]}','{$register_array["Role"]}','{$register_array["Password"]}' ); ";

    my_db_mysqli::query($query);

  }

  function deleteAdmin($admin_id){

    $q = "   DELETE FROM website_administrators
              WHERE id = '$admin_id';
              ";

    $result = my_db_mysqli::query($q);

    return $result;


  } // WORKING!

  function getLastInsertedAdminID(){

    return my_db_mysqli::last_id();

  }

}
