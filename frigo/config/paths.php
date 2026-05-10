<?php
/**
 * URLs du module frigo (fonctionne dans un sous-dossier du serveur, ex. /Projet/.../frigo/).
 */
if (!defined('FRIGO_BASE')) {
    $script = $_SERVER['SCRIPT_NAME'] ?? '/frigo/index.php';
    $script = str_replace('\\', '/', (string) $script);
    $dir = rtrim(dirname($script), '/');
    define('FRIGO_BASE', $dir !== '' ? $dir : '/frigo');
    define('FRIGO_INDEX', FRIGO_BASE . '/index.php');
}
