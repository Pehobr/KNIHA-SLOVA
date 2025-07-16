<?php
// Získání dat předaných z hlavního souboru
$story_data = get_query_var('story_data');
if (!$story_data) return;

$info = $story_data['info'];
$evangelists = ['Matous', 'Marek', 'Lukas', 'Jan'];

// Zkusíme najít první dostupný překlad pro zobrazení
$first_available_translation = null;
if (!empty($story_data['translations']['katolicky'])) {
    $first_available_translation = $story_data['translations']['katolicky'];
} elseif (!empty($story_data['translations']['ekumenicky'])) {
    $first_available_translation = $story_data['translations']['ekumenicky'];
} elseif (!empty($story_data['translations']['jeruzalemsky'])) {
    $first_available_translation = $story_data['translations']['jeruzalemsky'];
}

?>
<div class="comparison-grid">
    <?php foreach ($evangelists as $evangelist): ?>
        <div class="grid-column">
            <h3><?php echo $evangelist; ?></h3>
            <p class="citation"><em><?php echo esc_html($info[$evangelist . '_Citace'] ?? ''); ?></em></p>
            <div class="text-content">
                <?php
                // Zobrazíme text z prvního nalezeného překladu, pokud existuje
                if ($first_available_translation && !empty($first_available_translation[$evangelist . '_Text'])) {
                    echo nl2br(esc_html($first_available_translation[$evangelist . '_Text']));
                } else {
                    echo 'Tento evangelista příběh nezmiňuje.';
                }
                ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>