<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head>
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width, maximum-scale=1" />
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php esc_url(bloginfo('pingback_url')); ?>" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/hover.css/2.1.1/css/hover-min.css" />
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<?php if (rigid_get_option('show_preloader')): ?>
			<div class="mask">
				<div id="spinner"><div class="double-bounce1"></div><div class="double-bounce2"></div>
				</div>
			</div>
		<?php endif; ?>

		<?php if (rigid_get_option('add_to_cart_sound')): ?>
			<audio id="cart_add_sound" controls preload="auto" hidden="hidden">
				<source src="<?php echo get_template_directory_uri(); ?>/js/cart_add.wav" type="audio/wav">
			</audio>
		<?php endif; ?>

		<?php
		global $rigid_is_blank;

		// Set main menu as mobile if no mobile menu was set
		$mobile_menu_id  = 'primary';
		if ( has_nav_menu('mobile') ) {
			$mobile_menu_id = "mobile";
		}

		if (!$rigid_is_blank) {
			// Top mobile menu
			$rigid_top_nav_mobile_args = array(
					'theme_location' => $mobile_menu_id,
					'container' => 'div',
					'container_id' => 'menu_mobile',
					'menu_id' => 'mobile-menu',
					'items_wrap' => '<a class="mob-close-toggle"></a><ul id="%1$s" class="%2$s">%3$s</ul>',
					'fallback_cb' => '',
			);
			wp_nav_menu($rigid_top_nav_mobile_args);
		}

		// Are search or cart enabled or is account page
		$rigid_is_search_or_cart_or_account = false;
		if (rigid_get_option('show_searchform') || (RIGID_IS_WOOCOMMERCE && rigid_get_option('show_shopping_cart'))|| (RIGID_IS_WOOCOMMERCE && get_option( 'woocommerce_myaccount_page_id' ))) {
			$rigid_is_search_or_cart_or_account = true;
		}

		$rigid_is_left_header = false;

		$rigid_general_layout = rigid_get_option('general_layout');
		$rigid_specific_layout = get_post_meta(get_queried_object_id(), 'rigid_layout', true);

		if ($rigid_general_layout == 'rigid_header_left' || $rigid_specific_layout == 'rigid_header_left') {
			$rigid_is_left_header = true;
		}

		$rigid_meta_show_pre_header = get_post_meta(get_queried_object_id(), 'rigid_top_header', true);
		if (!$rigid_meta_show_pre_header) {
			$rigid_meta_show_pre_header = 'default';
		}

		$rigid_featured_slider = get_post_meta(get_queried_object_id(), 'rigid_rev_slider', true);
		if (!$rigid_featured_slider) {
			$rigid_featured_slider = 'none';
		}

		$rigid_rev_slider_before_header = get_post_meta(get_queried_object_id(), 'rigid_rev_slider_before_header', true);
		if (!$rigid_rev_slider_before_header) {
			$rigid_rev_slider_before_header = 0;
		}
		?>
		<?php if (rigid_get_option('show_searchform')): ?>
            <div id="search">
				<?php $rigid_search_options = rigid_get_option('search_options'); ?>
				<?php if (RIGID_IS_WOOCOMMERCE && isset($rigid_search_options['only_products']) && $rigid_search_options['only_products']): ?>
					<?php get_product_search_form(true) ?>
				<?php else: ?>
					<?php get_search_form(); ?>
				<?php endif; ?>
            </div>
		<?php endif; ?>
		<!-- MAIN WRAPPER -->
		<div id="container">
			<!-- If it is not a blank page template -->
			<?php if (!$rigid_is_blank): ?>
				<?php if (is_page() && $rigid_featured_slider != 'none' && function_exists('putRevSlider') && $rigid_rev_slider_before_header): ?>
					<!-- FEATURED REVOLUTION SLIDER -->
					<div class="rigid-intro slideshow">
						<div class="inner">
							<?php putRevSlider($rigid_featured_slider) ?>
						</div>
					</div>
					<!-- END OF FEATURED REVOLUTION SLIDER -->
				<?php endif; ?>
				<!-- Collapsible Pre-Header -->
				<?php if (rigid_get_option('enable_pre_header') && is_active_sidebar('pre_header_sidebar')): ?>
					<div id="pre_header"> <a href="#" class="toggler" id="toggle_switch" title="<?php esc_html_e('Show/Hide', 'rigid') ?>"><?php esc_html_e('Slide toggle', 'rigid') ?></a>
						<div id="togglerone" class="inner">
							<!-- Pre-Header widget area -->
							<?php dynamic_sidebar('pre_header_sidebar') ?>
							<div class="clear"></div>
						</div>
					</div>
				<?php endif; ?>
				<!-- END Collapsible Pre-Header -->
				<!-- HEADER -->
				<div id="header">

					<div class="inner main_menu_holder fixed">
						<?php
						$rigid_theme_logo_img = rigid_get_option('theme_logo');
						$rigid_transparent_theme_logo_img = rigid_get_option('transparent_theme_logo');

						// If there is no secondary logo add 'persistent_logo' class to the main logo
						$rigid_persistent_logo_class = $rigid_transparent_theme_logo_img ? '' : 'persistent_logo';

						if (!$rigid_theme_logo_img && !$rigid_transparent_theme_logo_img && (get_bloginfo('name') || get_bloginfo('description'))) {
							$rigid_is_text_logo = true;
						} else {
							$rigid_is_text_logo = false;
						}
						?>
						<div <?php if ($rigid_is_text_logo) echo 'class="rigid_text_logo"' ?> id="logo">
							<a href="<?php echo esc_url(rigid_wpml_get_home_url()); ?>"  title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
								<?php
								// Main logo
								if ($rigid_theme_logo_img) {
									echo wp_get_attachment_image($rigid_theme_logo_img, 'full', false, array('class' => esc_attr($rigid_persistent_logo_class)));
								}

								// Secondary logo
								if ($rigid_transparent_theme_logo_img) {
									echo wp_get_attachment_image($rigid_transparent_theme_logo_img, 'full', false, array('class' => 'transparent_logo'));
								}
								?>
								<?php if ($rigid_is_text_logo): ?>
									<span class="rigid-logo-title"><?php bloginfo('name') ?></span>
									<span class="rigid-logo-subtitle"><?php bloginfo('description') ?></span>
								<?php endif; ?>
							</a>
						</div>
						
						<div class="search-b">

                                    <div class="rigid-search-trigger">
                                        <a href="#" title="<?php echo esc_html__('Search', 'rigid') ?>"><div class="searchb-trig"><img width="300px;" src="https://dev.trifink.com/wp-content/uploads/2017/11/search-h.png"/></div></a>
                                    </div>


						</div>
						<a class="mob-menu-toggle" href="#"><i class="fa fa-bars"></i></a>

						<?php if ($rigid_is_search_or_cart_or_account): ?>
							<div class="rigid-search-cart-holder">
								

								<!-- SHOPPING CART -->
								<?php if (RIGID_IS_WOOCOMMERCE && rigid_get_option('show_shopping_cart')): ?>
									<ul id="cart-module" class="site-header-cart">
										<?php rigid_cart_link(); ?>
										<li>
											<?php the_widget('WC_Widget_Cart', 'title='); ?>
										</li>
									</ul>
								<?php endif; ?>
								<!-- END OF SHOPPING CART -->

								<?php if (RIGID_IS_WOOCOMMERCE && RIGID_IS_WISHLIST && rigid_get_option('show_wish_in_header')): ?>
									<div class="rigid-wishlist-counter">
										<a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>" title="<?php echo esc_html__('Wishlist', 'rigid') ?>">
											<i class="fa fa-star"></i>
											<span class="rigid-wish-number"><?php echo esc_html(YITH_WCWL()->count_products()); ?></span>
										</a>
									</div>
								<?php endif; ?>

								<?php if (RIGID_IS_WOOCOMMERCE && rigid_get_option('show_my_account') && get_option( 'woocommerce_myaccount_page_id' ) ): ?>
									<?php global $current_user; ?>
									<?php wp_get_current_user(); ?>
                                    <div id="rigid-account-holder">
                                        <a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" title="<?php esc_html_e( 'My Account', 'rigid' ); ?>">
                                            <i class="fa fa-user"></i>
                                        </a>
                                    </div>
								<?php endif; ?>

							</div>
						<?php endif; ?>
						<?php
						// Top menu
						$rigid_top_menu_container_class = 'menu-main-menu-container';
						if (rigid_get_option('menu_accent_style')) {
							$rigid_top_menu_container_class .= ' ' . rigid_get_option('menu_accent_style');
						}
						$rigid_top_nav_args = array(
								'theme_location' => 'primary',
								'container' => 'div',
								'container_id' => 'main-menu',
								'container_class' => $rigid_top_menu_container_class,
								'menu_id' => 'main_nav',
								'fallback_cb' => '',
								'walker' => new RigidFrontWalker()
						);
						wp_nav_menu($rigid_top_nav_args);
						?>
						<?php
								/* Secondary menu */
								$rigid_side_nav_args = array(
										'theme_location' => 'secondary',
										'container' => 'div',
										'container_id' => 'menu',
										'menu_class' => '',
										'menu_id' => 'topnav2',
										'fallback_cb' => '',
								);
								wp_nav_menu($rigid_side_nav_args);
								?>

					</div>
					
										<?php if (rigid_get_option('enable_top_header') && $rigid_meta_show_pre_header == 'default' || $rigid_meta_show_pre_header == 'show'): ?>
						<div id="header_top" class="fixed">
							<div class="inner">
								<?php if (function_exists('icl_get_languages')): ?>
									<div id="language">
										<?php rigid_language_selector_flags(); ?>
									</div>
								<?php endif; ?>
								<!--	Social profiles in header-->
								<?php if (rigid_get_option('social_in_header')): ?>
									<?php get_template_part('partials/social-profiles'); ?>
								<?php endif; ?>
								<?php if (rigid_get_option('top_bar_message') || rigid_get_option('top_bar_message_phone') || rigid_get_option('top_bar_message_email')): ?>
									<div class="rigid-top-bar-message">
										<?php echo esc_html(rigid_get_option('top_bar_message')) ?>
										<?php if (rigid_get_option('top_bar_message_email')): ?>
											<span class="rigid-top-bar-mail">
												<?php if ( rigid_get_option( 'top_bar_message_email_link' ) ): ?><a href="mailto:<?php echo esc_html( rigid_get_option( 'top_bar_message_email' ) ) ?>"><?php endif; ?>
													<?php echo esc_html(rigid_get_option('top_bar_message_email')) ?>
												<?php if ( rigid_get_option( 'top_bar_message_email_link' ) ): ?></a><?php endif; ?>
											</span>
										<?php endif; ?>
										<?php if (rigid_get_option('top_bar_message_phone')): ?>
											<span class="rigid-top-bar-phone">
												<?php if ( rigid_get_option( 'top_bar_message_phone_link' ) ): ?><a href="tel:<?php echo preg_replace( "/[^0-9+-]/", "", esc_html( rigid_get_option( 'top_bar_message_phone' ) ) ) ?>"><?php endif; ?>
													<?php echo esc_html( rigid_get_option( 'top_bar_message_phone' ) ) ?>
												<?php if ( rigid_get_option( 'top_bar_message_phone_link' ) ): ?></a><?php endif; ?>
											</span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								</div>
						</div>
					<?php endif; ?>

				</div>
				<!-- END OF HEADER -->
			<?php endif; ?>