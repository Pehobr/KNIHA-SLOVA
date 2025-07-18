<?php
/**
 * Template Name: Archiv příběhů
 * Template Post Type: page
 */

get_header(); // Načte hlavičku
?>

<div id="primary" class="content-area">
    <div class="content-container site-container">
        <main id="main" class="site-main" role="main">

            <?php
            // Zobrazí standardní obsah stránky (nadpis, text), pokud nějaký zadáte v editoru
            while ( have_posts() ) :
                the_post();
                // the_title( '<header class="entry-header evangelijni_pribeh-archive-title"><h1 class="page-title archive-title">', '</h1></header>' );
                the_content();
            endwhile;
            ?>

            <div id="archive-container" class="content-wrap grid-cols evangelijni_pribeh-archive grid-sm-col-2 grid-lg-col-4 item-image-style-above">
                <?php
                // Vlastní dotaz na všechny příběhy
                $args = array(
                    'post_type' => 'evangelijni_pribeh',
                    'posts_per_page' => -1, // Zobrazit všechny
                    'orderby' => 'date', // Řazení podle data
                    'order' => 'DESC',   // Od nejnovějšího po nejstarší
                );
                $pribehy_query = new WP_Query( $args );

                if ( $pribehy_query->have_posts() ) :
                    while ( $pribehy_query->have_posts() ) :
                        $pribehy_query->the_post();
                        // Zde replikujeme strukturu boxu tak, jak ji generuje šablona Kadence
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry content-bg loop-entry' ); ?>>
                            <div class="entry-content-wrap">
                                <header class="entry-header">
                                    <h3 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h3>
                                </header>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata(); // Obnoví původní dotaz
                else :
                    ?>
                    <p>Zatím nebyly vloženy žádné příběhy.</p>
                    <?php
                endif;
                ?>
            </div>
        </main>
        <?php get_sidebar(); // Načte postranní panel ?>
    </div><!-- .content-container -->
</div><!-- #primary --><?php
get_footer(); // Načte patičku
?>