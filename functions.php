<?php
/**
 * Child-Theme functions and definitions
 */

function impacto_patronus_child_scripts() {
    global $post;

    wp_enqueue_style( 'impacto-patronus-parent-style', get_template_directory_uri(). '/style.css' );
    wp_enqueue_style( 'impacto-patronus-child-style', get_stylesheet_uri() );

    /**
     * A contact form 7-hez tartozo scriptek csak azokon az oldalakon
     * toltodjenek be, ahol be van huzva a form.
     */
    if (!strstr($post->post_content, '[contact-form-7')) {
        wp_dequeue_script('contact-form-7');
        wp_dequeue_script('google-recaptcha');
        wp_dequeue_script('wpcf7-recaptcha');
        wp_dequeue_style('contact-form-7');
    }
}
add_action( 'wp_enqueue_scripts', 'impacto_patronus_child_scripts' );

/**
 * Hides admin widgets for subscribers.
 */
 function remove_admin_dashboard_widgets() {
     remove_meta_box( 'dashboard_primary','dashboard','side' ); // WordPress.com Blog
     remove_meta_box( 'dashboard_plugins','dashboard','normal' ); // Plugins
     remove_meta_box( 'dashboard_right_now','dashboard', 'normal' ); // Right Now
     remove_action( 'welcome_panel','wp_welcome_panel' ); // Welcome Panel
     remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel'); // Try Gutenberg
     remove_meta_box('dashboard_quick_press','dashboard','side'); // Quick Press widget
     remove_meta_box('dashboard_recent_drafts','dashboard','side'); // Recent Drafts
     remove_meta_box('dashboard_secondary','dashboard','side'); // Other WordPress News
     remove_meta_box('dashboard_incoming_links','dashboard','normal'); //Incoming Links
     remove_meta_box('rg_forms_dashboard','dashboard','normal'); // Gravity Forms
     remove_meta_box('dashboard_recent_comments','dashboard','normal'); // Recent Comments
     remove_meta_box('icl_dashboard_widget','dashboard','normal'); // Multi Language Plugin
     remove_meta_box('dashboard_activity','dashboard', 'normal'); // Activity

     remove_meta_box('e-dashboard-overview', 'dashboard', 'normal'); // Elementor Overview
     remove_meta_box('jetpack_summary_widget', 'dashboard', 'normal'); // Elementor Overview
 }

 function remove_admin_menus() {
     if (class_exists( 'Jetpack' )) {
       remove_menu_page( 'jetpack' );
     }
 }

 if (!current_user_can('manage_options')) {
     add_action( 'admin_init', 'remove_admin_menus' );
     add_action('wp_dashboard_setup', 'remove_admin_dashboard_widgets' );
 }

/**
 * Loads the child theme textdomain.
 */
function noar_child_theme_setup() {
    load_child_theme_textdomain( 'impacto-patronus-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'noar_child_theme_setup' );