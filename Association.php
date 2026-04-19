<?php
require_once __DIR__ . '/Model.php';

class Association extends Model {
    protected $table = 'associations';
    
    public function create($data) {
        $sql = "INSERT INTO associations (name, email, phone, address, city, postal_code, siret, mission) 
                VALUES (:name, :email, :phone, :address, :city, :postal_code, :siret, :mission)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function update($id, $data) {
        $sql = "UPDATE associations SET name = :name, email = :email, phone = :phone, 
                address = :address, city = :city, postal_code = :postal_code, 
                siret = :siret, mission = :mission, status = :status WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }
    
    public function getActiveAssociations() {
        $stmt = $this->pdo->prepare("SELECT * FROM associations WHERE status = 'active' ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>