</main>
    <footer class="bg-tertiary text-OnTertiary text-center p-4">
        <p>&copy; <?= date("Y") ?> - SONE</p>
    </footer>

    
    <!-- Mobile Navigation -->
    <div class="md:hidden shadow-lg bg-primary text-OnPrimary z-10">
        <!-- Overlay -->
        <div id="overlay" class="hidden fixed inset-0 bg- bg-opacity-50"></div>
        <!-- Sidebar -->
        <div id="mobileNav" class="fixed top-0 right-0 h-full w-64 bg-primary text-OnPrimary transform translate-x-full transition-transform duration-300 shadow-lg">
            <button id="closeNav" class="absolute top-4 right-4 ">
                <i data-lucide="x"></i>
            </button>
            <nav class="mt-12 space-y-4 px-6">
                <?php foreach ($navItems as $item) : ?>
                    <a href="?page=<?= $item['id'] ?>" class="flex items-center space-x-2 hover:text-gray-300 transition">
                        <i data-lucide="<?= $item['icon'] ?>"></i>
                        <span><?= $item['label'] ?></span>
                    </a>
                <?php endforeach; ?>
                <hr class="border-t border-gray-200 my-4">
                <button id="toggleTheme" class="flex items-center space-x-2 hover:text-gray-300 transition">
                    <i data-lucide="sun"></i>
                    <span>Changer de thème</span>
                </button>
                <script>
                    document.getElementById('toggleTheme').addEventListener('click', function() {
                        document.body.classList.toggle('dark');
                    });
                </script>
            </nav>
        </div>
    </div>

    <!-- Scripts for fetching and parsing the API data -->
    <script src="/assets/js/api_caller.js"></script>
    <script src="/assets/js/utils.js"></script>

    <!-- Script for the mobile navbar -->
    <script src="/assets/js/navbar.js"></script>
</body>
</html>
