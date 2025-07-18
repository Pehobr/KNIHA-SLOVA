<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//======================================================================
// 6. VLASTNÍ ÚPRAVY A FUNKCE
//======================================================================

/**
 * Změní výchozí řazení pro archiv vlastního typu příspěvku "evangelijni_pribeh".
 * Místo abecedního řazení se bude řadit podle data od nejnovějšího.
 * @param object $query Hlavní WP_Query objekt.
 */
function knihaslova_change_archive_order( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'evangelijni_pribeh' ) ) {
        $query->set( 'orderby', 'date' );
        $query->set( 'order', 'DESC' );
    }
}
add_action( 'pre_get_posts', 'knihaslova_change_archive_order' );