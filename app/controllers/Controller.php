<?php
namespace app\controllers;
use League\Plates\Engine;

abstract class Controller {

    public function view(string $view, array $data = [])
    {

        $pathViews = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR. 'views';
      
        $templates = new Engine($pathViews);

        // Render a template
        echo $templates->render($view, $data);
    }
}