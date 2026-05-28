<?php
require_once __DIR__ . '/../shared/services/Autoloader.php';
$config = new Configurator();
$migrator = $config->getDatabaseMigrator();
$migrator->runDatabaseMigrations();
