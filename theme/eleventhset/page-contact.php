<?php
/**
 * Template Name: Contact Page
 *
 * @package eleventhset
 */

get_header();
?>

<main id="primary" class="site-main">

	<!-- Page Hero -->
	<div class="page-hero" style="padding-top: calc(var(--nav-height) + var(--spacing-xl));">
		<div class="container">
			<p class="page-hero__eyebrow"><?php _e( 'Get In Touch', 'eleventhset' ); ?></p>
			<h1 class="page-hero__title"><?php _e( 'Contact Us', 'eleventhset' ); ?></h1>
			<p class="page-hero__description">
				<?php _e( 'We\'d love to hear from you. Whether it\'s about an order, a collaboration, or just to say hello.', 'eleventhset' ); ?>
			</p>
		</div>
	</div>

	<!-- Contact Layout -->
	<section class="section">
		<div class="container">
			<div class="contact-layout">

				<!-- Contact Info -->
				<div class="contact-info">
					<p class="contact-info__label"><?php _e( 'How To Reach Us', 'eleventhset' ); ?></p>
					<h2 class="contact-info__title">
						<?php _e( 'Let\'s Start A Conversation', 'eleventhset' ); ?>
					</h2>
					<p class="contact-info__text">
						<?php _e( 'Our team is here to help with any questions about your order, our products, or anything else on your mind. We typically respond within one business day.', 'eleventhset' ); ?>
					</p>

					<div class="contact-details">
						<!-- Email -->
						<div class="contact-detail-item">
							<div class="contact-detail-item__icon">
								<?php echo eleventhset_icon( 'email' ); ?>
							</div>
							<div class="contact-detail-item__content">
								<h5><?php _e( 'Email', 'eleventhset' ); ?></h5>
								<?php $email = eleventhset_get_option( 'contact_email', 'hello@eleventhset.com' ); ?>
								<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
							</div>
						</div>

						<!-- Phone -->
						<div class="contact-detail-item">
							<div class="contact-detail-item__icon">
								<?php echo eleventhset_icon( 'phone' ); ?>
							</div>
							<div class="contact-detail-item__content">
								<h5><?php _e( 'Phone', 'eleventhset' ); ?></h5>
								<?php $phone = eleventhset_get_option( 'contact_phone', '+1 (555) 000-0000' ); ?>
								<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
							</div>
						</div>

						<!-- Address -->
						<div class="contact-detail-item">
							<div class="contact-detail-item__icon">
								<?php echo eleventhset_icon( 'pin' ); ?>
							</div>
							<div class="contact-detail-item__content">
								<h5><?php _e( 'Address', 'eleventhset' ); ?></h5>
								<?php $address = eleventhset_get_option( 'contact_address', '123 Fashion Ave, New York, NY 10001' ); ?>
								<p><?php echo nl2br( esc_html( $address ) ); ?></p>
							</div>
						</div>

						<!-- Hours -->
						<div class="contact-detail-item">
							<div class="contact-detail-item__icon">
								<?php echo eleventhset_icon( 'clock' ); ?>
							</div>
							<div class="contact-detail-item__content">
								<h5><?php _e( 'Business Hours', 'eleventhset' ); ?></h5>
								<?php $hours = eleventhset_get_option( 'contact_hours', "Mon–Fri: 9am – 6pm EST\nSat–Sun: Closed" ); ?>
								<p><?php echo nl2br( esc_html( $hours ) ); ?></p>
							</div>
						</div>
					</div>

					<!-- Social Links -->
					<div style="margin-top: var(--spacing-lg);">
						<p class="text-uppercase" style="margin-bottom: var(--spacing-sm);"><?php _e( 'Follow Us', 'eleventhset' ); ?></p>
						<div class="footer-social" style="gap: 8px;">
							<?php
							$social_links = array(
								'instagram' => eleventhset_get_option( 'instagram_url', '#' ),
								'facebook'  => eleventhset_get_option( 'facebook_url', '#' ),
								'pinterest' => eleventhset_get_option( 'pinterest_url', '#' ),
							);
							foreach ( $social_links as $platform => $url ) :
								if ( ! empty( $url ) ) :
							?>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>" style="border-color: var(--color-border);">
								<?php echo eleventhset_social_icon( $platform ); ?>
							</a>
							<?php
								endif;
							endforeach;
							?>
						</div>
					</div>
				</div>

				<!-- Contact Form -->
				<div class="contact-form-wrapper">
					<h3><?php _e( 'Send Us A Message', 'eleventhset' ); ?></h3>

					<?php
					// Check for Contact Form 7
					if ( function_exists( 'wpcf7_contact_form' ) ) :
						// Use CF7 shortcode if available - user should configure CF7 form ID
						$cf7_form_id = get_option( 'eleventhset_cf7_contact_id', '' );
						if ( $cf7_form_id ) {
							echo do_shortcode( '[contact-form-7 id="' . esc_attr( $cf7_form_id ) . '" title="Contact Form"]' );
						} else {
							echo '<p style="color: var(--color-text-secondary); font-size: 0.875rem;">';
							printf(
								__( 'Please set up Contact Form 7 and add the form ID in <a href="%s">theme settings</a>.', 'eleventhset' ),
								esc_url( admin_url( 'options-general.php' ) )
							);
							echo '</p>';
							// Fall through to custom form
						}
					endif;

					// Always show our custom form (CF7 is optional)
					?>

					<form class="js-contact-form" id="eleventhset-contact-form" novalidate>
						<?php wp_nonce_field( 'eleventhset-nonce', 'nonce' ); ?>

						<div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-sm);">
							<div class="form-group">
								<label class="form-label" for="contact_name">
									<?php _e( 'Full Name', 'eleventhset' ); ?> <span style="color:var(--color-error)">*</span>
								</label>
								<input
									type="text"
									id="contact_name"
									name="contact_name"
									class="form-control"
									placeholder="<?php esc_attr_e( 'Jane Smith', 'eleventhset' ); ?>"
									required
									autocomplete="name"
								>
							</div>
							<div class="form-group">
								<label class="form-label" for="contact_email">
									<?php _e( 'Email Address', 'eleventhset' ); ?> <span style="color:var(--color-error)">*</span>
								</label>
								<input
									type="email"
									id="contact_email"
									name="contact_email"
									class="form-control"
									placeholder="<?php esc_attr_e( 'jane@example.com', 'eleventhset' ); ?>"
									required
									autocomplete="email"
								>
							</div>
						</div>

						<div class="form-group">
							<label class="form-label" for="contact_subject">
								<?php _e( 'Subject', 'eleventhset' ); ?>
							</label>
							<select id="contact_subject" name="contact_subject" class="form-control">
								<option value=""><?php _e( 'Select a topic', 'eleventhset' ); ?></option>
								<option value="Order Inquiry"><?php _e( 'Order Inquiry', 'eleventhset' ); ?></option>
								<option value="Product Question"><?php _e( 'Product Question', 'eleventhset' ); ?></option>
								<option value="Returns & Exchanges"><?php _e( 'Returns & Exchanges', 'eleventhset' ); ?></option>
								<option value="Wholesale Inquiry"><?php _e( 'Wholesale Inquiry', 'eleventhset' ); ?></option>
								<option value="Press & Media"><?php _e( 'Press & Media', 'eleventhset' ); ?></option>
								<option value="Collaboration"><?php _e( 'Collaboration', 'eleventhset' ); ?></option>
								<option value="Other"><?php _e( 'Other', 'eleventhset' ); ?></option>
							</select>
						</div>

						<div class="form-group">
							<label class="form-label" for="contact_message">
								<?php _e( 'Message', 'eleventhset' ); ?> <span style="color:var(--color-error)">*</span>
							</label>
							<textarea
								id="contact_message"
								name="contact_message"
								class="form-control"
								placeholder="<?php esc_attr_e( 'Tell us how we can help you...', 'eleventhset' ); ?>"
								required
								rows="6"
							></textarea>
						</div>

						<!-- Form Status Messages -->
						<div class="form-response js-form-response" style="display:none; padding: 14px; margin-bottom: var(--spacing-sm); font-size: 0.875rem;"></div>

						<button type="submit" class="btn btn--primary" style="width:100%;" id="contact-submit-btn">
							<span class="btn-text"><?php _e( 'Send Message', 'eleventhset' ); ?></span>
							<span class="btn-loading" style="display:none;"><?php _e( 'Sending...', 'eleventhset' ); ?></span>
						</button>
					</form>
				</div>

			</div>
		</div>
	</section>

	<!-- Map Section (optional embed placeholder) -->
	<section style="height: 400px; background-color: var(--color-light-gray); display:flex; align-items:center; justify-content:center;">
		<div style="text-align:center; color: var(--color-text-secondary);">
			<p class="text-uppercase" style="margin-bottom: 8px;"><?php _e( 'Find Us', 'eleventhset' ); ?></p>
			<p><?php echo esc_html( eleventhset_get_option( 'contact_address', '123 Fashion Ave, New York, NY 10001' ) ); ?></p>
			<!-- Replace with actual Google Maps embed in production -->
		</div>
	</section>

</main>

<?php get_footer(); ?>
