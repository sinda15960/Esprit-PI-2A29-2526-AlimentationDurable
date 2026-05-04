<?php
class CodePromo {
    private int $id;
    private string $code;
    private float $reduction;
    private string $type_reduction;
    private int $actif;
    private ?string $date_expiration;

    public function __construct(
        int $id = 0,
        string $code = '',
        float $reduction = 0,
        string $type_reduction = 'pourcentage',
        int $actif = 1,
        ?string $date_expiration = null
    ) {
        $this->id              = $id;
        $this->code            = $code;
        $this->reduction       = $reduction;
        $this->type_reduction  = $type_reduction;
        $this->actif           = $actif;
        $this->date_expiration = $date_expiration;
    }

    public function __destruct() {}

    public function getId(): int { return $this->id; }
    public function getCode(): string { return $this->code; }
    public function getReduction(): float { return $this->reduction; }
    public function getTypeReduction(): string { return $this->type_reduction; }
    public function getActif(): int { return $this->actif; }
    public function getDateExpiration(): ?string { return $this->date_expiration; }

    public function setId(int $id): void { $this->id = $id; }
    public function setCode(string $code): void { $this->code = $code; }
    public function setReduction(float $r): void { $this->reduction = $r; }
    public function setTypeReduction(string $t): void { $this->type_reduction = $t; }
    public function setActif(int $a): void { $this->actif = $a; }
    public function setDateExpiration(?string $d): void { $this->date_expiration = $d; }
}