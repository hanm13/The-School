<?php

define("TOKEN_KEY_NAME","Token");

$website_users = new usersModel();
$login_access_code = $_COOKIE['loginAccess'];
//$website_user = new userModel($website_users->getUserInfoByKey(TOKEN_KEY_NAME, $login_access_code)['id']);

// Functions

// Validation Functions

function check_base64_image($base64) {

    $img = imagecreatefromstring(base64_decode($base64));
    if (!$img) {
        return false;
    }

    imagepng($img, 'tmp.png');
    $info = getimagesize('tmp.png');

    unlink('tmp.png');

    if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
        return true;
    }

    return false;
}

function validateName($name) {
    if (preg_match("/^[a-zA-Z'. -]+$/", $name)) {
        return true;
    }
    return false;
}

function validateEmail($email) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function validatePassword($password) {
    if($password != ""){

      return true;

    }
    return false;
}

function createErrorMessage($msg, $type = "normal"){

  if($msg == ""){ return; }

  $type = strtolower($type);

  switch ($type) {
    case 'success':
      echo "<div class='alert alert-success'>
        <strong>Success!</strong> $msg
      </div>";
      break;

    case 'info':
      echo "<div class='alert alert-info'>
        <strong>Info!</strong> $msg
      </div>";
      break;

    case 'warning':
      echo "<div class='alert alert-warning'>
        <strong>Warning!</strong> $msg
      </div>";
      break;
    case 'danger':
      echo "<div class='alert alert-danger'>
        <strong>Danger!</strong> $msg
      </div>";
      break;
    default:
      echo "<div class='alert alert-info'>
        <strong>Info!</strong> $msg
      </div>";
      break;
  }

}

function createMessage($msg, $type = "normal"){

  if($msg == ""){ return; }

  $type = strtolower($type);

  switch ($type) {
    case 'success':
      echo "<div class='alert alert-success'>
        <strong>Success!</strong> $msg
      </div>";
      break;

    case 'info':
      echo "<div class='alert alert-info'>
        <strong>Info!</strong> $msg
      </div>";
      break;

    case 'warning':
      echo "<div class='alert alert-warning'>
        <strong>Warning!</strong> $msg
      </div>";
      break;
    case 'danger':
      echo "<div class='alert alert-danger'>
        <strong>Danger!</strong> $msg
      </div>";
      break;
    default:
      echo "<div class='alert alert-info'>
        <strong>Info!</strong> $msg
      </div>";
      break;
  }

}

function createLoginErrorMessage($msg, $type = "normal"){

  $type = strtolower($type);

  switch ($type) {
    case 'success':
      echo "<div class='alert alert-success'>
        <strong>Success!</strong> $msg
      </div>";
      break;

    case 'info':
      echo "<div class='alert alert-info'>
        <strong>Info!</strong> $msg
      </div>";
      break;

    case 'warning':
      echo "<div class='alert alert-warning'>
        <strong>Warning!</strong> $msg
      </div>";
      break;
    case 'danger':
      echo "<div class='alert alert-danger'>
        <strong>Danger!</strong> $msg
      </div>";
      break;
    default:
      echo "<div class='alert alert-info'>
        <strong>Info!</strong> $msg
      </div>";
      break;
  }

  echo "<script>

  var form_sign_in_el = document.querySelector('.form-signin');
  var alert_el = document.querySelector('.alert');

  form_sign_in_el.insertBefore(alert_el, form_sign_in_el.firstChild);

  </script>";

}

// From google.

function cvf_convert_object_to_array($data) {

    if (is_object($data)) {
        $data = get_object_vars($data);
    }

    if (is_array($data)) {
        return array_map(__FUNCTION__, $data);
    }
    else {
        return $data;
    }
}


// Login Procedure

function login($email, $password){

  global $website_users, $website_user;

  if ( $website_users->getUserInfoByKey("Email",$email)){

    $user = $website_users->getUserInfoByKey("Email", $email);

    if ( password_verify($password, $user['Password'])){

      $new_token = uniqid() . "" . uniqid();
      $website_users->updateUserInfo($user['id'],TOKEN_KEY_NAME, $new_token);
      //$website_users->updateUserLastLogin($user['id']);

      setcookie("loginAccess", $new_token, time()+24*60*60*7 );
      header('Location: /theschool/home');

    }else{

      createLoginErrorMessage("Invalid Passowrd!","warning");

    }

  }else{

    createLoginErrorMessage("User not found!","warning");

  }

}

function login_by_uniqueID($user_uniqueID){

    global $website_users, $website_user;

    $user = $website_users->getUserInfoByKey("id", $user_uniqueID);

    $new_token = uniqid() . "" . uniqid();
    $website_users->updateUserInfo($user['id'],TOKEN_KEY_NAME, $new_token);
    //$website_users->updateUserLastLogin($user['id']);


    setcookie("loginAccess", $new_token, time()+24*60*60*7 );

    header('Refresh: 3; /users/profile');

}

function validatePageAccess($custom_redirect=false){

  $website_users = new usersModel();

  if (!$website_users->getUserInfoByKey(TOKEN_KEY_NAME, $_COOKIE['loginAccess'])){

    if($custom_redirect){

      header("Location: " . $custom_redirect);

    }else{

      header("Location: login.php");

    }

  die();

  }

}
function is_logged_in(){

  global $website_users;

  if (!$website_users->getUserInfoByKey(TOKEN_KEY_NAME, $_COOKIE['loginAccess'])){

    return false;

  }

  return true;

}

function redirect_and_die($redirect){

  header("Location: " . $redirect);
  die();

}

// Define the JSON errors(Will echo them to the site).
$constants = get_defined_constants(true);
$json_errors = array();
foreach ($constants["json"] as $name => $value) {
    if (!strncmp($name, "JSON_ERROR_", 11)) {
        $json_errors[$value] = $name;
    }
}

function createLoginForm(){


  echo '<div class="loginForm">

          <form action="" method="post">

            <input type="text" placeholder="Email" name="email">
            <input type="password" placeholder="Password" name="password">

            <input type = "submit" value="Login">

          </form> ';

          if(isset($_POST['email']) && isset($_POST['password'])){

            login($_POST['email'],$_POST['password']);

          }

          echo "<p> If you do not have account please <a href ='registerform.php'> Register!</a><p>";

        echo '</div>';

}

function createHTMLInput($type,$name = "",$id = "",$class = "",$value = "",$formName = "", $add = ""){

  echo "<input type='$type' " . ($id != "" ? "id='$id'" :  "") . ($class != "" ? "class='$class'" :  "") . " name='$name' value='$value'" . ($formName != "" ? "form='$formName'" :  "") . "$add>";
}

function convertUserRoleToName($role_number){

  switch ($role_number) {
    case 1:
      $role = "Sales";
      break;
    case 2:
      $role = "Manager";
      break;
    case 3:
      $role = "Owner";
      break;
    default:
      $role = "No Access";
      break;
  }

  return $role;

}

// DEBUG!

if (DEBUG_MODE == true) {

  ini_set ('display_errors', 'on');

}else{

  ini_set ('display_errors', 'off');

}

?>
