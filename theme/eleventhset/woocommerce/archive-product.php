<?php
/**
 * Shop Archive Template
 *
 * @package eleventhset
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<main id="primary" class="site-main">

	<!-- Shop Header -->
	<div class="woo-shop-header">
		<div class="container">
			<?php if ( is_product_category() ) : ?>
				<?php
				$cat_obj     = get_queried_object();
				$cat_thumb_id = get_term_meta( $cat_obj->term_id, 'thumbnail_id', true );
				$cat_thumb    = $cat_thumb_id ? wp_get_attachment_image_url( $cat_thumb_id, 'eleventhset-hero' ) : '';
				?>
				<?php if ( $cat_thumb ) : ?>
				<div style="height:300px; overflow:hidden; margin-bottom: var(--spacing-md); position:relative;">
					<img src="<?php echo esc_url( $cat_thumb ); ?>" alt="<?php echo esc_attr( $cat_obj->name ); ?>" style="width:100%;height:100%;object-fit:cover;">
					<div style="position:absolute;inset:0;background:rgba(0,0,0,0.35);display:flex;align-items:center;justify-content:center;">
						<h1 style="color:#fff; margin:0;"><?php echo esc_html( $cat_obj->name ); ?></h1>
					</div>
				</div>
				<?php else : ?>
				<p class="page-hero__eyebrow"><?php _e( 'Browse', 'eleventhset' ); ?></p>
				<h1><?php echo esc_html( $cat_obj->name ); ?></h1>
				<?php if ( $cat_obj->description ) : ?>
				<p class="page-hero__description"><?php echo esc_html( $cat_obj->description ); ?></p>
				<?php endif; ?>
				<?php endif; ?>

			<?php elseif ( is_search() ) : ?>
				<h1><?php printf( __( 'Search Results: "%s"', 'eleventhset' ), get_search_query() ); ?></h1>

			<?php else : ?>
				<p class="page-hero__eyebrow"><?php _e( 'The Collection', 'eleventhset' ); ?></p>
				<h1><?php woocommerce_page_title(); ?></h1>
			<?php endif; ?>
		</div>
	</div>

	<div class="container">
		<!-- Shop Toolbar -->
		<div class="shop-toolbar">
			<div class="shop-result-count">
				<?php woocommerce_result_count(); ?>
			</div>

			<!-- Category Filter -->
			<div class="category-filter" style="display:flex; gap: 8px; flex-wrap: wrap; align-items: center;">
				<?php
				$terms = get_terms( array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => true,
					'parent'     => 0,
				) );
				if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) :
					$current_cat = is_product_category() ? get_queried_object()->slug : '';
				?>
				<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"
					style="font-size:0.7rem;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;padding:6px 16px;border:1px solid<?php echo $current_cat === '' ? ' var(--color-black);background:var(--color-black);color:#fff' : ' var(--color-border)'; ?>;">
					<?php _e( 'All', 'eleventhset' ); ?>
				</a>
				<?php foreach ( $terms as $term ) :
					$is_active = $current_cat === $term->slug;
				?>
				<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"
					style="font-size:0.7rem;font-weight:600;letter-spacing:0.1em;text-transform:uppercase;padding:6px 16px;border:1px solid<?php echo $is_active ? ' var(--color-black);background:var(--color-black);color:#fff' : ' var(--color-border)'; ?>;">
					<?php echo esc_html( $term->name ); ?>
				</a>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<?php woocommerce_catalog_ordering(); ?>
		</div>

		<!-- Products Grid -->
		<?php if ( woocommerce_product_loop() ) : ?>

		<?php woocommerce_product_loop_start(); ?>

		<?php if ( wc_get_loop_prop( 'total' ) ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
		<?php wc_get_template_part( 'content', 'product' ); ?>
		<?php endwhile; ?>
		<?php endif; ?>

		<?php woocommerce_product_loop_end(); ?>

		<!-- Pagination -->
		<div class="woocommerce-pagination" style="margin: var(--spacing-xl) 0;">
			<?php woocommerce_pagination(); ?>
		</div>

		<?php else : ?>

		<div style="text-align:center; padding: var(--spacing-xl) 0;">
			<?php wc_no_products_found(); ?>
		</div>

		<?php endif; ?>

	</div>

</main>

<?php get_footer( 'shop' ); ?>
