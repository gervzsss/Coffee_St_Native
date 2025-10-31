<?php

$config = file_exists(__DIR__ . '/config.php') ? require __DIR__ . '/config.php' : [];
$CLOUD_NAME = isset($config['cloud_name']) ? $config['cloud_name'] : null;

function cld_transform_string(array $opts = [])
{
  $parts = [];
  if (!array_key_exists('f', $opts) || $opts['f'] !== null) {
    $parts[] = 'f_' . (isset($opts['f']) ? $opts['f'] : 'auto');
  }
  if (!array_key_exists('q', $opts) || $opts['q'] !== null) {
    $parts[] = 'q_' . (isset($opts['q']) ? $opts['q'] : 'auto');
  }
  if (isset($opts['w']) && (int) $opts['w'] > 0)
    $parts[] = 'w_' . (int) $opts['w'];
  if (isset($opts['h']) && (int) $opts['h'] > 0)
    $parts[] = 'h_' . (int) $opts['h'];
  if (isset($opts['c']) && $opts['c'] !== '')
    $parts[] = 'c_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $opts['c']);
  if (isset($opts['dpr']) && $opts['dpr'] !== '')
    $parts[] = 'dpr_' . preg_replace('/[^0-9.]/', '', (string) $opts['dpr']);
  return implode(',', $parts);
}

function cld_inject_transform($url, $transform)
{
  if (!$transform)
    return $url;
  $needle = '/upload/';
  $pos = strpos($url, $needle);
  if ($pos === false)
    return $url;
  return substr($url, 0, $pos + strlen($needle)) . $transform . '/' . substr($url, $pos + strlen($needle));
}

function cld_url_with($url, array $opts = [])
{
  $t = cld_transform_string($opts);
  return cld_inject_transform($url, $t);
}

function cld_public_url($publicId, array $opts = [])
{
  global $CLOUD_NAME;
  if (!$CLOUD_NAME)
    return null;
  $resource = isset($opts['resource_type']) ? $opts['resource_type'] : 'image';
  $transform = cld_transform_string($opts);
  $format = isset($opts['format']) && $opts['format'] !== '' ? '.' . preg_replace('/[^a-z0-9]/i', '', $opts['format']) : '';

  $segments = array_map('rawurlencode', explode('/', trim($publicId, '/')));
  $pid = implode('/', $segments);

  $base = "https://res.cloudinary.com/{$CLOUD_NAME}/{$resource}/upload";
  if ($transform)
    $base .= '/' . $transform;
  return $base . '/' . $pid . $format;
}
