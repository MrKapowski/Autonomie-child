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
    wp_enqueue_style( 'autonomie-style', get_template_directory_uri() . '/style.css', array( 'dashicons' ) );
    wp_enqueue_style( 'autonomie-print-style', get_template_directory_uri() . '/css/print.css', array( 'autonomie-style' ), '1.0.0', 'print' );
    wp_enqueue_style( 'autonomie-narrow-style', get_template_directory_uri() . '/css/narrow-width.css', array( 'autonomie-style' ), '1.0.0', '(max-width: 800px)' );
    wp_enqueue_style( 'autonomie-default-style', get_template_directory_uri() . '/css/default-width.css', array( 'autonomie-style' ), '1.0.0', '(min-width: 800px)' );
    wp_enqueue_style( 'autonomie-wide-style', get_template_directory_uri() . '/css/wide-width.css', array( 'autonomie-style' ), '1.0.0', '(min-width: 1000px)' );
    wp_enqueue_style( 'autonomie-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( 'autonomie-style' ),
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

function autonomie_child_after_setup_theme() {
    add_theme_support( 'soil-clean-up' );
    add_theme_support( 'soil-jquery-cdn' );
    add_theme_support( 'soil-js-to-footer' );
    add_theme_support( 'soil-nav-walker' );
    add_theme_support( 'soil-nice-search' );

   

}
add_action( 'after_setup_theme', 'autonomie_child_after_setup_theme' );
/**
 * Re-enable the built-in Links manager
 */
add_filter( 'pre_option_link_manager_enabled', '__return_true' );


function autonomie_child_kinds_init() {
    //remove Post Kinds from the_excerpt generation.
    remove_filter( 'the_excerpt', array( 'Kind_View', 'excerpt_response' ), 9 );
	remove_filter( 'the_content', array( 'Kind_View', 'content_response' ), 9 );
}
add_action( 'init', 'autonomie_child_kinds_init' );

/**
 * Add useful extra classes to images, for layout and MF2
 */
function mrkapowski_add_image_classes( $class ) {
	$classes = array( 'img-fluid', 'u-photo' );
	$class  .= ' ';
	$class  .= implode( ' ', $classes );
	return $class;
}
/**
 * Filter inserted images, to apply our customisations
 */
add_filter( 'get_image_tag_class', 'mrkapowski_add_image_classes' );

function mrkapowski_attachment_attr( $attr, $attachment, $size ) {
	if ( isset( $attr['class'] ) && strpos($attr['class'], 'custom-logo') === false ) {
		$attr['class'] .= ' img-fluid u-photo';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'mrkapowski_attachment_attr', 10, 3 );

function add_hum_shortlink() {
    ?>
    
	<link rel="shortlink" href="<?php echo esc_url( wp_get_shortlink() ); ?>">
    <?php
}
add_action('wp_head', 'add_hum_shortlink');

//add_filter( 'wp_insert_post_data', 'crt_update_blank_title' );
function crt_update_blank_title( $data ) {
	$title = $data['post_title'];
	$post_type = $data['post_type'];
	
	if ( empty( $title ) && ( $post_type == 'post' ) ) {
		$timezone = get_option('timezone_string');
		date_default_timezone_set( $timezone );
		$title = date( 'Y-m-d H.i.s' );
		$data['post_title'] = $title;
	}
	return $data;
}
/**
 * Add useful extra classes to images, for layout and MF2
 */
function saorsa_add_image_classes( $class ) {
	$classes = array( 'img-fluid', 'u-photo' );
	$class  .= ' ';
	$class  .= implode( ' ', $classes );
	return $class;
}
/**
 * Remove width and height from editor images, for responsiveness
 */
function saorsa_remove_image_dimensions( $html ) {
	$html = preg_replace( '/(height|width)=\"\d*\"\s?/', '', $html );
	return $html;
}
/**
 * Filter inserted images, to apply our customisations
 */
add_filter( 'get_image_tag_class', 'saorsa_add_image_classes' );
/**
 * Filter thumbnails, to apply our customisations
 */
add_filter( 'post_thumbnail_html', 'saorsa_remove_image_dimensions', 10 );
/**
 * Filter images in the editor, to apply our customisations
 */
add_filter( 'image_send_to_editor', 'saorsa_remove_image_dimensions', 10 );
/**
 * Filter images in the content, to apply our customisations
 */
add_filter( 'the_content', 'saorsa_remove_image_dimensions', 30 );
if(!function_exists('saorsa_caption')) {
    function saorsa_caption( $output, $attr, $content = null ) {
        shortcode_atts(
            array(
                'id'      => '',
                'align'   => 'alignnone',
                'width'   => '',
                'caption' => '',
            ),
            $attr
        );

        if ( empty( $attr['caption'] ) ) {
            return $content;
        }

        if ( $attr['id'] ) {
            $attr['id'] = 'id="' . $attr['id'] . '" ';
        }

        return '<figure ' . $attr['id'] . 'class="card wp-caption ' . $attr['align'] . '">'
        . do_shortcode( $content ) . '<figcaption class="card-body wp-caption-text">' . $attr['caption'] . '</figcaption></figure>';
    }

    add_filter( 'img_caption_shortcode', 'saorsa_caption', 3, 10 );
}

if (!function_exists('saorsa_gallery')) {
    function saorsa_gallery( $output, $attr, $instance ) {
        $post = get_post();

#        static $instance = 0;
#        $instance++;

        if ( ! empty( $attr['ids'] ) ) {
                // 'ids' is explicitly ordered, unless you specify otherwise.
                if ( empty( $attr['orderby'] ) ) {
                        $attr['orderby'] = 'post__in';
                }
                $attr['include'] = $attr['ids'];
        }

        $html5 = current_theme_supports( 'html5', 'gallery' );


        // $output = apply_filters( 'post_gallery', '', $attr, $instance );
        // if ( $output != '' ) {
        //         return $output;
        // }

        $atts = shortcode_atts(
            array(
                'order'      => 'ASC',
                'orderby'    => 'menu_order ID',
                'id'         => $post ? $post->ID : 0,
                'itemtag'    => $html5 ? 'figure' : 'dl',
                'icontag'    => $html5 ? 'div' : 'dt',
                'captiontag' => $html5 ? 'figcaption' : 'dd',
                'columns'    => 3,
                'size'       => 'thumbnail',
                'include'    => '',
                'exclude'    => '',
                'link'       => '',
            ),
            $attr,
            'gallery'
        );

        $id = intval( $atts['id'] );

        if ( ! empty( $atts['include'] ) ) {
            $_attachments = get_posts(
                array(
                    'include'        => $atts['include'],
                    'post_status'    => 'inherit',
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'order'          => $atts['order'],
                    'orderby'        => $atts['orderby'],
                )
            );

            $attachments = array();
            foreach ( $_attachments as $key => $val ) {
                $attachments[ $val->ID ] = $_attachments[ $key ];
            }
        } elseif ( ! empty( $atts['exclude'] ) ) {
            $attachments = get_children(
                array(
                    'post_parent'    => $id,
                    'exclude'        => $atts['exclude'],
                    'post_status'    => 'inherit',
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'order'          => $atts['order'],
                    'orderby'        => $atts['orderby'],
                )
            );
        } else {
            $attachments = get_children(
                array(
                    'post_parent'    => $id,
                    'post_status'    => 'inherit',
                    'post_type'      => 'attachment',
                    'post_mime_type' => 'image',
                    'order'          => $atts['order'],
                    'orderby'        => $atts['orderby'],
                )
            );
        }

        if ( empty( $attachments ) ) {
                return '';
        }

        if ( is_feed() ) {
            $output = "\n";
            foreach ( $attachments as $att_id => $attachment ) {
                    $output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
            }
            return $output;
        }

        $itemtag    = tag_escape( $atts['itemtag'] );
        $captiontag = tag_escape( $atts['captiontag'] );
        $icontag    = tag_escape( $atts['icontag'] );
        $valid_tags = wp_kses_allowed_html( 'post' );
        if ( ! isset( $valid_tags[ $itemtag ] ) ) {
                $itemtag = 'div';
        }
        if ( ! isset( $valid_tags[ $captiontag ] ) ) {
                $captiontag = 'figcaption';
        }
        if ( ! isset( $valid_tags[ $icontag ] ) ) {
                $icontag = 'figure';
        }

        $columns   = intval( $atts['columns'] );
        $itemwidth = floor( 12 / $columns ); //$columns > 0 ? floor( 100 / $columns ) : 100;
        $float     = is_rtl() ? 'right' : 'left';

        $selector = "gallery-{$instance}";

        $gallery_style = '';

        $size_class  = sanitize_html_class( $atts['size'] );
        $gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

        /**
            * Filters the default gallery shortcode CSS styles.
            *
            * @since 2.5.0
            *
            * @param string $gallery_style Default CSS styles and opening HTML div container
            *                              for the gallery shortcode output.
            */
        $output = apply_filters( 'gallery_style', $gallery_style . $gallery_div );

        $i = 0;
        foreach ( $attachments as $id => $attachment ) {

            $attr = ( trim( $attachment->post_excerpt ) ) ? array( 'aria-describedby' => "$selector-$id" ) : '';
            if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
                    $image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
            } elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
                    $image_output = wp_get_attachment_image( $id, $atts['size'], false, $attr );
            } else {
                    $image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
            }
            $image_meta = wp_get_attachment_metadata( $id );

            $orientation = '';
            if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
                    $orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
            }
            $output .= "<{$itemtag} class='gallery-item {$orientation}'>";
            $output .= "
                    <{$icontag} class='gallery-icon {$orientation}'>
                            $image_output
                    </{$icontag}>";
            if ( $captiontag && trim( $attachment->post_excerpt ) ) {
                    $output .= "
                            <{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>"
                            . wptexturize( $attachment->post_excerpt ) .
                            "</{$captiontag}>";
            }
            $output .= "</{$itemtag}>";
        }

        $output .= "
                </div>\n";

        return $output;
    }
    add_filter( 'post_gallery', 'saorsa_gallery', 3, 10 );
}
