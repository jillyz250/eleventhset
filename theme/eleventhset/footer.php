	</div><!-- #content .site-content -->

	<!-- ===================== SITE FOOTER ===================== -->
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">

			<div class="footer-top">
				<!-- Brand Column -->
				<div class="footer-brand">
					<span class="logo-text"><?php echo get_bloginfo( 'name' ); ?></span>
					<p><?php echo esc_html( get_bloginfo( 'description' ) ?: __( 'Thoughtfully crafted clothing for the modern individual. Elevating everyday style through quality and intention.', 'eleventhset' ) ); ?></p>
					<div class="footer-social">
						<?php
						$social_links = array(
							'instagram' => eleventhset_get_option( 'instagram_url' ),
							'facebook'  => eleventhset_get_option( 'facebook_url' ),
							'twitter'   => eleventhset_get_option( 'twitter_url' ),
							'pinterest' => eleventhset_get_option( 'pinterest_url' ),
						);
						foreach ( $social_links as $platform => $url ) :
							if ( ! empty( $url ) ) :
						?>
						<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>">
							<?php echo eleventhset_social_icon( $platform ); ?>
						</a>
						<?php
							endif;
						endforeach;
						// Show placeholder icons if no social links set
						if ( empty( array_filter( $social_links ) ) ) :
						?>
						<a href="#" aria-label="Instagram"><?php echo eleventhset_social_icon( 'instagram' ); ?></a>
						<a href="#" aria-label="Facebook"><?php echo eleventhset_social_icon( 'facebook' ); ?></a>
						<a href="#" aria-label="Pinterest"><?php echo eleventhset_social_icon( 'pinterest' ); ?></a>
						<?php endif; ?>
					</div>
				</div>

				<!-- Shop Links -->
				<div class="footer-col">
					<h4><?php _e( 'Shop', 'eleventhset' ); ?></h4>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer-1',
						'container'      => false,
						'menu_class'     => '',
						'fallback_cb'    => function() {
							echo '<ul>';
							echo '<li><a href="' . esc_url( home_url( '/shop' ) ) . '">' . __( 'All Collections', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/product-category/tops' ) ) . '">' . __( 'Tops', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/product-category/bottoms' ) ) . '">' . __( 'Bottoms', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/product-category/outerwear' ) ) . '">' . __( 'Outerwear', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/product-category/accessories' ) ) . '">' . __( 'Accessories', 'eleventhset' ) . '</a></li>';
							echo '</ul>';
						},
					) );
					?>
				</div>

				<!-- Company Links -->
				<div class="footer-col">
					<h4><?php _e( 'Company', 'eleventhset' ); ?></h4>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer-2',
						'container'      => false,
						'menu_class'     => '',
						'fallback_cb'    => function() {
							echo '<ul>';
							echo '<li><a href="' . esc_url( home_url( '/about' ) ) . '">' . __( 'Our Story', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/sustainability' ) ) . '">' . __( 'Sustainability', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/careers' ) ) . '">' . __( 'Careers', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/press' ) ) . '">' . __( 'Press', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/contact' ) ) . '">' . __( 'Contact', 'eleventhset' ) . '</a></li>';
							echo '</ul>';
						},
					) );
					?>
				</div>

				<!-- Help Links -->
				<div class="footer-col">
					<h4><?php _e( 'Help', 'eleventhset' ); ?></h4>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer-3',
						'container'      => false,
						'menu_class'     => '',
						'fallback_cb'    => function() {
							echo '<ul>';
							echo '<li><a href="' . esc_url( home_url( '/faq' ) ) . '">' . __( 'FAQ', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/shipping' ) ) . '">' . __( 'Shipping & Returns', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/size-guide' ) ) . '">' . __( 'Size Guide', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/care-guide' ) ) . '">' . __( 'Care Guide', 'eleventhset' ) . '</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/track-order' ) ) . '">' . __( 'Track Order', 'eleventhset' ) . '</a></li>';
							echo '</ul>';
						},
					) );
					?>
				</div>
			</div>

			<!-- Footer Bottom -->
			<div class="footer-bottom">
				<p>
					&copy; <?php echo date( 'Y' ); ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo get_bloginfo( 'name' ); ?></a>.
					<?php _e( 'All rights reserved.', 'eleventhset' ); ?>
				</p>
				<div class="footer-bottom-links">
					<a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>"><?php _e( 'Privacy Policy', 'eleventhset' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/terms-of-service' ) ); ?>"><?php _e( 'Terms of Service', 'eleventhset' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/cookie-policy' ) ); ?>"><?php _e( 'Cookies', 'eleventhset' ); ?></a>
				</div>
			</div>

		</div>
	</footer>

</div><!-- #page .site -->

<?php wp_footer(); ?>

</body>
</html>
