<?php
class courseController {

    private $usersModel;
    private $coursesModel;
    private $studentsModel;

    function __construct(){

      $this->usersModel = new usersModel();
      $this->coursesModel = new coursesModel();
      $this->studentsModel = new studentsModel();

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

          $access_can_edit = true;
          $access_can_add = true;

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

          $course_obj = $this->coursesModel->getCourseInfoByKey("id", $_GET['id']);

          $course_students = $this->coursesModel->getCourseStudents($_GET['id']);

          $data['more_data'] = [
            'container_action' => "viewcourse",
            'course_obj' => $course_obj,
            'course_students' => $course_students,
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

        if ($user_info['Role'] != ROLE_MANAGER && $user_info['Role'] != ROLE_OWNER){

          redirect_and_die('/theschool/home');

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
        ];

        $errors = [];

        if(isset($_GET["id"]) && $_GET["id"] != ""){

          $_GET["id"] = my_db_mysqli::escape($_GET["id"]);

          $course_obj = $this->coursesModel->getCourseInfoByKey("id", $_GET['id']);

          $course_students_amount = count($this->coursesModel->getCourseStudents($_GET['id']));

          if(is_array($course_obj)){

            if( isset($_POST['action']) && $_POST['action'] == "Save"){

              if(isset($_POST['editadd_name']) && $_POST['editadd_name'] != $course_obj['Name']){

                if(validateName($_POST['editadd_name'])){

                  $_POST['editadd_name'] = my_db_mysqli::escape($_POST['editadd_name']); // in case...

                  $this->coursesModel->updateCourseInfo($course_obj['id'], "Name", $_POST['editadd_name']);

                }else{

                  array_push($errors, "Invalid name!");

                }

              }

              if(isset($_POST['editadd_phone']) && $_POST['editadd_phone'] != $course_obj['Phone']){

                if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['editadd_phone'])){

                  $_POST['editadd_phone'] = my_db_mysqli::escape($_POST['editadd_phone']); // in case...

                  $this->coursesModel->updateCourseInfo($course_obj['id'], "Phone", $_POST['editadd_phone']);

                }else{

                  array_push($errors, "Invalid phone, valid e.g 000-000-0000!");

                }

              }

              if(isset($_POST['editadd_description']) && $_POST['editadd_description'] != $course_obj['Description']){

                // Custom escape because the SQL esacpe function adds /r/n to the text.
                $_POST['editadd_description'] = str_replace("\"",'&quot;', $_POST['editadd_description']);
                $_POST['editadd_description'] = str_replace("'",'&#39;', $_POST['editadd_description']);

                $this->coursesModel->updateCourseInfo($course_obj['id'], "Description", $_POST['editadd_description']);


              }

              if(isset($_POST['editadd_image']) && $_POST['editadd_image'] != "" && $_POST['editadd_image'] != $course_obj['Image']){
                $imgdataTable = explode( ',', $_POST['editadd_image'] );
                if(check_base64_image($imgdataTable[1])){

                  $this->coursesModel->updateCourseInfo($course_obj['id'], "Image", $_POST['editadd_image']);

                }else{

                  array_push($errors, "Invalid image!");

                }

              }

              //$course_obj = $this->coursesModel->getCourseInfoByKey("id", $_GET['id']); // refresh student info for view!

              if(count($errors) == 0){

                redirect_and_die("/course/view/?id=" . $_GET['id'] . "&msg=Course%20Saved!"); // Disable this for DEBUG!

              }

            }elseif(isset($_POST['action']) && $_POST['action'] == "Delete"){

              if($course_students_amount == 0){

                $this->coursesModel->deleteCourse($_GET['id']);

                redirect_and_die("/theschool/home/?msg=Course%20Deleted!");

              }else{

                array_push($errors, "You can not delete course that has students!");

              }



            }

            // For edit view

            $data['more_data'] = [
              'container_action' => "editaddcourse",
              'course_obj' => $course_obj,
              "courses_obj" => $this->coursesModel->getCoursesObj(),
              'form_errors' => $errors,
              'course_students_amt' => $course_students_amount,
            ];



          }

          //Course Edit

        }else{ // Course ADD!

          if(count($_POST) > 0){

            if(isset($_POST['editadd_name'])){

              if(!validateName($_POST['editadd_name'])){

                array_push($errors, "Invalid name!");

              }

            }else{

              array_push($errors, "You must add a name!");

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

              $_POST['editadd_description'] = str_replace("\"",'&quot;', $_POST['editadd_description']);
              $_POST['editadd_description'] = str_replace("'",'&#39;', $_POST['editadd_description']);

              $register_array = [

                "Name" => $_POST["editadd_name"],
                "Description" => $_POST["editadd_description"],
                "Image" => $_POST["editadd_image"],

              ];

              $this->coursesModel->registerCourse($register_array);

              $new_course_id = $this->coursesModel->getLastInsertedCourseID();

              redirect_and_die("/course/view/?id=" . $new_course_id . "&msg=Course%20Added!"); // Disable this for DEBUG!

            }

          }

          // For add view:

          $data['more_data'] = [
            'container_action' => "editaddcourse",
            "courses_obj" => $this->coursesModel->getCoursesObj(),
            'course_obj' => [

              "Name" => $_POST["editadd_name"],
              "Description" => $_POST["editadd_description"],
              "Image" => $_POST["editadd_image"],

            ],
            "form_errors" => $errors,

          ];

          // course add

        }

        View::render('edit_of_student_course', $data);

    }

    function addAction(){

      $this->editAction();

    }

}
