<?php
$root = realpath(__DIR__ . '/../../.local-preview/public');
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$target = realpath($root . $path);

if ($target && str_starts_with($target, $root) && is_file($target)) {
    return false;
}

if (str_starts_with($path, '/blog')) {
    $_SERVER['SCRIPT_FILENAME'] = $root . '/blog/index.php';
    require $root . '/blog/index.php';
    return true;
}

if (is_file($root . $path . '/index.html')) {
    readfile($root . $path . '/index.html');
    return true;
}

http_response_code(404);
readfile($root . '/404.html');
return true;
