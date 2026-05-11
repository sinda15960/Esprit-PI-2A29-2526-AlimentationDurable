<?php
session_start();
require_once __DIR__ . '/Association.php';

try {
    $associationModel = new Association();
    $associations = $associationModel->getActiveAssociations();
} catch (Throwable $e) {
    $associations = [];
    $donateError = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate — NutriFlow AI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">
    <header class="max-w-4xl mx-auto mb-8 flex flex-wrap items-center justify-between gap-3">
        <a href="donations_hub.php" class="font-bold text-green-800 flex items-center gap-2">
            <span>🌿</span> NutriFlow AI
        </a>
        <nav class="flex gap-4 text-sm font-medium">
            <a href="donations_hub.php" class="text-gray-600 hover:text-green-700">Home</a>
            <a href="organizations_public.php" class="text-gray-600 hover:text-green-700">Organizations</a>
            <a href="donate_public.php" class="text-green-700">Donate</a>
            <a href="login.php" class="text-gray-600 hover:text-green-700">Admin</a>
        </nav>
    </header>
    <?php if (!empty($donateError)): ?>
        <p class="max-w-4xl mx-auto mb-4 text-red-600"><?php echo htmlspecialchars($donateError); ?></p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['donation_error'])): ?>
        <p class="max-w-4xl mx-auto mb-4 text-red-600"><?php echo htmlspecialchars($_SESSION['donation_error']); unset($_SESSION['donation_error']); ?></p>
    <?php endif; ?>
    <?php include __DIR__ . '/form.php'; ?>
</body>
</html>
