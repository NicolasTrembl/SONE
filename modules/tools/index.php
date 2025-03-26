<?php
$tools = [
    ["name" => "Créateur de rapport", "link" => "report-filler", "icon" => "notepad-text"],
    ["name" => "Notes", "link" => "notes", "icon" => "notebook-pen"]
];
?>
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4">Outils</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach ($tools as $tool): ?>
        <a href="?page=<?= $tool['link'] ?>" class="tool-card p-4 border rounded shadow block">
            <div class="icon mb-2">
                <i data-lucide="<?= $tool['icon'] ?>"></i>
            </div>
            <h2 class="text-xl font-semibold"><?= $tool['name'] ?></h2>
        </a>
    <?php endforeach; ?>
    </div>
</div>
<script>
    $(document).ready(function() {
        lucide.createIcons();
    });
</script>
