<?php
/**
 * Default page template
 *
 * @package eleventhset
 */

get_header();
?>

<main id="primary" class="site-main">

	<?php while ( have_posts() ) : the_post(); ?>

	<!-- Page Hero -->
	<div class="page-hero" style="padding-top: calc(var(--nav-height) + var(--spacing-xl));">
		<div class="container">
			<h1 class="page-hero__title"><?php the_title(); ?></h1>
		</div>
	</div>

	<!-- Page Content -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="container" style="padding-top: var(--spacing-xl); padding-bottom: var(--spacing-xl);">
			<div class="container--narrow" style="margin: 0 auto;">
				<div class="entry-content" style="line-height:1.9; color: var(--color-text-secondary);">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</article>

	<?php endwhile; ?>

</main>

<?php get_footer(); ?>
