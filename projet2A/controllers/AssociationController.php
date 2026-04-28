<?php
require_once __DIR__ . '/../models/Association.php';
require_once __DIR__ . '/Controller.php';

class AssociationController extends Controller {
    private $associationModel;
    
    public function __construct() {
        $this->associationModel = new Association();
    }
    
    // ============ FRONT OFFICE ============
    
    public function indexFront() {
        $associations = $this->getActiveAssociations();
        $this->render('associations/list', array('associations' => $associations), 'front');
    }
    
    public function showFront($id) {
        $association = $this->findAssociationById($id);
        $this->render('associations/show', array('association' => $association), 'front');
    }
    
    // ============ BACK OFFICE ============
    
    public function indexBack($search = '', $status = '', $city = '') {
        $associations = $this->getFilteredAssociations($search, $status, $city);
        $this->render('associations/index', array('associations' => $associations), 'back');
    }
    
    public function create() {
        $this->render('associations/create', array(), 'back');
    }
    
    public function store() {
        $errors = $this->validateAssociationData($_POST);
        
        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('/nutriflow-ai/public/admin/associations/create');
        }
        
        if($this->createAssociation($_POST)) {
            $_SESSION['success'] = "Association created successfully";
        } else {
            $_SESSION['error'] = "Error creating association";
        }
        $this->redirect('/nutriflow-ai/public/admin/associations');
    }
    
    public function edit($id) {
        $association = $this->findAssociationById($id);
        $this->render('associations/edit', array('association' => $association), 'back');
    }
    
    public function update($id) {
        $errors = $this->validateAssociationData($_POST);
        
        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $this->redirect("/nutriflow-ai/public/admin/associations/edit/{$id}");
        }
        
        if($this->updateAssociation($id, $_POST)) {
            $_SESSION['success'] = "Association updated successfully";
        } else {
            $_SESSION['error'] = "Error updating association";
        }
        $this->redirect('/nutriflow-ai/public/admin/associations');
    }
    
    public function delete($id) {
        if($this->deleteAssociation($id)) {
            $_SESSION['success'] = "Association deleted successfully";
        } else {
            $_SESSION['error'] = "Error deleting association";
        }
        $this->redirect('/nutriflow-ai/public/admin/associations');
    }
    
    // ============ EXPORT PDF ============
    
    public function exportPDF() {
        $associations = $this->findAllAssociations();
        
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="associations_' . date('Y-m-d') . '.html"');
        
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head><meta charset="UTF-8"><title>Liste des associations - NutriFlow AI</title>';
        echo '<style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #2d4a1e; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #2d4a1e; color: white; padding: 10px; text-align: left; }
            td { border: 1px solid #ddd; padding: 8px; }
            tr:nth-child(even) { background-color: #f2f2f2; }
            .total { margin-top: 20px; text-align: right; font-size: 16px; font-weight: bold; }
        </style></head><body>';
        
        echo '<h1>Liste des associations - NutriFlow AI</h1>';
        echo '<p>Date d\'export: ' . date('d/m/Y H:i') . '</p>';
        
        echo '<table border="1" cellpadding="8" cellspacing="0" width="100%">';
        echo '<tr style="background-color:#2d4a1e; color:white;"><th>ID</th><th>Nom</th><th>Email</th><th>Telephone</th><th>Ville</th><th>Statut</th></tr>';
        
        foreach($associations as $assoc) {
            $statut = ($assoc['status'] == 'active') ? 'Active' : 'Inactive';
            
            echo '<tr>';
            echo '<td>' . $assoc['id'] . '</td>';
            echo '<td>' . htmlspecialchars($assoc['name']) . '</td>';
            echo '<td>' . htmlspecialchars($assoc['email']) . '</td>';
            echo '<td>' . htmlspecialchars($assoc['phone']) . '</td>';
            echo '<td>' . htmlspecialchars($assoc['city']) . '</td>';
            echo '<td>' . $statut . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        echo '<div class="total">Total associations: ' . count($associations) . '</div>';
        echo '</body></html>';
        exit();
    }
    
    // ============ QR CODE ============
    
    public function generateQRCode($id) {
        $association = $this->findAssociationById($id);
        if(!$association) {
            die("Association not found");
        }
        
        $ip = $_SERVER['SERVER_ADDR'];
        if($ip == '::1' || $ip == '127.0.0.1') {
            $host = gethostname();
            $ip = gethostbyname($host);
        }
        
        $url = "http://" . $ip . "/nutriflow-ai/public/associations/show/" . $id;
        
        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($url);
        
        header('Content-Type: image/png');
        
        $imageData = file_get_contents($qrUrl);
        
        if($imageData) {
            echo $imageData;
        } else {
            echo '<html><body style="text-align:center;padding:50px;font-family:Arial;">';
            echo '<h2>QR Code pour ' . htmlspecialchars($association['name']) . '</h2>';
            echo '<p><strong>URL à scanner:</strong></p>';
            echo '<p><a href="' . $url . '" target="_blank">' . $url . '</a></p>';
            echo '</body></html>';
        }
        exit();
    }
    
    // ============ RECHERCHE ET FILTRES ============
    
    public function getFilteredAssociations($search = '', $status = '', $city = '') {
        $pdo = $this->associationModel->getPdo();
        
        $sql = "SELECT * FROM associations WHERE 1=1";
        $params = array();
        
        if(!empty($search)) {
            $sql .= " AND (name LIKE :search OR email LIKE :search OR city LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        
        if(!empty($status)) {
            $sql .= " AND status = :status";
            $params['status'] = $status;
        }
        
        if(!empty($city)) {
            $sql .= " AND city LIKE :city";
            $params['city'] = '%' . $city . '%';
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    // ============ MÉTHODES MÉTIER (SQL) ============
    
    private function findAllAssociations() {
        $pdo = $this->associationModel->getPdo();
        $stmt = $pdo->query("SELECT * FROM associations ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    private function findAssociationById($id) {
        $pdo = $this->associationModel->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM associations WHERE id = :id");
        $stmt->execute(array('id' => $id));
        return $stmt->fetch();
    }
    
    private function getActiveAssociations() {
        $pdo = $this->associationModel->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM associations WHERE status = 'active' ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    private function createAssociation($data) {
        $sql = "INSERT INTO associations (name, email, phone, address, city, postal_code, siret, mission) 
                VALUES (:name, :email, :phone, :address, :city, :postal_code, :siret, :mission)";
        $pdo = $this->associationModel->getPdo();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    private function updateAssociation($id, $data) {
        $sql = "UPDATE associations SET name = :name, email = :email, phone = :phone, 
                address = :address, city = :city, postal_code = :postal_code, 
                siret = :siret, mission = :mission, status = :status WHERE id = :id";
        $data['id'] = $id;
        $pdo = $this->associationModel->getPdo();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    private function deleteAssociation($id) {
        $pdo = $this->associationModel->getPdo();
        $stmt = $pdo->prepare("DELETE FROM associations WHERE id = :id");
        return $stmt->execute(array('id' => $id));
    }
    
    private function validateAssociationData($data) {
        $errors = array();
        
        if(empty($data['name']) || strlen($data['name']) < 3) {
            $errors['name'] = "Name must contain at least 3 characters";
        }
        
        if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Invalid email";
        }
        
        if(empty($data['phone']) || !preg_match('/^[0-9]{8}$/', $data['phone'])) {
            $errors['phone'] = "Invalid phone number (8 digits)";
        }
        
        if(empty($data['siret']) || !preg_match('/^[0-9]{8}$/', $data['siret'])) {
            $errors['siret'] = "Invalid Tax ID (8 digits)";
        }
        
        if(empty($data['address']) || strlen($data['address']) < 5) {
            $errors['address'] = "Invalid address";
        }
        
        if(empty($data['city']) || strlen($data['city']) < 2) {
            $errors['city'] = "Invalid city";
        }
        
        if(empty($data['postal_code']) || !preg_match('/^[0-9]{5}$/', $data['postal_code'])) {
            $errors['postal_code'] = "Invalid postal code (5 digits)";
        }
        
        if(empty($data['mission']) || strlen($data['mission']) < 20) {
            $errors['mission'] = "Mission must contain at least 20 characters";
        }
        
        return $errors;
    }
    
    // ============ MÉTHODES POUR LE DASHBOARD ============
    
    public function getAllAssociationsForDashboard() {
        $pdo = $this->associationModel->getPdo();
        $stmt = $pdo->query("SELECT * FROM associations ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
}
?>
