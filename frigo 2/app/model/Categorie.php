<?php
class Categorie {
    private ?int $id = null;
    private string $nom;
    private ?string $description = null;
    private ?string $image = null;

    public function __construct(?int $id = null, string $nom = '', ?string $description = null, ?string $image = null) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->image = $image;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getDescription(): ?string { return $this->description; }
    public function getImage(): ?string { return $this->image; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setNom(string $nom): void { $this->nom = $nom; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setImage(?string $image): void { $this->image = $image; }
}