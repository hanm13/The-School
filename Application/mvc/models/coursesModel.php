<?php
class coursesModel extends baseModel {

  function getCourseInfoByKey($key, $value){

    if( $value == NULL){ return false;}

    $query = "
      SELECT *
      FROM website_courses
      WHERE $key = '$value';
      ";

    $array_result = my_db_mysqli::query_to_array($query);

    if (count($array_result) > 0){

      return $array_result[0];

    }

    return false;

  }

  function updateCourseInfo($courseID,$key,$newvalue){

      $newvalue = my_db_mysqli::escape($newvalue);

      $query = "
        UPDATE website_courses
        SET $key = '$newvalue'
        WHERE id = '$courseID';
        ";

      my_db_mysqli::query($query);

      }

  function getCourseStudents($course_id){

    $q = " SELECT *

                FROM website_courses_to_students c2s
                  INNER JOIN website_courses c ON (
                    c.id = c2s.cid
                  )
                  INNER JOIN website_students s ON (

                    s.id = c2s.sid

                  )
                  WHERE c.id = '$course_id';
              ";

    $array_result = my_db_mysqli::query_to_array($q);

    $array_result_normal = $array_result;

    return $array_result_normal;

  } // WORKING!

  function getCourseStudentsAmt($course_id){

    return count($this->getCourseStudents($course_id));

  }

  function getCoursesObj(){
    $query = "
      SELECT *
      FROM website_courses;";

    $array_result = my_db_mysqli::query_to_array($query);

    return $array_result;

  }

  function getCoursesAmt(){

    $query = "SELECT COUNT(*) as total FROM website_courses";

    $result = my_db_mysqli::query_fetch($query);

    return $result['total'];


  }

  function registerCourse($register_array){
    $query = " INSERT INTO website_courses ( Name, Image, Description )
    VALUES( '{$register_array["Name"]}','{$register_array["Image"]}','{$register_array["Description"]}' ); ";

    my_db_mysqli::query($query);

  }

  function deleteCourse($course_id){

    $q = "   DELETE FROM website_courses
              WHERE id = '$course_id';
              ";

    $result = my_db_mysqli::query($q);

    return $result;


  } // WORKING!

  function getLastInsertedCourseID(){

    return my_db_mysqli::last_id();

  }

}
