<?php
require_once __DIR__ . '/../Model/Allergie.php';

class AllergieController {
    
    public function getAllAllergies() {
        $allergies = Allergie::findAll();
        $result = [];
        foreach ($allergies as $allergie) {
            $result[] = $allergie->toArray();
        }
        return $result;
    }
    
    public function searchAllergies($term) {
        $allergies = Allergie::search($term);
        $result = [];
        foreach ($allergies as $allergie) {
            $result[] = $allergie->toArray();
        }
        return $result;
    }
}
?>