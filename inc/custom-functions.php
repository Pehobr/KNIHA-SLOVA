<?php
/**
 * Vlastní úpravy a funkce pro child šablonu Kniha Slova.
 *
 * @package Kadence Child
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registrace vlastních umístění pro menu.
 */
function kniha_slova_register_nav_menus() {
	register_nav_menus( array(
		'leve_mobilni_menu' => __( 'Levé mobilní menu', 'kadence-child' ),
	) );
}
add_action( 'init', 'kniha_slova_register_nav_menus' );

