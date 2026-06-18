<?php

interface Renderer
{
  public function render(string $viewName, array $data = [], bool $echoOutput = true): string;
}
