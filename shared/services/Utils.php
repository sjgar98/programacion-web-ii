<?php

class Utils
{
  private static function ipCIDRCheck(string $IP, string $CIDR): bool {
    list ($net, $mask) = explode ("/", $CIDR);
    $ip_net = ip2long ($net);
    $ip_mask = ~((1 << (32 - $mask)) - 1);
    $ip_ip = ip2long ($IP);
    $ip_ip_net = $ip_ip & $ip_mask;
    return ($ip_ip_net == $ip_net);
  }

  public static function isRequestFromNetwork(string ...$cidr_masks): bool {
    foreach ($cidr_masks as $cidr) {
      if (self::ipCIDRCheck($_SERVER['REMOTE_ADDR'], $cidr)) {
        return true;
      }
    }
    return false;
  }

  public static function getBaseUrl(): string {
    return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://" . $_SERVER['HTTP_HOST'];
  }
}
