<?php

error_reporting(E_ALL);
ini_set('display_errors',1);
class Controller {

    protected function view($view, $data = []){

        extract($data);

        require_once __DIR__ . '/../views/' . $view . '.php';
    }

    protected function redirect($url){

        header("Location: $url");
        exit;
    }
    
}