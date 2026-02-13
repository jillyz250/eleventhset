<?php
/**
 * Template Name: About Page
 *
 * @package eleventhset
 */

get_header();
?>

<main id="primary" class="site-main">

	<!-- Page Hero -->
	<div class="page-hero" style="padding-top: calc(var(--nav-height) + var(--spacing-xl));">
		<div class="container">
			<p class="page-hero__eyebrow"><?php _e( 'Who We Are', 'eleventhset' ); ?></p>
			<h1 class="page-hero__title"><?php _e( 'Our Story', 'eleventhset' ); ?></h1>
			<p class="page-hero__description">
				<?php _e( 'Born from a love of quality, intentionality, and the quiet power of getting dressed.', 'eleventhset' ); ?>
			</p>
		</div>
	</div>

	<!-- Story Section -->
	<section class="section">
		<div class="container">
			<div class="about-story">
				<div class="about-story__images">
					<div class="about-story__image-main">
						<img src="<?php echo esc_url( ELEVENTHSET_URI . '/assets/images/about-studio.jpg' ); ?>" alt="<?php _e( 'Eleventh Set Studio', 'eleventhset' ); ?>" loading="lazy">
					</div>
					<div class="about-story__image-secondary">
						<img src="<?php echo esc_url( ELEVENTHSET_URI . '/assets/images/about-craft.jpg' ); ?>" alt="<?php _e( 'Craftsmanship', 'eleventhset' ); ?>" loading="lazy">
					</div>
					<div class="about-story__image-secondary">
						<img src="<?php echo esc_url( ELEVENTHSET_URI . '/assets/images/about-fabric.jpg' ); ?>" alt="<?php _e( 'Fabrics', 'eleventhset' ); ?>" loading="lazy">
					</div>
				</div>

				<div class="about-story__content">
					<p class="about-story__label"><?php _e( 'The Beginning', 'eleventhset' ); ?></p>
					<h2 class="about-story__title">
						<?php _e( 'Clothes That Mean Something', 'eleventhset' ); ?>
					</h2>
					<p class="about-story__text">
						<?php _e( 'Eleventh Set was founded on a simple belief: that the clothes we wear should be worthy of our lives. Not fast, not disposable—but considered, crafted, and lasting.', 'eleventhset' ); ?>
					</p>
					<p class="about-story__text">
						<?php _e( 'We started small—a design studio, a handful of trusted makers, and an obsession with getting the details right. Each collection is a careful study in restraint: fewer pieces, more intention.', 'eleventhset' ); ?>
					</p>
					<p class="about-story__text">
						<?php _e( 'The name comes from an idea: that the eleventh set—the one you keep reaching for—should be the best one. The one that fits like it was made for you, because in a way, it was.', 'eleventhset' ); ?>
					</p>
					<a href="<?php echo esc_url( home_url( '/shop' ) ); ?>" class="btn btn--primary" style="margin-top: var(--spacing-sm);">
						<?php _e( 'Explore the Collection', 'eleventhset' ); ?>
					</a>
				</div>
			</div>
		</div>
	</section>

	<!-- Values Section -->
	<section class="about-values">
		<div class="container">
			<div class="section-header">
				<p class="section-header__label"><?php _e( 'What We Stand For', 'eleventhset' ); ?></p>
				<h2 class="section-header__title"><?php _e( 'Our Values', 'eleventhset' ); ?></h2>
			</div>
			<div class="values-grid">
				<div class="value-card">
					<div class="value-card__icon">
						<?php echo eleventhset_icon( 'leaf' ); ?>
					</div>
					<h3 class="value-card__title"><?php _e( 'Sustainability', 'eleventhset' ); ?></h3>
					<p class="value-card__text">
						<?php _e( 'We measure success not just in sales, but in the footprint we leave. Every decision—from fabric sourcing to packaging—is made with the planet in mind.', 'eleventhset' ); ?>
					</p>
				</div>
				<div class="value-card">
					<div class="value-card__icon">
						<?php echo eleventhset_icon( 'star' ); ?>
					</div>
					<h3 class="value-card__title"><?php _e( 'Craftsmanship', 'eleventhset' ); ?></h3>
					<p class="value-card__text">
						<?php _e( 'We partner with skilled artisans and factories who share our commitment to excellence. Every stitch, seam, and silhouette is intentional.', 'eleventhset' ); ?>
					</p>
				</div>
				<div class="value-card">
					<div class="value-card__icon">
						<?php echo eleventhset_icon( 'heart' ); ?>
					</div>
					<h3 class="value-card__title"><?php _e( 'Inclusivity', 'eleventhset' ); ?></h3>
					<p class="value-card__text">
						<?php _e( 'Style has no size. Our collections are designed to be worn by everyone, with extended sizing available across all core pieces.', 'eleventhset' ); ?>
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Process Section -->
	<section class="section">
		<div class="container">
			<div class="about-story" style="grid-template-columns: 1.3fr 1fr;">
				<div class="about-story__content">
					<p class="about-story__label"><?php _e( 'How We Work', 'eleventhset' ); ?></p>
					<h2 class="about-story__title">
						<?php _e( 'From Sketch To Your Wardrobe', 'eleventhset' ); ?>
					</h2>
					<p class="about-story__text">
						<?php _e( 'Each collection begins with months of research—studying silhouettes, testing fabrics, and refining fits across diverse body types. We reject seasonal pressure in favor of thoughtful iteration.', 'eleventhset' ); ?>
					</p>
					<p class="about-story__text">
						<?php _e( 'Our production partners are carefully vetted for fair labor practices and environmental standards. We visit every facility we work with, because accountability starts with showing up.', 'eleventhset' ); ?>
					</p>
					<p class="about-story__text">
						<?php _e( 'The result? Clothes you\'ll wear season after season—that get better with time, just like the relationships they\'re built for.', 'eleventhset' ); ?>
					</p>
				</div>
				<div style="display: flex; align-items: center; justify-content: center;">
					<img src="<?php echo esc_url( ELEVENTHSET_URI . '/assets/images/about-process.jpg' ); ?>" alt="<?php _e( 'Design Process', 'eleventhset' ); ?>" loading="lazy" style="width:100%; height: 500px; object-fit: cover;">
				</div>
			</div>
		</div>
	</section>

	<!-- CTA Section -->
	<section class="newsletter-section">
		<div class="container">
			<p class="newsletter-section__label"><?php _e( 'The Collection', 'eleventhset' ); ?></p>
			<h2 class="newsletter-section__title"><?php _e( 'Wear The Difference', 'eleventhset' ); ?></h2>
			<p class="newsletter-section__subtitle">
				<?php _e( 'Discover pieces designed to last. Explore our current collection and find your eleventh set.', 'eleventhset' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/shop' ) ); ?>" class="btn btn--outline-white" style="margin-top: var(--spacing-sm);">
				<?php _e( 'Shop Now', 'eleventhset' ); ?>
			</a>
		</div>
	</section>

</main>

<?php get_footer(); ?>
