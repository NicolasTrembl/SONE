<?php
require_once __DIR__ . '/config.php';

$page = $_GET['page'] ?? 'home';

$module_path = MODULES_DIR . "/$page/index.php";

include __DIR__ . '/templates/header.php';  
if (is_dir(MODULES_DIR . "/$page") && file_exists($module_path)) {
    include $module_path;                       
} else {
    http_response_code(404);
    include __DIR__ . '/../modules/404.php';
}
include __DIR__ . '/templates/footer.php';  
?>
