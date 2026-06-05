<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/shared/services/Autoloader.php';
session_start();
$config = new Configurator();
$router = $config->getRouter();
$router->dispatch(
  $_GET['controller'] ?? '',
  $_GET['method'] ?? ''
);
