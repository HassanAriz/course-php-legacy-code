<?php declare(strict_types=1);

use Core\Routing;

require "conf.inc.php";


/*
function myAutoloader($class)
{
    var_dump($class);
    $classname = substr($class, strpos($class, '\\') + 1);
    var_dump($classname);



    $classPath = "Core/" . $classname . ".php";
    $classModel = "Model/" . $classname . ".php";
    if (file_exists($classPath)) {
        include $classPath;
    } else if (file_exists($classModel)) {
        include $classModel;
    }
}
*/

function myAutoloader($class)
{
    var_dump('link-before='.$class);
    $classname = str_replace('\\', '/', $class) . ".php";
    var_dump('link-after'.$classname);

    if (!file_exists($classname)) {
        throw  new \Exception("File Error : the file ".$class."does not exist");
        return;
    }

    require $classname;
}


$container = [];
$container['config'] = require 'Config/config.php';
$container += require 'Config/di.global.php';


// La fonction myAutoloader est lancé sur la classe appelée n'est pas trouvée
spl_autoload_register("myAutoloader");

// Récupération des paramètres dans l'url - Routing
$slug = explode("?", $_SERVER["REQUEST_URI"])[0];
$routes = Routing::getRoute($slug);

extract($routes); ///
var_dump($routes);

// Vérifie l'existence du fichier et de la classe pour charger le controlleur
if (file_exists($cPath)) {
    include $cPath;
    // var_dump($controller_file);
    if (class_exists('\\Controller\\'.$controller_file)) {
        //instancier dynamiquement le controller
        //var_dump($container);
        var_dump($c);
        $cObject = $container[$controller_file]($container);
        //vérifier que la méthode (l'action) existe
        if (method_exists($cObject, $method)) {
            //appel dynamique de la méthode
            $cObject->$method();
        } else {
            die("La methode " . $method . " n'existe pas");
        }

    } else {
        die("La class controller " . $controller_file . " n'existe pas");
    }
} else {
    die("Le fichier controller " . $controller_file . " n'existe pas");
}
