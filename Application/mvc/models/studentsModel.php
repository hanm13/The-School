<?php
class studentsModel extends baseModel {

  function getStudentInfoByKey($key, $value){

    if( $value == NULL){ return false;}

    $query = "
      SELECT *
      FROM website_students
      WHERE $key = '$value';
      ";

    $array_result = my_db_mysqli::query_to_array($query);

    if (count($array_result) > 0){

      return $array_result[0];

    }

    return false;

  }

  function updateStudentInfo($studentID,$key,$newvalue){

      $newvalue = my_db_mysqli::escape($newvalue);

      $query = "
        UPDATE website_students
        SET $key = '$newvalue'
        WHERE id = '$studentID';
        ";

      my_db_mysqli::query($query);

      }

  function getStudentsObj($limit="",$offest=""){

    $query = "
      SELECT *
      FROM website_students";

      if($limit != "" && $limit > 0){

        $query .= " LIMIT $limit";

        if($offest != "" && $offest > 0 ){

          $query .= ", $offest";

        }

      }

    $array_result = my_db_mysqli::query_to_array($query);

    return $array_result;

  }

  function getStudentsAmt(){

    $query = "SELECT COUNT(*) as total FROM website_students";

    $result = my_db_mysqli::query_fetch($query);

    return $result['total'];


  }

  function getStudentCourses($student_id){

    $q = " SELECT c.id AS course_id, c.Name AS course_name, c.Image AS course_image, c.Description AS course_description

                FROM website_courses_to_students c2s
                  INNER JOIN website_courses c ON (
                    c.id = c2s.cid
                  )
                  INNER JOIN website_students s ON (

                    s.id = c2s.sid

                  )
                  WHERE s.id = '$student_id';
              ";

    $array_result = my_db_mysqli::query_to_array($q);

    $array_result_normal = $array_result;

    return $array_result_normal;

  } // WORKING!

  function getStudentCoursesIDsArray($student_id){

    $q = " SELECT c.id AS course_id

                FROM website_courses_to_students c2s
                  INNER JOIN website_courses c ON (
                    c.id = c2s.cid
                  )
                  INNER JOIN website_students s ON (

                    s.id = c2s.sid

                  )
                  WHERE s.id = '$student_id';
              ";

    $array_result = my_db_mysqli::query_to_array($q);

    $array_result_normal = [];

    foreach ($array_result as $key => $value) {
      array_push($array_result_normal, $value['course_id']);
    }

    return $array_result_normal;

  } // WORKING!

  function getCourseStudentsAmt($course_id){

    return count($this->getCourseStudents($course_id));

  }

  function deleteStudentCourses($student_id){

    $q = "   DELETE FROM website_courses_to_students
              WHERE sid = '$student_id';
              ";

    $result = my_db_mysqli::query($q);

    return $result;


  } // WORKING!

  function deleteStudent($student_id){

    $this->deleteStudentCourses($student_id);

    $q = "   DELETE FROM website_students
              WHERE id = '$student_id';
              ";

    $result = my_db_mysqli::query($q);

    return $result;


  } // WORKING!

  function addstudentCourse($student_id, $student_course){

    $q = "INSERT INTO website_courses_to_students (sid,cid)
              VALUES ('$student_id', '$student_course');
              ";

    $result = my_db_mysqli::query($q);

    return $result;


  }

  function deleteStudentCourse($student_id, $course_id){

    $q = "   DELETE FROM website_courses_to_students
              WHERE sid = '$student_id' AND cid = '$course_id';
              ";

    $result = my_db_mysqli::query($q);

    return $result;


  } // WORKING!


  function registerStudent($register_array){
    $query = " INSERT INTO website_students ( Name,Phone ,Email, Image )
    VALUES( '{$register_array["Name"]}','{$register_array["Phone"]}','{$register_array["Email"]}','{$register_array["Image"]}' ); ";

    my_db_mysqli::query($query);

  }

  function getLastInsertedStudentID(){

    return my_db_mysqli::last_id();

  }

}
