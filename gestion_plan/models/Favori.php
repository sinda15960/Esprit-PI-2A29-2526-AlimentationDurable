<?php

class Favori {
    private int $id;
    private int $user_id;
    private int $programme_id;
    private string $date_ajout;

    public function __construct(
        int $id = 0,
        int $user_id = 0,
        int $programme_id = 0,
        string $date_ajout = ''
    ) {
        $this->id           = $id;
        $this->user_id      = $user_id;
        $this->programme_id = $programme_id;
        $this->date_ajout   = $date_ajout;
    }

    public function __destruct() {}

    // ── Getters ──────────────────────────────────────────────────
    public function getId(): int            { return $this->id; }
    public function getUserId(): int        { return $this->user_id; }
    public function getProgrammeId(): int   { return $this->programme_id; }
    public function getDateAjout(): string  { return $this->date_ajout; }

    // ── Setters ──────────────────────────────────────────────────
    public function setId(int $id): void            { $this->id = $id; }
    public function setUserId(int $id): void         { $this->user_id = $id; }
    public function setProgrammeId(int $id): void    { $this->programme_id = $id; }
    public function setDateAjout(string $d): void    { $this->date_ajout = $d; }
}
?>