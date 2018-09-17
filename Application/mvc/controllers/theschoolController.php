<?php
class theschoolController {

    private $usersModel;
    private $coursesModel;
    private $studentsModel;

    function __construct(){

      $this->usersModel = new usersModel();
      $this->coursesModel = new coursesModel();
      $this->studentsModel = new studentsModel();

    }

    public function homeAction() {

        // Access Validation

        if (!is_logged_in()){

          $this->loginAction();
          die();
        }

        $login_access_code = $_COOKIE['loginAccess'];
        $user_info = $this->usersModel->getUserInfoByKey(TOKEN_KEY_NAME, $login_access_code);

        $user_id = $user_info['id'];

        if ($user_info['Role'] == ROLE_MANAGER || $user_info['Role'] == ROLE_OWNER){

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
          'default_container_data' => [

            "courses_amt" => $this->coursesModel->getCoursesAmt(),
            "students_amt" => $this->studentsModel->getStudentsAmt(),
            "msg" => [$_GET['msg'], "success"],

          ],
        ];

        View::render('home', $data);
    }

    function loginAction(){

      if (is_logged_in()){
          redirect_and_die('/theschool/home');
      }

      if(isset($_POST['email']) && isset($_POST['password'])){

        $_POST["email"] = my_db_mysqli::escape($_POST["email"]);
        $_POST["password"] = my_db_mysqli::escape($_POST["password"]);

        $data = [
          'header_view' => [
            'header_info' => [

              "Title" => "Login",

            ],
          ],

        ];
        View::render('login', $data);

        $password = $_POST['password'];
        $email = $_POST['email'];

        if ( $this->usersModel->getUserInfoByKey("Email",$email)){

          $user = $this->usersModel->getUserInfoByKey("Email", $email);

          if ( password_verify($password, $user['Password'])){

            $new_token = uniqid() . "" . uniqid();
            $this->usersModel->updateUserInfo($user['id'],TOKEN_KEY_NAME, $new_token);

            setcookie("loginAccess", $new_token, time()+24*60*60*7,'/' ); // cookie for whole domain!
            header('Location: /theschool/home');

          }else{

            createLoginErrorMessage("Invalid Passowrd!","warning");

          }

        }else{

          createLoginErrorMessage("User not found!","warning");

        }

      }else{

        $data = [
          'header_view' => [
            'header_info' => [

              "Title" => "Login",

            ],
          ],

        ];

        View::render('login', $data);

      }

    }

    function logoutAction(){

      unset($_COOKIE['loginAccess']);
      setcookie("loginAccess", null, -1, '/');
      redirect_and_die('/theschool/login');

    }

    function adminAction(){

      if (!is_logged_in()){
          redirect_and_die('/theschool/login');
      }

      $login_access_code = $_COOKIE['loginAccess'];
      $user_info = $this->usersModel->getUserInfoByKey(TOKEN_KEY_NAME, $login_access_code);

      $user_id = $user_info['id'];

      if ($user_info['Role'] != ROLE_MANAGER && $user_info['Role'] != ROLE_OWNER){

        redirect_and_die('/theschool/home');

      }

      $admins_obj = $this->usersModel->getUsersObj();

      if($user_info["Role"] != ROLE_OWNER){

        foreach ($admins_obj as $key => $admin) {

          if( $admin['Role'] > $user_info['Role'] ){

            unset($admins_obj[$key]); // We don't want to send the owner info to lower class user.

          }else{

            if ( $admin['Role'] >= $user_info['Role']){ // Lower class can not edit equal or higher class!

              $admins_obj[$key]["showClickableAnchor"] = false;

            }else{

              $admins_obj[$key]["showClickableAnchor"] = true;

            }

          }

        }

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
        'admins_obj' => [ "admins_obj" => $admins_obj ],
        'admins_amt' => $this->usersModel->getRegisteredUsersAmt(),
        'default_container_data' => [

          "admins_amt" => $this->usersModel->getRegisteredUsersAmt(),
          "msg" => [$_GET['msg'], "success"],

        ],
      ];

      View::render('admin', $data);

    }

}
