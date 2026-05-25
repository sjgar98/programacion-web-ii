<?php
require_once __DIR__ . '/shared/services/Autoloader.php';
$config = new Configurator();
$router = $config->getRouter();
$router->dispatch(
  $_GET['controller'] ?? '',
  $_GET['method'] ?? ''
);
