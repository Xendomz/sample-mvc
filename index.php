<?php

require_once('Config/Config.php');
require_once('Helpers/Helpers.php');

// Set default method and controller
$url = !empty($_GET['url']) ? $_GET['url'] : 'home/home';

// Memisahkan url berdasarkan garis miring ke dan diconvert ke bentuk array
$arrUrl = explode("/", $url);

// Posisi $controller pada array
$controller = $arrUrl[0];

// Posisi $method pada array
$method = $arrUrl[0];

// Instansiasi parameter dengan nilai awal empty string
$params = "";

// cek kondisi posisi array untuk method
if (!empty($arrUrl[1])) {
    if ($arrUrl[1] != "") {
        $method = $arrUrl[1];
    }
}

// Cek posisi array untuk parameter
if (!empty($arrUrl[2])) {
    if ($arrUrl[2] != "") {
        for ($i=2; $i < count($arrUrl) ; $i++) { 
            $params .= $arrUrl[$i].',';
        }
        $params = trim($params, ',');
    }
}

spl_autoload_register(function ($class) {
    if (file_exists(LIBS."Core/".$class.".php")) {
        require_once(LIBS."Core/".$class.".php");
    }
});

$controllerFile = "Controllers/".$controller.".php";

if (file_exists($controllerFile)) {
    require_once($controllerFile);

    $controller = new $controller();

    if (method_exists($controller, $method)) {
        $controller->$method($params);
    } else {
        // echo "Method tidak ditemukan";
        require_once('Controllers/Errors.php');
    }
} else {
    // echo "Controller Tidak ditemukan";
    require_once('Controllers/Errors.php');
}