<?php
    spl_autoload_register('loadModel');
    spl_autoload_register('loadController');
    spl_autoload_register('loadView');
    spl_autoload_register('loadLoginCheck');

    function loadModel($model) {
        $extension = '.php';

        $pathModel      = '../model/';
        $fullPathModel  = $pathModel . $model . $extension;

        if (!file_exists($fullPathModel)) {
            return false;
        }

        include_once $fullPathModel;
    }

    function loadController($controller) {
        $extension = '.php';

        $pathController      = '../controller/';
        $fullPathController  = $pathController . $controller . $extension;

        if (!file_exists($fullPathController)) {
            return false;
        }

        include_once $fullPathController;
    }

    function loadView($view) {
        $extension = '.php';

        $pathView      = '../view/';
        $fullPathView  = $pathView . $view . $extension;

        if (!file_exists($fullPathView)) {
            return false;
        }

        include_once $fullPathView;
    }

    function loadLoginCheck() {
        $fullPath = '../system/loggedIn.php';

        include_once $fullPath;
    }
?>