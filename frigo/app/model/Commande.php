<?php
class Commande {
    private ?int $id = null;
    private string $nom_client;
    private string $telephone;
    private string $adresse;
    private string $methode_paiement;
    private float $total = 0;
    private string $statut = 'en_attente';
    private string $date_commande;
    private PDO $pdo;

    public function __construct(
        ?int $id = null,
        string $nom_client = '',
        string $telephone = '',
        string $adresse = '',
        string $methode_paiement = '',
        float $total = 0,
        string $statut = 'en_attente',
        string $date_commande = ''
    ) {
        $this->id = $id;
        $this->nom_client = $nom_client;
        $this->telephone = $telephone;
        $this->adresse = $adresse;
        $this->methode_paiement = $methode_paiement;
        $this->total = $total;
        $this->statut = $statut;
        $this->date_commande = $date_commande ?: date('Y-m-d H:i:s');
        $this->pdo = Database::getInstance()->getPdo();
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNomClient(): string { return $this->nom_client; }
    public function getTelephone(): string { return $this->telephone; }
    public function getAdresse(): string { return $this->adresse; }
    public function getMethodePaiement(): string { return $this->methode_paiement; }
    public function getTotal(): float { return $this->total; }
    public function getStatut(): string { return $this->statut; }
    public function getDateCommande(): string { return $this->date_commande; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setNomClient(string $nom_client): void { $this->nom_client = $nom_client; }
    public function setTelephone(string $telephone): void { $this->telephone = $telephone; }
    public function setAdresse(string $adresse): void { $this->adresse = $adresse; }
    public function setMethodePaiement(string $methode_paiement): void { $this->methode_paiement = $methode_paiement; }
    public function setTotal(float $total): void { $this->total = $total; }
    public function setStatut(string $statut): void { $this->statut = $statut; }
    public function setDateCommande(string $date_commande): void { $this->date_commande = $date_commande; }

    // ========== MÉTHODES BDD ==========

    public function getHistorique(): array {
        $stmt = $this->pdo->query("
            SELECT * FROM commande ORDER BY date_commande DESC LIMIT 50
        ");
        return $stmt->fetchAll();
    }

    public function getCodesPromo(): array {
        $stmt = $this->pdo->query("
            SELECT * FROM code_promo ORDER BY id DESC
        ");
        return $stmt->fetchAll();
    }

    public function valider(array $data): array {
        $errors = [];
        if (empty(trim($data['nom_client'] ?? ''))) {
            $errors[] = "Le nom est obligatoire.";
        } elseif (strlen(trim($data['nom_client'])) < 2) {
            $errors[] = "Le nom doit contenir au moins 2 caractères.";
        }
        $tel = preg_replace('/\s+/', '', $data['telephone'] ?? '');
        if (empty($tel)) {
            $errors[] = "Le téléphone est obligatoire.";
        } elseif (!preg_match('/^\d{8}$/', $tel)) {
            $errors[] = "Le téléphone doit contenir 8 chiffres.";
        }
        if (empty(trim($data['adresse'] ?? ''))) {
            $errors[] = "L'adresse est obligatoire.";
        } elseif (strlen(trim($data['adresse'])) < 5) {
            $errors[] = "L'adresse est trop courte.";
        }
        if (empty($data['methode_paiement'] ?? '')) {
            $errors[] = "Veuillez choisir une méthode de paiement.";
        }
        return $errors;
    }

    public function create(array $data): int {
        $stmt = $this->pdo->prepare("
            INSERT INTO commande (nom_client, telephone, adresse, methode_paiement, total, statut)
            VALUES (:nom_client, :telephone, :adresse, :methode_paiement, :total, 'en_attente')
        ");
        $stmt->execute($data);
        return (int)$this->pdo->lastInsertId();
    }

    public function addProduit(int $commandeId, int $produitId, int $quantite, float $prix): void {
        $stmt = $this->pdo->prepare("
            INSERT INTO commande_produit (commande_id, produit_id, quantite, prix_unitaire)
            VALUES (:commande_id, :produit_id, :quantite, :prix_unitaire)
        ");
        $stmt->execute([
            ':commande_id' => $commandeId,
            ':produit_id' => $produitId,
            ':quantite' => $quantite,
            ':prix_unitaire' => $prix
        ]);
    }

    public function updateCommande(int $id, array $data): void {
        $stmt = $this->pdo->prepare("
            UPDATE commande SET 
                nom_client = :nom_client,
                telephone = :telephone,
                adresse = :adresse,
                methode_paiement = :methode_paiement,
                total = :total,
                statut = :statut
            WHERE id = :id
        ");
        $data[':id'] = $id;
        $stmt->execute($data);
    }

    public function deleteCommande(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM commande WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}