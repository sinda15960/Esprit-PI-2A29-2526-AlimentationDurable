<?php
session_start();

spl_autoload_register(function($class) {
    $paths = array(__DIR__ . '/../controllers/', __DIR__ . '/../models/');
    foreach($paths as $path) {
        $file = $path . $class . '.php';
        if(file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$request = $_SERVER['REQUEST_URI'];
$request = str_replace('/nutriflow-ai/public', '', $request);
$request = trim($request, '/');
$params = explode('/', $request);
$controller = isset($params[0]) && !empty($params[0]) ? $params[0] : 'home';
$action = isset($params[1]) ? $params[1] : 'index';
$id = isset($params[2]) ? $params[2] : null;

if($controller == 'associations') {
    $c = new AssociationController();
    if($action == 'index' || $action == '') $c->indexFront();
    elseif($action == 'show' && $id) $c->showFront($id);
    else $c->indexFront();
}
elseif($controller == 'don') {
    $c = new DonController();
    if($action == 'index' || $action == '') $c->create();
    elseif($action == 'store') $c->store();
    else $c->create();
}
elseif($controller == 'admin') {
    // ACCÈS LIBRE - PAS D'AUTHENTIFICATION
    if($action == 'dashboard') {
        require_once __DIR__ . '/../views/back/dashboard.php';
    }
    elseif($action == 'associations') {
        $c = new AssociationController();
        if($id == 'qrcode' && isset($params[3])) {
            $c->generateQRCode($params[3]);
        }
        elseif($id == 'export-pdf') {
            $c->exportPDF();
        }
        elseif($id == 'create') {
            $c->create();
        }
        elseif($id == 'store') {
            $c->store();
        }
        elseif($id == 'edit' && isset($params[3])) {
            $c->edit($params[3]);
        }
        elseif($id == 'update' && isset($params[3])) {
            $c->update($params[3]);
        }
        elseif($id == 'delete' && isset($params[3])) {
            $c->delete($params[3]);
        }
        else {
            $c->indexBack();
        }
    }
    elseif($action == 'dons') {
        $c = new DonController();
        if($id == 'export-pdf') {
            $c->exportPDF();
        }
        elseif($id == 'create') {
            $c->createBack();
        }
        elseif($id == 'store') {
            $c->storeBack();
        }
        elseif($id == 'view' && isset($params[3])) {
            $c->viewDon($params[3]);
        }
        elseif($id == 'edit' && isset($params[3])) {
            $c->editBack($params[3]);
        }
        elseif($id == 'update' && isset($params[3])) {
            $c->updateBack($params[3]);
        }
        elseif($id == 'delete' && isset($params[3])) {
            $c->deleteBack($params[3]);
        }
        elseif($id == 'update-status' && isset($params[3])) {
            $c->updateStatus($params[3]);
        }
        else {
            $c->indexBack();
        }
    }
    else {
        header('Location: /nutriflow-ai/public/admin/dashboard');
        exit();
    }
}
else {
    $content = '';
    require_once __DIR__ . '/../views/front/layout.php';
}
?>
