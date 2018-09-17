<?php
class adminController {

    private $usersModel;
    private $coursesModel;
    private $studentsModel;

    function __construct(){

      $this->usersModel = new usersModel();
      $this->coursesModel = new coursesModel();
      $this->studentsModel = new studentsModel();

    }

    function viewAction(){

      if (!is_logged_in()){
          redirect_and_die('/noaccess/no');
      }

      $login_access_code = $_COOKIE['loginAccess'];
      $user_info = $this->usersModel->getUserInfoByKey(TOKEN_KEY_NAME, $login_access_code);

      $user_id = $user_info['id'];

      if ($user_info['Role'] != ROLE_MANAGER && $user_info['Role'] != ROLE_OWNER){

        redirect_and_die('/noaccess/no');

      }

      $data = [
        'user_info' => $user_info,
        'website_user' => new userModel($user_id),
        'header_view' => [
          'user_info' => $user_info,
          'user_role' => convertUserRoleToName($user_info['Role']),
          'header_info' => [

            "Title" => "Administration",

          ],
        ],
        'admins_obj' => [ "admins_obj" => $this->usersModel->getUsersObj() ],
        'admins_amt' => $this->usersModel->getRegisteredUsersAmt(),
      ];

      if(isset($_GET["id"]) && $_GET["id"] != ""){

        $_GET["id"] = my_db_mysqli::escape($_GET["id"]);

        $admin_obj = $this->usersModel->getUserInfoByKey("id", $_GET['id']);

        if ($user_info['Role'] == ROLE_MANAGER && $admin_obj['Role'] == ROLE_OWNER){

          redirect_and_die('/noaccess/no');

        }

        if ( $admin_obj['Role'] < $user_info['Role']){
          $access_can_edit = true;
        }

        $data['more_data'] = [
          'container_action' => "viewadmin",
          'admin_obj' => $admin_obj,
          'admin_role' => convertUserRoleToName($admin_obj['Role']),
          'msg' => $_GET['msg'],
          'access_can_edit' => $access_can_edit
        ];

      }

      View::render('admin', $data);

    }

