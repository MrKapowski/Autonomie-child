<?php
/**
 * /* Template Name: Links Page
 * The template for displaying Link directory pages.
 *
 * @package Autonomie-child
 * @since Autonomie-child 1.0.0
 */
get_header(); ?>

		<main id="primary">

			<?php while ( have_posts() ) : the_post(); ?>

            <article <?php autonomie_post_id(); ?> <?php post_class(); ?><?php autonomie_semantics( 'post' ); ?>>
                <?php get_template_part( 'templates/partials/entry-header' ); ?>

                <?php if ( is_search() ) : // Only display Excerpts for Search ?>
                <div class="entry-summary p-summary" itemprop="description articleBody">
                    <?php the_excerpt(); ?>
                </div><!-- .entry-summary -->
                <?php else : ?>
                <?php autonomie_the_post_thumbnail( '<div class="entry-media">', '</div>' ); ?>
                <div class="entry-content e-content" itemprop="description articleBody">
                    <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'autonomie' ) ); ?>
                    <ul class="list-unstyled">
                    <?php
                    wp_list_bookmarks(
                        array(
                            'title_before' => '<h3>',
                            'title_after'  => '</h3>',
                        )
                    );
                    ?>
                    </ul>
                    <?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'autonomie' ), 'after' => '</div>' ) ); ?>
                </div><!-- .entry-content -->
                <?php endif; ?>

                <?php get_template_part( 'templates/partials/entry-footer' ); ?>
            </article><!-- #post-<?php the_ID(); ?> -->

				<?php comments_template( '', true ); ?>

			<?php endwhile; // end of the loop. ?>

		</main><!-- #content -->

<?php get_footer(); ?>							
