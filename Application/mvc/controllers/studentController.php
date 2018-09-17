<?php
class studentController {

    private $usersModel;
    private $coursesModel;
    private $studentsModel;

    function __construct(){

      $this->usersModel = new usersModel();
      $this->coursesModel = new coursesModel();
      $this->studentsModel = new studentsModel();

    }

    /*public function __call(​$method_name​, $args_array​)
    {
      ​if​( !​method_exists​(​$this​, "​"​ . ​$method_name) ) {
        ​die​(​"Method ​$method_name​ does not exist in "​. ​get_class​(​$this​) .​" controller"​);
      }
      ​//Do stuff BEFORE method called
      ​$this​->​do_before​(); ​//you can configure this to return false and then STOP the
      //execution here (auth for example)
      ​//Call the 'real' action/method
      ​call_user_func_array​([​$this​, "​"​ . ​$method_name],[]);
      ​//Do stuff AFTER method called
      ​$this​->​do_after​();
    }

    function do_before​(){

    }

    function ​do_after​(){

    }*/

    public function listAllAction() {

        $model = new usersModel();

        $filter = [];
        if(isset($_GET['sort_by'])) {
            $filter['sort_by'] = $_GET['sort_by'];
        }

        $list = $model->get_list($filter);

        $data = ['users' => $list];

        View::render('profiles_list', $data);
    }
    public function viewAction() {

        // Access Validation

        if (!is_logged_in()){

          redirect_and_die('/theschool/home');
        }

        $login_access_code = $_COOKIE['loginAccess'];
        $user_info = $this->usersModel->getUserInfoByKey(TOKEN_KEY_NAME, $login_access_code);

        $user_id = $user_info['id'];


        if ($user_info['Role'] == ROLE_MANAGER || $user_info['Role'] == ROLE_OWNER){

          $access_can_add = true;
          $access_can_edit = true;

        }

        $data = [
          'user_info' => $user_info,
          'website_user' => new userModel($user_id),
          'header_view' => [
            'user_info' => $user_info,
            'user_role' => convertUserRoleToName($user_info['Role']),
            'header_info' => [

              "Title" => "School",

            ],
          ],
          'courses_obj' => [ "courses_obj" => $this->coursesModel->getCoursesObj() ],
          'students_obj' => [ "students_obj" => $this->studentsModel->getStudentsObj() ],
          'courses_amt' => $this->coursesModel->getCoursesAmt(),
          'students_amt' => $this->studentsModel->getStudentsAmt(),
          'access_can_add' => $access_can_add,
        ];

        if(isset($_GET["id"]) && $_GET["id"] != ""){

          $_GET["id"] = my_db_mysqli::escape($_GET["id"]);

          $student_obj = $this->studentsModel->getStudentInfoByKey("id", $_GET['id']);

          $data['more_data'] = [
            'container_action' => "viewstudent",
            'student_obj' => $student_obj,
            'student_courses' => $this->studentsModel->getStudentCourses($_GET['id']),
            'msg' => $_GET['msg'],
            'access_can_edit' => $access_can_edit,

          ];

        }

        View::render('home_of_student_course', $data);

    }

