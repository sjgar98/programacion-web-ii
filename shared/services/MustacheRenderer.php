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

  public function render(string $viewName, array $data = []): void
  {
    $extraData = array();
    if ($_SESSION['usuario_loggeado']) {
      $extraData['usuario_loggeado'] = $_SESSION['usuario_loggeado'];
    }
    $template = $this->mustache->loadTemplate($viewName);
    echo $template->render(array_merge($data, $extraData));
  }
}
