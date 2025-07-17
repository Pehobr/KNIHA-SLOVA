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
                    'orderby' => 'title',
                    'order' => 'ASC',
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
                                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
                                </header>
                                <footer class="entry-footer">
                                    <div class="entry-actions">
                                        <p class="more-link-wrap">
                                            <a href="<?php the_permalink(); ?>" class="post-more-link">
                                                Přečtěte si více<span class="screen-reader-text"> <?php the_title(); ?></span><span class="kadence-svg-iconset svg-baseline"><svg aria-hidden="true" class="kadence-svg-icon kadence-arrow-right-alt-svg" fill="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" width="27" height="28" viewBox="0 0 27 28"><title>Pokračovat</title><path d="M27 13.953c0 0.141-0.063 0.281-0.156 0.375l-6 5.531c-0.156 0.141-0.359 0.172-0.547 0.094-0.172-0.078-0.297-0.25-0.297-0.453v-3.5h-19.5c-0.281 0-0.5-0.219-0.5-0.5v-3c0-0.281 0.219-0.5 0.5-0.5h19.5v-3.5c0-0.203 0.109-0.375 0.297-0.453s0.391-0.047 0.547 0.078l6 5.469c0.094 0.094 0.156 0.219 0.156 0.359v0z"></path></svg></span>
                                            </a>
                                        </p>
                                    </div>
                                </footer>
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
    </div></div><?php
get_footer(); // Načte patičku
?>