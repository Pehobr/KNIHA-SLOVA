<?php
get_header(); // Načte hlavičku šablony
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <div class="container">
            <header class="page-header">
                <h1 class="page-title">Příběhy z evangelií</h1>
            </header>

            <div class="story-list">
                <?php if ( have_posts() ) : ?>
                    <ul>
                        <?php while ( have_posts() ) : the_post(); ?>
                            <li>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else : ?>
                    <p>Zatím nebyly vloženy žádné texty.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php
get_footer(); // Načte patičku šablony
?>