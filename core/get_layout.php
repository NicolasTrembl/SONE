<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['layout'])) {
    $layout = json_decode($_POST['layout'], true);

    foreach ($layout as $widget) {
        $classes = htmlspecialchars($widget['classes'], ENT_QUOTES, 'UTF-8'); 
        echo "<div class='$classes'>";
        
        if (!empty($widget['content'])) {
            $file_path = __DIR__ . '/../modules/' . $widget['content'] . '/widget.php';

            if (file_exists($file_path)) {
                include $file_path;
            } else {
                echo "<p>Erreur : Widget introuvable</p>";
            }
        }

        echo "</div>";
    }
}
?>
