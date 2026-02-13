<?php
/**
 * Eleventh Set Theme Functions
 *
 * @package eleventhset
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ELEVENTHSET_VERSION', '1.0.0' );
define( 'ELEVENTHSET_DIR', get_template_directory() );
define( 'ELEVENTHSET_URI', get_template_directory_uri() );

// Load includes
require_once ELEVENTHSET_DIR . '/inc/setup-wizard.php';

/* =============================================================================
   THEME SETUP
============================================================================= */

function eleventhset_setup() {
	load_theme_textdomain( 'eleventhset', ELEVENTHSET_DIR . '/languages' );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
	) );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 280,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'custom-background', array(
		'default-color' => 'ffffff',
	) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );

	// WooCommerce support
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 600,
		'single_image_width'    => 900,
		'product_grid'          => array(
			'default_rows'    => 3,
			'min_rows'        => 1,
			'default_columns' => 4,
			'min_columns'     => 1,
			'max_columns'     => 6,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Image sizes
	add_image_size( 'eleventhset-hero',       1920, 1080, true );
	add_image_size( 'eleventhset-product',    600,  800,  true );
	add_image_size( 'eleventhset-product-sm', 400,  533,  true );
	add_image_size( 'eleventhset-square',     600,  600,  true );
	add_image_size( 'eleventhset-wide',       1200, 600,  true );

	// Navigation menus
	register_nav_menus( array(
		'primary'     => __( 'Primary Menu', 'eleventhset' ),
		'footer-1'    => __( 'Footer: Shop Links', 'eleventhset' ),
		'footer-2'    => __( 'Footer: Company Links', 'eleventhset' ),
		'footer-3'    => __( 'Footer: Help Links', 'eleventhset' ),
	) );
}
add_action( 'after_setup_theme', 'eleventhset_setup' );

/* =============================================================================
   ENQUEUE SCRIPTS & STYLES
============================================================================= */

function eleventhset_enqueue_scripts() {
	// Google Fonts
	wp_enqueue_style(
		'eleventhset-google-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap',
		array(),
		null
	);

	// Main stylesheet
	wp_enqueue_style(
		'eleventhset-style',
		get_stylesheet_uri(),
		array( 'eleventhset-google-fonts' ),
		ELEVENTHSET_VERSION
	);

	// Main JS
	wp_enqueue_script(
		'eleventhset-main',
		ELEVENTHSET_URI . '/assets/js/main.js',
		array( 'jquery' ),
		ELEVENTHSET_VERSION,
		true
	);

	// Localize script
	wp_localize_script( 'eleventhset-main', 'eleventhsetData', array(
		'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
		'nonce'    => wp_create_nonce( 'eleventhset-nonce' ),
		'cartUrl'  => wc_get_cart_url(),
		'currency' => get_woocommerce_currency_symbol(),
	) );

	// Comments
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'eleventhset_enqueue_scripts' );

/* =============================================================================
   WIDGETS
============================================================================= */

