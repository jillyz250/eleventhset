<?php
/**
 * Product Loop Item Template
 *
 * @package eleventhset
 */

defined( 'ABSPATH' ) || exit;

global $product;

$title    = get_the_title();
$link     = get_permalink();
$img_id   = $product->get_image_id();
$img_url  = $img_id ? wp_get_attachment_image_url( $img_id, 'eleventhset-product' ) : wc_placeholder_img_src( 'eleventhset-product' );
$gallery  = $product->get_gallery_image_ids();
$img2_url = ! empty( $gallery ) ? wp_get_attachment_image_url( $gallery[0], 'eleventhset-product' ) : null;
$is_new   = ( time() - $product->get_date_created()->getTimestamp() ) < ( 30 * 24 * 60 * 60 );
$on_sale  = $product->is_on_sale();
$in_stock = $product->is_in_stock();
?>

<div class="product-card" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">

	<!-- Image -->
	<div class="product-card__image">
		<?php if ( $on_sale ) : ?>
		<span class="product-card__badge product-card__badge--sale"><?php _e( 'Sale', 'eleventhset' ); ?></span>
		<?php elseif ( $is_new ) : ?>
		<span class="product-card__badge product-card__badge--new"><?php _e( 'New', 'eleventhset' ); ?></span>
		<?php elseif ( ! $in_stock ) : ?>
		<span class="product-card__badge" style="background:#999;"><?php _e( 'Sold Out', 'eleventhset' ); ?></span>
		<?php endif; ?>

		<a href="<?php echo esc_url( $link ); ?>">
			<img
				src="<?php echo esc_url( $img_url ); ?>"
				alt="<?php echo esc_attr( $title ); ?>"
				loading="lazy"
				width="600"
				height="800"
			>
		</a>

		<?php if ( $img2_url ) : ?>
		<div class="product-card__image-secondary">
			<img src="<?php echo esc_url( $img2_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy">
		</div>
		<?php endif; ?>

		<a href="<?php echo esc_url( $link ); ?>" class="product-card__quick-add">
			<?php _e( 'View Product', 'eleventhset' ); ?>
		</a>
	</div>

	<!-- Info -->
	<div class="product-card__info">
		<h3 class="product-card__name">
			<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $title ); ?></a>
		</h3>
		<div class="product-card__price">
			<?php echo $product->get_price_html(); ?>
		</div>

		<?php
		// Show color swatches if available
		if ( $product->is_type( 'variable' ) ) :
			$attrs = $product->get_variation_attributes();
			foreach ( $attrs as $attr_name => $options ) :
				if ( stripos( $attr_name, 'color' ) !== false || stripos( $attr_name, 'colour' ) !== false ) :
					$color_map = array(
						'black' => '#0a0a0a', 'white' => '#ffffff', 'cream' => '#f5f0e8',
						'beige' => '#c8b89a', 'tan' => '#c19a6b', 'brown' => '#795548',
						'navy' => '#1a237e', 'blue' => '#1565c0', 'green' => '#2e7d32',
						'sage' => '#8fae88', 'red' => '#c62828', 'burgundy' => '#880e4f',
						'pink' => '#f8bbd0', 'grey' => '#9e9e9e', 'gray' => '#9e9e9e',
						'charcoal' => '#424242', 'stone' => '#d6cfc4',
					);
		?>
		<div class="product-card__colors">
			<?php foreach ( array_slice( $options, 0, 5 ) as $color ) :
				$hex = $color_map[ strtolower( $color ) ] ?? '#cccccc';
			?>
			<span
				class="color-swatch"
				title="<?php echo esc_attr( $color ); ?>"
				style="background-color:<?php echo esc_attr( $hex ); ?>; <?php echo strtolower($color) === 'white' ? 'border-color:#ddd;' : ''; ?>"
			></span>
			<?php endforeach; ?>
			<?php if ( count( $options ) > 5 ) : ?>
			<span style="font-size:0.7rem; color:var(--color-text-secondary); line-height:14px; margin-left:4px;">+<?php echo count($options) - 5; ?></span>
			<?php endif; ?>
		</div>
		<?php
				endif;
				break; // Only show first color attribute
			endforeach;
		endif;
		?>
	</div>

</div>
