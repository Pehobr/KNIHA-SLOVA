<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//======================================================================
// 2. REGISTRACE VLASTNÍHO TYPU PŘÍSPĚVKU (CUSTOM POST TYPE)
//======================================================================

function register_evangelijni_pribeh_cpt() {
    $labels = array(
        'name'                  => _x( 'Příběhy evangelia', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Evangelijní Příběh', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Evangelijní Příběhy', 'text_domain' ),
        'name_admin_bar'        => __( 'Evangelijní Příběh', 'text_domain' ),
        'archives'              => __( 'Archiv příběhů', 'text_domain' ),
        'attributes'            => __( 'Vlastnosti příběhu', 'text_domain' ),
        'parent_item_colon'     => __( 'Rodičovský příběh:', 'text_domain' ),
        'all_items'             => __( 'Všechny příběhy', 'text_domain' ),
        'add_new_item'          => __( 'Přidat nový příběh', 'text_domain' ),
        'add_new'               => __( 'Přidat nový', 'text_domain' ),
        'new_item'              => __( 'Nový příběh', 'text_domain' ),
        'edit_item'             => __( 'Upravit příběh', 'text_domain' ),
        'update_item'           => __( 'Aktualizovat příběh', 'text_domain' ),
        'view_item'             => __( 'Zobrazit příběh', 'text_domain' ),
        'view_items'            => __( 'Zobrazit příběhy', 'text_domain' ),
        'search_items'          => __( 'Hledat příběh', 'text_domain' ),
        'not_found'             => __( 'Nenalezeno', 'text_domain' ),
        'not_found_in_trash'    => __( 'Nenalezeno v koši', 'text_domain' ),
        'featured_image'        => __( 'Hlavní obrázek příběhu', 'text_domain' ),
        'set_featured_image'    => __( 'Nastavit hlavní obrázek', 'text_domain' ),
        'remove_featured_image' => __( 'Odstranit hlavní obrázek', 'text_domain' ),
        'use_featured_image'    => __( 'Použít jako hlavní obrázek', 'text_domain' ),
        'insert_into_item'      => __( 'Vložit do příběhu', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Nahráno k tomuto příběhu', 'text_domain' ),
        'items_list'            => __( 'Seznam příběhů', 'text_domain' ),
        'items_list_navigation' => __( 'Navigace seznamu příběhů', 'text_domain' ),
        'filter_items_list'     => __( 'Filtrovat seznam příběhů', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Evangelijní Příběh', 'text_domain' ),
        'description'           => __( 'Biblické texty pro exegezi.', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'revisions', 'custom-fields' ),
        'taxonomies'            => array(),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-book-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array( 'slug' => 'pribeh', 'with_front' => false ),
        'show_in_rest'          => true,
    );
    register_post_type( 'evangelijni_pribeh', $args );
}
add_action( 'init', 'register_evangelijni_pribeh_cpt', 0 );