<?php

class Log
{
  private static function write(string $level, string $message)
  {
    $logDir = __DIR__ . '/../log';
    if (!is_dir($logDir)) {
      mkdir($logDir, 0755, true);
    }
    $file = $logDir . '/' . date('Y-m-d') . '.log';
    $line = sprintf("[%s] [%s] %s\n", date('H:i:s'), strtoupper($level), $message);
    file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
  }

  public static function info(string $message)
  {
    self::write('info', $message);
  }

  public static function warning(string $message)
  {
    self::write('warning', $message);
  }

  public static function error(string $message)
  {
    self::write('error', $message);
  }
}
