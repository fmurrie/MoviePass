<?php namespace Config;

     class Request {

          private $controller;
          private $method;
          private $parameters;

          public function __construct() {

               $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);

               $urlToArray = explode("/", $url);

               $arrayURL = array_filter($urlToArray);

               if(empty($arrayURL)) {
                    $this->controller = 'Home';
               } else {
                    $this->controller = ucwords(array_shift($arrayURL));
               }

               if(empty($arrayURL)) {
                    $this->method = 'index';
               } else {
                    $this->method = array_shift($arrayURL);
               }

               $requestMethod = $this->getRequestMethod();

               if($requestMethod == 'GET') {
                    unset($_GET["url"]);
                    $this->parameters = $_GET;
               } else {
                    $this->parameters = $_POST;
               }

               if($_FILES) {
                    $this->parameters[] = $_FILES;
               }

          }

          public static function getRequestMethod()
          {
               return $_SERVER['REQUEST_METHOD'];
          }

          public function getController() {
               return $this->controller;
          }

          public function getMethod() {
               return $this->method;
          }

          public function getParameters() {
               return $this->parameters;
          }
     }

?>