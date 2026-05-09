<?php
require_once __DIR__ . '/../config/database.php';

/**
 * Classe abstraite Model
 * Tous les modèles doivent hériter de cette classe
 */
abstract class Model {
    /**
     * @var \PDO Connexion à la base de données
     */
    protected $pdo;
    
    /**
     * @var string Nom de la table associée au modèle
     */
    protected $table;
    
    /**
     * @var string Nom de la clé primaire
     */
    protected $primaryKey = 'id';
    
    /**
     * Constructeur - Initialise la connexion PDO
     */
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    /**
     * Destructeur - Ferme la connexion PDO
     */
    public function __destruct() {
        $this->pdo = null;
    }
    
    /**
     * Getter - Récupère le nom de la table
     * @return string
     */
    public function getTable() {
        return $this->table;
    }
    
    /**
     * Getter - Récupère le nom de la clé primaire
     * @return string
     */
    public function getPrimaryKey() {
        return $this->primaryKey;
    }
    
    /**
     * Getter - Récupère la connexion PDO
     * @return \PDO
     */
    public function getPdo() {
        return $this->pdo;
    }
    
    /**
     * Setter - Modifie le nom de la table
     * @param string $table
     * @return $this
     */
    public function setTable($table) {
        $this->table = $table;
        return $this;
    }
    
    /**
     * Récupère tous les enregistrements de la table
     * @return array
     */
    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    /**
     * Récupère un enregistrement par son ID
     * @param int $id
     * @return array|false
     */
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->execute(array('id' => $id));
        return $stmt->fetch();
    }
    
    /**
     * Supprime un enregistrement par son ID
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute(array('id' => $id));
    }
    
    /**
     * Compte le nombre d'enregistrements
     * @return int
     */
    public function count() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM {$this->table}");
        $result = $stmt->fetch();
        return (int)$result['total'];
    }
    
    /**
     * Récupère les derniers enregistrements
     * @param int $limit
     * @return array
     */
    public function getLast($limit = 5) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
