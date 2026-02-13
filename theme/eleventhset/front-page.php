<?php
/**
 * Homepage Template
 *
 * @package eleventhset
 */

get_header();

// Customizer options
$hero_eyebrow  = get_theme_mod( 'hero_eyebrow', 'New Collection — 2024' );
$hero_heading  = get_theme_mod( 'hero_heading', "The Art\nOf Dressing" );
$hero_subtitle = get_theme_mod( 'hero_subtitle', 'Thoughtfully crafted clothing for the modern individual. Elevating everyday style through quality and intention.' );
$hero_img_id   = get_theme_mod( 'hero_image', 0 );
$hero_img_url  = $hero_img_id ? wp_get_attachment_image_url( $hero_img_id, 'eleventhset-hero' ) : '';
?>

<!-- ============================================================
     HERO SECTION
============================================================ -->
<section class="hero" id="hero">
	<div class="hero__bg">
		<?php if ( $hero_img_url ) : ?>
		<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="<?php _e( 'Eleventh Set — New Collection', 'eleventhset' ); ?>" loading="eager">
		<?php else : ?>
		<!-- Placeholder gradient when no image is set -->
		<div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a1a 0%,#3d3028 50%,#1a1a1a 100%);"></div>
		<?php endif; ?>
	</div>
	<div class="hero__overlay"></div>
	<div class="container">
		<div class="hero__content">
			<?php if ( $hero_eyebrow ) : ?>
			<p class="hero__eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></p>
			<?php endif; ?>

			<h1 class="hero__title">
				<?php
				$lines = explode( "\n", $hero_heading );
				foreach ( $lines as $i => $line ) {
					echo esc_html( trim( $line ) );
					if ( $i < count( $lines ) - 1 ) echo '<br>';
				}
				?>
			</h1>

			<?php if ( $hero_subtitle ) : ?>
			<p class="hero__subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
			<?php endif; ?>

			<div class="hero__actions">
				<a href="<?php echo esc_url( home_url( '/shop' ) ); ?>" class="btn btn--outline-white">
					<?php _e( 'Shop Collection', 'eleventhset' ); ?>
				</a>
				<a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="btn btn--outline-white">
					<?php _e( 'Our Story', 'eleventhset' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>

<!-- ============================================================
     MARQUEE / TICKER
============================================================ -->
<div class="marquee-section">
	<div class="marquee-track" aria-hidden="true">
		<?php
		$items = array(
			__( 'Free Shipping on Orders Over $150', 'eleventhset' ),
			__( 'Ethically Sourced Materials', 'eleventhset' ),
			__( 'Sustainable Fashion', 'eleventhset' ),
			__( 'New Arrivals Weekly', 'eleventhset' ),
			__( 'Free Returns Within 30 Days', 'eleventhset' ),
			__( 'Handcrafted With Care', 'eleventhset' ),
		);
		// Repeat twice for seamless loop
		for ( $i = 0; $i < 2; $i++ ) {
			foreach ( $items as $item ) {
				echo '<span class="marquee-item">' . esc_html( $item ) . '</span>';
			}
		}
		?>
	</div>
</div>

<!-- ============================================================
     INTRO SECTION
============================================================ -->
<section class="home-intro section">
	<div class="container">
		<p class="home-intro__label"><?php _e( 'Eleventh Set', 'eleventhset' ); ?></p>
		<h2 class="home-intro__heading">
			<?php _e( 'Clothing That Moves With You', 'eleventhset' ); ?>
		</h2>
		<p class="home-intro__text">
			<?php _e( 'We believe in the quiet luxury of well-made things. Each piece in our collection is designed with intention—using responsibly sourced fabrics and timeless silhouettes that transcend seasons.', 'eleventhset' ); ?>
		</p>
		<a href="<?php echo esc_url( home_url( '/about' ) ); ?>" class="btn btn--outline">
			<?php _e( 'Discover Our Story', 'eleventhset' ); ?>
		</a>
	</div>
</section>

<!-- ============================================================
     FEATURED PRODUCTS
============================================================ -->
<section class="section section--sm" id="featured-products">
	<div class="container">
		<div class="section-header">
			<p class="section-header__label"><?php _e( 'This Season', 'eleventhset' ); ?></p>
			<h2 class="section-header__title"><?php _e( 'New Arrivals', 'eleventhset' ); ?></h2>
			<p class="section-header__subtitle">
				<?php _e( 'Fresh pieces thoughtfully designed for the modern wardrobe.', 'eleventhset' ); ?>
			</p>
		</div>

		<?php
		if ( function_exists( 'wc_get_products' ) ) :
			$products = wc_get_products( array(
				'status'  => 'publish',
				'limit'   => 8,
				'orderby' => 'date',
				'order'   => 'DESC',
			) );

			if ( ! empty( $products ) ) :
		?>
		<div class="products-grid">
			<?php foreach ( $products as $product ) : ?>
			<div class="product-card">
				<div class="product-card__image">
					<?php
					$is_new  = ( time() - $product->get_date_created()->getTimestamp() ) < ( 30 * 24 * 60 * 60 );
					$on_sale = $product->is_on_sale();
					if ( $on_sale ) :
					?>
					<span class="product-card__badge product-card__badge--sale"><?php _e( 'Sale', 'eleventhset' ); ?></span>
					<?php elseif ( $is_new ) : ?>
					<span class="product-card__badge product-card__badge--new"><?php _e( 'New', 'eleventhset' ); ?></span>
					<?php endif; ?>
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
						<?php
						$img_id  = $product->get_image_id();
						$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'eleventhset-product' ) : wc_placeholder_img_src();
						?>
						<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" loading="lazy">
					</a>
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="product-card__quick-add">
						<?php _e( 'View Product', 'eleventhset' ); ?>
					</a>
				</div>
				<div class="product-card__info">
					<h3 class="product-card__name">
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
					</h3>
					<div class="product-card__price"><?php echo $product->get_price_html(); ?></div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
			else :
		?>
		<!-- Placeholder product cards when no products exist -->
		<div class="products-grid">
			<?php for ( $i = 1; $i <= 8; $i++ ) : ?>
			<div class="product-card">
				<div class="product-card__image" style="background-color: #e8e6e1; aspect-ratio: 3/4; display:flex; align-items:center; justify-content:center;">
					<span style="color:#999; font-size:0.8rem;"><?php _e( 'Product Image', 'eleventhset' ); ?></span>
				</div>
				<div class="product-card__info">
					<h3 class="product-card__name"><?php printf( __( 'Product Name %d', 'eleventhset' ), $i ); ?></h3>
					<div class="product-card__price">$89.00</div>
				</div>
			</div>
			<?php endfor; ?>
		</div>
		<?php
			endif;
		else :
		?>
		<p style="text-align:center; color: #999;"><?php _e( 'Install WooCommerce to display products here.', 'eleventhset' ); ?></p>
		<?php endif; ?>

		<div style="text-align:center; margin-top: var(--spacing-lg);">
			<a href="<?php echo esc_url( home_url( '/shop' ) ); ?>" class="btn btn--primary">
				<?php _e( 'View All Products', 'eleventhset' ); ?>
			</a>
		</div>
	</div>
