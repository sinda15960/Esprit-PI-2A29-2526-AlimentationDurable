<?php
class Controller {
    protected function render($view, $data = array(), $layout = 'front') {
        extract($data);
        ob_start();
        require_once __DIR__ . "/../views/{$layout}/{$view}.php";
        $content = ob_get_clean();
        require_once __DIR__ . "/../views/{$layout}/layout.php";
    }
    
    protected function redirect($url) {
        header("Location: {$url}");
        exit();
    }
}
?>
