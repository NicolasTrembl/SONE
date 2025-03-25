<?php
$navItems = [
    ["id" => "courses", "icon" => "book-marked", "label" => "Cours"],
    ["id" => "calendar", "icon" => "calendar-days", "label" => "Agenda"],
    ["id" => "grades", "icon" => "hash", "label" => "Notes"],
    ["id" => "tools", "icon" => "layout-grid", "label" => "Outils"],
    ["id" => "account", "icon" => "circle-user-round", "label" => "Compte"],
    ["id" => "settings", "icon" => "settings", "label" => "Réglages"],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SONE</title>
    <link rel="icon" href="/assets/images/logo.jpg">
    <link href="/assets/css/style.css" rel="stylesheet">
    <script src="/assets/js/jquery.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="bg-background text-OnBackground flex flex-col min-h-screen max-h-screen">
<header>
    <!-- Desktop Navigation -->
    <nav class="hidden md:flex bg-primary text-OnPrimary justify-between items-center h-16 px-6 fixed left-0 right-0 top-0 shadow-lg z-10">
        <div class="flex flex-row items-center space-x-4">
            <a href="/" class="flex items-center space-x-2">
                <img src="/assets/images/logo.jpg" alt="ECE" class="w-12 h-12 rounded-md shadow-md">
            </a>
            <p class="name"></p>
        </div>
        <div class="flex gap-4 transition-all duration-300">
            <?php foreach ($navItems as $item) : ?>
                <a href="?page=<?= $item['id'] ?>" id="<?= $item['id'] ?>" 
                   class="group flex items-center gap-2 transition-all duration-300 relative px-2 hover:px-6">
                    <i data-lucide="<?= $item['icon'] ?>" class="w-8 h-8 transition-all duration-300"></i>
                    <span data-original="<?= $item['label'] ?>" class="hidden md:inline-block whitespace-nowrap overflow-hidden w-0 opacity-0 text-sm transition-all duration-500">
                        <?= $item['label'] ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
        
    </nav>
    
    <div id="overflow" class="absolute hidden">
            <div class="fixed top-16 flex flex-col right-0 w-48 bg-surface shadow-lg overflow-hidden z-10">
                    <button id="toggleThemeDsktp" class="flex px-4 py-2 hover:bg-primary hover:text-OnPrimary transition-all duration-300">
                        <span>Changer de thème</span>
                    </button>
                    <script>
                        document.getElementById('toggleThemeDsktp').addEventListener('click', function() {
                            document.body.classList.toggle('dark');
                        });
                    </script>
            </div>
        </div>div>

    <!-- Mobile Navigation -->
    <div class="md:hidden shadow-lg bg-primary text-OnPrimary">
        <div class="flex justify-between items-center h-16 px-6">
            <a href="/" class="flex items-center space-x-2">
                <img src="/assets/images/logo.jpg" alt="ECE" class="w-10 h-10 rounded-md shadow-md">
            </a>
            <p class="name"></p>
            <button id="openNav">
                <i data-lucide="menu"></i>
            </button>
        </div>
    </div>


</header>

<main >
