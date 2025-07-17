<?php
get_header();

$post_slug = get_post_field( 'post_name', get_the_ID() );
$story_data = knihaslova_get_story_data($post_slug);
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
                    <button class="nav-tab active" data-target="evangelists-view">Srovnání evangelistů</button>
                    <button class="nav-tab" data-target="translations-view">Srovnání překladů</button>
                    <button class="nav-tab" data-target="analysis-view">TEXTOVÁ EXEGEZE</button>
                    <button class="nav-tab" data-target="spiritual-view">Duchovní výklad</button>
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

            </div>
        </main>
        <?php get_sidebar(); ?>
    </div>
</div>

<?php
get_footer();
?>