<?php
//Include the autoloader
require_once 'vendor/autoload.php';

use Afosto\Ecs\Components\App;

//Use whoops for development
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

//Initiate the application with the config parameters
App::init($config);