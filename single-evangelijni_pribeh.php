<?php
get_header(); // Načte hlavičku šablony

// Získáme aktuální post (příběh) a jeho slug (ID pro Google Sheet)
$post_slug = get_post_field( 'post_name', get_the_ID() );

// Získáme všechna data pro daný příběh pomocí vaší funkce
$story_data = knihaslova_get_story_data($post_slug);

// Načtení stránky pro jazykový rozbor
$analysis_slug = $post_slug . '-jazyk';
$analysis_page = get_page_by_path($analysis_slug, OBJECT, 'page');
?>

<div id="primary" class="content-area">
    <div class="content-container site-container">
        <main id="main" class="site-main">
            <div class="container story-detail">

                <?php if ($story_data): ?>
                    
                    <?php // <<< ZMĚNA ZDE: Nový kontejner pro nadpis a tlačítko >>> ?>
                    <div class="page-header-container">
                        <h1 class="page-title"><?php echo esc_html($story_data['info']['Nazev_pribehu'] ?? get_the_title()); ?></h1>
                        <div class="back-to-archive-link">
                            <a href="<?php echo get_post_type_archive_link('evangelijni_pribeh'); ?>" class="button-link-archive">&larr; Zpět na všechny příběhy</a>
                        </div>
                    </div>
                    <?php // <<< KONEC ZMĚNY >>> ?>

                    <div class="view-switcher">
                        <button class="nav-tab active" data-target="evangelists-view">Srovnání evangelistů</button>
                        <button class="nav-tab" data-target="translations-view">Srovnání překladů</button>
                        <?php if ($analysis_page): ?>
                            <button class="nav-tab" data-target="analysis-view">Jazykový rozbor</button>
                        <?php endif; ?>
                    </div>

                    <div id="evangelists-view" class="tab-content active">
                        <?php
                        set_query_var('story_data', $story_data);
                        get_template_part('template-parts/content-evangelists');
                        ?>
                    </div>

                    <div id="translations-view" class="tab-content">
                        <?php
                        set_query_var('story_data', $story_data);
                        get_template_part('template-parts/content-translations');
                        ?>
                    </div>

                    <?php if ($analysis_page): ?>
                    <div id="analysis-view" class="tab-content">
                        <div class="analysis-content" style="padding-top: 20px;">
                            <?php echo apply_filters('the_content', $analysis_page->post_content); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                <?php else: ?>
                    <header class="page-header">
                        <h1 class="page-title"><?php the_title(); ?></h1>
                    </header>
                    <p>K tomuto příběhu se nepodařilo načíst data z databáze. Zkontrolujte prosím ID příběhu v Google Sheets.</p>
                <?php endif; ?>

            </div>
        </main>

        <?php get_sidebar(); // Načte boční menu ?>

    </div></div><?php
get_footer(); // Načte patičku šablony
?>