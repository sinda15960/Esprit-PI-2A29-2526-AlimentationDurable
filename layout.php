<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NutriFlow AI - Sustainable Food</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/nutriflow-ai/public/assets/css/front.css">
    <style>
        /* Custom buttons LARGER */
        .btn-custom {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 16px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-block;
            text-align: center;
            text-decoration: none;
            border: none;
            cursor: pointer;
            min-width: 220px;
        }
        
        .btn-custom:hover {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(249, 115, 22, 0.4);
        }
        
        .btn-custom i {
            margin-right: 10px;
            font-size: 20px;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100">
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <!-- Logo on left -->
                <div class="flex items-center space-x-3">
                    <i class="fas fa-leaf text-3xl text-green-600"></i>
                    <span class="text-2xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">NutriFlow AI</span>
                </div>
                
                <!-- Navigation links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/nutriflow-ai/public/" class="text-gray-700 hover:text-green-600 transition font-medium">Home</a>
                    <a href="/nutriflow-ai/public/associations" class="text-gray-700 hover:text-green-600 transition font-medium">Organizations</a>
                    <a href="/nutriflow-ai/public/don" class="text-gray-700 hover:text-green-600 transition font-medium">Donate</a>
                    <a href="/nutriflow-ai/public/admin/dashboard" class="text-gray-700 hover:text-green-600 transition font-medium">Admin</a>
                </div>
            </div>
        </div>
    </nav>
    
    <main>
        <!-- Home page with 3 LARGER buttons -->
        <div class="container mx-auto px-6 py-20 text-center">
            <i class="fas fa-leaf text-7xl text-green-600 mb-6"></i>
            <h1 class="text-5xl font-bold text-gray-800 mb-4">NutriFlow AI</h1>
            <p class="text-xl text-gray-600 mb-12">Together for sustainable food</p>
            
            <div class="flex flex-wrap justify-center gap-6">
                <a href="/nutriflow-ai/public/associations" class="btn-custom">
                    <i class="fas fa-hand-holding-heart"></i>View Organizations
                </a>
                <a href="/nutriflow-ai/public/don" class="btn-custom">
                    <i class="fas fa-donate"></i>Donate
                </a>
                <a href="/nutriflow-ai/public/admin/dashboard" class="btn-custom">
                    <i class="fas fa-crown"></i>Administration
                </a>
            </div>
        </div>
        
        <!-- Dynamic content (organizations list, donation form, etc.) -->
        <div class="container mx-auto px-6 py-12">
            <?php echo $content; ?>
        </div>
    </main>
    
    <footer class="bg-gray-900 text-white mt-20 py-8 text-center">
        <div class="container mx-auto px-6">
            <i class="fas fa-leaf text-3xl text-green-500 mb-4"></i>
            <p class="text-gray-400">© 2026 NutriFlow AI - Together for sustainable food</p>
        </div>
    </footer>
</body>
</html>