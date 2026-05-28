<?php
spl_autoload_register(function ($class) {
  $dirs = ['shared/interfaces', 'shared/services', 'controller', 'model', 'config'];
  foreach ($dirs as $dir) {
    $file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $class . '.php';
    if (file_exists($file)) {
      require_once $file;
      return;
    }
  }
});
