<?php
class Favori {
    private int $id;
    private int $produit_id;
    private ?string $date_ajout;

    public function __construct(
        int $id = 0,
        int $produit_id = 0,
        ?string $date_ajout = null
    ) {
        $this->id         = $id;
        $this->produit_id = $produit_id;
        $this->date_ajout = $date_ajout;
    }

    public function __destruct() {}

    public function getId(): int { return $this->id; }
    public function getProduitId(): int { return $this->produit_id; }
    public function getDateAjout(): ?string { return $this->date_ajout; }

    public function setId(int $id): void { $this->id = $id; }
    public function setProduitId(int $id): void { $this->produit_id = $id; }
    public function setDateAjout(?string $date): void { $this->date_ajout = $date; }
}