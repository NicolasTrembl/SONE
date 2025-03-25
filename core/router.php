<?php
require_once __DIR__ . '/config.php';

$request_uri = $_SERVER['REQUEST_URI'];
$file_path = __DIR__ . '/../' . $request_uri;

if (file_exists($file_path) && preg_match('/\.php$/', $request_uri)) {
    include $file_path;
    exit;
}

if (file_exists($file_path) && preg_match('/\.(js|css|png|jpg|jpeg|gif|svg|woff2?|ttf|eot)$/', $request_uri)) {
    $mime_types = [
        'js'   => 'application/javascript',
        'css'  => 'text/css',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
        'eot'  => 'application/vnd.ms-fontobject',
    ];
    
    $ext = pathinfo($file_path, PATHINFO_EXTENSION);
    if (isset($mime_types[$ext])) {
        header("Content-Type: " . $mime_types[$ext]);
    }
    
    readfile($file_path);
    exit;
}

$page = $_GET['page'] ?? 'home';

$module_path = MODULES_DIR . "/$page/index.php";

include __DIR__ . '/templates/header.php';  
if (is_dir(MODULES_DIR . "/$page") && file_exists($module_path)) {
    include $module_path;                       
} else {
    include __DIR__ . '/../modules/404.php';
}
include __DIR__ . '/templates/footer.php';  
?>
