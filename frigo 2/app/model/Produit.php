<?php
class Produit {
    private ?int $id = null;
    private string $nom;
    private ?string $description = null;
    private float $prix;
    private int $quantite;
    private ?string $date_expiration = null;
    private ?int $categorie_id = null;
    private ?string $image = null;

    public function __construct(
        ?int $id = null,
        string $nom = '',
        ?string $description = null,
        float $prix = 0,
        int $quantite = 0,
        ?string $date_expiration = null,
        ?int $categorie_id = null,
        ?string $image = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->prix = $prix;
        $this->quantite = $quantite;
        $this->date_expiration = $date_expiration;
        $this->categorie_id = $categorie_id;
        $this->image = $image;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getDescription(): ?string { return $this->description; }
    public function getPrix(): float { return $this->prix; }
    public function getQuantite(): int { return $this->quantite; }
    public function getDateExpiration(): ?string { return $this->date_expiration; }
    public function getCategorieId(): ?int { return $this->categorie_id; }
    public function getImage(): ?string { return $this->image; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setNom(string $nom): void { $this->nom = $nom; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setPrix(float $prix): void { $this->prix = $prix; }
    public function setQuantite(int $quantite): void { $this->quantite = $quantite; }
    public function setDateExpiration(?string $date_expiration): void { $this->date_expiration = $date_expiration; }
    public function setCategorieId(?int $categorie_id): void { $this->categorie_id = $categorie_id; }
    public function setImage(?string $image): void { $this->image = $image; }
}