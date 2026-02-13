<?php
/**
 * Single Product Content Template
 *
 * @package eleventhset
 */

defined( 'ABSPATH' ) || exit;

global $product;
?>

<main id="primary" class="site-main">

	<div class="container">

		<!-- Breadcrumbs -->
		<div class="breadcrumbs" style="padding: calc(var(--nav-height) + 1rem) 0 0;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'eleventhset' ); ?></a>
			<span class="separator">/</span>
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php _e( 'Shop', 'eleventhset' ); ?></a>

			<?php
			$terms = get_the_terms( $product->get_id(), 'product_cat' );
			if ( $terms && ! is_wp_error( $terms ) ) :
				$term = reset( $terms );
			?>
			<span class="separator">/</span>
			<a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a>
			<?php endif; ?>

			<span class="separator">/</span>
			<span><?php the_title(); ?></span>
		</div>

		<!-- Product Layout -->
		<div class="single-product-layout">

			<!-- ============================================================
			     PRODUCT GALLERY
			============================================================ -->
			<div class="product-gallery" id="product-gallery">
				<?php
				$main_image_id  = $product->get_image_id();
				$gallery_ids    = $product->get_gallery_image_ids();
				$all_image_ids  = array_merge(
					$main_image_id ? array( $main_image_id ) : array(),
					$gallery_ids
				);
				$main_img_url   = $main_image_id
					? wp_get_attachment_image_url( $main_image_id, 'eleventhset-product' )
					: wc_placeholder_img_src( 'eleventhset-product' );
				$main_img_full  = $main_image_id
					? wp_get_attachment_image_url( $main_image_id, 'full' )
					: wc_placeholder_img_src( 'full' );
				?>

				<!-- Main Image -->
				<div class="product-gallery__main" id="gallery-main">
					<a href="<?php echo esc_url( $main_img_full ); ?>" data-lightbox="product" aria-label="<?php _e( 'View full size', 'eleventhset' ); ?>">
						<img
							id="gallery-main-img"
							src="<?php echo esc_url( $main_img_url ); ?>"
							alt="<?php echo esc_attr( get_the_title() ); ?>"
							loading="eager"
						>
					</a>
				</div>

				<!-- Thumbnails -->
				<?php if ( count( $all_image_ids ) > 1 ) : ?>
				<div class="product-gallery__thumbs" id="gallery-thumbs">
					<?php foreach ( $all_image_ids as $index => $img_id ) :
						$thumb_url = wp_get_attachment_image_url( $img_id, 'eleventhset-product-sm' );
						$full_url  = wp_get_attachment_image_url( $img_id, 'eleventhset-product' );
						$full_src  = wp_get_attachment_image_url( $img_id, 'full' );
					?>
					<div
						class="product-gallery__thumb <?php echo $index === 0 ? 'active' : ''; ?> js-gallery-thumb"
						data-full="<?php echo esc_url( $full_url ); ?>"
						data-src="<?php echo esc_url( $full_src ); ?>"
					>
						<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php printf( __( 'Product image %d', 'eleventhset' ), $index + 1 ); ?>" loading="lazy">
					</div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>

			<!-- ============================================================
			     PRODUCT DETAILS
			============================================================ -->
			<div class="product-details">
				<?php
				$categories = wc_get_product_category_list( $product->get_id(), ', ' );
				if ( $categories ) :
				?>
				<p class="product-details__category"><?php echo wp_strip_all_tags( $categories ); ?></p>
				<?php endif; ?>

				<h1 class="product-details__title"><?php the_title(); ?></h1>

				<div class="product-details__price">
					<?php echo $product->get_price_html(); ?>
				</div>

				<div class="product-details__description">
					<?php echo $product->get_short_description() ?: $product->get_description(); ?>
				</div>

				<?php
				// ============================================================
				// VARIATION FORM (for variable products)
				// ============================================================
				if ( $product->is_type( 'variable' ) ) :
					$variations   = $product->get_available_variations();
					$attributes   = $product->get_variation_attributes();
				?>

				<form class="variations_form cart js-variation-form" method="post" enctype="multipart/form-data"
					data-product_id="<?php echo absint( $product->get_id() ); ?>"
					data-product_variations="<?php echo esc_attr( wp_json_encode( $variations ) ); ?>">

					<?php foreach ( $attributes as $attribute_name => $options ) :
						$attr_label = wc_attribute_label( $attribute_name );
						$attr_slug  = sanitize_title( $attribute_name );
						$is_color   = stripos( $attribute_name, 'color' ) !== false || stripos( $attribute_name, 'colour' ) !== false;
						$is_size    = stripos( $attribute_name, 'size' ) !== false;
					?>

					<div class="product-option">
						<div class="product-option__label">
							<?php echo esc_html( $attr_label ); ?>:
							<span class="product-option__selected js-selected-<?php echo esc_attr( $attr_slug ); ?>"></span>
						</div>

						<?php if ( $is_color ) : ?>
						<!-- Color Swatches -->
						<div class="color-swatches" role="radiogroup" aria-label="<?php echo esc_attr( $attr_label ); ?>">
							<?php
							$color_map = array(
								'black'       => '#0a0a0a',
								'white'       => '#ffffff',
								'cream'       => '#f5f0e8',
								'beige'       => '#c8b89a',
								'tan'         => '#c19a6b',
								'brown'       => '#795548',
								'camel'       => '#c19a6b',
								'navy'        => '#1a237e',
								'blue'        => '#1565c0',
								'light blue'  => '#90caf9',
								'green'       => '#2e7d32',
								'sage'        => '#8fae88',
								'olive'       => '#808000',
								'red'         => '#c62828',
								'burgundy'    => '#880e4f',
								'pink'        => '#f8bbd0',
								'blush'       => '#f9a8c9',
								'yellow'      => '#f9a825',
								'mustard'     => '#f59e0b',
								'orange'      => '#ef6c00',
								'grey'        => '#9e9e9e',
								'gray'        => '#9e9e9e',
								'light grey'  => '#e0e0e0',
								'charcoal'    => '#424242',
								'stone'       => '#d6cfc4',
								'ivory'       => '#fffff0',
								'ecru'        => '#c2b280',
							);
							foreach ( $options as $option ) :
								$hex   = $color_map[ strtolower( $option ) ] ?? '#cccccc';
								$style = 'background-color:' . esc_attr( $hex ) . ';';
								if ( strtolower( $option ) === 'white' ) {
									$style .= 'border-color:#ddd;';
								}
							?>
							<button
								type="button"
								class="color-swatch-btn js-color-swatch"
								style="<?php echo esc_attr( $style ); ?>"
								title="<?php echo esc_attr( $option ); ?>"
								data-attr="<?php echo esc_attr( $attribute_name ); ?>"
								data-value="<?php echo esc_attr( $option ); ?>"
								role="radio"
								aria-checked="false"
								aria-label="<?php echo esc_attr( $option ); ?>"
							></button>
							<?php endforeach; ?>
						</div>

						<?php elseif ( $is_size ) : ?>
						<!-- Size Buttons -->
						<div class="size-buttons" role="radiogroup" aria-label="<?php echo esc_attr( $attr_label ); ?>">
							<?php foreach ( $options as $option ) : ?>
							<button
								type="button"
								class="size-btn js-size-btn"
								data-attr="<?php echo esc_attr( $attribute_name ); ?>"
								data-value="<?php echo esc_attr( $option ); ?>"
								role="radio"
								aria-checked="false"
								aria-label="<?php echo esc_attr( $option ); ?>"
							><?php echo esc_html( $option ); ?></button>
							<?php endforeach; ?>
						</div>

						<?php else : ?>
						<!-- Default Select -->
						<select
							name="attribute_<?php echo esc_attr( $attr_slug ); ?>"
							class="form-control js-variation-select"
							data-attr="<?php echo esc_attr( $attribute_name ); ?>"
						>
							<option value=""><?php printf( __( 'Choose %s', 'eleventhset' ), $attr_label ); ?></option>
							<?php foreach ( $options as $option ) : ?>
							<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
							<?php endforeach; ?>
						</select>
						<?php endif; ?>

						<!-- Hidden input for form submission -->
						<input
							type="hidden"
							name="attribute_<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"
							class="js-attr-input"
							data-attr="<?php echo esc_attr( $attribute_name ); ?>"
							value=""
						>
					</div>
					<?php endforeach; ?>

					<!-- Hidden variation ID -->
					<input type="hidden" name="variation_id" class="variation_id js-variation-id" value="0">

					<!-- Stock notice -->
					<div class="js-stock-notice" style="font-size:0.875rem; color:var(--color-error); margin-bottom:var(--spacing-sm); display:none;">
						<?php _e( 'This variation is currently out of stock.', 'eleventhset' ); ?>
					</div>

					<!-- Add to Cart -->
					<div class="product-add-to-cart">
						<div class="quantity-selector">
							<button type="button" class="quantity-btn js-qty-minus" aria-label="<?php esc_attr_e( 'Decrease quantity', 'eleventhset' ); ?>">&#x2212;</button>
							<input
								type="number"
								name="quantity"
								class="quantity-input js-qty-input"
								value="1"
								min="1"
								max="<?php echo $product->get_stock_quantity() ?: 99; ?>"
								step="1"
								aria-label="<?php esc_attr_e( 'Quantity', 'eleventhset' ); ?>"
							>
							<button type="button" class="quantity-btn js-qty-plus" aria-label="<?php esc_attr_e( 'Increase quantity', 'eleventhset' ); ?>">&#x2b;</button>
						</div>

						<button
							type="submit"
							name="add-to-cart"
							value="<?php echo esc_attr( $product->get_id() ); ?>"
							class="add-to-cart-btn js-add-to-cart single_add_to_cart_button"
						>
							<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
						</button>

						<button type="button" class="wishlist-btn">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
								<path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
							</svg>
							<?php _e( 'Add to Wishlist', 'eleventhset' ); ?>
						</button>
					</div>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</form>

				<?php
				// Simple product add to cart
				elseif ( $product->is_type( 'simple' ) ) :
				?>

				<div class="product-add-to-cart">
					<div class="quantity-selector">
						<button type="button" class="quantity-btn js-qty-minus" aria-label="<?php esc_attr_e( 'Decrease', 'eleventhset' ); ?>">&#x2212;</button>
						<input
							type="number"
							class="quantity-input js-qty-input"
							value="1" min="1"
							max="<?php echo $product->get_stock_quantity() ?: 99; ?>"
							aria-label="<?php esc_attr_e( 'Quantity', 'eleventhset' ); ?>"
						>
						<button type="button" class="quantity-btn js-qty-plus" aria-label="<?php esc_attr_e( 'Increase', 'eleventhset' ); ?>">&#x2b;</button>
					</div>

					<button
						class="add-to-cart-btn js-simple-add-to-cart"
						data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
					>
						<?php echo esc_html( $product->single_add_to_cart_text() ); ?>
					</button>

					<button type="button" class="wishlist-btn">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
							<path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
						</svg>
						<?php _e( 'Add to Wishlist', 'eleventhset' ); ?>
					</button>
				</div>

				<?php endif; ?>

				<!-- Product Accordion -->
				<div class="product-accordion">
					<div class="accordion-item">
						<div class="accordion-header js-accordion-toggle">
							<?php _e( 'Description', 'eleventhset' ); ?>
							<span class="accordion-icon"></span>
						</div>
						<div class="accordion-content">
							<div class="accordion-content-inner">
								<?php echo $product->get_description() ?: __( 'No description available.', 'eleventhset' ); ?>
							</div>
						</div>
					</div>

					<div class="accordion-item">
						<div class="accordion-header js-accordion-toggle">
							<?php _e( 'Size & Fit', 'eleventhset' ); ?>
							<span class="accordion-icon"></span>
						</div>
						<div class="accordion-content">
							<div class="accordion-content-inner">
								<p><?php _e( 'Model is 5\'9" and wearing a size S/M. This piece runs true to size.', 'eleventhset' ); ?></p>
								<p>
									<strong><?php _e( 'Size Guide:', 'eleventhset' ); ?></strong><br>
									XS: UK 6–8 | S: UK 8–10 | M: UK 10–12 | L: UK 12–14 | XL: UK 14–16
								</p>
								<a href="<?php echo esc_url( home_url( '/size-guide' ) ); ?>" style="text-decoration:underline; font-size:0.875rem;">
									<?php _e( 'View Full Size Guide', 'eleventhset' ); ?>
								</a>
							</div>
						</div>
					</div>

					<div class="accordion-item">
						<div class="accordion-header js-accordion-toggle">
							<?php _e( 'Materials & Care', 'eleventhset' ); ?>
							<span class="accordion-icon"></span>
						</div>
						<div class="accordion-content">
							<div class="accordion-content-inner">
								<?php
								$materials = get_post_meta( $product->get_id(), '_materials', true );
								if ( $materials ) {
									echo wpautop( esc_html( $materials ) );
								} else {
									echo '<p>' . __( 'Please see the product label for material composition and care instructions.', 'eleventhset' ) . '</p>';
									echo '<ul style="list-style:disc; padding-left:1.5rem; margin-top:0.5rem;">';
									echo '<li>' . __( 'Machine wash cold, gentle cycle', 'eleventhset' ) . '</li>';
									echo '<li>' . __( 'Do not bleach', 'eleventhset' ) . '</li>';
									echo '<li>' . __( 'Lay flat to dry or tumble dry low', 'eleventhset' ) . '</li>';
									echo '<li>' . __( 'Iron on low heat if needed', 'eleventhset' ) . '</li>';
									echo '</ul>';
								}
								?>
							</div>
						</div>
					</div>

					<div class="accordion-item">
						<div class="accordion-header js-accordion-toggle">
							<?php _e( 'Shipping & Returns', 'eleventhset' ); ?>
							<span class="accordion-icon"></span>
						</div>
						<div class="accordion-content">
							<div class="accordion-content-inner">
								<ul style="list-style:disc; padding-left:1.5rem;">
									<li><?php _e( 'Free standard shipping on orders over $150', 'eleventhset' ); ?></li>
									<li><?php _e( 'Standard delivery: 3–5 business days', 'eleventhset' ); ?></li>
									<li><?php _e( 'Express delivery: 1–2 business days', 'eleventhset' ); ?></li>
									<li><?php _e( 'Free returns within 30 days of purchase', 'eleventhset' ); ?></li>
								</ul>
								<a href="<?php echo esc_url( home_url( '/shipping' ) ); ?>" style="text-decoration:underline; font-size:0.875rem; display:inline-block; margin-top:0.75rem;">
									<?php _e( 'Full Shipping & Returns Policy', 'eleventhset' ); ?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<!-- Trust Badges -->
				<div style="display:grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-top: var(--spacing-md); padding-top: var(--spacing-md); border-top: 1px solid var(--color-border); text-align:center;">
					<div style="font-size:0.7rem; color:var(--color-text-secondary); line-height:1.5;">
						<span style="display:block; font-size:1.2rem; margin-bottom:4px;">🌿</span>
						<?php _e( 'Sustainably Sourced', 'eleventhset' ); ?>
					</div>
					<div style="font-size:0.7rem; color:var(--color-text-secondary); line-height:1.5;">
						<span style="display:block; font-size:1.2rem; margin-bottom:4px;">📦</span>
						<?php _e( 'Free Returns', 'eleventhset' ); ?>
					</div>
					<div style="font-size:0.7rem; color:var(--color-text-secondary); line-height:1.5;">
						<span style="display:block; font-size:1.2rem; margin-bottom:4px;">🔒</span>
						<?php _e( 'Secure Checkout', 'eleventhset' ); ?>
					</div>
				</div>

			</div><!-- .product-details -->
		</div><!-- .single-product-layout -->

		<!-- Related Products -->
		<?php
		$related_ids = wc_get_related_products( $product->get_id(), 4 );
		if ( ! empty( $related_ids ) ) :
			$related_products = array_filter( array_map( 'wc_get_product', $related_ids ) );
		?>
		<section class="section section--sm" style="border-top: 1px solid var(--color-border);">
			<div class="section-header">
				<p class="section-header__label"><?php _e( 'You May Also Like', 'eleventhset' ); ?></p>
				<h2 class="section-header__title"><?php _e( 'Related Products', 'eleventhset' ); ?></h2>
			</div>
			<div class="products-grid products-grid--3col" style="grid-template-columns: repeat(4, 1fr);">
				<?php foreach ( $related_products as $related ) :
					$r_title  = $related->get_name();
					$r_link   = $related->get_permalink();
					$r_img_id = $related->get_image_id();
					$r_img    = $r_img_id ? wp_get_attachment_image_url( $r_img_id, 'eleventhset-product' ) : wc_placeholder_img_src();
				?>
				<div class="product-card">
					<div class="product-card__image">
						<a href="<?php echo esc_url( $r_link ); ?>">
							<img src="<?php echo esc_url( $r_img ); ?>" alt="<?php echo esc_attr( $r_title ); ?>" loading="lazy">
						</a>
						<a href="<?php echo esc_url( $r_link ); ?>" class="product-card__quick-add"><?php _e( 'View Product', 'eleventhset' ); ?></a>
					</div>
					<div class="product-card__info">
						<h3 class="product-card__name"><a href="<?php echo esc_url( $r_link ); ?>"><?php echo esc_html( $r_title ); ?></a></h3>
						<div class="product-card__price"><?php echo $related->get_price_html(); ?></div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</section>
		<?php endif; ?>

	</div><!-- .container -->

</main>
