<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//======================================================================
// 1. ZÁKLADNÍ NASTAVENÍ CHILD ŠABLONY
//======================================================================

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

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

function child_theme_configurator_css() {
    // Načte základní styl child šablony
    wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'kadence-global','kadence-header','kadence-content','kadence-footer' ) );

    // Podmíněné načítání stylů podle typu stránky
    if ( is_singular('evangelijni_pribeh') ) {
        // Načte styly pouze pro detail příběhu
        wp_enqueue_style( 'kniha-slova-single-styles', get_stylesheet_directory_uri() . '/css/single-pribehu.css', array('chld_thm_cfg_child'), '1.0.0' );
    } elseif ( is_post_type_archive('evangelijni_pribeh') ) {
        // Načte styly pouze pro archiv příběhů
        wp_enqueue_style( 'kniha-slova-archive-styles', get_stylesheet_directory_uri() . '/css/archiv-pribehu.css', array('chld_thm_cfg_child'), '1.0.0' );
    }
}
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 20 );

function knihaslova_enqueue_scripts() {
    // Načítání JS souboru zůstává stejné, je potřeba na detailu příběhu
    if ( is_singular('evangelijni_pribeh') ) {
        wp_enqueue_script( 'kniha-slova-main-js', get_stylesheet_directory_uri() . '/js/main.js', array(), '1.0.1', true );
    }
}
add_action( 'wp_enqueue_scripts', 'knihaslova_enqueue_scripts' );


//======================================================================
// 4. FUNKCE PRO KOMUNIKACI S GOOGLE SHEETS
//======================================================================

/**
 * Načte a zpracuje data z publikované Google Tabulky (CSV).
 * Tato verze správně zpracovává i buňky s více řádky.
 * @param string $sheet_url URL publikovaného CSV souboru.
 * @return array|false Pole dat nebo false při chybě.
 */
function get_data_from_google_sheet($sheet_url) {
    $response = wp_remote_get($sheet_url, array('timeout' => 20));

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        error_log('Google Sheets Chyba: Nepodařilo se stáhnout data z URL: ' . $sheet_url);
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $body = preg_replace('/^\x{FEFF}|\x{EF}\x{BB}\x{BF}/', '', $body);

    $stream = fopen('php://memory', 'r+');
    fwrite($stream, $body);
    rewind($stream);

    $header = fgetcsv($stream);
    if ($header === false) {
        fclose($stream);
        return false;
    }

    $data = [];
    while (($row = fgetcsv($stream)) !== false) {
        if (count($header) === count($row)) {
            if (count(array_filter($row)) > 0) {
                 $data[] = array_combine($header, $row);
            }
        }
    }
    fclose($stream);

    return $data;
}

/**
 * Získá všechny potřebné texty a citace pro daný příběh.
 * Upraveno o možnost vynuceného mazání transientů z administrace.
 * @param string $story_id Unikátní ID příběhu (např. 'jan-krtitel').
 * @return array|null Komplexní pole s daty pro daný příběh nebo null.
 */
function knihaslova_get_story_data($story_id) {
    if (empty($story_id)) {
        return null;
    }

    // Získáme nastavení z administrace
    $dev_options = get_option('knihaslova_dev_options');
    $force_clear = isset($dev_options['force_clear_transients']) && $dev_options['force_clear_transients'] === 'on';

    $transient_key = 'knihaslova_story_' . $story_id;

    // Pokud je volba aktivní, smažeme transient
    if ($force_clear) {
        delete_transient($transient_key);
        error_log('Kniha Slova Debug: Transient pro "' . $story_id . '" byl smazán na základě nastavení v administraci.');
    }

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
            if (isset($row['ID_pribehu']) && trim($row['ID_pribehu']) === $id) {
                return $row;
            }
        }
        return null;
    };

    $story_info = $find_row($pribehy_data, $story_id);
    if (!$story_info) {
        return null;
    }

    $final_data = [
        'info' => $story_info,
        'translations' => [
            'katolicky' => $find_row($katolicky_data, $story_id),
            'ekumenicky' => $find_row($ekumenicky_data, $story_id),
            'jeruzalemsky' => $find_row($jeruzalemsky_data, $story_id)
        ]
    ];

    set_transient($transient_key, $final_data, 3600); // Cache na 1 hodinu

    return $final_data;
}

//======================================================================
// 5. VÝVOJÁŘSKÁ SEKCE PRO SPRÁVU WEBU
//======================================================================

/**
 * Přidá do administrace novou položku hlavního menu "Admin".
 */
function knihaslova_add_admin_menu() {
    add_menu_page(
        'Admin Nastavení', // Název stránky (title)
        'Admin',           // Text v menu
        'manage_options',  // Oprávnění pro zobrazení
        'kniha_slova_admin', // Slug stránky
        'knihaslova_admin_page_html', // Funkce pro vykreslení obsahu
        'dashicons-admin-generic', // Ikona
        2 // Pozice v menu
    );
}
add_action('admin_menu', 'knihaslova_add_admin_menu');

/**
 * Vykreslí obsah stránky "Admin".
 */
function knihaslova_admin_page_html() {
    // Zkontroluje, zda má uživatel oprávnění
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // Skrytá pole pro bezpečnost a funkčnost WordPressu
            settings_fields('knihaslova_admin_settings');
            // Vykreslí všechny sekce a pole přidaná k této stránce
            do_settings_sections('kniha_slova_admin');
            // Tlačítko pro uložení
            submit_button('Uložit nastavení');
            ?>
        </form>
    </div>
    <?php
}

/**
 * Registruje nastavení, sekce a pole.
 */
function knihaslova_settings_init() {
    // Registruje skupinu nastavení
    register_setting('knihaslova_admin_settings', 'knihaslova_dev_options');

    // Přidá sekci pro nastavení
    add_settings_section(
        'knihaslova_dev_section', // ID sekce
        'Vývojářské nástroje',    // Nadpis sekce
        'knihaslova_dev_section_cb', // Callback pro popis sekce (může být prázdný)
        'kniha_slova_admin'      // Slug stránky, kde se má sekce zobrazit
    );

    // Přidá pole pro zapnutí/vypnutí mazání cache
    add_settings_field(
        'force_clear_transients', // ID pole
        'Vynutit smazání cache',   // Popisek pole
        'knihaslova_force_clear_transients_cb', // Callback pro vykreslení pole
        'kniha_slova_admin',      // Slug stránky
        'knihaslova_dev_section'  // ID sekce, do které patří
    );
}
add_action('admin_init', 'knihaslova_settings_init');

/**
 * Popis sekce (nepovinné).
 */
function knihaslova_dev_section_cb($args) {
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>">Zde můžete spravovat pokročilé funkce webu.</p>
    <?php
}

/**
 * Vykreslí checkbox pro volbu mazání transientů.
 */
function knihaslova_force_clear_transients_cb() {
    // Získá uložené hodnoty
    $options = get_option('knihaslova_dev_options');
    $checked = isset($options['force_clear_transients']) ? $options['force_clear_transients'] : 'off';
    ?>
    <label for="force_clear_transients">
        <input type="checkbox"
               id="force_clear_transients"
               name="knihaslova_dev_options[force_clear_transients]"
               value="on"
               <?php checked('on', $checked); ?>
        >
        Aktivovat (Při každém načtení stránky příběhu se smaže její dočasná mezipaměť a data se stáhnou znovu z Google Sheets).
    </label>
    <p class="description">
        <strong>Varování:</strong> Tuto volbu zapínejte pouze dočasně pro vývoj a ladění. Ponechání této volby aktivní může výrazně zpomalit web.
    </p>
    <?php
}