    public function editAction() {

        // Access Validation

        if (!is_logged_in()){

          redirect_and_die('/noaccess/no');
        }

        $login_access_code = $_COOKIE['loginAccess'];
        $user_info = $this->usersModel->getUserInfoByKey(TOKEN_KEY_NAME, $login_access_code);

        $user_id = $user_info['id'];

        if ($user_info['Role'] != ROLE_MANAGER && $user_info['Role'] != ROLE_OWNER){

          redirect_and_die('/noaccess/no');

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
          'admins_obj' => [ "admins_obj" => $this->usersModel->getUsersObj() ],
          'admins_amt' => $this->usersModel->getRegisteredUsersAmt(),
        ];

        $access_can_edit_role = false;

        $errors = [];

        if(isset($_GET["id"]) && $_GET["id"] != ""){

          $_GET["id"] = my_db_mysqli::escape($_GET["id"]);

          $admin_obj = $this->usersModel->getUserInfoByKey("id", $_GET['id']);

          if($user_info["Role"] != ROLE_OWNER){

            if ($user_info['Role'] <= $admin_obj['Role']){ // Lower class can not edit equal or higher class!

              redirect_and_die('/noaccess/no');

            }

          }

          if(is_array($admin_obj)){

            if ($user_info['Role'] == ROLE_OWNER){

              if($admin_obj["Role"] == ROLE_OWNER){

                $access_can_edit_role = false;

              }else{
                $access_can_edit_role = true;
              }

            }

            if( isset($_POST['action']) && $_POST['action'] == "Save"){

              if(isset($_POST['editadd_name']) && $_POST['editadd_name'] != $admin_obj['Name']){

                if(validateName($_POST['editadd_name'])){

                  $_POST['editadd_name'] = my_db_mysqli::escape($_POST['editadd_name']); // in case...

                  $this->usersModel->updateUserInfo($admin_obj['id'], "Name", $_POST['editadd_name']);

                }else{

                  array_push($errors, "Invalid name!");

                }

              }

              if(isset($_POST['editadd_phone']) && $_POST['editadd_phone'] != $admin_obj['Phone']){

                if(preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['editadd_phone'])){

                  $_POST['editadd_phone'] = my_db_mysqli::escape($_POST['editadd_phone']); // in case...

                  $this->usersModel->updateUserInfo($admin_obj['id'], "Phone", $_POST['editadd_phone']);

                }else{

                  array_push($errors, "Invalid phone, valid e.g 000-000-0000!");

                }

              }

              if(isset($_POST['editadd_role']) && $_POST['editadd_role'] != $admin_obj['Role']){

                if ($user_info['Role'] == ROLE_OWNER ){

                  if($access_can_edit_role == false){

                    array_push($errors, "You can not edit your role!");

                  }else{

                    if(preg_match("/^[1-3]{1}$/", $_POST['editadd_role'])){

                      $_POST['editadd_role'] = my_db_mysqli::escape($_POST['editadd_role']); // in case...

                      if ($user_info['Role'] > $_POST['editadd_role']){

                        $this->usersModel->updateUserInfo($admin_obj['id'], "Role", $_POST['editadd_role']);

                      }else{

                        array_push($errors, "You can not set role equal or higher than yours!");

                      }

                    }else{

                      array_push($errors, "Invalid role, 0=noaccess, 1=Sales, 2=Manager, 3=Owner");

                    }

                  }

                }else{

                  array_push($errors, "Only owner can change roles!");

                }

              }

              if(isset($_POST['editadd_email']) && $_POST['editadd_email'] != $admin_obj['Email']){

                if(validateEmail($_POST['editadd_email'])){

                  if(!$this->usersModel->getUserInfoByKey("Email", $_POST['editadd_email'])){

                    $_POST['editadd_email'] = my_db_mysqli::escape($_POST['editadd_email']); // in case...

                    $this->usersModel->updateUserInfo($admin_obj['id'], "Email", $_POST['editadd_email']);

                  }else{

                    array_push($errors, "Email already used!");

                  }

                }else{

                  array_push($errors, "Invalid email!");

                }
              }
              if(isset($_POST['editadd_image']) && $_POST['editadd_image'] != "" && $_POST['editadd_image'] != $admin_obj['Image']){
                $imgdataTable = explode( ',', $_POST['editadd_image'] );
                if(check_base64_image($imgdataTable[1])){

                  $this->usersModel->updateUserInfo($admin_obj['id'], "Image", $_POST['editadd_image']);

                }else{

                  array_push($errors, "Invalid image!");

                }

              }

              //$admin_obj = $this->studentsModel->getUserInfoByKey("id", $_GET['id']); // refresh student info for view!

              if(count($errors) == 0){

                redirect_and_die("/admin/view/?id=" . $_GET['id'] . "&msg=Admin%20Saved!"); // Disable this for DEBUG!

              }

            }elseif(isset($_POST['action']) && $_POST['action'] == "Delete"){

              if($admin_obj['id'] == $user_info['id']){

                array_push($errors, "You can not delete yourself!");

              }

              if($admin_obj['Role'] >= $user_info['Role']){

                array_push($errors, "You can not delete equal or higher class admin!");

              }

              if(count($errors) == 0){

                $this->usersModel->deleteAdmin($_GET['id']);

                redirect_and_die("/theschool/admin/?msg=Admin%20Deleted!");

              }

            }

            // For edit view

            $data['more_data'] = [
              'container_action' => "editaddadmin",
              'admin_obj' => $admin_obj,
              "courses_obj" => $this->coursesModel->getCoursesObj(),
              'form_errors' => $errors,
              'access_can_edit_role' => $access_can_edit_role,
            ];



          }

          //admin Edit

        }else{ // admin ADD!

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

                if($this->usersModel->getUserInfoByKey("Email", $_POST['editadd_email'])){

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

            if ($user_info['Role'] <= $_POST['editadd_role']){

              array_push($errors, "You can not set role equal or higher than yours!");

            }

            // Creation:

            if( count($errors) == 0 ){

              $_POST['editadd_name'] = my_db_mysqli::escape($_POST['editadd_name']);
              $_POST['editadd_phone'] = my_db_mysqli::escape($_POST['editadd_phone']);
              $_POST['editadd_email'] = my_db_mysqli::escape($_POST['editadd_email']);
              $_POST['editadd_role'] = my_db_mysqli::escape($_POST['editadd_role']);
              $_POST['editadd_password'] = my_db_mysqli::escape($_POST['editadd_password']);

              $password_php = password_hash($_POST['editadd_password'], PASSWORD_BCRYPT);

              $register_array = [

                "Name" => $_POST["editadd_name"],
                "Phone" => $_POST["editadd_phone"],
                "Email" => $_POST["editadd_email"],
                "Role" => $_POST["editadd_role"],
                "Image" => $_POST["editadd_image"],
                "Password" => $password_php,

              ];

              $this->usersModel->registerAdmin($register_array);

              $new_admin_id = $this->usersModel->getLastInsertedAdminID();

              redirect_and_die("/admin/view/?id=" . $new_admin_id . "&msg=Admin%20Added!"); // Disable this for DEBUG!

            }

          }

          // For add view:

          $data['more_data'] = [
            'container_action' => "editaddadmin",
            'admin_obj' => [

              "Name" => $_POST["editadd_name"],
              "Phone" => $_POST["editadd_phone"],
              "Email" => $_POST["editadd_email"],
              "Role" => $_POST["editadd_role"],
              "Image" => $_POST["editadd_image"],

            ],
            "form_errors" => $errors,
          ];

          // Student add

        }

        View::render('edit_of_admin', $data);

    }

    function addAction(){

      $this->editAction();

    }

}
