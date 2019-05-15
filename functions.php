<?php

add_action( 'wp_enqueue_scripts', 'autonomie_child_enqueue_styles' );
function autonomie_child_enqueue_styles() {
 
    $parent_style = 'parent-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

function autonomie_child_kinds_init() {
    //remove Post Kinds from the_excerpt generation.
    remove_filter( 'the_excerpt', array( 'Kind_View', 'excerpt_response' ), 20 );
}
add_action( 'init', 'autonomie_child_kinds_init' );