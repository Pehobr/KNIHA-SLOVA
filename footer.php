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
    <?php // Odkaz na profil (pokud bude existovat) ?>
    <a href="<?php echo esc_url( get_edit_user_link() ); ?>" class="mobile-nav-icon"><i class="fa fa-user" aria-hidden="true"></i></a>
    <?php // Odkaz na katalog - get_permalink() je robustnější než pevná URL ?>
	<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'katalog' ) ) ); ?>" class="mobile-nav-icon"><i class="fa fa-book" aria-hidden="true"></i></a>
    <?php // Odkaz na domovskou stránku ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="mobile-nav-icon mobile-nav-icon-home"><i class="fa fa-home" aria-hidden="true"></i></a>
    <?php // Odkaz na archiv příběhů ?>
    <a href="<?php echo esc_url( get_post_type_archive_link('evangelijni_pribeh') ); ?>" class="mobile-nav-icon"><i class="fa fa-archive" aria-hidden="true"></i></a>
    <?php // Odkaz na nastavení (pokud bude existovat) ?>
    <a href="<?php echo esc_url( admin_url() ); ?>" class="mobile-nav-icon"><i class="fa fa-cog" aria-hidden="true"></i></a>
</div>
<?php // ?>

<!-- Left Mobile Drawer -->
<div id="left-mobile-drawer" class="popup-drawer-left" aria-hidden="true">
    <div class="drawer-inner-left">
        <div class="drawer-header-left">
            <button class="drawer-toggle-left close-drawer" aria-label="Zavřít menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="kadence-svg-icon kadence-x-svg"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>
        <div class="drawer-content-left">
            <?php
            // Vykreslíme menu přiřazené k umístění 'leve_mobilni_menu'.
            if ( has_nav_menu( 'leve_mobilni_menu' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'leve_mobilni_menu',
                    'container'      => 'nav',
                    'menu_class'     => 'left-mobile-navigation',
                ) );
            } else {
                echo '<p style="padding: 1em;">Přiřaďte menu k umístění "Levé mobilní menu" v sekci Vzhled -> Menu.</p>';
            }
            ?>
        </div>
    </div>
    <div class="drawer-overlay-left"></div>
</div>

<?php wp_footer(); ?>
</body>
</html>