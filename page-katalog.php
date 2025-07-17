<?php
/*
Template Name: Katalog příběhů
*/

get_header();

// --- NAČTENÍ VŠECH PŘÍBĚHŮ ---
$all_stories_query = new WP_Query(array(
    'post_type' => 'evangelijni_pribeh',
    'posts_per_page' => -1, // Zobrazit všechny
    'orderby' => 'title',
    'order' => 'ASC'
));

$stories_by_name = [];
$citations_by_evangelist = [
    'Matous' => [],
    'Marek'  => [],
    'Lukas'  => [],
    'Jan'    => [],
];
$display_names = [
    'Matous' => 'Matouš',
    'Marek'  => 'Marek',
    'Lukas'  => 'Lukáš',
    'Jan'    => 'Jan'
];

if ($all_stories_query->have_posts()) {
    while ($all_stories_query->have_posts()) {
        $all_stories_query->the_post();
        // Získáme slug (ID) příběhu přímo z objektu post
        $post_slug = get_post_field('post_name', get_the_ID());
        
        // Načteme data pomocí vaší funkce, která využívá slug
        $story_data = knihaslova_get_story_data($post_slug);

        // 1. Uložení pro katalog podle názvu
        $stories_by_name[] = [
            'title' => get_the_title(),
            'permalink' => get_the_permalink()
        ];

        // 2. Zpracování pro katalog podle citací
        if ($story_data && isset($story_data['info'])) {
            foreach (['Matous', 'Marek', 'Lukas', 'Jan'] as $evangelist) {
                $citation_key = $evangelist . '_Citace';
                if (!empty($story_data['info'][$citation_key])) {
                    $citation = $story_data['info'][$citation_key];
                    $citations_by_evangelist[$evangelist][] = [
                        'citation' => $citation,
                        'permalink' => get_the_permalink(),
                        // Použijeme globální funkci z functions.php
                        'sort_key' => knihaslova_get_citation_sort_key($citation)
                    ];
                }
            }
        }
    }
    wp_reset_postdata();
}

// Seřadíme citace pro každého evangelistu podle sort_key
foreach ($citations_by_evangelist as $evangelist => &$citations) {
    if (!empty($citations)) {
        // Vytvoříme pole sort_key pro řazení
        $sort_keys = array_column($citations, 'sort_key');
        // Seřadíme pole citací podle klíčů
        array_multisort($sort_keys, SORT_ASC, $citations);
    }
}
unset($citations); // Zrušíme referenci

?>

<div id="primary" class="content-area page-katalog">
    <div class="content-container site-container">
        <main id="main" class="site-main">
            <header class="page-header">
                <h1 class="page-title"><?php the_title(); ?></h1>
            </header>

            <div class="view-switcher">
                <button class="nav-tab active" data-target="name-view">Podle názvů</button>
                <button class="nav-tab" data-target="citation-view">Podle citací</button>
            </div>

            <div id="name-view" class="tab-content active">
                <div class="katalog-grid">
                    <?php if (!empty($stories_by_name)): ?>
                        <?php foreach ($stories_by_name as $story): ?>
                            <a href="<?php echo esc_url($story['permalink']); ?>" class="katalog-button">
                                <?php echo esc_html($story['title']); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nebyly nalezeny žádné příběhy.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div id="citation-view" class="tab-content">
                <div class="evangelist-switcher">
                    <?php $first = true; ?>
                    <?php foreach ($citations_by_evangelist as $key => $citations): ?>
                        <?php if(!empty($citations)): ?>
                        <button class="nav-tab <?php if($first) { echo 'active'; $first = false; } ?>" data-evangelist="<?php echo strtolower($key); ?>">
                            <?php echo $display_names[$key]; ?>
                        </button>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="citations-content">
                     <?php $first = true; ?>
                    <?php foreach ($citations_by_evangelist as $key => $citations): ?>
                         <?php if(!empty($citations)): ?>
                        <div id="evangelist-<?php echo strtolower($key); ?>" class="evangelist-citation-content <?php if($first) { echo 'active'; $first = false; } ?>">
                            <div class="katalog-grid">
                                <?php foreach ($citations as $citation_data): ?>
                                    <a href="<?php echo esc_url($citation_data['permalink']); ?>" class="katalog-button">
                                        <?php echo esc_html($citation_data['citation']); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                         <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

        </main>
    </div>
</div>

<?php
get_footer();
?>