</section>

<!-- ============================================================
     SPLIT SECTION — LOOKBOOK / COLLECTION FEATURE
============================================================ -->
<section class="split-section">
	<div class="split-section__image">
		<img src="<?php echo esc_url( ELEVENTHSET_URI . '/assets/images/collection-women.jpg' ); ?>" alt="<?php _e( 'Women\'s Collection', 'eleventhset' ); ?>" loading="lazy">
	</div>
	<div class="split-section__content">
		<p class="split-section__eyebrow"><?php _e( 'Women\'s Collection', 'eleventhset' ); ?></p>
		<h2 class="split-section__title">
			<?php _e( 'Effortless Femininity', 'eleventhset' ); ?>
		</h2>
		<p class="split-section__text">
			<?php _e( 'Flowing silhouettes, tactile fabrics, and an understated palette — our women\'s collection celebrates quiet elegance. Designed for the woman who moves through the world with intention.', 'eleventhset' ); ?>
		</p>
		<a href="<?php echo esc_url( home_url( '/product-category/women' ) ); ?>" class="btn btn--primary">
			<?php _e( 'Shop Women', 'eleventhset' ); ?>
		</a>
	</div>
</section>

<section class="split-section split-section--reverse">
	<div class="split-section__image">
		<img src="<?php echo esc_url( ELEVENTHSET_URI . '/assets/images/collection-men.jpg' ); ?>" alt="<?php _e( 'Men\'s Collection', 'eleventhset' ); ?>" loading="lazy">
	</div>
	<div class="split-section__content">
		<p class="split-section__eyebrow"><?php _e( 'Men\'s Collection', 'eleventhset' ); ?></p>
		<h2 class="split-section__title">
			<?php _e( 'Refined Simplicity', 'eleventhset' ); ?>
		</h2>
		<p class="split-section__text">
			<?php _e( 'Clean lines and considered details define our men\'s range. Each piece is built to last—versatile enough to move from desk to dinner with ease.', 'eleventhset' ); ?>
		</p>
		<a href="<?php echo esc_url( home_url( '/product-category/men' ) ); ?>" class="btn btn--primary">
			<?php _e( 'Shop Men', 'eleventhset' ); ?>
		</a>
	</div>
