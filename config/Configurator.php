<?php
class Configurator
{
  private array $config;

  public function __construct()
  {
    $this->config = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . "config.ini");
    if (getenv("PREGUNTADOS_AUTORUN_MIGRATIONS") == "true") {
      $dbModel = $this->getDatabaseModel();
      $dbModel->migrarDatabase();
    }
  }

  private function getDatabase(): Database
  {
    return new MysqliDatabase(
      $this->config['hostname'],
      $this->config['username'],
      $this->config['password'],
      $this->config['database']
    );
  }

  private function getRenderer(): Renderer
  {
    return new MustacheRenderer(__DIR__ . '/../view');
  }

  public function getOrDefault(string $controllerName, string $defaultControllerName)
  {
    $getter = 'get' . ucfirst($controllerName) . 'Controller';
    if (method_exists($this, $getter)) {
      return $this->{$getter}();
    }
    $defaultGetter = 'get' . ucfirst($defaultControllerName) . 'Controller';
    return $this->{$defaultGetter}();
  }

  public function getRouter()
  {
    return new Router($this, 'ejemplo', 'ver');
  }

  public function getEjemploController()
  {
    return new EjemploController($this->getEjemploModel(), $this->getRenderer(), new Request());
  }

  public function getEjemploModel()
  {
    return new EjemploModel($this->getDatabase());
  }

  public function getDatabaseController()
  {
    return new DatabaseController($this->getDatabaseModel());
  }

  public function getDatabaseModel()
  {
    return new DatabaseModel($this->getDatabase());
  }

  public function getUsuarioController()
  {
      return new UsuarioController($this->getUsuarioModel(), $this->getRenderer(), new Request(),new ImageService());
  }

  public function getUsuarioModel()
  {
      return new UsuarioModel($this->getDatabase());
  }

  public function getPerfilController()
  {
      return new PerfilController($this->getPerfilModel(), $this->getRenderer(), new Request());
  }

  public function getPerfilModel()
  {
      return new PerfilModel($this->getDatabase());
  }
}
