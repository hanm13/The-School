<?php
class Router {
    //route requests to appropriate controllers
    //We agreed on this structure: /$controller/$action/

    private $controller, $action;

    public function __construct($controller_from_url = "", $action_from_url = "") {

      if( $controller_from_url == '' ){
          $controller_from_url = $_GET['controller'];
      }

      if( $action_from_url == '' ){
          $action_from_url = $_GET['action'];
      }

      $this->controller = $controller_from_url;
      $this->action = $action_from_url;

    }

    private function normalize_name($name, $type) {
        //type = action/controller
        //name = name of the component

        if($type=='action') {
            return strtolower($name) . 'Action';
        }

        if($type=='controller'){
            return strtolower($name) . 'Controller';
        }
    }

    public function go() {
        $controller_name    = $this->normalize_name($this->controller, 'controller');
        // //usersController
        $action_name        = $this->normalize_name($this->action, 'action');
        // //profileAction

        if( !class_exists($controller_name) ) {

            notfoundController::urlNotFound($controller_name);
            die('Controller: '. $controller_name .' DOES NOT EXIST' );

        }

        // var_dump($controller_name);

        $controller_instance = new $controller_name();
        //as if: $controller_instance = new usersController();

        $callable_param = [$controller_instance, $action_name];
        //
        if( !is_callable($callable_param) ) {

            notfoundController::urlNotFound($action_name);

            die('Action: '. $action_name .' DOES NOT EXIST' );

            //View::render('actionNotFound', $data);

        }

        call_user_func_array($callable_param, []);

    }


}