</section>

<!-- ============================================================
     QUOTE / BRAND STATEMENT
============================================================ -->
<section class="quote-section">
	<div class="container">
		<div class="quote-section__content">
			<div class="quote-section__mark" aria-hidden="true">&ldquo;</div>
			<p class="quote-section__text">
				<?php _e( 'Fashion is about something that comes from within you.', 'eleventhset' ); ?>
			</p>
			<span class="quote-section__author"><?php _e( 'The Eleventh Set Philosophy', 'eleventhset' ); ?></span>
		</div>
	</div>
</section>

<!-- ============================================================
     VALUES / FEATURES SECTION
============================================================ -->
<section class="section" style="background-color: var(--color-off-white);">
	<div class="container">
		<div class="section-header">
			<p class="section-header__label"><?php _e( 'Why Eleventh Set', 'eleventhset' ); ?></p>
			<h2 class="section-header__title"><?php _e( 'Crafted With Purpose', 'eleventhset' ); ?></h2>
		</div>
		<div class="values-grid">
			<div class="value-card">
				<div class="value-card__icon">
					<?php echo eleventhset_icon( 'leaf' ); ?>
				</div>
				<h3 class="value-card__title"><?php _e( 'Sustainably Made', 'eleventhset' ); ?></h3>
				<p class="value-card__text">
					<?php _e( 'Every fabric is carefully selected for its environmental footprint. We work only with certified sustainable and ethical suppliers.', 'eleventhset' ); ?>
				</p>
			</div>
			<div class="value-card">
				<div class="value-card__icon">
					<?php echo eleventhset_icon( 'star' ); ?>
				</div>
				<h3 class="value-card__title"><?php _e( 'Exceptional Quality', 'eleventhset' ); ?></h3>
				<p class="value-card__text">
					<?php _e( 'Each garment is constructed to withstand the test of time, with reinforced seams, high-quality hardware, and meticulous finishing.', 'eleventhset' ); ?>
				</p>
			</div>
			<div class="value-card">
				<div class="value-card__icon">
					<?php echo eleventhset_icon( 'truck' ); ?>
				</div>
				<h3 class="value-card__title"><?php _e( 'Free Shipping & Returns', 'eleventhset' ); ?></h3>
				<p class="value-card__text">
					<?php _e( 'Complimentary shipping on all orders over $150. Free, easy returns within 30 days—no questions asked.', 'eleventhset' ); ?>
				</p>
			</div>
		</div>
	</div>
</section>

<!-- ============================================================
     NEWSLETTER
============================================================ -->
<section class="newsletter-section">
	<div class="container">
		<p class="newsletter-section__label"><?php _e( 'Stay In The Loop', 'eleventhset' ); ?></p>
		<h2 class="newsletter-section__title"><?php _e( 'Join The Eleventh Set', 'eleventhset' ); ?></h2>
		<p class="newsletter-section__subtitle">
			<?php _e( 'Be the first to know about new arrivals, exclusive offers, and the stories behind our collections.', 'eleventhset' ); ?>
		</p>
		<form class="newsletter-form js-newsletter-form" action="#" method="post">
			<?php wp_nonce_field( 'eleventhset-newsletter', 'newsletter_nonce' ); ?>
			<input type="email" name="email" placeholder="<?php esc_attr_e( 'Your email address', 'eleventhset' ); ?>" required>
			<button type="submit"><?php _e( 'Subscribe', 'eleventhset' ); ?></button>
		</form>
	</div>
</section>

<?php get_footer(); ?>
