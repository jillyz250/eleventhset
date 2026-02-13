<?php
/**
 * Eleventh Set Setup Wizard
 * Run once after theme activation to create pages, menus, and WooCommerce settings.
 *
 * Access via: WP Admin → Appearance → Eleventh Set Setup
 *
 * @package eleventhset
 */

defined( 'ABSPATH' ) || exit;

class Eleventhset_Setup_Wizard {

	public static function run() {
		$results = array();
		$results[] = self::create_pages();
		$results[] = self::create_navigation_menu();
		$results[] = self::configure_woocommerce();
		$results[] = self::set_homepage();
		return $results;
	}

	/**
	 * Create required pages
	 */
	private static function create_pages() {
		$pages = array(
			'home' => array(
				'title'     => 'Home',
				'template'  => '', // Uses front-page.php automatically when set as home
				'content'   => '',
			),
			'about' => array(
				'title'    => 'About',
				'template' => 'page-about.php',
				'content'  => '',
			),
			'contact' => array(
				'title'    => 'Contact',
				'template' => 'page-contact.php',
				'content'  => '',
			),
			'shop' => array(
				'title'   => 'Shop',
				'content' => '',
			),
			'cart' => array(
				'title'   => 'Cart',
				'content' => '[woocommerce_cart]',
			),
			'checkout' => array(
				'title'   => 'Checkout',
				'content' => '[woocommerce_checkout]',
			),
			'my-account' => array(
				'title'   => 'My Account',
				'content' => '[woocommerce_my_account]',
			),
			'size-guide' => array(
				'title'   => 'Size Guide',
				'content' => self::get_size_guide_content(),
			),
			'shipping' => array(
				'title'   => 'Shipping & Returns',
				'content' => self::get_shipping_content(),
			),
			'faq' => array(
				'title'   => 'FAQ',
				'content' => self::get_faq_content(),
			),
			'privacy-policy' => array(
				'title'   => 'Privacy Policy',
				'content' => 'Your privacy policy content goes here.',
			),
			'terms-of-service' => array(
				'title'   => 'Terms of Service',
				'content' => 'Your terms of service content goes here.',
			),
		);

		$created = 0;
		$skipped = 0;

		foreach ( $pages as $slug => $page_data ) {
			$existing = get_page_by_path( $slug );

			if ( ! $existing ) {
				$page_args = array(
					'post_title'   => $page_data['title'],
					'post_name'    => $slug,
					'post_content' => $page_data['content'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_author'  => 1,
				);

				$page_id = wp_insert_post( $page_args );

				if ( ! is_wp_error( $page_id ) && isset( $page_data['template'] ) && $page_data['template'] ) {
					update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
				}
				$created++;
			} else {
				$skipped++;
			}
		}

		return "Pages: Created {$created}, Skipped {$skipped} existing.";
	}

	/**
	 * Create navigation menu
	 */
	private static function create_navigation_menu() {
		$menu_name = 'Primary Menu';
		$menu_exists = wp_get_nav_menu_object( $menu_name );

		if ( $menu_exists ) {
			wp_delete_nav_menu( $menu_exists->term_id );
		}

		$menu_id = wp_create_nav_menu( $menu_name );

		if ( is_wp_error( $menu_id ) ) {
			return 'Menu: Failed to create — ' . $menu_id->get_error_message();
		}

		$menu_items = array(
			array( 'title' => 'Home',    'url' => home_url( '/' ) ),
			array( 'title' => 'Shop',    'url' => home_url( '/shop' ) ),
			array( 'title' => 'About',   'url' => home_url( '/about' ) ),
			array( 'title' => 'Contact', 'url' => home_url( '/contact' ) ),
		);

		foreach ( $menu_items as $order => $item ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title'   => $item['title'],
				'menu-item-url'     => $item['url'],
				'menu-item-status'  => 'publish',
				'menu-item-type'    => 'custom',
				'menu-item-position'=> $order + 1,
			) );
		}

