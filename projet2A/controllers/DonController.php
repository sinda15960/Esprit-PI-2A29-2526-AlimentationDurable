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
    
    // ============ FRONT OFFICE ============
    
    public function create() {
        $associations = $this->getActiveAssociations();
        $this->render('dons/form', array('associations' => $associations), 'front');
    }
    
    public function store() {
        $errors = $this->validateDonData($_POST);
        
        if(!empty($errors)) {
            $_SESSION['don_errors'] = $errors;
            $_SESSION['don_data'] = $_POST;
            $this->redirect('/nutriflow-ai/public/don');
        }
        
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
        
        if($this->createDonation($data)) {
            $this->render('dons/success', array(), 'front');
        } else {
            $_SESSION['error'] = "Error saving donation";
            $this->redirect('/nutriflow-ai/public/don');
        }
    }
    
    // ============ BACK OFFICE ============
    
    public function indexBack($sort = 'date_desc', $search = '', $date_start = '', $date_end = '') {
        $dons = $this->getFilteredDons($sort, $search, $date_start, $date_end);
        $total = $this->getTotalDonations();
        $this->render('dons/index', array('dons' => $dons, 'total' => $total), 'back');
    }
    
    public function createBack() {
        $associations = $this->getActiveAssociations();
        $this->render('dons/create', array('associations' => $associations), 'back');
    }
    
    public function storeBack() {
        $errors = $this->validateDonData($_POST);
        
        if(!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('/nutriflow-ai/public/admin/dons/create');
        }
        
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
        
        if($this->createDonation($data)) {
            $_SESSION['success'] = "Donation added successfully";
        } else {
            $_SESSION['error'] = "Error adding donation";
        }
        $this->redirect('/nutriflow-ai/public/admin/dons');
    }
    
    public function editBack($id) {
        $don = $this->findDonationById($id);
        $associations = $this->getActiveAssociations();
        $this->render('dons/edit_back', array('don' => $don, 'associations' => $associations), 'back');
    }
    
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
        
        if($this->updateDonation($id, $data)) {
            $_SESSION['success'] = "Donation updated successfully";
        } else {
            $_SESSION['error'] = "Error updating donation";
        }
        $this->redirect('/nutriflow-ai/public/admin/dons');
    }
    
    public function deleteBack($id) {
        if($this->deleteDonation($id)) {
            $_SESSION['success'] = "Donation deleted successfully";
        } else {
            $_SESSION['error'] = "Error deleting donation";
        }
        $this->redirect('/nutriflow-ai/public/admin/dons');
    }
    
    public function updateStatus($id) {
        $status = isset($_POST['status']) ? $_POST['status'] : 'pending';
        if($this->updateDonationStatus($id, $status)) {
            $_SESSION['success'] = "Status updated successfully";
        } else {
            $_SESSION['error'] = "Error updating status";
        }
        $this->redirect('/nutriflow-ai/public/admin/dons');
    }
    
    // ============ EXPORT PDF (VERSION HTML = SIMPLE ET FONCTIONNELLE) ============
    
    public function exportPDF() {
        $dons = $this->getAllDonsWithAssociation();
        
        // En-têtes pour forcer le téléchargement en HTML
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="dons_' . date('Y-m-d') . '.html"');
        
        // Créer le contenu HTML
        echo '<!DOCTYPE html>';
        echo '<html>';
        echo '<head><meta charset="UTF-8"><title>Liste des dons - NutriFlow AI</title>';
        echo '<style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            h1 { color: #2d4a1e; text-align: center; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #2d4a1e; color: white; padding: 10px; text-align: left; }
            td { border: 1px solid #ddd; padding: 8px; }
            tr:nth-child(even) { background-color: #f2f2f2; }
            .total { margin-top: 20px; text-align: right; font-size: 16px; font-weight: bold; }
            .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
        </style>';
        echo '</head><body>';
        
        echo '<h1>Liste des dons - NutriFlow AI</h1>';
        echo '<p>Date d\'export: ' . date('d/m/Y H:i') . '</p>';
        
        echo '<table>';
        echo '<tr><th>ID</th><th>Donateur</th><th>Email</th><th>Association</th><th>Type</th><th>Montant/Quantite</th><th>Statut</th><th>Date</th></tr>';
        
        $totalMontant = 0;
        foreach($dons as $don) {
            if($don['donation_type'] == 'monetary') {
                $montant = number_format($don['amount'], 2) . ' DT';
                $totalMontant += $don['amount'];
            } else {
                $montant = $don['food_type'] . ' - ' . $don['quantity'] . ' kg';
            }
            
            $type = ucfirst($don['donation_type']);
            $statut = $this->getStatusLabel($don['status']);
            $date = date('d/m/Y', strtotime($don['created_at']));
            
            echo '<tr>';
            echo '<td>' . $don['id'] . '</td>';
            echo '<td>' . htmlspecialchars($don['donor_name']) . '</td>';
            echo '<td>' . htmlspecialchars($don['donor_email']) . '</td>';
            echo '<td>' . htmlspecialchars($don['association_name']) . '</td>';
            echo '<td>' . $type . '</td>';
            echo '<td>' . $montant . '</td>';
            echo '<td>' . $statut . '</td>';
            echo '<td>' . $date . '</td>';
            echo '</tr>';
        }
        
        echo '</table>';
        echo '<div class="total">Montant total: ' . number_format($totalMontant, 2) . ' DT</div>';
        echo '<div class="footer">Document généré par NutriFlow AI</div>';
        echo '</body></html>';
        exit();
    }
    
    private function getStatusLabel($status) {
        $labels = array(
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        );
        return isset($labels[$status]) ? $labels[$status] : $status;
    }
    
    // ============ RECHERCHE ET FILTRE ============
    
    public function getFilteredDons($sort = 'date_desc', $search = '', $date_start = '', $date_end = '') {
        $pdo = $this->donModel->getPdo();
        
        $sql = "SELECT d.*, a.name as association_name 
                FROM dons d 
                JOIN associations a ON d.association_id = a.id 
                WHERE 1=1";
        $params = array();
        
        if(!empty($search)) {
            $sql .= " AND (d.donor_name LIKE :search OR d.donor_email LIKE :search OR a.name LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        
        if(!empty($date_start)) {
            $sql .= " AND DATE(d.created_at) >= :date_start";
            $params['date_start'] = $date_start;
        }
        
        if(!empty($date_end)) {
            $sql .= " AND DATE(d.created_at) <= :date_end";
            $params['date_end'] = $date_end;
        }
        
        switch($sort) {
            case 'amount_asc':
                $sql .= " ORDER BY d.amount ASC";
                break;
            case 'amount_desc':
                $sql .= " ORDER BY d.amount DESC";
                break;
            case 'date_asc':
                $sql .= " ORDER BY d.created_at ASC";
                break;
            case 'name_asc':
                $sql .= " ORDER BY d.donor_name ASC";
                break;
            case 'name_desc':
                $sql .= " ORDER BY d.donor_name DESC";
                break;
            case 'status_asc':
                $sql .= " ORDER BY d.status ASC";
                break;
            default:
                $sql .= " ORDER BY d.created_at DESC";
        }
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function getDonsByDateRange($start, $end) {
        $pdo = $this->donModel->getPdo();
        $sql = "SELECT d.*, a.name as association_name 
                FROM dons d 
                JOIN associations a ON d.association_id = a.id 
                WHERE DATE(d.created_at) BETWEEN :start AND :end
                ORDER BY d.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array('start' => $start, 'end' => $end));
        return $stmt->fetchAll();
    }
    
    // ============ MÉTHODES MÉTIER (SQL) ============
    
    private function getActiveAssociations() {
        $pdo = $this->associationModel->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM associations WHERE status = 'active' ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    private function createDonation($data) {
        $sql = "INSERT INTO dons (association_id, donor_name, donor_email, donor_phone, amount, 
                donation_type, food_type, quantity, message, payment_method) 
                VALUES (:association_id, :donor_name, :donor_email, :donor_phone, :amount, 
                :donation_type, :food_type, :quantity, :message, :payment_method)";
        $pdo = $this->donModel->getPdo();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    private function getAllDonsWithAssociation() {
        $pdo = $this->donModel->getPdo();
        $sql = "SELECT d.*, a.name as association_name 
                FROM dons d JOIN associations a ON d.association_id = a.id 
                ORDER BY d.created_at DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    private function findDonationById($id) {
        $pdo = $this->donModel->getPdo();
        $stmt = $pdo->prepare("SELECT * FROM dons WHERE id = :id");
        $stmt->execute(array('id' => $id));
        return $stmt->fetch();
    }
    
    private function updateDonation($id, $data) {
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
        $data['id'] = $id;
        $pdo = $this->donModel->getPdo();
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    private function deleteDonation($id) {
        $pdo = $this->donModel->getPdo();
        $stmt = $pdo->prepare("DELETE FROM dons WHERE id = :id");
        return $stmt->execute(array('id' => $id));
    }
    
    private function updateDonationStatus($id, $status) {
        $pdo = $this->donModel->getPdo();
        $stmt = $pdo->prepare("UPDATE dons SET status = :status WHERE id = :id");
        return $stmt->execute(array('status' => $status, 'id' => $id));
    }
    
    private function getTotalDonations() {
        $pdo = $this->donModel->getPdo();
        $stmt = $pdo->query("SELECT SUM(amount) as total FROM dons WHERE status != 'cancelled'");
        $result = $stmt->fetch();
        return isset($result['total']) ? $result['total'] : 0;
    }
    
    private function validateDonData($data) {
        $errors = array();
        
        if(empty($data['association_id'])) {
            $errors['association_id'] = "Please select an organization";
        }
        
        if(empty($data['donor_name']) || strlen($data['donor_name']) < 3) {
            $errors['donor_name'] = "Name must contain at least 3 characters";
        }
        
        if(empty($data['donor_email']) || !filter_var($data['donor_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['donor_email'] = "Invalid email";
        }
        
        if($data['donation_type'] === 'monetary') {
            if(empty($data['amount']) || $data['amount'] <= 0 || $data['amount'] > 100000) {
                $errors['amount'] = "Invalid amount (between 1 and 100000)";
            }
        } elseif($data['donation_type'] === 'food') {
            $food_type = isset($data['food_type']) ? $data['food_type'] : '';
            $quantity = isset($data['quantity']) ? $data['quantity'] : 0;
            
            if(empty($food_type) || strlen($food_type) < 2) {
                $errors['food_type'] = "Invalid food type";
            }
            if(empty($quantity) || $quantity <= 0 || $quantity > 10000) {
                $errors['quantity'] = "Invalid quantity (between 1 and 10000 kg)";
            }
        }
        
        return $errors;
    }
    
    // ============ MÉTHODES POUR LE DASHBOARD ============
    
    public function getAllDonsForDashboard() {
        $pdo = $this->donModel->getPdo();
        $sql = "SELECT d.*, a.name as association_name 
                FROM dons d JOIN associations a ON d.association_id = a.id 
                ORDER BY d.created_at DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }
    
    public function getTotalDonationsForDashboard() {
        $pdo = $this->donModel->getPdo();
        $stmt = $pdo->query("SELECT SUM(amount) as total FROM dons WHERE status != 'cancelled'");
        $result = $stmt->fetch();
        return isset($result['total']) ? $result['total'] : 0;
    }
}
?>
