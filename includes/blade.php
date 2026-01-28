<?php
/**
 * Blade Template Engine Initialization
 * Using jenssegers/blade for standalone Blade templating
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Jenssegers\Blade\Blade;

// Define paths
$viewsPath = __DIR__ . '/../views';
$cachePath = __DIR__ . '/../cache';

// Create cache directory if it doesn't exist
if (!file_exists($cachePath)) {
    mkdir($cachePath, 0755, true);
}

// Initialize Blade
$blade = new Blade($viewsPath, $cachePath);

/**
 * Render a Blade view
 * @param string $view View name (without .blade.php)
 * @param array $data Data to pass to the view
 * @return string
 */
function view($view, $data = []) {
    global $blade;
    return $blade->render($view, $data);
}

/**
 * Render and output a Blade view
 * @param string $view View name (without .blade.php)
 * @param array $data Data to pass to the view
 */
function renderView($view, $data = []) {
    echo view($view, $data);
}
