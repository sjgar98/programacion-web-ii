<?php

class Router
{
  private Configurator $config;
  private string $defaultController;
  private string $defaultMethod;

  public function __construct(Configurator $config, string $defaultController, string $defaultMethod)
  {
    $this->config            = $config;
    $this->defaultController = $defaultController;
    $this->defaultMethod     = $defaultMethod;
  }

  public function dispatch(string $controller, string $method)
  {
    $controller = $this->getController($controller);
    $method     = $this->getMethod($controller, $method);
    $controller->{$method}();
  }

  private function getController(string $controller)
  {
    return $this->config->getOrDefault($controller, $this->defaultController);
  }

  private function getMethod(object $controller, string $method)
  {
    return method_exists($controller, $method) ? $method : $this->defaultMethod;
  }
}
