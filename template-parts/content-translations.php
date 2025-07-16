<?php
// Získání dat předaných z hlavního souboru
$story_data = get_query_var('story_data');
if (!$story_data) return;

// Pro jednoduchost vybereme prvního evangelistu, který má text
$evangelist_to_show = 'Matous'; // Můžete změnit na Marek, Lukas, Jan
$evangelist_citation = $story_data['info'][$evangelist_to_show . '_Citace'] ?? '';
?>
<h2>Srovnání překladů pro evangelistu: <?php echo $evangelist_to_show; ?> (<?php echo $evangelist_citation; ?>)</h2>

<div class="comparison-grid">
    <div class="grid-column">
        <h3>Katolický překlad</h3>
        <div class="text-content">
            <?php echo nl2br(esc_html($story_data['translations']['katolicky'][$evangelist_to_show . '_Text'] ?? 'Text není dostupný.')); ?>
        </div>
    </div>
    <div class="grid-column">
        <h3>Ekumenický překlad</h3>
        <div class="text-content">
            <?php echo nl2br(esc_html($story_data['translations']['ekumenicky'][$evangelist_to_show . '_Text'] ?? 'Text není dostupný.')); ?>
        </div>
    </div>
    <div class="grid-column">
        <h3>Jeruzalémský překlad</h3>
        <div class="text-content">
            <?php echo nl2br(esc_html($story_data['translations']['jeruzalemsky'][$evangelist_to_show . '_Text'] ?? 'Text není dostupný.')); ?>
        </div>
    </div>
</div>