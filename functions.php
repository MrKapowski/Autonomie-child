<?php
/**
	 * Enqueue theme scripts
	 *
	 * @uses wp_enqueue_scripts() To enqueue scripts
	 *
	 * @since Autonomie 1.0.0
	 */
	function autonomie_enqueue_scripts() {
		/*
		 * Adds JavaScript to pages with the comment form to support sites with
		 * threaded comments (when in use).
		 */
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Add  support to older versions of IE
		if ( isset( $_SERVER['HTTP_USER_AGENT'] ) &&
			( false !== strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE' ) ) &&
			( false === strpos( $_SERVER['HTTP_USER_AGENT'], 'MSIE 9' ) ) ) {

			wp_enqueue_script( '', get_template_directory_uri() . '/js/html5shiv.min.js', false, '3.7.3' );
		}

		wp_enqueue_script( 'autonomie-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0.0', true );
		wp_enqueue_script( 'autonomie-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '1.0.0', true );

		wp_enqueue_style( 'dashicons' );

		// Loads our main stylesheet.
		wp_enqueue_style( 'autonomie-style', get_stylesheet_uri(), array( 'dashicons' ) );
		wp_enqueue_style( 'autonomie-print-style', get_template_directory_uri() . '/css/print.css', array( 'autonomie-style' ), '1.0.0', 'print' );
		wp_enqueue_style( 'autonomie-narrow-style', get_template_directory_uri() . '/css/narrow-width.css', array( 'autonomie-style' ), '1.0.0', '(max-width: 800px)' );
		wp_enqueue_style( 'autonomie-default-style', get_template_directory_uri() . '/css/default-width.css', array( 'autonomie-style' ), '1.0.0', '(min-width: 800px)' );
		wp_enqueue_style( 'autonomie-wide-style', get_template_directory_uri() . '/css/wide-width.css', array( 'autonomie-style' ), '1.0.0', '(min-width: 1000px)' );
  wp_enqueue_style( 'child-style',
      get_stylesheet_directory_uri() . '/style.css',
      array( $parent_style ),
      wp_get_theme()->get('Version')
  );
		wp_localize_script(
			'autonomie',
			'vars',
			array(
				'template_url' => get_template_directory_uri(),
			)
		);

		if ( has_header_image() ) {
			if ( is_author() ) {
				$css = '.page-banner {
					background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), url(' . get_header_image() . ') no-repeat center center scroll;
				}' . PHP_EOL;
			} else {
				$css = '.page-banner {
					background: linear-gradient(rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.7)), url(' . get_header_image() . ') no-repeat center center scroll;
				}' . PHP_EOL;
			}

			wp_add_inline_style( 'autonomie-style', $css );
		}
	}

function autonomie_child_kinds_init() {
    //remove Post Kinds from the_excerpt generation.
    remove_filter( 'the_excerpt', array( 'Kind_View', 'excerpt_response' ), 20 );
}
add_action( 'init', 'autonomie_child_kinds_init' );
