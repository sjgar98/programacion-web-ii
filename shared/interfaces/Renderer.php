<?php

interface Renderer
{
  public function render(string $viewName, array $data): void;
}
