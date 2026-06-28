<?php
class Configurator
{
  private array $config;

  public function __construct()
  {
    $this->config = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . "config.ini");
    if ($this->config['db_automigrate'] == "true") {
      $dbModel = $this->getDatabaseModel();
      $dbModel->migrarDatabase();
    }
  }

  private function getDatabase(): Database
  {
    return new MysqliDatabase(
      $this->config['db_hostname'],
      $this->config['db_username'],
      $this->config['db_password'],
      $this->config['db_database']
    );
  }

  private function getRenderer(): Renderer
  {
    return new MustacheRenderer(__DIR__ . '/../view');
  }

  private function getMailer(): Mailer
  {
    return new Mailer(
      $this->config['smtp_hostname'],
      $this->config['smtp_port'],
      $this->config['smtp_username'],
      $this->config['smtp_password']
    );
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
    return new Router($this, 'lobby', 'ver');
  }

  public function getDatabaseController()
  {
    return new DatabaseController($this->getDatabaseModel());
  }

  public function getDatabaseModel()
  {
    return new DatabaseModel($this->getDatabase());
  }

  public function getRegistroController()
  {
    return new RegistroController($this->getUsuarioModel(), $this->getRenderer(), new Request(), new ImageService());
  }

  public function getUsuarioModel()
  {
    return new UsuarioModel($this->getDatabase(), $this->getMailer(), $this->getRenderer());
  }

  public function getPreguntasModel()
  {
    return new PreguntasModel($this->getDatabase(), $this->getRandom());
  }
  public function getRespuestasModel()
  {
    return new RespuestasModel($this->getDatabase(), $this->getRandom());
  }

  public function getRandom()
  {
    return new Random($this->getDatabase());
  }

  public function getPreguntasController()
  {
    return new PreguntasController($this->getPreguntasModel(), $this->getRenderer(), new Request(), $this->getRespuestasModel());
  }

  public function getLoginController()
  {
    return new LoginController($this->getUsuarioModel(), $this->getRenderer(), new Request());
  }

  public function getPerfilController()
  {
    return new PerfilController($this->getPerfilModel(), $this->getRenderer(), new Request());
  }

  public function getPerfilModel()
  {
    return new PerfilModel($this->getDatabase());
  }

  public function getPartidaModel()
  {
    return new PartidaModel($this->getDatabase());
  }

  public function getPartidaController()
  {
    return new PartidaController($this->getPreguntasModel(), $this->getRenderer(), new Request(), $this->getPartidaModel());
  }

  public function getLobbyController()
  {
    return new LobbyController($this->getLobbyModel(), $this->getRenderer(), new Request());
  }

  public function getLobbyModel()
  {
    return new LobbyModel($this->getDatabase());
  }

  public function getRankingController()
  {
    return new RankingController($this->getRankingModel(), $this->getRenderer(), new Request());
  }

  public function getRankingModel()
  {
    return new RankingModel($this->getDatabase());
  }

  public function getAdminController()
  {
    return new AdminController($this->getAdminModel(), $this->getRenderer(), new Request());
  }

  public function getAdminModel()
  {
    return new AdminModel($this->getDatabase());
  }

  public function getReportesModel()
  {
    return new ReportesModel($this->getDatabase());
  }

  public function getReportesController()
  {

    return new ReportesController($this->getRenderer(), new Request(), $this->getReportesModel(), $this->getPartidaModel());
  }
}
