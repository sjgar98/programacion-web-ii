<?php

class MustacheRenderer implements Renderer
{
  private \Mustache\Engine $mustache;

  public function __construct(string $viewsFolder)
  {
    $this->mustache = new \Mustache\Engine([
      'loader'          => new \Mustache\Loader\FilesystemLoader($viewsFolder),
      'partials_loader' => new \Mustache\Loader\FilesystemLoader($viewsFolder),
    ]);
  }

  public function render(string $viewName, array $data = [], bool $echoOutput = true): string
  {
    $extraData = array();
    if ($_SESSION['usuario_loggeado']) {
      $extraData['usuario_loggeado'] = $_SESSION['usuario_loggeado'];
    }
    $extraData['base_url'] = Utils::getBaseUrl();
    $template = $this->mustache->loadTemplate($viewName);
    $renderedTemplate = $template->render(array_merge($data, $extraData));
    if ($echoOutput) {
      echo $renderedTemplate;
    }
    return $renderedTemplate;
  }
}
