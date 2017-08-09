<?php
/**
* Enqueues child theme stylesheet, loading first the parent theme stylesheet.
*/

function my_theme_enqueue_styles() {
    $parent_style = 'storefront'; // This is 'storefront-style' for the Storefront theme.
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

// All other functions are located in plugins directory
?>