		// Assign menu to primary location
		$locations = get_theme_mod( 'nav_menu_locations' );
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );

		return 'Menu: Primary menu created and assigned.';
	}

	/**
	 * Configure WooCommerce
	 */
	private static function configure_woocommerce() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return 'WooCommerce: Not installed. Please install WooCommerce plugin.';
		}

		// Set WooCommerce pages
		$woo_pages = array(
			'woocommerce_shop_page_id'     => get_page_by_path( 'shop' ),
			'woocommerce_cart_page_id'     => get_page_by_path( 'cart' ),
			'woocommerce_checkout_page_id' => get_page_by_path( 'checkout' ),
			'woocommerce_myaccount_page_id'=> get_page_by_path( 'my-account' ),
		);

		foreach ( $woo_pages as $option => $page ) {
			if ( $page ) {
				update_option( $option, $page->ID );
			}
		}

		// WooCommerce settings
		update_option( 'woocommerce_enable_reviews', 'yes' );
		update_option( 'woocommerce_enable_star_rating', 'yes' );
		update_option( 'woocommerce_catalog_columns', 4 );
		update_option( 'woocommerce_catalog_rows', 3 );
		update_option( 'woocommerce_thumbnail_image_width', 600 );
		update_option( 'woocommerce_single_image_width', 900 );

		// Create product categories
		$categories = array(
			'Women'       => array( 'description' => 'Women\'s Collection', 'slug' => 'women' ),
			'Men'         => array( 'description' => 'Men\'s Collection', 'slug' => 'men' ),
			'Tops'        => array( 'description' => 'Tops and Blouses', 'slug' => 'tops' ),
			'Bottoms'     => array( 'description' => 'Pants, Skirts, Shorts', 'slug' => 'bottoms' ),
			'Outerwear'   => array( 'description' => 'Jackets and Coats', 'slug' => 'outerwear' ),
			'Dresses'     => array( 'description' => 'Dresses and Jumpsuits', 'slug' => 'dresses' ),
			'Accessories' => array( 'description' => 'Bags, Scarves, and More', 'slug' => 'accessories' ),
			'New Arrivals'=> array( 'description' => 'Latest Pieces', 'slug' => 'new-arrivals' ),
			'Sale'        => array( 'description' => 'Sale Items', 'slug' => 'sale' ),
		);

		$cats_created = 0;
		foreach ( $categories as $name => $data ) {
			if ( ! term_exists( $data['slug'], 'product_cat' ) ) {
				wp_insert_term( $name, 'product_cat', array(
					'description' => $data['description'],
					'slug'        => $data['slug'],
				) );
				$cats_created++;
			}
		}

		return "WooCommerce: Configured. Created {$cats_created} product categories.";
	}

	/**
	 * Set homepage settings
	 */
	private static function set_homepage() {
		$home_page = get_page_by_path( 'home' );

		if ( $home_page ) {
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $home_page->ID );
			return 'Homepage: Set to static "Home" page.';
		}

		return 'Homepage: Could not find home page to set.';
	}

	/**
	 * Size Guide content
	 */
	private static function get_size_guide_content() {
		return '<h2>Size Guide</h2>
<p>All our pieces are designed with a relaxed, considered fit. When in doubt, size up for an oversized silhouette or size down for a tailored look.</p>

<h3>Women\'s Sizing</h3>
<table>
<thead><tr><th>Size</th><th>UK</th><th>EU</th><th>US</th><th>Bust (cm)</th><th>Waist (cm)</th><th>Hip (cm)</th></tr></thead>
<tbody>
<tr><td>XS</td><td>6–8</td><td>34–36</td><td>2–4</td><td>80–84</td><td>60–64</td><td>86–90</td></tr>
<tr><td>S</td><td>8–10</td><td>36–38</td><td>4–6</td><td>84–88</td><td>64–68</td><td>90–94</td></tr>
<tr><td>M</td><td>10–12</td><td>38–40</td><td>8–10</td><td>88–93</td><td>68–73</td><td>94–99</td></tr>
<tr><td>L</td><td>12–14</td><td>40–42</td><td>12–14</td><td>93–98</td><td>73–78</td><td>99–104</td></tr>
<tr><td>XL</td><td>14–16</td><td>42–44</td><td>16–18</td><td>98–104</td><td>78–84</td><td>104–110</td></tr>
</tbody>
</table>

<h3>Men\'s Sizing</h3>
<table>
<thead><tr><th>Size</th><th>UK/EU</th><th>US</th><th>Chest (cm)</th><th>Waist (cm)</th></tr></thead>
<tbody>
<tr><td>XS</td><td>34</td><td>34</td><td>86–89</td><td>71–74</td></tr>
<tr><td>S</td><td>36</td><td>36</td><td>89–94</td><td>74–79</td></tr>
<tr><td>M</td><td>38</td><td>38</td><td>94–99</td><td>79–84</td></tr>
<tr><td>L</td><td>40</td><td>40</td><td>99–104</td><td>84–89</td></tr>
<tr><td>XL</td><td>42</td><td>42</td><td>104–110</td><td>89–95</td></tr>
<tr><td>XXL</td><td>44</td><td>44</td><td>110–116</td><td>95–101</td></tr>
</tbody>
</table>';
	}

	/**
	 * Shipping content
	 */
	private static function get_shipping_content() {
		return '<h2>Shipping & Returns</h2>
<h3>Shipping</h3>
<ul>
<li><strong>Free Standard Shipping</strong> on all orders over $150</li>
<li><strong>Standard Shipping</strong> (3–5 business days): $8.95</li>
<li><strong>Express Shipping</strong> (1–2 business days): $19.95</li>
<li><strong>International Shipping</strong>: Rates calculated at checkout</li>
</ul>
<p>Orders are processed within 1–2 business days. You will receive a shipping confirmation email with tracking information.</p>

<h3>Returns & Exchanges</h3>
<p>We offer free returns within 30 days of purchase. Items must be unworn, unwashed, and in their original condition with all tags attached.</p>
<ul>
<li>To initiate a return, email us at returns@eleventhset.com</li>
<li>We\'ll send you a prepaid return label</li>
<li>Refunds are processed within 5–7 business days of receiving the item</li>
<li>Final sale items cannot be returned</li>
</ul>';
	}

	/**
	 * FAQ content
	 */
	private static function get_faq_content() {
		return '<h2>Frequently Asked Questions</h2>

<h3>How do I know which size to choose?</h3>
<p>Refer to our <a href="/size-guide">Size Guide</a> for detailed measurements. If you\'re between sizes, we recommend sizing up for an oversized fit or sizing down for a tailored look.</p>

<h3>What are your garments made from?</h3>
<p>We use only sustainably sourced, high-quality fabrics including organic cotton, recycled polyester, Tencel, and ethically sourced wool. Each product page lists the specific materials used.</p>

<h3>How do I care for my Eleventh Set pieces?</h3>
<p>Care instructions are printed on the label inside each garment. Generally, we recommend washing on a cold, gentle cycle and laying flat to dry to preserve the shape and fabric integrity.</p>

<h3>Can I change or cancel my order?</h3>
<p>We process orders quickly, but please contact us within 2 hours of placing your order if you need to make changes. Email us at hello@eleventhset.com.</p>

<h3>Do you ship internationally?</h3>
<p>Yes! We ship worldwide. International shipping rates and delivery times are calculated at checkout based on your location.</p>

<h3>How do I track my order?</h3>
<p>Once your order ships, you\'ll receive a confirmation email with a tracking link. Orders typically ship within 1–2 business days.</p>';
	}
}

