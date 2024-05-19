<?php
    class Views
    {
        function getView($controller, $view, $data="")
        {
            $controller = get_class($controller);

            if ($controller == "Home") {
                $view = VIEWS.$view.".php";
            } else {
                $view = VIEWS.$controller.'/'.$view.".php";
            }
            // print_r($view);
            require_once($view);
        }
    }