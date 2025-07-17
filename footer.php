<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package kadence
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hook for bottom of inner wrap.
 */
do_action( 'kadence_after_content' );
?>
	</div><?php
	do_action( 'kadence_before_footer' );
	/**
	 * Kadence footer hook.
	 *
	 * @hooked Kadence/footer_markup - 10
	*/

/**
 * KOD ZOBRAZÍ TOTO ZÁPATÍ © 2025 KNIHA SLOVA - Šablona pro WordPress od Kadence WP
 * do_action( 'kadence_footer' );
 */
	
	do_action( 'kadence_after_footer' );
	?>
</div><?php do_action( 'kadence_after_wrapper' ); ?>

<?php // ?>
<div class="mobile-bottom-bar">
    <a href="#" class="mobile-nav-icon"><i class="fa fa-user" aria-hidden="true"></i></a>
	<a href="/katalog-pribehu/" class="mobile-nav-icon"><i class="fa fa-list-alt" aria-hidden="true"></i></a>	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-nav-icon mobile-nav-icon-home"><i class="fa fa-home" aria-hidden="true"></i></a>    <a href="#" class="mobile-nav-icon"><i class="fa fa-archive" aria-hidden="true"></i></a>
    <a href="#" class="mobile-nav-icon"><i class="fa fa-cog" aria-hidden="true"></i></a>
</div>
<?php // ?>


<?php wp_footer(); ?>
</body>
</html>