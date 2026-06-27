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
    $extraData = array(
      'base_url' => Utils::getBaseUrl(),
      'usuario_loggeado' => Auth::getUsuarioLoggeado(false),
      'es_editor' => Auth::esEditor(),
      'es_admin' => Auth::esAdmin()
    );
    $template = $this->mustache->loadTemplate($viewName);
    $renderedTemplate = $template->render(array_merge($data, $extraData));
    if ($echoOutput) {
      echo $renderedTemplate;
    }
    return $renderedTemplate;
  }
}