function eleventhset_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Shop Sidebar', 'eleventhset' ),
		'id'            => 'shop-sidebar',
		'description'   => __( 'Widgets for the WooCommerce shop sidebar.', 'eleventhset' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => __( 'Footer Widget Area', 'eleventhset' ),
		'id'            => 'footer-widgets',
		'description'   => __( 'Footer widget area.', 'eleventhset' ),
		'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="footer-widget__title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'eleventhset_widgets_init' );

/* =============================================================================
   WOOCOMMERCE CUSTOMIZATIONS
============================================================================= */

// Remove default WooCommerce styles (we use our own)
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Re-add WooCommerce main stylesheet selectively
function eleventhset_woo_styles( $styles ) {
	$styles['woocommerce-layout']    = array(
		'src'     => plugins_url( 'assets/css/woocommerce-layout.css', WC_PLUGIN_FILE ),
		'deps'    => '',
		'version' => WC()->version,
		'media'   => 'all',
		'has_rtl' => true,
	);
	return $styles;
}
// Uncommenting the following line re-adds the WooCommerce layout CSS:
// add_filter( 'woocommerce_enqueue_styles', 'eleventhset_woo_styles' );

// Remove woocommerce sidebar on shop/product pages
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Change shop columns
add_filter( 'loop_shop_columns', function() { return 4; } );
add_filter( 'loop_shop_per_page', function() { return 12; } );

// Remove breadcrumbs (we have our own)
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Remove default product page sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Move product title/price outside product div
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// Remove related products (we have custom implementation)
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

// Customize WooCommerce product loop
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

// Add custom product loop content
add_action( 'woocommerce_before_shop_loop_item', 'eleventhset_product_loop_open', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'eleventhset_product_loop_close', 30 );

function eleventhset_product_loop_open() {
	echo '<div class="product-card">';
}

function eleventhset_product_loop_close() {
	global $product;
	$title = get_the_title();
	$price = $product->get_price_html();
	$link  = get_permalink();
	$thumb = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'eleventhset-product' ) : wc_placeholder_img_src( 'eleventhset-product' );
	$is_new  = ( time() - strtotime( get_the_date( 'c' ) ) ) < ( 30 * 24 * 60 * 60 );
	$on_sale = $product->is_on_sale();

	echo '<div class="product-card__image">';
	if ( $on_sale ) {
		echo '<span class="product-card__badge product-card__badge--sale">' . __( 'Sale', 'eleventhset' ) . '</span>';
	} elseif ( $is_new ) {
		echo '<span class="product-card__badge product-card__badge--new">' . __( 'New', 'eleventhset' ) . '</span>';
	}
	echo '<a href="' . esc_url( $link ) . '"><img src="' . esc_url( $thumb ) . '" alt="' . esc_attr( $title ) . '" loading="lazy"></a>';
	echo '<a href="' . esc_url( $link ) . '" class="product-card__quick-add">' . __( 'View Product', 'eleventhset' ) . '</a>';
	echo '</div>'; // .product-card__image

	echo '<div class="product-card__info">';
	echo '<h3 class="product-card__name"><a href="' . esc_url( $link ) . '">' . esc_html( $title ) . '</a></h3>';
	echo '<div class="product-card__price">' . $price . '</div>';
	echo '</div>'; // .product-card__info
	echo '</div>'; // .product-card
}

// WooCommerce main content wrappers
function eleventhset_woocommerce_wrapper_before() {
	echo '<main id="primary" class="site-main woocommerce-page">';
}
function eleventhset_woocommerce_wrapper_after() {
	echo '</main>';
}
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'eleventhset_woocommerce_wrapper_before', 10 );
add_action( 'woocommerce_after_main_content', 'eleventhset_woocommerce_wrapper_after', 10 );

/* =============================================================================
   AJAX: ADD TO CART (for quick-add functionality)
============================================================================= */

function eleventhset_ajax_add_to_cart() {
	check_ajax_referer( 'eleventhset-nonce', 'nonce' );

	$product_id   = absint( $_POST['product_id'] ?? 0 );
	$quantity     = absint( $_POST['quantity'] ?? 1 );
	$variation_id = absint( $_POST['variation_id'] ?? 0 );
	$variation    = $_POST['variation'] ?? array();

	if ( ! $product_id ) {
		wp_send_json_error( __( 'Invalid product.', 'eleventhset' ) );
	}

	$cart_item_key = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );

	if ( $cart_item_key ) {
		wp_send_json_success( array(
			'message'    => __( 'Product added to cart.', 'eleventhset' ),
			'cart_count' => WC()->cart->get_cart_contents_count(),
			'cart_url'   => wc_get_cart_url(),
		) );
	} else {
		wp_send_json_error( __( 'Could not add product to cart.', 'eleventhset' ) );
	}
}
add_action( 'wp_ajax_eleventhset_add_to_cart', 'eleventhset_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_eleventhset_add_to_cart', 'eleventhset_ajax_add_to_cart' );

