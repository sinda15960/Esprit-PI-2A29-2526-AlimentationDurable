<?php
class FrigoUtilisateur {
    private ?int $id = null;
    private ?int $produit_id = null;
    private ?string $nom_custom = null;
    private int $quantite;
    private ?string $date_expiration = null;
    private int $seuil_alerte = 2;

    public function __construct(
        ?int $id = null,
        ?int $produit_id = null,
        ?string $nom_custom = null,
        int $quantite = 0,
        ?string $date_expiration = null,
        int $seuil_alerte = 2
    ) {
        $this->id = $id;
        $this->produit_id = $produit_id;
        $this->nom_custom = $nom_custom;
        $this->quantite = $quantite;
        $this->date_expiration = $date_expiration;
        $this->seuil_alerte = $seuil_alerte;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getProduitId(): ?int { return $this->produit_id; }
    public function getNomCustom(): ?string { return $this->nom_custom; }
    public function getQuantite(): int { return $this->quantite; }
    public function getDateExpiration(): ?string { return $this->date_expiration; }
    public function getSeuilAlerte(): int { return $this->seuil_alerte; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setProduitId(?int $produit_id): void { $this->produit_id = $produit_id; }
    public function setNomCustom(?string $nom_custom): void { $this->nom_custom = $nom_custom; }
    public function setQuantite(int $quantite): void { $this->quantite = $quantite; }
    public function setDateExpiration(?string $date_expiration): void { $this->date_expiration = $date_expiration; }
    public function setSeuilAlerte(int $seuil_alerte): void { $this->seuil_alerte = $seuil_alerte; }
}