<?php
/**
 * Funções do tema Kaspersky Imagetech Landing
 */

if (!defined('ABSPATH')) {
    exit;
}

define('KASPERSKY_IMAGETECH_VERSION', '1.0.0');

define('KASPERSKY_IMAGETECH_PATH', get_template_directory());

define('KASPERSKY_IMAGETECH_URI', get_template_directory_uri());

/**
 * Configurações básicas do tema
 */
function kaspersky_imagetech_setup(): void
{
    load_theme_textdomain('kaspersky-imagetech', KASPERSKY_IMAGETECH_PATH . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support(
        'html5',
        ['search-form', 'gallery', 'caption', 'style', 'script']
    );
    register_nav_menus([
        'primary' => __('Menu principal', 'kaspersky-imagetech'),
    ]);
}
add_action('after_setup_theme', 'kaspersky_imagetech_setup');

/**
 * Enfileira estilos e scripts
 */
function kaspersky_imagetech_assets(): void
{
    wp_enqueue_style(
        'kaspersky-imagetech-fonts',
        'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap',
        [],
        null
    );

    $css_path = KASPERSKY_IMAGETECH_PATH . '/assets/css/main.css';
    $css_ver = file_exists($css_path) ? filemtime($css_path) : KASPERSKY_IMAGETECH_VERSION;
    wp_enqueue_style(
        'kaspersky-imagetech-main',
        KASPERSKY_IMAGETECH_URI . '/assets/css/main.css',
        ['kaspersky-imagetech-fonts'],
        $css_ver
    );

    wp_enqueue_script(
        'kaspersky-imagetech-gsap',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js',
        [],
        '3.12.2',
        true
    );

    $js_path = KASPERSKY_IMAGETECH_PATH . '/assets/js/main.js';
    $js_ver = file_exists($js_path) ? filemtime($js_path) : KASPERSKY_IMAGETECH_VERSION;
    wp_enqueue_script(
        'kaspersky-imagetech-main',
        KASPERSKY_IMAGETECH_URI . '/assets/js/main.js',
        ['kaspersky-imagetech-gsap'],
        $js_ver,
        true
    );

    wp_localize_script(
        'kaspersky-imagetech-main',
        'kasperskyTheme',
        [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('kaspersky_lead_nonce'),
            'successMessage' => __('Recebemos seus dados!', 'kaspersky-imagetech'),
            'errorMessage' => __('Não foi possível enviar agora. Tente novamente.', 'kaspersky-imagetech'),
        ]
    );
}
add_action('wp_enqueue_scripts', 'kaspersky_imagetech_assets');

/**
 * Registro de padrões de blocos (Gutenberg)
 */
function kaspersky_imagetech_register_block_patterns(): void
{
    if (!function_exists('register_block_pattern')) {
        return;
    }

    static $registered = false;
    if ($registered) {
        return;
    }

    register_block_pattern_category(
        'kaspersky-imagetech',
        ['label' => __('Kaspersky Imagetech', 'kaspersky-imagetech')]
    );

    $pattern_dir = KASPERSKY_IMAGETECH_PATH . '/patterns';
    if (!is_dir($pattern_dir)) {
        return;
    }

    $files = glob($pattern_dir . '/*.php');
    if (!$files) {
        return;
    }

    foreach ($files as $file) {
        $pattern = require $file;
        if (!is_array($pattern) || empty($pattern['title']) || empty($pattern['content'])) {
            continue;
        }
        $slug = 'kaspersky-imagetech/' . basename($file, '.php');
        register_block_pattern($slug, $pattern);
    }

    $registered = true;
}
add_action('init', 'kaspersky_imagetech_register_block_patterns');

function kaspersky_imagetech_get_pattern_content(string $slug = 'kaspersky-imagetech/landing'): string
{
    if (!class_exists('WP_Block_Patterns_Registry')) {
        return '';
    }

    $pattern = WP_Block_Patterns_Registry::get_instance()->get_registered($slug);
    return $pattern['content'] ?? '';
}

function kaspersky_imagetech_render_default_pattern(string $slug = 'kaspersky-imagetech/landing'): string
{
    $pattern_content = kaspersky_imagetech_get_pattern_content($slug);
    if ($pattern_content === '') {
        return '';
    }

    return do_blocks(
        sprintf(
            '<!-- wp:pattern {"slug":"%s"} /-->',
            esc_attr($slug)
        )
    );
}

function kaspersky_imagetech_seed_landing_page(): void
{
    if (get_option('kaspersky_imagetech_default_page_created')) {
        return;
    }

    kaspersky_imagetech_register_block_patterns();

    $content = kaspersky_imagetech_get_pattern_content();
    if ($content === '') {
        return;
    }

    $existing = get_page_by_path('kaspersky-landing');
    $current_front = (int) get_option('page_on_front');
    $show_on_front = get_option('show_on_front');

    if ($existing instanceof WP_Post) {
        if ($show_on_front !== 'page' || $current_front === 0) {
            update_option('page_on_front', $existing->ID);
            update_option('show_on_front', 'page');
        }
        update_option('kaspersky_imagetech_default_page_created', 1);
        return;
    }

    $page_id = wp_insert_post([
        'post_title' => __('Kaspersky Landing', 'kaspersky-imagetech'),
        'post_name' => 'kaspersky-landing',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_content' => $content,
    ]);

    if (!is_wp_error($page_id)) {
        if ($show_on_front !== 'page' || $current_front === 0) {
            update_option('page_on_front', $page_id);
            update_option('show_on_front', 'page');
        }
        update_option('kaspersky_imagetech_default_page_created', 1);
    }
}
add_action('after_switch_theme', 'kaspersky_imagetech_seed_landing_page');

/**
 * AJAX para o formulário de leads
 */
function kaspersky_imagetech_handle_lead(): void
{
    check_ajax_referer('kaspersky_lead_nonce', 'nonce');

    $fields = [
        'nome' => sanitize_text_field(wp_unslash($_POST['nome'] ?? '')),
        'empresa' => sanitize_text_field(wp_unslash($_POST['empresa'] ?? '')),
        'email' => sanitize_email(wp_unslash($_POST['email'] ?? '')),
        'telefone' => sanitize_text_field(wp_unslash($_POST['telefone'] ?? '')),
        'mensagem' => sanitize_textarea_field(wp_unslash($_POST['mensagem'] ?? '')),
    ];

    if (empty($fields['nome']) || empty($fields['empresa']) || empty($fields['email'])) {
        wp_send_json_error([
            'message' => __('Campos obrigatórios ausentes.', 'kaspersky-imagetech'),
        ], 400);
    }

    if (!is_email($fields['email'])) {
        wp_send_json_error([
            'message' => __('E-mail inválido.', 'kaspersky-imagetech'),
        ], 400);
    }

    $to = get_option('admin_email');
    $subject = sprintf(
        __('Novo lead Kaspersky - %s', 'kaspersky-imagetech'),
        $fields['empresa']
    );

    $message = sprintf(
        "Nome: %s\nEmpresa: %s\nE-mail: %s\nTelefone: %s\n\nNecessidade:\n%s",
        $fields['nome'],
        $fields['empresa'],
        $fields['email'],
        $fields['telefone'],
        $fields['mensagem']
    );

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
    ];

    $mail_sent = wp_mail($to, $subject, $message, $headers);

    if (!$mail_sent) {
        wp_send_json_error([
            'message' => __('Não foi possível enviar o e-mail agora.', 'kaspersky-imagetech'),
        ], 500);
    }

    wp_send_json_success([
        'message' => __('Recebemos seus dados!', 'kaspersky-imagetech'),
    ]);
}
add_action('wp_ajax_kaspersky_submit_lead', 'kaspersky_imagetech_handle_lead');
add_action('wp_ajax_nopriv_kaspersky_submit_lead', 'kaspersky_imagetech_handle_lead');
