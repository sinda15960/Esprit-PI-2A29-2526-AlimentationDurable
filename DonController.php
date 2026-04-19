<?php
require_once __DIR__ . '/../models/Don.php';
require_once __DIR__ . '/../models/Association.php';
require_once __DIR__ . '/Controller.php';

class DonController extends Controller {
    private $donModel;
    private $associationModel;
    
    public function __construct() {
        $this->donModel = new Don();
        $this->associationModel = new Association();
    }
    
    // ==================== FRONT OFFICE ====================
    
    // Formulaire de don (Front)
    public function create() {
        $associations = $this->associationModel->getActiveAssociations();
        $this->render('dons/form', array('associations' => $associations), 'front');
    }
    
    // Sauvegarde du don (Front)
    public function store() {
        $data = array(
            'association_id' => $_POST['association_id'],
            'donor_name' => htmlspecialchars($_POST['donor_name']),
            'donor_email' => $_POST['donor_email'],
            'donor_phone' => isset($_POST['donor_phone']) ? $_POST['donor_phone'] : '',
            'amount' => isset($_POST['amount']) ? $_POST['amount'] : 0,
            'donation_type' => $_POST['donation_type'],
            'food_type' => isset($_POST['food_type']) ? $_POST['food_type'] : '',
            'quantity' => isset($_POST['quantity']) ? $_POST['quantity'] : 0,
            'message' => isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '',
            'payment_method' => $_POST['payment_method']
        );
        
        if($this->donModel->create($data)) {
            $this->render('dons/success', array(), 'front');
        } else {
            $_SESSION['error'] = "Erreur lors de l'enregistrement";
            $this->redirect('/nutriflow-ai/public/don');
        }
    }
    
    // ==================== BACK OFFICE ====================
    
    // Liste des dons (Back)
    public function indexBack() {
        $dons = $this->donModel->getDonsWithAssociation();
        $total = $this->donModel->getTotalDonations();
        $this->render('dons/index', array('dons' => $dons, 'total' => $total), 'back');
    }
    
    // Formulaire AJOUTER un don (Back)
    public function createBack() {
        $associations = $this->associationModel->getActiveAssociations();
        $this->render('dons/create', array('associations' => $associations), 'back');
    }
    
    // Sauvegarde AJOUTER un don (Back)
    public function storeBack() {
        $data = array(
            'association_id' => $_POST['association_id'],
            'donor_name' => htmlspecialchars($_POST['donor_name']),
            'donor_email' => $_POST['donor_email'],
            'donor_phone' => isset($_POST['donor_phone']) ? $_POST['donor_phone'] : '',
            'amount' => isset($_POST['amount']) ? $_POST['amount'] : 0,
            'donation_type' => $_POST['donation_type'],
            'food_type' => isset($_POST['food_type']) ? $_POST['food_type'] : '',
            'quantity' => isset($_POST['quantity']) ? $_POST['quantity'] : 0,
            'message' => isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '',
            'payment_method' => $_POST['payment_method']
        );
        
        if($this->donModel->create($data)) {
            $_SESSION['success'] = "Don ajouté avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout";
        }
        $this->redirect('/nutriflow-ai/public/admin/dons');
    }
    
    // Formulaire MODIFIER un don (Back)
    public function editBack($id) {
        $don = $this->donModel->findById($id);
        $associations = $this->associationModel->getActiveAssociations();
        $this->render('dons/edit_back', array('don' => $don, 'associations' => $associations), 'back');
    }
    
    // Sauvegarde MODIFICATION don (Back)
    public function updateBack($id) {
        $data = array(
            'association_id' => $_POST['association_id'],
            'donor_name' => htmlspecialchars($_POST['donor_name']),
            'donor_email' => $_POST['donor_email'],
            'donor_phone' => isset($_POST['donor_phone']) ? $_POST['donor_phone'] : '',
            'amount' => isset($_POST['amount']) ? $_POST['amount'] : 0,
            'donation_type' => $_POST['donation_type'],
            'food_type' => isset($_POST['food_type']) ? $_POST['food_type'] : '',
            'quantity' => isset($_POST['quantity']) ? $_POST['quantity'] : 0,
            'message' => isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '',
            'payment_method' => $_POST['payment_method'],
            'status' => $_POST['status']
        );
        
        $sql = "UPDATE dons SET 
                association_id = :association_id, 
                donor_name = :donor_name, 
                donor_email = :donor_email, 
                donor_phone = :donor_phone, 
                amount = :amount, 
                donation_type = :donation_type, 
                food_type = :food_type, 
                quantity = :quantity, 
                message = :message, 
                payment_method = :payment_method, 
                status = :status 
                WHERE id = :id";
        $stmt = $this->donModel->pdo->prepare($sql);
        $data['id'] = $id;
        
        if($stmt->execute($data)) {
            $_SESSION['success'] = "Don modifié avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la modification";
        }
        $this->redirect('/nutriflow-ai/public/admin/dons');
    }
    
    // Supprimer un don (Back)
    public function deleteBack($id) {
        if($this->donModel->delete($id)) {
            $_SESSION['success'] = "Don supprimé avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression";
        }
        $this->redirect('/nutriflow-ai/public/admin/dons');
    }
    
    // Changer le statut d'un don
    public function updateStatus($id) {
        $status = isset($_POST['status']) ? $_POST['status'] : 'pending';
        if($this->donModel->update($id, array('status' => $status))) {
            $_SESSION['success'] = "Statut mis à jour";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour";
        }
        $this->redirect('/nutriflow-ai/public/admin/dons');
    }
}
?>