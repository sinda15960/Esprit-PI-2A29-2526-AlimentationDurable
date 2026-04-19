<?php
require_once __DIR__ . '/../models/Association.php';
require_once __DIR__ . '/Controller.php';

class AssociationController extends Controller {
    private $associationModel;
    
    public function __construct() {
        $this->associationModel = new Association();
    }
    
    public function indexFront() {
        $associations = $this->associationModel->getActiveAssociations();
        $this->render('associations/list', array('associations' => $associations), 'front');
    }
    
    public function showFront($id) {
        $association = $this->associationModel->findById($id);
        $this->render('associations/show', array('association' => $association), 'front');
    }
    
    public function indexBack() {
        $associations = $this->associationModel->findAll();
        $this->render('associations/index', array('associations' => $associations), 'back');
    }
    
    public function create() {
        $this->render('associations/create', array(), 'back');
    }
    
    public function store() {
        if($this->associationModel->create($_POST)) {
            $_SESSION['success'] = "Association créée";
        }
        $this->redirect('/nutriflow-ai/public/admin/associations');
    }
    
    public function edit($id) {
        $association = $this->associationModel->findById($id);
        $this->render('associations/edit', array('association' => $association), 'back');
    }
    
    public function update($id) {
        if($this->associationModel->update($id, $_POST)) {
            $_SESSION['success'] = "Association mise à jour";
        }
        $this->redirect('/nutriflow-ai/public/admin/associations');
    }
    
    public function delete($id) {
        $this->associationModel->delete($id);
        $_SESSION['success'] = "Association supprimée";
        $this->redirect('/nutriflow-ai/public/admin/associations');
    }
}
?>