<?php
    class Controllers {
        protected $views;
        public function __construct()
        {
            $this->views = new Views();
        }

        public function model($modelName)
        {
            $routeClass = "Models/".$modelName.".php";

            if (file_exists($routeClass)) {
                require_once($routeClass);
                return new $modelName();
            } else {
                echo "Model Not Found";
            }
        }
    }