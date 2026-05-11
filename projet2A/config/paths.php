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
 * Utilise SCRIPT_NAME puis REQUEST_URI — évite les CSS en /assets/... (404 sous sous-dossier)
 * quand DOCUMENT_ROOT ne correspond pas au disque (8081, proxies, etc.).
 */
function nf_projet_root_web_path(): string
{
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $sn = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($sn !== '' && $sn[0] !== '/') {
        $sn = '/' . $sn;
    }
    if ($sn === '') {
        $sn = '/index.php';
    }

    $urlDir = dirname($sn);
    if ($urlDir !== '/' && $urlDir !== '\\' && $urlDir !== '.') {
        $urlDir = rtrim($urlDir, '/');
        if (preg_match('#/public$#', $urlDir)) {
            $urlDir = dirname($urlDir);
            $urlDir = ($urlDir === '/' || $urlDir === '\\' || $urlDir === '.') ? '' : rtrim($urlDir, '/');
        }
        if ($urlDir !== '' && $urlDir !== '/') {
            $cached = nf_normalize_web_path($urlDir);
            return $cached;
        }
    }

    $uri = str_replace('\\', '/', (string)($_SERVER['REQUEST_URI'] ?? ''));
    $uri = strtok($uri, '?') ?: '';
    if ($uri !== '' && $uri[0] !== '/') {
        $uri = '/' . $uri;
    }
    if ($uri !== '' && $uri !== '/') {
        if (preg_match('#/index\.php$#i', $uri)) {
            $uri = substr($uri, 0, -strlen('/index.php'));
        }
        $uri = rtrim($uri, '/');
        if ($uri !== '' && $uri !== '/') {
            $cached = nf_normalize_web_path($uri);
            return $cached;
        }
    }

    $cached = '';
    return $cached;
}

/**
 * URL vers un fichier sous projet2A/assets/…
 * Si la base d’URL est inconnue, renvoie un chemin relatif (résolu par le navigateur depuis index.php).
 */
function nf_projet_asset(string $relativePath): string
{
    $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
    $base = nf_projet_root_web_path();
    if ($base === '') {
        return $relativePath;
    }
    return nf_normalize_web_path($base . '/' . $relativePath);
}

/**
 * URL absolue du dossier projet2A (slash final) pour &lt;base href&gt; du back-office.
 * Sans cache statique : évite les CSS bloqués si la première résolution était vide.
 */
function nf_projet_admin_base_href(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    $sn = str_replace('\\', '/', (string)($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($sn !== '' && $sn[0] !== '/') {
        $sn = '/' . $sn;
    }
    if ($sn === '') {
        $sn = '/index.php';
    }

    $dir = dirname($sn);
    if ($dir === '/' || $dir === '\\' || $dir === '.') {
        $dir = '';
    } else {
        $dir = rtrim($dir, '/');
        if (preg_match('#/public$#', $dir)) {
            $pd = dirname($dir);
            $dir = ($pd === '/' || $pd === '\\' || $pd === '.') ? '' : rtrim($pd, '/');
        }
    }

    if ($dir === '') {
        $uri = strtok(str_replace('\\', '/', (string)($_SERVER['REQUEST_URI'] ?? '')), '?') ?: '';
        if ($uri !== '' && $uri[0] !== '/') {
            $uri = '/' . $uri;
        }
        if ($uri !== '' && preg_match('#/index\.php$#i', $uri)) {
            $dir = rtrim(substr($uri, 0, -strlen('/index.php')), '/');
        }
    }

    if ($dir !== '' && $dir[0] !== '/') {
        $dir = '/' . $dir;
    }

    $suffix = ($dir === '' || $dir === '/') ? '/' : ($dir . '/');
    return $scheme . '://' . $host . $suffix;
}

/**
 * URL absolue vers un fichier sous projet2A/ (assets/…) pour &lt;link&gt; / &lt;script&gt;.
 * Cache-bust avec filemtime pour forcer le rechargement après déploiement.
 */
function nf_projet_admin_asset_url(string $relativePath): string
{
    $relativePath = ltrim(str_replace('\\', '/', $relativePath), '/');
    $baseUrl = rtrim(nf_projet_admin_base_href(), '/');
    $projetRootFs = dirname(__DIR__);
    $fsFile = $projetRootFs . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    $ver = is_readable($fsFile) ? (string)filemtime($fsFile) : '1';
    return $baseUrl . '/' . $relativePath . '?v=' . rawurlencode($ver);
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
