<?php
/**
 * Main template file
 *
 * @package eleventhset
 */

get_header();
?>

<main id="primary" class="site-main" style="padding-top: var(--nav-height);">

	<div class="page-hero">
		<div class="container">
			<?php
			if ( is_home() && ! is_front_page() ) {
				echo '<h1 class="page-hero__title">' . esc_html( get_the_title( get_option( 'page_for_posts' ) ) ) . '</h1>';
			} elseif ( is_archive() ) {
				the_archive_title( '<h1 class="page-hero__title">', '</h1>' );
				the_archive_description( '<p class="page-hero__description">', '</p>' );
			} elseif ( is_search() ) {
				printf(
					'<h1 class="page-hero__title">' . __( 'Search: %s', 'eleventhset' ) . '</h1>',
					get_search_query()
				);
			} else {
				echo '<h1 class="page-hero__title">' . __( 'Latest Posts', 'eleventhset' ) . '</h1>';
			}
			?>
		</div>
	</div>

	<div class="container section">
		<?php if ( have_posts() ) : ?>
		<div class="posts-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--spacing-md);">
			<?php while ( have_posts() ) : the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
				<div style="aspect-ratio: 16/9; overflow:hidden; margin-bottom: var(--spacing-sm);">
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'eleventhset-wide', array( 'style' => 'width:100%;height:100%;object-fit:cover;' ) ); ?>
					</a>
				</div>
				<?php endif; ?>
				<div>
					<p style="font-size:0.75rem; color:var(--color-accent); font-weight:600; letter-spacing:0.15em; text-transform:uppercase; margin-bottom:8px;">
						<?php echo get_the_date(); ?>
					</p>
					<h2 style="font-family:var(--font-primary); font-size:1.5rem; font-weight:300; margin-bottom:8px;">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h2>
					<p style="color:var(--color-text-secondary); font-size:0.875rem; line-height:1.7;">
						<?php the_excerpt(); ?>
					</p>
					<a href="<?php the_permalink(); ?>" class="btn btn--outline" style="margin-top: var(--spacing-sm); padding: 10px 24px;">
						<?php _e( 'Read More', 'eleventhset' ); ?>
					</a>
				</div>
			</article>
			<?php endwhile; ?>
		</div>

		<div class="page-pagination">
			<?php the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => '&larr;',
				'next_text' => '&rarr;',
			) ); ?>
		</div>

		<?php else : ?>
		<div style="text-align:center; padding: var(--spacing-xl) 0;">
			<h2 style="font-family:var(--font-primary); font-size:2rem; font-weight:300; margin-bottom: var(--spacing-md);">
				<?php _e( 'Nothing Found', 'eleventhset' ); ?>
			</h2>
			<p style="color:var(--color-text-secondary);">
				<?php _e( 'It seems we can\'t find what you\'re looking for.', 'eleventhset' ); ?>
			</p>
		</div>
		<?php endif; ?>
	</div>

</main>

<?php get_footer(); ?>