/* =============================================================================
   CONTACT FORM (CF7-compatible AJAX fallback)
============================================================================= */

function eleventhset_handle_contact_form() {
	check_ajax_referer( 'eleventhset-nonce', 'nonce' );

	$name    = sanitize_text_field( $_POST['contact_name'] ?? '' );
	$email   = sanitize_email( $_POST['contact_email'] ?? '' );
	$subject = sanitize_text_field( $_POST['contact_subject'] ?? 'Contact Form Submission' );
	$message = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

	if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
		wp_send_json_error( __( 'Please fill in all required fields.', 'eleventhset' ) );
	}

	if ( ! is_email( $email ) ) {
		wp_send_json_error( __( 'Please enter a valid email address.', 'eleventhset' ) );
	}

	$to      = get_option( 'admin_email' );
	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . $name . ' <' . $email . '>',
		'Reply-To: ' . $email,
	);

	$body = '<h3>New Contact Form Submission</h3>';
	$body .= '<p><strong>Name:</strong> ' . esc_html( $name ) . '</p>';
	$body .= '<p><strong>Email:</strong> ' . esc_html( $email ) . '</p>';
	$body .= '<p><strong>Subject:</strong> ' . esc_html( $subject ) . '</p>';
	$body .= '<p><strong>Message:</strong><br>' . nl2br( esc_html( $message ) ) . '</p>';

	$sent = wp_mail( $to, '[Eleventh Set] ' . $subject, $body, $headers );

	if ( $sent ) {
		wp_send_json_success( __( 'Your message has been sent. We\'ll be in touch soon!', 'eleventhset' ) );
	} else {
		wp_send_json_error( __( 'Sorry, there was an error sending your message. Please try again.', 'eleventhset' ) );
	}
}
add_action( 'wp_ajax_eleventhset_contact', 'eleventhset_handle_contact_form' );
add_action( 'wp_ajax_nopriv_eleventhset_contact', 'eleventhset_handle_contact_form' );

/* =============================================================================
   CUSTOM ATTRIBUTES FOR WOOCOMMERCE (Size & Color variants)
============================================================================= */

function eleventhset_register_product_attributes() {
	if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
		return;
	}

	$existing = array_column( wc_get_attribute_taxonomies(), 'attribute_name' );

	$attributes_to_create = array(
		array(
			'name'    => 'Size',
			'slug'    => 'size',
			'type'    => 'select',
			'orderby' => 'menu_order',
			'public'  => true,
		),
		array(
			'name'    => 'Color',
			'slug'    => 'color',
			'type'    => 'color',
			'orderby' => 'menu_order',
			'public'  => true,
		),
	);

	foreach ( $attributes_to_create as $attr ) {
		if ( ! in_array( $attr['slug'], $existing, true ) ) {
			wc_create_attribute( $attr );
		}
	}
}
add_action( 'init', 'eleventhset_register_product_attributes', 20 );

/* =============================================================================
   THEME CUSTOMIZER
============================================================================= */