    public function editAction() {

        // Access Validation

        if (!is_logged_in()){

          redirect_and_die('/theschool/home');
        }

        $login_access_code = $_COOKIE['loginAccess'];
        $user_info = $this->usersModel->getUserInfoByKey(TOKEN_KEY_NAME, $login_access_code);

        $user_id = $user_info['id'];

        $data = [
          'user_info' => $user_info,
          'website_user' => new userModel($user_id),
          'header_view' => [
            'user_info' => $user_info,
            'user_role' => convertUserRoleToName($user_info['Role']),
            'header_info' => [

              "Title" => "School",

            ],
          ],
          'courses_obj' => [ "courses_obj" => $this->coursesModel->getCoursesObj() ],
          'students_obj' => [ "students_obj" => $this->studentsModel->getStudentsObj() ],
        ];

        $errors = [];

        if(isset($_GET["id"]) && $_GET["id"] != ""){

          $_GET["id"] = my_db_mysqli::escape($_GET["id"]);

          $student_obj = $this->studentsModel->getStudentInfoByKey("id", $_GET['id']);

          if(is_array($student_obj)){

            if( isset($_POST['action']) && $_POST['action'] == "Save"){

              if(isset($_POST['editadd_name']) && $_POST['editadd_name'] != $student_obj['Name']){

                if(validateName($_POST['editadd_name'])){

                  $_POST['editadd_name'] = my_db_mysqli::escape($_POST['editadd_name']); // in case...

                  $this->studentsModel->updateStudentInfo($student_obj['id'], "Name", $_POST['editadd_name']);

                }else{

                  array_push($errors, "Invalid name!");

                }

              }

              if(isset($_POST['editadd_phone']) && $_POST['editadd_phone'] != $student_obj['Phone']){

                if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['editadd_phone'])){

                  $_POST['editadd_phone'] = my_db_mysqli::escape($_POST['editadd_phone']); // in case...

                  $this->studentsModel->updateStudentInfo($student_obj['id'], "Phone", $_POST['editadd_phone']);

                }else{

                  array_push($errors, "Invalid phone, valid e.g 000-000-0000!");

                }

              }

              if(isset($_POST['editadd_email']) && $_POST['editadd_email'] != $student_obj['Email']){

                if(validateEmail($_POST['editadd_email'])){

                  if(!$this->studentsModel->getStudentInfoByKey("Email", $_POST['editadd_email'])){

                    $_POST['editadd_email'] = my_db_mysqli::escape($_POST['editadd_email']); // in case...

                    $this->studentsModel->updateStudentInfo($student_obj['id'], "Email", $_POST['editadd_email']);

                  }else{

                    array_push($errors, "Email already used!");

                  }

                }else{

                  array_push($errors, "Invalid email!");

                }
              }
              if(isset($_POST['editadd_image']) && $_POST['editadd_image'] != "" && $_POST['editadd_image'] != $student_obj['Image']){
                $imgdataTable = explode( ',', $_POST['editadd_image'] );
                if(check_base64_image($imgdataTable[1])){

                  $this->studentsModel->updateStudentInfo($student_obj['id'], "Image", $_POST['editadd_image']);

                }else{

                  array_push($errors, "Invalid image!");

                }

              }

              if(isset($_POST['editadd_courses']) && $_POST['editadd_courses'] != ""){

                $updatedStudentCoursesIDs = $this->studentsModel->getStudentCoursesIDsArray($_GET['id']);

                if(is_array($_POST['editadd_courses'])){

                  foreach ($_POST['editadd_courses'] as $key => $value) {

                    if( !in_array($value, $updatedStudentCoursesIDs)){ // check if the student has the course, if not we will add it:

                      $this->studentsModel->addStudentCourse($student_obj['id'], $value);

                    }

                  }

                  //

                  foreach ($_POST['editadd_courses'] as $key => $cvalue) {

                    if (in_array($cvalue,$updatedStudentCoursesIDs)){

                      foreach ($updatedStudentCoursesIDs as $key => $value) {
                        if($value == $cvalue ){

                          unset($updatedStudentCoursesIDs[$key]);

                        }
                      }

                    }

                  }

                  foreach ($updatedStudentCoursesIDs as $key => $value) {
                    $this->studentsModel->deleteStudentCourse($student_obj['id'], $value);
                  }

                }else{

                  array_push($errors, "Invalid courses!");

                }

              }else{

                // Remove all if nothing is input checked

                $studentCoursesRemove = $this->studentsModel->getStudentCoursesIDsArray($_GET['id']);

                foreach ($studentCoursesRemove as $key => $value) {
                  $this->studentsModel->deleteStudentCourse($student_obj['id'], $value);
                }
              }

              //$student_obj = $this->studentsModel->getStudentInfoByKey("id", $_GET['id']); // refresh student info for view!

              if(count($errors) == 0){

                redirect_and_die("/student/view/?id=" . $_GET['id'] . "&msg=Student%20Saved!"); // Disable this for DEBUG!

              }

            }elseif(isset($_POST['action']) && $_POST['action'] == "Delete"){

              $this->studentsModel->deleteStudent($_GET['id']);

              redirect_and_die("/theschool/home/?msg=Student%20Deleted!");

            }

            // For edit view

            $data['more_data'] = [
              'container_action' => "editaddstudent",
              'student_obj' => $student_obj,
              "courses_obj" => $this->coursesModel->getCoursesObj(),
              'student_courses' => $this->studentsModel->getStudentCourses($_GET['id']),
              'student_courses_ids' => $this->studentsModel->getStudentCoursesIDsArray($_GET['id']),
              'form_errors' => $errors,
            ];



          }

