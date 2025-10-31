<?php
// backend/cloudinary.php
// Display-only helpers for generating Cloudinary delivery URLs (no uploads, no Composer).
// This file DOES NOT use your API secret and does not perform server-side uploads.
// It simply builds or enhances URLs for optimized delivery.

// Load config for cloud_name when building from public_id
$config = file_exists(__DIR__ . '/config.php') ? require __DIR__ . '/config.php' : [];
$CLOUD_NAME = isset($config['cloud_name']) ? $config['cloud_name'] : null;

/**
 * Build a Cloudinary transformation string from options.
 * Supported keys (minimal):
 * - f (format): e.g., 'auto'  => f_auto
 * - q (quality): e.g., 'auto' => q_auto
 * - w (width): int            => w_600
 * - h (height): int           => h_400
 * - c (crop): e.g., 'fill','fit','pad'
 * - dpr: e.g., '2.0'
 *
 * Defaults: f_auto, q_auto unless explicitly disabled via ['f' => null, 'q' => null].
 */
function cld_transform_string(array $opts = [])
{
  $parts = [];
  // Defaults
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

/**
 * Inject a transformation string into an existing Cloudinary URL right after '/upload/'.
 * If the URL already contains transformations, this will simply insert the new string after '/upload/'.
 * Assumes your stored URLs do NOT include an existing transformation segment (typical for your data).
 */
function cld_inject_transform($url, $transform)
{
  if (!$transform)
    return $url;
  $needle = '/upload/';
  $pos = strpos($url, $needle);
  if ($pos === false)
    return $url; // Not a standard Cloudinary upload URL
  // Insert transform segment
  return substr($url, 0, $pos + strlen($needle)) . $transform . '/' . substr($url, $pos + strlen($needle));
}

/**
 * Convenience: produce a transformed URL from an existing full URL and simple options.
 */
function cld_url_with($url, array $opts = [])
{
  $t = cld_transform_string($opts);
  return cld_inject_transform($url, $t);
}

/**
 * Build a delivery URL from a public_id (no secret needed).
 * Options support same keys as cld_transform_string plus:
 * - format: e.g., 'jpg' (optional; omit to let f_auto pick best)
 * - resource_type: 'image' (default) or 'video'
 */
function cld_public_url($publicId, array $opts = [])
{
  global $CLOUD_NAME;
  if (!$CLOUD_NAME)
    return null;
  $resource = isset($opts['resource_type']) ? $opts['resource_type'] : 'image';
  $transform = cld_transform_string($opts);
  $format = isset($opts['format']) && $opts['format'] !== '' ? '.' . preg_replace('/[^a-z0-9]/i', '', $opts['format']) : '';

  // Public ID may contain folders; encode path segments
  $segments = array_map('rawurlencode', explode('/', trim($publicId, '/')));
  $pid = implode('/', $segments);

  $base = "https://res.cloudinary.com/{$CLOUD_NAME}/{$resource}/upload";
  if ($transform)
    $base .= '/' . $transform;
  return $base . '/' . $pid . $format;
}
