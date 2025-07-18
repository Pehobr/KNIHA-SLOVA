<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

//======================================================================
// 4. FUNKCE PRO ZPRACOVÁNÍ DAT (GOOGLE SHEETS A DALŠÍ)
//======================================================================

/**
 * Načte a zpracuje data z publikované Google Tabulky (CSV).
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
 * Získá všechny potřebné texty a citace pro daný příběh z Google Sheets.
 * Využívá transienty (dočasnou mezipaměť) pro zrychlení.
 * @param string $story_id Unikátní ID příběhu (post slug).
 * @return array|null Komplexní pole s daty pro daný příběh nebo null.
 */
function knihaslova_get_story_data($story_id) {
    if (empty($story_id)) {
        return null;
    }

    $dev_options = get_option('knihaslova_dev_options');
    $force_clear = isset($dev_options['force_clear_transients']) && $dev_options['force_clear_transients'] === 'on';
    $transient_key = 'knihaslova_story_' . $story_id;

    if ($force_clear) {
        delete_transient($transient_key);
        error_log('Kniha Slova Debug: Transient pro "' . $story_id . '" byl smazán na základě nastavení v administraci.');
    }

    $cached_data = get_transient($transient_key);
    if (false !== $cached_data) {
        return $cached_data;
    }

    $urls = [
        'pribehy'      => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSjUiTc1VHd8teOLlQF51n5PLw1Z7MffXrovWmjfuypO5qR0ZV-vOE1oEZ2fFn95RvjpToiwFepiMm0/pub?gid=0&single=true&output=csv',
        'katolicky'    => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSjUiTc1VHd8teOLlQF51n5PLw1Z7MffXrovWmjfuypO5qR0ZV-vOE1oEZ2fFn95RvjpToiwFepiMm0/pub?gid=581207951&single=true&output=csv',
        'ekumenicky'   => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSjUiTc1VHd8teOLlQF51n5PLw1Z7MffXrovWmjfuypO5qR0ZV-vOE1oEZ2fFn95RvjpToiwFepiMm0/pub?gid=1989356485&single=true&output=csv',
        'jeruzalemsky' => 'https://docs.google.com/spreadsheets/d/e/2PACX-1vSjUiTc1VHd8teOLlQF51n5PLw1Z7MffXrovWmjfuypO5qR0ZV-vOE1oEZ2fFn95RvjpToiwFepiMm0/pub?gid=493482207&single=true&output=csv',
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

/**
 * Převede biblickou citaci na číslo pro snadné řazení.
 * Potřebné pro Katalog podle citací.
 * @param string $citation Citace ve formátu "Mt 3,1-12".
 * @return int Číselná hodnota pro řazení.
 */
function knihaslova_get_citation_sort_key($citation) {
    if (preg_match('/(\d+),\s*(\d+)/', $citation, $matches)) {
        $chapter = (int)$matches[1];
        $verse_start = (int)$matches[2];
        // Vytvoří unikátní číslo, kde kapitola má větší váhu
        return $chapter * 1000 + $verse_start;
    }
    // Pokud se nepodaří parsovat, vrátí vysoké číslo, aby byla položka na konci
    return 999999;
}