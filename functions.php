<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//======================================================================
// 1. ZÁKLADNÍ NASTAVENÍ CHILD ŠABLONY
//======================================================================

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:
if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
// END ENQUEUE PARENT ACTION


//======================================================================
// 2. REGISTRACE VLASTNÍHO TYPU PŘÍSPĚVKU (CUSTOM POST TYPE)
//======================================================================

/**
 * Registruje vlastní typ příspěvku "Evangelijní Příběh".
 */
function register_evangelijni_pribeh_cpt() {
    $labels = array(
        'name'                  => _x( 'Evangelijní Příběhy', 'Post Type General Name', 'text_domain' ),
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
        'description'           => __( 'Biblické příběhy z evangelií pro exegezi.', 'text_domain' ),
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


//======================================================================
// 3. NAČÍTÁNÍ VLASTNÍCH CSS A JS SOUBORŮ
//======================================================================

/**
 * Načte styly pro child šablonu.
 */
function child_theme_configurator_css() {
    // Původní styly
    wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'kadence-global','kadence-header','kadence-content','kadence-footer' ) );
    
    // Naše nové vlastní styly
    wp_enqueue_style( 'kniha-slova-custom-styles', get_stylesheet_directory_uri() . '/css/custom-styles.css', array('chld_thm_cfg_child'), '1.0.1' );
}
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 20 );

/**
 * Načte vlastní JavaScript soubory.
 */
function knihaslova_enqueue_scripts() {
    // Načte náš hlavní JS soubor a zajistí, aby se načetl v patičce
    wp_enqueue_script( 'kniha-slova-main-js', get_stylesheet_directory_uri() . '/js/main.js', array(), '1.0.1', true );
}
add_action( 'wp_enqueue_scripts', 'knihaslova_enqueue_scripts' );


//======================================================================
// 4. FUNKCE PRO KOMUNIKACI S GOOGLE SHEETS
//======================================================================

/**
 * Načte a zpracuje data z publikované Google Tabulky (CSV).
 * @param string $sheet_url URL publikovaného CSV souboru.
 * @return array|false Pole dat nebo false při chybě.
 */
function get_data_from_google_sheet($sheet_url) {
    $response = wp_remote_get($sheet_url);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $body = preg_replace('/^\x{FEFF}|\x{EF}\x{BB}\x{BF}/', '', $body); // Odstranění BOM
    $rows = explode("\n", trim($body));
    $header = str_getcsv(array_shift($rows));

    $data = [];
    foreach ($rows as $row_str) {
        if (trim($row_str) === '') continue;
        $row = str_getcsv($row_str);
        if (count($row) === count($header)) {
            $data[] = array_combine($header, $row);
        }
    }
    return $data;
}

/**
 * Získá všechny potřebné texty a citace pro daný příběh.
 * @param string $story_id Unikátní ID příběhu (např. 'jan-krtitel').
 * @return array|null Komplexní pole s daty pro daný příběh nebo null.
 */
function knihaslova_get_story_data($story_id) {
    if (empty($story_id)) {
        return null;
    }

    $transient_key = 'knihaslova_story_' . $story_id;
    $cached_data = get_transient($transient_key);

    if (false !== $cached_data) {
        return $cached_data;
    }

    $urls = [
        'pribehy'       => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSjUiTc1VHd8teOLlQF51n5PLw1Z7MffXrovWmjfuypO5qR0ZV-vOE1oEZ2fFn95RvjpToiwFepiMm0/pub?gid=0&single=true&output=csv',
        'katolicky'     => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSjUiTc1VHd8teOLlQF51n5PLw1Z7MffXrovWmjfuypO5qR0ZV-vOE1oEZ2fFn95RvjpToiwFepiMm0/pub?gid=581207951&single=true&output=csv',
        'ekumenicky'    => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSjUiTc1VHd8teOLlQF51n5PLw1Z7MffXrovWmjfuypO5qR0ZV-vOE1oEZ2fFn95RvjpToiwFepiMm0/pub?gid=1989356485&single=true&output=csv',
        'jeruzalemsky'  => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSjUiTc1VHd8teOLlQF51n5PLw1Z7MffXrovWmjfuypO5qR0ZV-vOE1oEZ2fFn95RvjpToiwFepiMm0/pub?gid=493482207&single=true&output=csv',
    ];

    $pribehy_data = get_data_from_google_sheet($urls['pribehy']);
    $katolicky_data = get_data_from_google_sheet($urls['katolicky']);
    $ekumenicky_data = get_data_from_google_sheet($urls['ekumenicky']);
    $jeruzalemsky_data = get_data_from_google_sheet($urls['jeruzalemsky']);

    $find_row = function($data, $id) {
        if (!$data) return null;
        foreach ($data as $row) {
            if (isset($row['ID_pribehu']) && $row['ID_pribehu'] == $id) {
                return $row;
            }
        }
        return null;
    };

    // Najdeme informace o příběhu (jeden řádek)
    $story_info = $find_row($pribehy_data, $story_id);
    if (!$story_info) {
        return null;
    }

    // A k nim přidáme kompletní data překladů
    $final_data = [
        'info' => $story_info,
        'translations' => [
            'katolicky' => $find_row($katolicky_data, $story_id),     // OPRAVENO: Pro každý překlad najdeme správný řádek
            'ekumenicky' => $find_row($ekumenicky_data, $story_id),
            'jeruzalemsky' => $find_row($jeruzalemsky_data, $story_id)
        ]
    ];

    set_transient($transient_key, $final_data, 3600);

    return $final_data;
}