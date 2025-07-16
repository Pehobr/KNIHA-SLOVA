<?php
// Získání dat předaných z hlavního souboru
$story_data = get_query_var('story_data');
if (!$story_data) return;

$info = $story_data['info'];
$texts = $story_data['translations']['katolicky']; // Prozatím použijeme katolický jako výchozí
$evangelists = ['Matous', 'Marek', 'Lukas', 'Jan'];
?>
<div class="comparison-grid">
    <?php foreach ($evangelists as $evangelist): ?>
        <div class="grid-column">
            <h3><?php echo $evangelist; ?></h3>
            <p class="citation"><em><?php echo esc_html($info[$evangelist . '_Citace'] ?? ''); ?></em></p>
            <div class="text-content">
                <?php echo nl2br(esc_html($texts[$evangelist . '_Text'] ?? 'Tento evangelista příběh nezmiňuje.')); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>