<?php
/**
 * Chemins web vers la racine du dépôt (dashboard.php, allergies.php, frigo/, etc.)
 * depuis projet2A. Évite les href en « ../ » qui sortent du vhost (ex. /nutriflow-ai/).
 */

function nf_normalize_web_path(string $path): string
{
    $path = str_replace('\\', '/', $path);
    $parts = [];
    foreach (explode('/', $path) as $p) {
        if ($p === '' || $p === '.') {
            continue;
        }
        if ($p === '..') {
            if ($parts) {
                array_pop($parts);
            }
        } else {
            $parts[] = $p;
        }
    }
    return '/' . implode('/', $parts);
}

/**
 * Segments d’URL du dossier contenant index.php (ex. nutriflow-ai ou nutriflow-ai/projet2A).
 */
function nf_projet_base_url(): string
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }
    $scriptName = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $dir = dirname($scriptName);
    if ($dir === '/' || $dir === '\\' || $dir === '.') {
        $cached = '';
    } else {
        $cached = rtrim($dir, '/');
    }
    return $cached;
}

/**
 * Racine du dépôt côté URL (là où se trouvent dashboard.php, allergies.php, frigo/).
 */
function nf_repo_base_url(): string
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $scriptName = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $scriptFile = str_replace('\\', '/', (string)($_SERVER['SCRIPT_FILENAME'] ?? ''));

    $indexDirFs = dirname($scriptFile);
    $indexDirUrl = dirname($scriptName);
    if ($indexDirUrl === '/' || $indexDirUrl === '\\' || $indexDirUrl === '.') {
        $indexDirUrl = '';
    } else {
        $indexDirUrl = rtrim($indexDirUrl, '/');
    }

    if (is_file($indexDirFs . '/dashboard.php')) {
        $cached = $indexDirUrl;
        return $cached;
    }

    $parentFs = dirname($indexDirFs);
    $parentUrl = $indexDirUrl === '' ? '' : dirname($indexDirUrl);
    if ($parentUrl === '/' || $parentUrl === '\\' || $parentUrl === '.') {
        $parentUrl = '';
    } else {
        $parentUrl = rtrim($parentUrl, '/');
    }

    if (is_file($parentFs . '/dashboard.php')) {
        $cached = $parentUrl;
        return $cached;
    }

    $cached = $parentUrl;
    return $cached;
}

/** URL absolue sur le site (chemin) vers un fichier à la racine du dépôt. */
function nf_repo_url(string $file): string
{
    $file = ltrim(str_replace('\\', '/', $file), '/');
    $base = nf_repo_base_url();
    if ($base === '') {
        return nf_normalize_web_path('/' . $file);
    }
    return nf_normalize_web_path($base . '/' . $file);
}

/** URL (chemin) vers un fichier sous le dossier de projet2A (ex. public/index.php). */
function nf_projet_url(string $path): string
{
    $path = ltrim(str_replace('\\', '/', $path), '/');
    $base = nf_projet_base_url();
    if ($base === '') {
        return nf_normalize_web_path('/' . $path);
    }
    return nf_normalize_web_path($base . '/' . $path);
}

/**
 * Chemin web vers la racine projet2A (dossier qui contient assets/, index.php).
 * Corrige les CSS 404 quand SCRIPT_NAME pointe sous public/.
 */
function nf_projet_root_web_path(): string
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $scriptFile = str_replace('\\', '/', (string)($_SERVER['SCRIPT_FILENAME'] ?? ''));
    if ($scriptFile !== '') {
        $dir = dirname($scriptFile);
        if (basename($dir) === 'public') {
            $dir = dirname($dir);
        }
        $docRoot = rtrim(str_replace('\\', '/', (string)($_SERVER['DOCUMENT_ROOT'] ?? '')), '/');
        if ($docRoot !== '' && strpos($dir, $docRoot) === 0) {
            $rel = substr($dir, strlen($docRoot));
            $cached = nf_normalize_web_path('/' . ltrim(str_replace('\\', '/', $rel), '/'));
            return $cached;
        }
    }

    $sn = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $urlDir = dirname($sn);
    if ($urlDir !== '/' && preg_match('#/public$#', $urlDir)) {
        $urlDir = dirname($urlDir);
    }
    if ($urlDir === '/' || $urlDir === '\\' || $urlDir === '.') {
        $cached = '';
    } else {
        $cached = nf_normalize_web_path(rtrim($urlDir, '/'));
    }
    return $cached;
}

/** URL absolue (chemin) vers un fichier sous projet2A/assets/ ou autre fichier racine projet. */
function nf_projet_asset(string $relativePath): string
{
    $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
    $base = nf_projet_root_web_path();
    if ($base === '') {
        return nf_normalize_web_path('/' . $relativePath);
    }
    return nf_normalize_web_path($base . '/' . $relativePath);
}

/**
 * Panneau admin NutriFlow (sidebar verte, cartes Management Dashboard).
 */
function nf_admin_dashboard_url(string $fragment = ''): string
{
    $url = nf_projet_url('index.php') . '?action=admin_dashboard';
    $fragment = ltrim($fragment, '#');
    if ($fragment !== '') {
        $url .= '#' . $fragment;
    }
    return $url;
}
