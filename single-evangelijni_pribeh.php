<?php
get_header();

$post_slug = get_post_field( 'post_name', get_the_ID() );
$story_data = knihaslova_get_story_data($post_slug);

// Načtení stránky pro Srovnání (dříve SHRNUTÍ)
$comparison_page_slug = $post_slug . '-srovnani';
$comparison_page = get_page_by_path($comparison_page_slug, OBJECT, 'page');

// --- PŘIDÁNO: Načtení stránky pro Podcast ---
$podcast_page_slug = $post_slug . '-podcast';
$podcast_page = get_page_by_path($podcast_page_slug, OBJECT, 'page');
?>

<div id="primary" class="content-area">
    <div class="content-container site-container">
        <main id="main" class="site-main">
            <div class="container story-detail">

                <div class="back-to-archive-link">
                    <a href="<?php echo get_post_type_archive_link('evangelijni_pribeh'); ?>" class="button-link-archive">&larr; Zpět na všechny příběhy</a>
                </div>
                
                <div class="page-header-container">
                    <h1 class="page-title"><?php echo esc_html($story_data['info']['Nazev_pribehu'] ?? get_the_title()); ?></h1>
                </div>

                <div class="view-switcher">
                    <button class="nav-tab active" data-target="evangelists-view">Texty evangelistů</button>
                    <button class="nav-tab" data-target="translations-view">Srovnání překladů</button>
                    <button class="nav-tab" data-target="analysis-view">Textová exegeze</button>
                    <button class="nav-tab" data-target="spiritual-view">Duchovní výklad</button>

                    <?php if ($comparison_page): ?>
                        <button class="nav-tab" data-target="text-comparison-view">SHRNUTÍ</button>
                    <?php endif; ?>
                    
                    <?php // --- PŘIDÁNO: Tlačítko pro AI Podcast --- ?>
                    <?php if ($podcast_page): ?>
                        <button class="nav-tab" data-target="podcast-view">AI podcast</button>
                    <?php endif; ?>
                </div>

                <div id="evangelists-view" class="tab-content active">
                    <?php set_query_var('story_data', $story_data); get_template_part('template-parts/content-evangelists'); ?>
                </div>

                <div id="translations-view" class="tab-content">
                    <?php set_query_var('story_data', $story_data); get_template_part('template-parts/content-translations'); ?>
                </div>

                <div id="analysis-view" class="tab-content">
                    <?php
                    set_query_var('story_data', $story_data);
                    set_query_var('post_slug', $post_slug);
                    get_template_part('template-parts/content-exegesis');
                    ?>
                </div>
                
                <div id="spiritual-view" class="tab-content">
                    <?php
                    set_query_var('story_data', $story_data);
                    set_query_var('post_slug', $post_slug);
                    get_template_part('template-parts/content-spiritual');
                    ?>
                </div>

                <?php if ($comparison_page): ?>
                <div id="text-comparison-view" class="tab-content">
                    <div class="analysis-content" style="padding-top: 20px;">
                        <?php echo apply_filters('the_content', $comparison_page->post_content); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php // --- PŘIDÁNO: Kontejner pro AI Podcast --- ?>
                <?php if ($podcast_page): ?>
                <div id="podcast-view" class="tab-content">
                    <div class="podcast-player-container">
                        <div class="podcast-source-content" style="display: none;">
                            <?php echo apply_filters('the_content', $podcast_page->post_content); ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </main>
        <?php get_sidebar(); ?>
    </div>
</div>

<?php
get_footer();
?>