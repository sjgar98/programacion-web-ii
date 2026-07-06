<?php

enum Rol: int
{
  case ADMIN = 1;
  case EDITOR = 2;
  case JUGADOR = 3;
}

class Auth
{
  public static function setUsuarioLoggeado(stdClass|null $usuario): void
  {
    if ($usuario === null && isset($_SESSION['usuario_loggeado'])) {
      unset($_SESSION['usuario_loggeado']);
    } else {
      $_SESSION['usuario_loggeado'] = $usuario;
    }
  }

  public static function getUsuarioLoggeado(bool $redirect = true): stdClass|null
  {
    if (!isset($_SESSION['usuario_loggeado']) || empty($_SESSION['usuario_loggeado'])) {
      if ($redirect) {
        Redirect::toLogin();
      } else {
        return null;
      }
    }
    return $_SESSION['usuario_loggeado'];
  }

  public static function esEditor(): bool
  {
    $usuario = self::getUsuarioLoggeado(false);
    if ($usuario === null) return false;
    return $usuario->rol_id === Rol::EDITOR->value || $usuario->rol_id === Rol::ADMIN->value;
  }

  public static function esAdmin(): bool
  {
    $usuario = self::getUsuarioLoggeado(false);
    if ($usuario === null) return false;
    return $usuario->rol_id === Rol::ADMIN->value;
  }

  public static function puedeAccederJugador(): void
  {
    if (!self::getUsuarioLoggeado(false)) {
      Redirect::toLogin();
    }
  }

  public static function puedeAccederEditor(): void
  {
    if (!self::esEditor() && !self::esAdmin()) {
      Redirect::toIndex();
    }
  }

  public static function puedeAccederAdmin(): void
  {
    if (!self::esAdmin()) {
      Redirect::toIndex();
    }
  }
}
