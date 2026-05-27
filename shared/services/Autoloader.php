<?php
spl_autoload_register(function ($class) {
  $dirs = ['shared/interfaces', 'shared/services', 'controller', 'model', 'config'];
  foreach ($dirs as $dir) {
    $file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $class . '.php';
    //$file = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file);
    //echo "Buscando clase [$class] en: " . $file . "<br>";
    if (file_exists($file)) {
      require_once $file;
      return;
    }
  }
});
