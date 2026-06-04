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
    $template = $this->mustache->loadTemplate($viewName);
    echo $template->render($data);
  }
}
