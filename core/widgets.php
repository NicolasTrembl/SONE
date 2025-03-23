<?php
$modules = [];

foreach (scandir(MODULES_DIR) as $module) {
    if ($module === "." || $module === "..") continue;

    $json_file = MODULES_DIR . "/$module/module.json";
    if (file_exists($json_file)) {
        $json_data = json_decode(file_get_contents($json_file), true);
        $modules[$module] = $json_data;
    }
}
?>
