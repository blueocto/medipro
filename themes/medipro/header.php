<!DOCTYPE html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	
	<title><?php wp_title('|', true, 'right'); ?> <?php bloginfo('name'); ?></title>
	<meta name="author" content="Caroline Murphy">
	<meta name="viewport" content="width=device-width">
	
	<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/colorbox.css">

	<!-- Facebook -->
	<meta property="og:title" content="<?php bloginfo('name'); ?>">
	<meta property="og:description" content="">
	<meta property="og:image" content="facebook.png">
	<!-- Apple Icons -->
	<link rel="apple-touch-icon" href="<?php bloginfo('stylesheet_directory'); ?>/apple-touch-icon.png" />

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php bloginfo('url'); ?>/xmlrpc.php?rsd" />
	
	<?php wp_head(); ?>
</head>
<!--[if lt IE 8]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<body <?php body_class(); ?>>

<div id="fb-root"></div>

<div id="contactDetails" class="cont contact_details_cont">
	<div class="innr">

		<div class="get_in_touch">
			<h3>Get In Touch</h3>
			<?php echo do_shortcode( '[contact-form-7 id="22" title="Get In Touch"]' ); ?>
		</div><!-- //get_in_touch -->

		<div class="our_address">
			<h3>Address</h3>
			<p>MediPro Training Ltd<br />Prospect House<br />24 Ellerbeck Court<br />Stokesley<br />North Yorkshire<br />TS9 5PT</p>
			<p>Tel. 0845 8387322<br />Fax. 0845 8387344<br /><a href="mailto:contact@mediprotraining.co.uk">contact@mediprotraining.co.uk</a></p>
			<div id="map-canvas" class="map"></div>
		</div><!-- //our_address -->

	</div><!-- //innr -->
</div><!-- //cont contact_details_cont -->


<div class="cont hdr_cont">
	<div class="innr hdr clrfx findbasket">

		<a class="logo" href="<?php bloginfo('url'); ?>" title="<?php bloginfo('name'); ?>"><span class="visuallyhidden"><?php bloginfo('name'); ?></span></a>

		<div class="cntct_dtls">
			<p class="social_icons">
				<a class="facebook" href="https://www.facebook.com/medipro" title="Medipro on Facebook" rel="external"></a><a class="twitter" href="https://twitter.com/mediprolimited" title="Medipro on Twitter" rel="external"></a><!-- <a class="google" href="#" title="Medipro on Google+" rel="external"></a> -->
			</p>
			<p class="tel_email"><strong>0845 8387322</strong><br /><a href="mailto:contact@mediprotraining.co.uk">contact@mediprotraining.co.uk</a></p>
			<a class="btn btn_arrow_right btn_expand_contact" href="#">Contact Details</a>
		</div><!-- //cntct_dtls -->

		<div class="shop_menu">
			
			<form class="find_course" role="search" method="get" id="searchform" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
				<label class="visuallyhidden" for="s">Search for:</label>
				<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="Find a Course&hellip;" />
				<input type="submit" id="searchsubmit" value="" />
				<input type="hidden" name="post_type" value="product" />
			</form>
			<?php /*
			<form class="find_course">
				<input type="text" placeholder="Find a Course&hellip;" />
				<input type="submit" value="" />
			</form>
			*/ ?>
			
			<a<?php if ( is_user_logged_in() ) {} else { ?> id="colorboxAccount"<?php } ?> class="btn btn_my_account" href="/my-account/">My Account</a>
			
			<a class="btn btn_basket" href="" title="Click to Expland">Basket</a>

		</div><!-- //shop_menu -->
		
	</div><!-- //innr hdr -->
</div><!-- //cont -->