function eleventhset_customize_register( $wp_customize ) {
	// Panel: Eleventh Set Options
	$wp_customize->add_panel( 'eleventhset_options', array(
		'title'    => __( 'Eleventh Set Options', 'eleventhset' ),
		'priority' => 30,
	) );

	// Section: Hero
	$wp_customize->add_section( 'eleventhset_hero', array(
		'title' => __( 'Homepage Hero', 'eleventhset' ),
		'panel' => 'eleventhset_options',
	) );

	$wp_customize->add_setting( 'hero_eyebrow', array(
		'default'           => 'New Collection',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'hero_eyebrow', array(
		'label'   => __( 'Hero Eyebrow Text', 'eleventhset' ),
		'section' => 'eleventhset_hero',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hero_heading', array(
		'default'           => "The Art\nOf Dressing",
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'hero_heading', array(
		'label'   => __( 'Hero Heading', 'eleventhset' ),
		'section' => 'eleventhset_hero',
		'type'    => 'textarea',
	) );

	$wp_customize->add_setting( 'hero_subtitle', array(
		'default'           => 'Thoughtfully crafted clothing for the modern individual.',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'hero_subtitle', array(
		'label'   => __( 'Hero Subtitle', 'eleventhset' ),
		'section' => 'eleventhset_hero',
		'type'    => 'text',
	) );

	$wp_customize->add_setting( 'hero_image', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'hero_image', array(
		'label'     => __( 'Hero Background Image', 'eleventhset' ),
		'section'   => 'eleventhset_hero',
		'mime_type' => 'image',
	) ) );

	// Section: Colors
	$wp_customize->add_section( 'eleventhset_colors', array(
		'title' => __( 'Brand Colors', 'eleventhset' ),
		'panel' => 'eleventhset_options',
	) );

	$wp_customize->add_setting( 'brand_accent_color', array(
		'default'           => '#c9a96e',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'brand_accent_color', array(
		'label'   => __( 'Accent Color', 'eleventhset' ),
		'section' => 'eleventhset_colors',
	) ) );

	// Section: Contact Info
	$wp_customize->add_section( 'eleventhset_contact', array(
		'title' => __( 'Contact Information', 'eleventhset' ),
		'panel' => 'eleventhset_options',
	) );

	$fields = array(
		'contact_email'   => array( 'label' => 'Email Address', 'default' => 'hello@eleventhset.com' ),
		'contact_phone'   => array( 'label' => 'Phone Number', 'default' => '+1 (555) 000-0000' ),
		'contact_address' => array( 'label' => 'Address', 'default' => '123 Fashion Ave, New York, NY 10001' ),
		'contact_hours'   => array( 'label' => 'Business Hours', 'default' => 'Mon–Fri: 9am – 6pm EST' ),
		'instagram_url'   => array( 'label' => 'Instagram URL', 'default' => '' ),
		'facebook_url'    => array( 'label' => 'Facebook URL', 'default' => '' ),
		'twitter_url'     => array( 'label' => 'Twitter/X URL', 'default' => '' ),
		'pinterest_url'   => array( 'label' => 'Pinterest URL', 'default' => '' ),
	);

	foreach ( $fields as $key => $field ) {
		$wp_customize->add_setting( $key, array(
			'default'           => $field['default'],
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( $key, array(
			'label'   => __( $field['label'], 'eleventhset' ),
			'section' => 'eleventhset_contact',
			'type'    => 'text',
		) );
	}
}
add_action( 'customize_register', 'eleventhset_customize_register' );

// Output custom CSS from customizer
function eleventhset_customizer_css() {
	$accent = get_theme_mod( 'brand_accent_color', '#c9a96e' );
	if ( $accent !== '#c9a96e' ) {
		echo '<style id="eleventhset-customizer-css">:root { --color-accent: ' . esc_attr( $accent ) . '; }</style>';
	}
}
add_action( 'wp_head', 'eleventhset_customizer_css' );

/* =============================================================================
   HELPER FUNCTIONS
============================================================================= */

/**
 * Get theme mod with fallback
 */
function eleventhset_get_option( $key, $default = '' ) {
	return get_theme_mod( $key, $default );
}

/**
 * Output social icon SVGs
 */
function eleventhset_social_icon( $platform ) {
	$icons = array(
		'instagram' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>',
		'facebook'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>',
		'twitter'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>',
		'pinterest' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C6.48 2 2 6.48 2 12c0 4.24 2.65 7.86 6.39 9.29-.09-.78-.17-1.98.04-2.83.18-.77 1.24-5.23 1.24-5.23s-.32-.63-.32-1.57c0-1.47.85-2.57 1.91-2.57.9 0 1.34.68 1.34 1.49 0 .91-.58 2.27-.88 3.53-.25 1.06.53 1.91 1.57 1.91 1.88 0 3.14-2.4 3.14-5.24 0-2.16-1.45-3.78-4.07-3.78-2.96 0-4.8 2.21-4.8 4.67 0 .85.25 1.44.64 1.9.18.22.21.3.14.55-.05.18-.16.61-.2.78-.07.25-.28.34-.51.25-1.41-.58-2.07-2.14-2.07-3.9 0-2.89 2.43-6.36 7.25-6.36 3.88 0 6.44 2.81 6.44 5.83 0 3.99-2.21 6.97-5.47 6.97-1.09 0-2.13-.59-2.48-1.26l-.68 2.61c-.24.92-.71 1.84-1.12 2.56.84.26 1.73.4 2.65.4 5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>',
	);
	return $icons[ $platform ] ?? '';
}

/**
 * Get SVG icon
 */
function eleventhset_icon( $name, $args = array() ) {
	$defaults = array(
		'class'       => '',
		'width'       => 24,
		'height'      => 24,
		'stroke'      => 'currentColor',
		'fill'        => 'none',
		'stroke-width'=> '1.5',
	);
	$args = wp_parse_args( $args, $defaults );

	$icons = array(
		'cart'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>',
		'search'  => '<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>',
		'user'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>',
		'heart'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>',
		'email'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>',
		'phone'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6.75Z"/>',
		'pin'     => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>',
		'clock'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
		'star'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/>',
		'truck'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>',
		'leaf'    => '<path stroke-linecap="round" stroke-linejoin="round" d="M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 0 1-1.161.886l-.143.048a1.107 1.107 0 0 0-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 0 1-1.652.928l-.679-.906a1.125 1.125 0 0 0-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 0 0-8.862 12.872M12.75 3.031a9 9 0 0 1 6.69 14.036m0 0-.177-.529A2.25 2.25 0 0 0 17.128 15H16.5l-.324-.324a1.453 1.453 0 0 0-2.328.377l-.036.073a1.586 1.586 0 0 1-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 0 1-5.276 3.67m0 0a9 9 0 0 1-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25"/>',
	);

	$path   = $icons[ $name ] ?? '';
	$class  = $args['class'] ? ' class="' . esc_attr( $args['class'] ) . '"' : '';
	$stroke = $args['stroke'] ? ' stroke="' . esc_attr( $args['stroke'] ) . '"' : '';
	$fill   = $args['fill'] ? ' fill="' . esc_attr( $args['fill'] ) . '"' : '';
	$sw     = $args['stroke-width'] ? ' stroke-width="' . esc_attr( $args['stroke-width'] ) . '"' : '';

	return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="' . intval( $args['width'] ) . '" height="' . intval( $args['height'] ) . '"' . $class . $fill . $stroke . $sw . '>' . $path . '</svg>';
}

/**
 * Get cart item count for nav badge
 */
function eleventhset_cart_count() {
	if ( function_exists( 'WC' ) ) {
		return WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	}
	return 0;
}

/* =============================================================================
   EXCERPT
============================================================================= */

add_filter( 'excerpt_length', function() { return 20; } );
add_filter( 'excerpt_more', function() { return '&hellip;'; } );

/* =============================================================================
   TITLE TAG
============================================================================= */

function eleventhset_wp_title( $title, $sep ) {
	if ( is_feed() ) return $title;
	$title .= ' ' . $sep . ' ' . get_bloginfo( 'name' );
	if ( is_home() || is_front_page() ) {
		$title .= ' ' . $sep . ' ' . get_bloginfo( 'description' );
	}
	return trim( $title );
}
add_filter( 'wp_title', 'eleventhset_wp_title', 10, 2 );

/* =============================================================================
   BODY CLASSES
============================================================================= */

function eleventhset_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'is-front-page';
	}
	if ( is_woocommerce() || is_cart() || is_checkout() ) {
		$classes[] = 'is-woo-page';
	}
	return $classes;
}
add_filter( 'body_class', 'eleventhset_body_classes' );

/* =============================================================================
   SECURITY
============================================================================= */

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