/* =============================================================================
   ADMIN PAGE: SETUP WIZARD
============================================================================= */

function eleventhset_setup_admin_menu() {
	add_theme_page(
		__( 'Eleventh Set Setup', 'eleventhset' ),
		__( 'Theme Setup', 'eleventhset' ),
		'manage_options',
		'eleventhset-setup',
		'eleventhset_setup_admin_page'
	);
}
add_action( 'admin_menu', 'eleventhset_setup_admin_menu' );

function eleventhset_setup_admin_page() {
	$results = array();
	$ran     = false;

	if ( isset( $_POST['run_setup'] ) && check_admin_referer( 'eleventhset_setup' ) ) {
		$results = Eleventhset_Setup_Wizard::run();
		$ran     = true;
	}
	?>
	<div class="wrap">
		<h1><?php _e( 'Eleventh Set — Theme Setup Wizard', 'eleventhset' ); ?></h1>
		<p><?php _e( 'Click the button below to automatically create pages, navigation menus, and configure WooCommerce for your Eleventh Set store.', 'eleventhset' ); ?></p>

		<?php if ( $ran && ! empty( $results ) ) : ?>
		<div class="notice notice-success">
			<p><strong><?php _e( 'Setup complete!', 'eleventhset' ); ?></strong></p>
			<ul>
				<?php foreach ( $results as $result ) : ?>
				<li>&bull; <?php echo esc_html( $result ); ?></li>
				<?php endforeach; ?>
			</ul>
			<p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="button button-secondary">
					<?php _e( 'View Site', 'eleventhset' ); ?>
				</a>
			</p>
		</div>
		<?php endif; ?>

		<div class="card" style="max-width:600px;">
			<h2 style="margin-top:0;"><?php _e( 'Setup Checklist', 'eleventhset' ); ?></h2>
			<ul>
				<li>✅ <?php _e( 'Creates pages: Home, About, Contact, Shop, Cart, Checkout, My Account, Size Guide, Shipping, FAQ', 'eleventhset' ); ?></li>
				<li>✅ <?php _e( 'Creates and assigns Primary Navigation Menu', 'eleventhset' ); ?></li>
				<li>✅ <?php _e( 'Configures WooCommerce pages and settings', 'eleventhset' ); ?></li>
				<li>✅ <?php _e( 'Creates product categories (Women, Men, Tops, Bottoms, Outerwear, Dresses, Accessories)', 'eleventhset' ); ?></li>
				<li>✅ <?php _e( 'Sets static homepage', 'eleventhset' ); ?></li>
			</ul>

			<form method="post">
				<?php wp_nonce_field( 'eleventhset_setup' ); ?>
				<input type="hidden" name="run_setup" value="1">
				<button type="submit" class="button button-primary button-large">
					<?php $ran ? _e( 'Re-run Setup', 'eleventhset' ) : _e( 'Run Setup Now', 'eleventhset' ); ?>
				</button>
			</form>
		</div>

		<div class="card" style="max-width:600px; margin-top: 20px;">
			<h2 style="margin-top:0;"><?php _e( 'Next Steps', 'eleventhset' ); ?></h2>
			<ol>
				<li><?php _e( 'Upload your brand logo: <strong>Appearance → Customize → Site Identity</strong>', 'eleventhset' ); ?></li>
				<li><?php _e( 'Set hero image: <strong>Appearance → Customize → Eleventh Set Options → Homepage Hero</strong>', 'eleventhset' ); ?></li>
				<li><?php _e( 'Add your contact info: <strong>Appearance → Customize → Eleventh Set Options → Contact Information</strong>', 'eleventhset' ); ?></li>
				<li><?php _e( 'Add products: <strong>Products → Add New</strong> — create variable products with Color and Size attributes', 'eleventhset' ); ?></li>
				<li><?php _e( 'Install Contact Form 7 (optional) for advanced contact forms', 'eleventhset' ); ?></li>
				<li><?php _e( 'Install WooCommerce Variation Swatches plugin for visual color/size swatches', 'eleventhset' ); ?></li>
			</ol>
		</div>
	</div>
	<?php
}
