<?php
class Exercice {
    private $id;
    private $nom;
    private $description;
    private $ordre;
    private $duree_minutes;
    private $video_url;
    private $statut;
    private $programme_id;
    
    public function __construct($nom = '', $description = '', $ordre = 0, $duree_minutes = 0, $video_url = '', $statut = 'en_attente', $programme_id = null) {
        $this->nom = $nom;
        $this->description = $description;
        $this->ordre = $ordre;
        $this->duree_minutes = $duree_minutes;
        $this->video_url = $video_url;
        $this->statut = $statut;
        $this->programme_id = $programme_id;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    public function getOrdre() { return $this->ordre; }
    public function getDureeMinutes() { return $this->duree_minutes; }
    public function getVideoUrl() { return $this->video_url; }
    public function getStatut() { return $this->statut; }
    public function getProgrammeId() { return $this->programme_id; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setDescription($description) { $this->description = $description; }
    public function setOrdre($ordre) { $this->ordre = $ordre; }
    public function setDureeMinutes($duree_minutes) { $this->duree_minutes = $duree_minutes; }
    public function setVideoUrl($video_url) { $this->video_url = $video_url; }
    public function setStatut($statut) { $this->statut = $statut; }
    public function setProgrammeId($programme_id) { $this->programme_id = $programme_id; }
}
?>