          //Student Edit

        }else{ // Student ADD!

          if ($user_info['Role'] == ROLE_SALES){

            redirect_and_die('/theschool/home');

          }

          if(count($_POST) > 0){

            if(isset($_POST['editadd_name'])){

              if(!validateName($_POST['editadd_name'])){

                array_push($errors, "Invalid name!");

              }

            }else{

              array_push($errors, "You must add a name!");

            }

            if(isset($_POST['editadd_phone']) && $_POST['editadd_phone'] != ""){

              if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['editadd_phone'])){

                array_push($errors, "Invalid phone, valid e.g 000-000-0000!");

              }

            }else{

              array_push($errors, "You must add phone number!");

            }

            if(isset($_POST['editadd_email']) && $_POST['editadd_email'] != ""){

              if(validateEmail($_POST['editadd_email'])){

                if($this->studentsModel->getStudentInfoByKey("Email", $_POST['editadd_email'])){

                  array_push($errors, "Email already used!");
                }

              }else{

                array_push($errors, "Invalid email address!");
              }

            }else{

              array_push($errors, "You must add email address!");

            }

            if(isset($_POST['editadd_image']) && $_POST['editadd_image'] != ""){
              $imgdataTable = explode( ',', $_POST['editadd_image'] );
              if(!check_base64_image($imgdataTable[1])){

                array_push($errors, "Invalid image!");

              }

            }else{

              array_push($errors, "You must add image!");

            }

            // Creation:

            if( count($errors) == 0 ){

              $register_array = [

                "Name" => $_POST["editadd_name"],
                "Phone" => $_POST["editadd_phone"],
                "Email" => $_POST["editadd_email"],
                "Image" => $_POST["editadd_image"],

              ];

              $this->studentsModel->registerStudent($register_array);

              $new_student_id = $this->studentsModel->getLastInsertedStudentID();

              // After adding we need to add the courses:

              if(isset($_POST['editadd_courses']) && $_POST['editadd_courses'] != ""){

                if(is_array($_POST['editadd_courses'])){

                  foreach ($_POST['editadd_courses'] as $key => $value) {

                    $this->studentsModel->addStudentCourse($new_student_id, $value);

                  }

                }

              }

              redirect_and_die("/student/view/?id=" . $new_student_id . "&msg=Student%20Added!"); // Disable this for DEBUG!

            }

          }

          // For add view:

          $data['more_data'] = [
            'container_action' => "editaddstudent",
            "courses_obj" => $this->coursesModel->getCoursesObj(),
            'student_obj' => [

              "Name" => $_POST["editadd_name"],
              "Phone" => $_POST["editadd_phone"],
              "Email" => $_POST["editadd_email"],
              "Courses" => $_POST["editadd_courses"],
              "Image" => $_POST["editadd_image"],

            ],
            "form_errors" => $errors,
          ];

          // Student add

        }

        View::render('edit_of_student_course', $data);

    }

    function addAction(){

      $this->editAction();

    }

    function listJsonAction(){

      // Access Validation

      if (!is_logged_in()){

        redirect_and_die('/theschool/home');
      }

      if(isset($_POST['limit']) && $_POST['limit'] != "" && $_POST['limit'] > 0){

        if(isset($_POST['offest']) && $_GET['offest'] != "" && $_POST['offest'] > 0){

          $students_obj_data = $this->studentsModel->getStudentsObj($_POST['limit'], $_POST['offest']);

        }else{

          $students_obj_data = $this->studentsModel->getStudentsObj($_POST['limit']);

        }

      }else{
        $students_obj_data = $this->studentsModel->getStudentsObj();
      }

      $data = [
        'students_obj' => [ "students_obj" => $students_obj_data ],
      ];

      View::render('api_students', $data);

    }

    function update_apiAction(){

      // Access Validation

      if (!is_logged_in()){

        redirect_and_die('/theschool/home');
      }

      $errors = [];

      if(isset($_POST["id"]) && $_POST["id"] != ""){

        $_POST["id"] = my_db_mysqli::escape($_POST["id"]);

        $student_obj = $this->studentsModel->getStudentInfoByKey("id", $_POST['id']);

        if(is_array($student_obj)){

          if( isset($_POST['action_name']) && $_POST['action_name'] == "Save"){

            if(isset($_POST['editadd_name']) && $_POST['editadd_name'] != $student_obj['Name']){

              if(validateName($_POST['editadd_name'])){

                $_POST['editadd_name'] = my_db_mysqli::escape($_POST['editadd_name']); // in case...

                $this->studentsModel->updateStudentInfo($student_obj['id'], "Name", $_POST['editadd_name']);

              }else{

                array_push($errors, "Invalid name!");

              }

            }

            if(isset($_POST['editadd_phone']) && $_POST['editadd_phone'] != $student_obj['Phone']){

              if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['editadd_phone'])){

                $_POST['editadd_name'] = my_db_mysqli::escape($_POST['editadd_phone']); // in case...

                $this->studentsModel->updateStudentInfo($student_obj['id'], "Phone", $_POST['editadd_phone']);

              }else{

                array_push($errors, "Invalid phone, valid e.g 000-000-0000!");

              }

            }

            if(isset($_POST['editadd_email']) && $_POST['editadd_email'] != $student_obj['Email']){
              if(validateEmail($_POST['editadd_email'])){

                if(!$this->studentsModel->getStudentInfoByKey("Email", $_POST['editadd_email'])){

                  $_POST['editadd_email'] = my_db_mysqli::escape($_POST['editadd_email']); // in case...

                  $this->studentsModel->updateStudentInfo($student_obj['id'], "Email", $_POST['editadd_email']);

                }else{

                  array_push($errors, "Email already used!");

                }

              }else{

                array_push($errors, "Invalid email!");

              }

            }
            if(isset($_POST['editadd_image']) && $_POST['editadd_image'] != "" && $_POST['editadd_image'] != $student_obj['Image']){
              $imgdataTable = explode( ',', $_POST['editadd_image'] );
              if(check_base64_image($imgdataTable[1])){

                $this->studentsModel->updateStudentInfo($student_obj['id'], "Image", $_POST['editadd_image']);

              }else{

                array_push($errors, "Invalid image!");

              }

            }

            if(isset($_POST['editadd_courses']) && $_POST['editadd_courses'] != ""){

              $updatedStudentCoursesIDs = $this->studentsModel->getStudentCoursesIDsArray($_GET['id']);

              if(is_array($_POST['editadd_courses'])){

                foreach ($_POST['editadd_courses'] as $key => $value) {

                  if( !in_array($value, $updatedStudentCoursesIDs)){ // check if the student has the course, if not we will add it:

                    $this->studentsModel->addStudentCourse($student_obj['id'], $value);

                  }

                }

                //

                foreach ($_POST['editadd_courses'] as $key => $cvalue) {

                  if (in_array($cvalue,$updatedStudentCoursesIDs)){

                    foreach ($updatedStudentCoursesIDs as $key => $value) {
                      if($value == $cvalue ){

                        unset($updatedStudentCoursesIDs[$key]);

                      }
                    }

                  }

                }

                foreach ($updatedStudentCoursesIDs as $key => $value) {
                  $this->studentsModel->deleteStudentCourse($student_obj['id'], $value);
                }

              }else{

                array_push($errors, "Invalid courses!");

              }

            }else{

              // Remove all if nothing is input checked

              $studentCoursesRemove = $this->studentsModel->getStudentCoursesIDsArray($_GET['id']);

              foreach ($studentCoursesRemove as $key => $value) {
                $this->studentsModel->deleteStudentCourse($student_obj['id'], $value);
              }
            }

            //$student_obj = $this->studentsModel->getStudentInfoByKey("id", $_GET['id']); // refresh student info for view!

            echo "Student found/updated!";

          }

        }else{

          array_push($errors, "Invalid student ID!");

        }

      }

      if(count($errors) > 0){

        echo "errors: ";
        echo json_encode($errors);

      }

    }

}
