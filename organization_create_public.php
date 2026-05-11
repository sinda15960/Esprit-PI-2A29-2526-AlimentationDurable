<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New organization — NutriFlow AI</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">
    <header class="max-w-4xl mx-auto mb-8 flex flex-wrap items-center justify-between gap-3">
        <a href="donations_hub.php" class="font-bold text-green-800 flex items-center gap-2">
            <span>🌿</span> NutriFlow AI
        </a>
        <nav class="flex gap-4 text-sm font-medium">
            <a href="donations_admin.php" class="text-gray-600 hover:text-green-700">Dashboard</a>
            <a href="organizations_public.php" class="text-gray-600 hover:text-green-700">Organizations</a>
            <a href="donate_public.php" class="text-gray-600 hover:text-green-700">Donate</a>
        </nav>
    </header>
    <?php if (!empty($_SESSION['assoc_error'])): ?>
        <p class="max-w-4xl mx-auto mb-4 text-red-600"><?php echo htmlspecialchars($_SESSION['assoc_error']); unset($_SESSION['assoc_error']); ?></p>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <p class="max-w-4xl mx-auto mb-4 text-green-700"><?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
    <?php endif; ?>
    <?php include __DIR__ . '/create.php'; ?>
</body>
</html>
