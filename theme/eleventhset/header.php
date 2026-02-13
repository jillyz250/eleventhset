<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

	<!-- ===================== SITE HEADER ===================== -->
	<header id="masthead" class="site-header <?php echo is_front_page() ? 'header--transparent' : 'header--solid'; ?>" role="banner">
		<div class="container">

			<!-- Logo -->
			<div class="site-logo">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php
					if ( has_custom_logo() ) {
						the_custom_logo();
					} else {
						echo '<span class="logo-text">' . get_bloginfo( 'name' ) . '</span>';
					}
					?>
				</a>
			</div>

			<!-- Primary Navigation -->
			<nav class="main-nav" id="site-navigation" aria-label="<?php esc_attr_e( 'Primary', 'eleventhset' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'main-nav__menu',
					'container'      => false,
					'fallback_cb'    => function() {
						echo '<ul class="main-nav__menu">';
						echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home', 'eleventhset' ) . '</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/shop' ) ) . '">' . __( 'Shop', 'eleventhset' ) . '</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/about' ) ) . '">' . __( 'About', 'eleventhset' ) . '</a></li>';
						echo '<li><a href="' . esc_url( home_url( '/contact' ) ) . '">' . __( 'Contact', 'eleventhset' ) . '</a></li>';
						echo '</ul>';
					},
				) );
				?>

				<!-- Nav Actions -->
				<div class="nav-actions">
					<!-- Search -->
					<a href="<?php echo esc_url( get_search_link() ); ?>" aria-label="<?php esc_attr_e( 'Search', 'eleventhset' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
						</svg>
					</a>

					<!-- Account -->
					<?php if ( function_exists( 'wc_get_account_endpoint_url' ) ) : ?>
					<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" aria-label="<?php esc_attr_e( 'My Account', 'eleventhset' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
						</svg>
					</a>
					<?php endif; ?>

					<!-- Cart -->
					<?php if ( function_exists( 'wc_get_cart_url' ) ) : ?>
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-link" aria-label="<?php esc_attr_e( 'Cart', 'eleventhset' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
						</svg>
						<?php
						$cart_count = eleventhset_cart_count();
						if ( $cart_count > 0 ) :
						?>
						<span class="cart-count js-cart-count"><?php echo esc_html( $cart_count ); ?></span>
						<?php endif; ?>
					</a>
					<?php endif; ?>
				</div>
			</nav>

			<!-- Mobile Toggle -->
			<button class="mobile-menu-toggle js-mobile-toggle" aria-label="<?php esc_attr_e( 'Toggle Menu', 'eleventhset' ); ?>" aria-expanded="false">
				<span></span>
				<span></span>
				<span></span>
			</button>
		</div>
	</header>

	<!-- Mobile Nav Overlay -->
	<div class="mobile-nav-overlay js-mobile-nav" role="dialog" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'eleventhset' ); ?>">
		<?php
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'menu_class'     => 'nav-menu',
			'container'      => 'nav',
			'fallback_cb'    => function() {
				echo '<nav><ul class="nav-menu">';
				echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . __( 'Home', 'eleventhset' ) . '</a></li>';
				echo '<li><a href="' . esc_url( home_url( '/shop' ) ) . '">' . __( 'Shop', 'eleventhset' ) . '</a></li>';
				echo '<li><a href="' . esc_url( home_url( '/about' ) ) . '">' . __( 'About', 'eleventhset' ) . '</a></li>';
				echo '<li><a href="' . esc_url( home_url( '/contact' ) ) . '">' . __( 'Contact', 'eleventhset' ) . '</a></li>';
				echo '</ul></nav>';
			},
		) );
		?>
	</div>

	<!-- Page Content Wrapper -->
	<div id="content" class="site-content">
