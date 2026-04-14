<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>NutriFlow AI</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/frontoffice.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <a href="index.php?action=frontRecipes">
                    <i class="fas fa-leaf"></i>
                    <span>NutriFlow AI</span>
                </a>
            </div>
            <div class="nav-links">
                <a href="index.php?action=frontRecipes" class="<?php echo isset($activePage) && $activePage === 'recipes' ? 'active' : ''; ?>">Recettes</a>
                <a href="#">Nutrition</a>
                <a href="#">Durable</a>
                <a href="#">Contact</a>
                <a href="index.php?action=backRecipes" class="admin-btn">Admin</a>
            </div>
        </div>
    </nav>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .navbar {
            background: rgba(255,255,255,0.95);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            padding: 1rem 0;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo a {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        .nav-links a {
            text-decoration: none;
            color: #1a2a3a;
            font-weight: 500;
        }
        .admin-btn {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white !important;
            padding: 0.5rem 1.2rem;
            border-radius: 25px;
        }
        .hero {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-top: 70px;
            position: relative;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .recipes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            padding: 2rem 0;
        }
        .recipe-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .recipe-card:hover {
            transform: translateY(-10px);
        }
        .card-image {
            height: 200px;
            overflow: hidden;
        }
        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .card-content {
            padding: 1.5rem;
        }
        .btn-view {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        @media (max-width: 768px) {
            .recipes-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>