<?php
class notfoundController {
    static public function urlNotFound($info) {

      $data = [ "error" => $info ];

        View::render('404', $data);
    }
}
