<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/Favori.php';

class FavoriController {
    private $pdo;

    public function __construct() {
        $this->pdo = getConnection();
    }

    // ─── REQUETES SQL ─────────────────────────────────────────────

    private function dbGetByUser(int $user_id): array {
        $sql = "SELECT f.*, p.nom AS programme_nom, p.niveau, p.description AS programme_description,
                       p.duree_semaines, o.titre AS objectif_titre, c.nom AS categorie_nom
                FROM favori f
                JOIN programme p ON f.programme_id = p.id
                LEFT JOIN objectif o ON p.objectif_id = o.id
                LEFT JOIN categorie c ON p.categorie_id = c.id_categorie
                WHERE f.user_id = ?
                ORDER BY f.date_ajout DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    private function dbIsFavori(int $user_id, int $programme_id): bool {
        $sql = "SELECT COUNT(*) FROM favori WHERE user_id = ? AND programme_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$user_id, $programme_id]);
        return $stmt->fetchColumn() > 0;
    }

    private function dbAjouter(int $user_id, int $programme_id): bool {
        if ($this->dbIsFavori($user_id, $programme_id)) return false;
        $sql = "INSERT INTO favori (user_id, programme_id) VALUES (?, ?)";
        return $this->pdo->prepare($sql)->execute([$user_id, $programme_id]);
    }

    private function dbSupprimer(int $user_id, int $programme_id): bool {
        $sql = "DELETE FROM favori WHERE user_id = ? AND programme_id = ?";
        return $this->pdo->prepare($sql)->execute([$user_id, $programme_id]);
    }

    // ─── ACTIONS ──────────────────────────────────────────────────

    // Liste des favoris de l'user
    public function index($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }
        $favoris = $this->dbGetByUser($_SESSION['user_id']);
        require __DIR__ . "/../views/front/favoris/index.php";
    }

    // Ajouter un programme aux favoris
    public function ajouter($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }
        $programme_id = intval($_GET['programme_id'] ?? 0);
        $redirect     = $_GET['redirect'] ?? 'index.php?module=programme&action=index&office=front';

        if ($programme_id > 0) {
            $this->dbAjouter($_SESSION['user_id'], $programme_id);
        }

        header("Location: " . $redirect);
        exit;
    }

    // Supprimer un programme des favoris
    public function supprimer($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }
        $programme_id = intval($_GET['programme_id'] ?? 0);
        $redirect     = $_GET['redirect'] ?? 'index.php?module=favori&action=index&office=front';

        if ($programme_id > 0) {
            $this->dbSupprimer($_SESSION['user_id'], $programme_id);
        }

        header("Location: " . $redirect);
        exit;
    }

    // Toggle : ajouter si pas favori, supprimer sinon
    public function toggle($office = 'front') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php"); exit;
        }
        $programme_id = intval($_GET['programme_id'] ?? 0);
        $redirect     = $_GET['redirect'] ?? 'index.php?module=programme&action=index&office=front';

        if ($programme_id > 0) {
            if ($this->dbIsFavori($_SESSION['user_id'], $programme_id)) {
                $this->dbSupprimer($_SESSION['user_id'], $programme_id);
            } else {
                $this->dbAjouter($_SESSION['user_id'], $programme_id);
            }
        }

        header("Location: " . $redirect);
        exit;
    }
}
?>