<div class="cont basket_cont">
	<div class="innr basket_innr">
		<div id="basketOverview" class="basket_overview">
			<?php global $woocommerce; do_action( 'woocommerce_before_mini_cart' ); ?>
				
			<table class="shop_table cart" cellspacing="0">
				<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>

					<?php
						foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
							$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
							$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

							if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

								$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
								$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
								$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );

								?>

				<tr>
					<td width="66">
						<?php /* Product Thumbnail */
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key ); 
							if ( ! $_product->is_visible() )
								echo $thumbnail;
							else
								printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );
						?>
					</td>
					<td>
						<?php /* Product Title */
							if ( ! $_product->is_visible() ) 
								echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
							else
								echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a class="basket_title" href="%s">%s</a>', $_product->get_permalink(), $_product->get_title() ), $cart_item, $cart_item_key );
							// Meta data
							echo WC()->cart->get_item_data( $cart_item );
							// Backorder notification
							if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
								echo '<p class="backorder_notification">' . __( 'Available on backorder', 'woocommerce' ) . '</p>';
						?>
						<p>Product Code: <?php echo $_product->sku; ?></p>
					</td>
					<td>
						<p class="prod_price">
							<?php /* Product Price */ 
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
							<span class="quantity">
								( x <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', sprintf( '%s', $cart_item['quantity'], $product_price), $cart_item, $cart_item_key ); ?> )
							</span>
						</p>
						<?php /* Remove */
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">Remove from Basket</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( '', 'woocommerce' ) ), $cart_item_key ); 
						?>
					</td>
				</tr>

				<?php
							}
						}
					?>

				<?php else : ?>

				<tr>
					<td colspan="3">Sorry, no products added to Cart.</td>
				</tr>

				<?php endif; ?>

				<tr>
					<td></td>
					<td></td>
					<td align="right">
						<?php if ( sizeof( WC()->cart->get_cart() ) > 0 ) : ?>
							<p class="totals">
								Sub Total : <?php echo WC()->cart->get_cart_subtotal(); ?><br />
								Delivery : FREE<br />
								<span class="total">Total : <?php echo $woocommerce->cart->get_cart_total(); ?></span>
							</p>
							<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
							<a href="<?php echo WC()->cart->get_checkout_url(); ?>" class="btn btn_arrow_right btn_checkout"><?php _e( 'Checkout', 'woocommerce' ); ?></a>
						<?php endif; ?>
					</td>
				</tr>
			</table>

			<?php do_action( 'woocommerce_after_mini_cart' ); ?>
		</div><!-- //basket_overview -->
	</div>
</div>


<div class="cont nav">
	<div class="innr">
	<ul class="main_nav">
			<li><a href="<?php bloginfo('url'); ?>">Home</a></li>
			<li class="dropdown_1">
				<a href="/product-category/courses/">Courses</a>
				
				<div class="sub_menu">
					
					<div class="categories">
						<h4>Categories</h4>
						<?php wp_nav_menu( array('container' => false, 'menu'=> 'Courses : Categories' )); ?>
						<?php /* ?>
						<ul>
							<li><a href="#">Offshore Medic</a></li>
							<li><a href="#">Diving Medical Technician</a></li>
							<li><a href="#">Trauma Care</a></li>
							<li class="dropdown_2">
								<a href="#">Remote Medicine</a>
								<ul class="sub_sub_menu">
									<li><a href="#">ABCs of Wound Care for the HCA</a></li>
									<li><a href="#">Abdominal and Gynae Workshop</a></li>
									<li><a href="#">Acute Asthma Workshop</a></li>
									<li><a href="#">Acute Back Pain</a></li>
									<li><a href="#">Ambulance Emergency Care Support Worker, RSC Ed</a></li>
									<li><a href="#">Automated External Defibrillator (AED)</a></li>
									<li><a href="#">Clinical History Taking &amp; Physical Examination Skills</a></li>
									<li><a href="#">eACLS</a></li>
									<li><a href="#">ECG Recognition Part 1</a></li>
								</ul>
							</li>
							<li><a href="#">Primary Health Care</a></li>
							<li><a href="#">First Aid</a></li>
							<li><a href="#">Mimms ( Major Incident )</a></li>
							<li><a href="#">Pre Hospital Responder / Emt</a></li>
							<li><a href="#">Online Learning</a></li>
							<li><a href="#">Maritime</a></li>
							<li><a href="#">Tactical Medic</a></li>
							<li><a href="#">Paramedic Skills</a></li>
						</ul>
						<?php */ ?>
					</div><!-- //categories -->
					
					<div class="more_info_courses">
						<h4>More Information On Courses</h4>
						<?php wp_nav_menu( array('container' => false, 'menu'=> 'Courses : More Information' )); ?>
						<?php /* ?>
						<ul>
							<li><a href="/about-our-facilities/">Our Facilities</a></li>
							<li><a href="/our-approving-bodies/">Approving Bodies</a></li>
							<li><a href="http://medipro.caroline-murphy.co.uk/our-staff/">Our Staff</a></li>
							<li><a href="#">Page Name</a></li>
							<li><a href="#">Page Name</a></li>
						</ul>
						<?php */ ?>
					</div><!-- //more_info_courses -->
				</div><!-- //sub_menu -->

			</li>
			<li><a href="/product-category/equipment/">Equipment</a></li>
			<li><a href="/clinical-governance/">Clinical Governance</a></li>
			<li><a href="/brochures/">Brochures</a></li>
			<li><a href="/about/">About</a></li>
			<li><a href="/latest-news/">Latest News</a></li>
		</ul>
	</div><!-- //innr -->
</div><!-- //cont nav -->