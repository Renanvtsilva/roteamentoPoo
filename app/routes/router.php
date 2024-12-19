<?php
namespace app\routes;

use app\helpers\Request;
use app\helpers\Uri;
use Exception;

class Router
{

    const CONTROLLER_NAMESPACE = 'app\\controllers';

    public static function load(string $controller, string $method)
    {
        try{
            //verificar se o controller existe
            $controllerNamespace = self::CONTROLLER_NAMESPACE.'\\'.$controller;
            if(!class_exists($controllerNamespace)){
                throw new Exception("O controller {$controller} não existe");
            }

            $controllerInstance = new $controllerNamespace;

            if(!method_exists($controllerInstance, $method)){
                throw new Exception("O método {$method} não existe no controller {$controller}");
            }

            $controllerInstance->$method((object)$_REQUEST);

        }catch(\throwable $th){
            echo $th->getMessage();
        }
    }


    public static function routes(): array
    {
        return [
            'get' => [
                '/' => fn() => self::load ('HomeController','index'),
                '/contact' => fn() =>  self::load ('ContactController','index'),
                '/product' => fn() => self::load ('ProductController','index'),
                
            ],

            'post' => [
                '/contact' => fn() =>  self::load ('ContactController','store'),
            ],

            'put' => [
                '/product' => fn() => self::load ('ProductController', 'update'),
            ],

            'delete' => [

            ]
            ];
    }

    public static function execute()
    {
        try{

            $routes = self::routes();
            $request = Request::get();
            $uri = Uri::get('path');

            if(!isset($routes[$request])){
                throw new Exception("A rota não existe");
            }

            if(!array_key_exists($uri, $routes[$request])){
                throw new Exception("A rota não existe");
            }

            $router =  $routes[$request][$uri];
            
            if(!is_callable($router)){
                throw new Exception("A rota {$uri} não existe");
            }
           
            $router();
        }catch(\throwable $th)
        {
            echo $th->getMessage();
        }
    }
}