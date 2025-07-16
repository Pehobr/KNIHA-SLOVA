<?php
get_header(); // Načte hlavičku šablony

// Získáme aktuální post (příběh) a jeho slug (ID pro Google Sheet)
$post_slug = get_post_field( 'post_name', get_the_ID() );

// Získáme všechna data pro daný příběh pomocí vaší funkce
$story_data = knihaslova_get_story_data($post_slug);
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="container story-detail">

            <?php if ($story_data): ?>
                <header class="page-header">
                    <h1 class="page-title"><?php echo esc_html($story_data['info']['Nazev_pribehu'] ?? get_the_title()); ?></h1>
                </header>

                <div class="view-switcher">
                    <button class="nav-tab active" data-target="evangelists-view">Srovnání evangelistů</button>
                    <button class="nav-tab" data-target="translations-view">Srovnání překladů</button>
                </div>

                <div id="evangelists-view" class="tab-content active">
                    <?php
                    // Předání dat do šablony a její načtení
                    set_query_var('story_data', $story_data);
                    get_template_part('template-parts/content-evangelists');
                    ?>
                </div>

                <div id="translations-view" class="tab-content">
                    <?php
                    // Předání dat do šablony a její načtení
                    set_query_var('story_data', $story_data);
                    get_template_part('template-parts/content-translations');
                    ?>
                </div>

            <?php else: ?>
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>
                <p>K tomuto příběhu se nepodařilo načíst data z databáze. Zkontrolujte prosím ID příběhu v Google Sheets.</p>
            <?php endif; ?>

        </div>
    </main>
</div>

<?php
get_footer(); // Načte patičku šablony
?>