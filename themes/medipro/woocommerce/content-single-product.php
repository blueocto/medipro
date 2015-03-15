<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<?php
	$product_cats = get_the_terms( $post->ID, 'product_cat' );
	if ( $product_cats && ! is_wp_error ( $product_cats ) ) {
		$single_cat = array_shift( $product_cats );

	/* ==========================================================================
	Courses Product page
	========================================================================== */
	if ($single_cat->slug == 'courses') { ?>

	<div class="cont">
		<div class="innr brdcrmbs">
			<ul class="breadcrumbs">
				<li><a href="/">Home</a></li>
				<li>&gt;</li>
				<li><a href="/product-category/courses/">Courses</a></li>
				<li>&gt;</li>
				<li class="course_subs">
					<?php /* if there is a better way to do this, be my guest! */
						global $post, $product;
						$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
						echo $product->get_categories( '', '', _n( '', '', $cat_count, '' ) );
					?>
				</li>
				<li>&gt;</li>
				<li><?php the_title(); ?></li>
			</ul>
		</div><!-- //innr brdcrmbs -->
	</div><!-- //cont breadcrumbs -->


	<div class="cont usps">
		<div class="innr">
			<?php if( get_field( 'usp_left', 72 ) ): ?>
			<h5 class="usp"><?php the_field('usp_left', 72); ?></h5>
			<?php endif; ?>
			<?php if( get_field( 'usp_center', 72 ) ): ?>
			<h5 class="usp"><?php the_field('usp_center', 72); ?></h5>
			<?php endif; ?>
			<?php if( get_field( 'usp_right', 72 ) ): ?>
			<h5 class="usp"><?php the_field('usp_right', 72); ?></h5>
			<?php endif; ?>
		</div><!-- //innr -->
	</div><!-- //cont usps -->


	<div class="cont shrng">
		<div class="innr clrfx">
			<div class="sharing_btns">
				<div class="fb-like" data-href="https://www.facebook.com/medipro" data-width="10" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="https://twitter.com/mediprolimited" data-text="Check out this course!" data-via="mediprolimited" data-dnt="true">Tweet</a>
				<div class="g-plusone" data-size="medium" data-annotation="inline" data-width="190"></div>
			</div><!-- //sharing_btns -->
		</div><!-- //innr clrfx -->
	</div><!-- //cont shrng -->


	<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="cont main">

			<div class="innr sldr cat_hero course_hero">
				<div class="slides">
					<div class="slide clrfx">
						<?php if( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail() ) { the_post_thumbnail( array(725,725) ); } ?>
						<div class="slide_content">
							<h1><?php the_title(); ?></h1>
						</div>
					</div><!-- //slide clrfx -->
				</div><!-- //slides -->
			</div><!-- //innr sldr cat_hero course_hero -->

			<div class="innr">

				<div class="course_ovrvw clrfx">
					<div class="course_ovrvw_cntnt">
						<h3>Duration : <?php $duration = the_field('duration'); print_r($duration); ?></h3>
						<p class="criteria"><?php $criteria = the_field('criteria'); print_r($criteria); ?></p>
						<h4><?php $firstheading = the_field('first_heading'); print_r($firstheading); ?></h4>
						<p class="lrg"><?php $secondheading = the_field('second_heading'); print_r($secondheading); ?></p>
					</div><!-- //course_ovrvw_cntnt -->
					<a class="viewdates_bookcourse" href="#courseDates">
						<img src="<?php bloginfo('template_url'); ?>/img/icon-viewdates.png" width="59" height="59" alt="" />
						View Dates<br />&amp; Book Course
					</a>
				</div><!-- //course_overview clrfx -->

				<div class="crs_tbs">
					<ul id="tabsTop" class="course_tabs">
						<li class="active" rel="tab1">Overview</li>
						<li rel="tab2">Syllabus</li>
						<li rel="tab3">Certification</li>
						<li rel="tab4">Pre-Requisites</li>
						<li rel="tab5">Downloads</li>
						<li rel="tab6">Where to Stay</li>
					</ul>
					<div class="course_tab_cont">
						<h3 class="d_active tab_drawer_heading" rel="tab1">Overview</h3>
						<div id="tab1" class="tab_content">
							<?php $overview = the_field('overview'); print_r($overview); ?>
						</div>
						<!-- //tab1 -->
						<h3 class="tab_drawer_heading" rel="tab2">Syllabus</h3>
						<div id="tab2" class="tab_content">
							<?php $syllabus = the_field('syllabus'); print_r($syllabus); ?>
						</div>
						<!-- //tab2 -->
						<h3 class="tab_drawer_heading" rel="tab3">Certification</h3>
						<div id="tab3" class="tab_content">
							<?php $certification = the_field('certification'); print_r($certification); ?>
						</div>
						<!-- //tab3 -->
						<h3 class="tab_drawer_heading" rel="tab4">Pre-Requisites</h3>
						<div id="tab4" class="tab_content">
							<?php $prerequisites = the_field('pre-requisites'); print_r($prerequisites); ?>
						</div>
						<!-- //tab4 -->
						<h3 class="tab_drawer_heading" rel="tab5">Downloads</h3>
						<div id="tab5" class="tab_content">
							<?php $downloads = the_field('downloads'); print_r($downloads); ?>
						</div>
						<!-- //tab4 -->
						<h3 class="tab_drawer_heading" rel="tab6">Where to Stay</h3>
						<div id="tab6" class="tab_content">
							<?php $wheretostay = the_field('where_to_stay'); print_r($wheretostay); ?>
						</div>
						<!-- //tab5 -->
					</div><!-- //course_tab_cont -->
				</div><!-- //crs_tbs -->

				<div id="courseDates" class="course_dates">

					<h2>Course Dates</h2>
					<table class="crs_dts_tbl" width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<th scope="col">Year</th>
							<th scope="col">Starts</th>
							<th scope="col">Ends</th>
							<th scope="col">Status</th>
							<th scope="col">Notes</th>
							<th scope="col">&nbsp;</th>
						</tr>
					<?php
                        $variations = $product->get_available_variations();
                        foreach ($variations as $variation) :
                            $date_time = (isset($variation['attributes']['attribute_course-date-time'])) ? $variation['attributes']['attribute_course-date-time'] : false;
                            if ($date_time) :
                                // Split the variation slug into start and end
                                $split = strpos($date_time, '-to-');
                                $start = substr($date_time, 0, $split);
                                $end = substr($date_time, $split + 4);

                                // Split the pattern value into bits
                                $pattern = '/([0-9]+[thrndst]+)-([a-z]+)-([0-9]+)-([0-9]+[amp]+)-([0-9]+[amp]+)/';
                                preg_match($pattern, $start, $start_matches);
                                preg_match($pattern, $end, $end_matches);

                                // Split and format times
                                $start_time = $start_matches[4];
                                $start_split = (strlen($start_time) == 5) ? 1 : 2;
                                $start_time =  substr($start_time, 0, $start_split) .':'. substr($start_time, $start_split);

                                $end_time = $end_matches[5];
                                $end_split = (strlen($end_time) == 5) ? 1 : 2;
                                $end_time =  substr($end_time, 0, $end_split) .':'. substr($end_time, $end_split);

                                $status = str_replace(' in stock', '', strip_tags($variation['availability_html']));
                                if (strtolower($status) == 'out of stock') {
                                    $status = 'Unavailable';
                                    $spaces = 0;
                                } else {
                                    $spaces = (int)$status;
                                    $status .= ($spaces > 1) ? ' spaces' : ' space';
                                }

                                if ($spaces == 0) {
                                    $notes = 'Full';
                                } elseif ($spaces <= 5) {
                                    $notes = 'Almost full';
                                } else {
                                    $notes = 'Spaces Available';
                                }

                                $course_data = array(
                                    'year'                  => $end_matches[3],
                                    'start_date_and_month'  => $start_matches[1] .' '. ucfirst($start_matches[2]),
                                    'start_time'            => $start_time,
                                    'end_date_and_month'    => $end_matches[1] .' '. ucfirst($end_matches[2]),
                                    'end_time'              => $end_time,
                                    'status'                => $status,
                                    'notes'                 => $notes,
                                );

                            // Build a date object using the course data attributes
                            $date_start = new DateTime($course_data['start_date_and_month'] . ' '. $course_data['year'] .' '. $course_data['start_time']);
                            $date_end = new DateTime($course_data['end_date_and_month'] . ' '. $course_data['year'] .' '. $course_data['end_time']);

                            $start_date = $date_start->format('dS F Y, g:ia');
                            $end_date = $date_end->format('dS F Y, g:ia');

                            $variant = strtolower($date_start->format('jS-F-Y') .'-to-'. $date_end->format('jS-F-Y'));
					?>
						<tr>
							<td><?php echo $course_data['year']; ?></td>
							<td><?php echo $course_data['start_date_and_month']; ?>, <?php echo $course_data['start_time']; ?></td>
							<td><?php echo $course_data['end_date_and_month']; ?>, <?php echo $course_data['end_time']; ?></td>
							<td><?php echo $course_data['status']; ?></td>
							<td><?php echo $course_data['notes']; ?></td>
							<td><a class="btn btn_selectdate btn_coursedate" href="#" data-start="<?php echo $start_date; ?>" data-end="<?php echo $end_date; ?>" data-variant="<?php echo $date_time ?>">Select Date</a></td>
						</tr>
					<?php
                            endif;
						endforeach;
					?>
					</table>

					<div class="crs_slctd_book clrfx">
						<div class="course_selected">
							<p class="lrg">Your Selected date for</p>
							<h2><?php the_title(); ?></h2>
							<p class="lrg">
								<strong><!-- 2014 --></strong><br />
								<strong class="selected_start">Start - <span></span></strong> <!-- 06 January, 09:00 --><br />
								<strong class="selected_end">End - <span></span></strong> <!-- 17 January, 17:00 -->
							</p>
						</div><!-- //course_selected -->
						<div class="book_course">
							<p class="lrg">Your Price :</p>
							<p class="course_price"><?php echo $product->get_price_html(); ?><br />
								<span class="course_vat">per person inc. VAT</span>
							</p>
                            <?php do_action( 'woocommerce_single_product_summary' ); ?>
						</div><!-- //book_course -->
					</div><!-- //crs_slctd_book clrfx -->
				</div><!-- //course_dates -->

				<div class="course_location">
					<div id="map" class="course_map"></div>
					<p class="course_address">
						<a class="map_pin" href="https://goo.gl/maps/4VL6d" rel="external">View on Google Maps</a><br /><br />
						<strong>MediPro Training Ltd.</strong><br />
						Prospect House, 24 Ellerbeck Court, Stokesley<br />
						North Yorkshire<br />
						TS9 5PT<br />
						Tel. 0845 8387322<br /><br />
						This course can also be undertaken at your workplace (UK only/minimum numbers apply). <a href="#">Contact us</a> for more information.
					</p>
				</div><!-- //course_location -->

			</div><!-- //innr -->
		</div><!-- //cont main -->

		<meta itemprop="url" content="<?php the_permalink(); ?>" />

	</div><!-- //itemscope #product-<?php the_ID(); ?> -->

	<?php
		/* ==========================================================================
		Equipment Product page
		========================================================================== */
		} else {
	?>


	<div class="cont">
		<div class="innr brdcrmbs">
			<ul class="breadcrumbs">
				<li><a href="/">Home</a></li>
				<li>&gt;</li>
				<li><a href="/product-category/equipment/">Equipment</a></li>
				<li>&gt;</li>
				<li><a href="/product-category/equipment/<?php echo $single_cat->slug; ?>/"><?php echo $single_cat->name; ?></a></li>
				<li>&gt;</li>
				<li><?php the_title(); ?></li>
			</ul>
		</div><!-- //innr brdcrmbs -->
	</div><!-- //cont breadcrumbs -->


	<div class="cont usps">
		<div class="innr">
			<?php if( get_field( 'usp_left', 63 ) ): ?>
			<h5 class="usp"><?php the_field('usp_left', 63); ?></h5>
			<?php endif; ?>
			<?php if( get_field( 'usp_center', 63 ) ): ?>
			<h5 class="usp"><?php the_field('usp_center', 63); ?></h5>
			<?php endif; ?>
			<?php if( get_field( 'usp_right', 63 ) ): ?>
			<h5 class="usp"><?php the_field('usp_right', 63); ?></h5>
			<?php endif; ?>
		</div><!-- //innr -->
	</div><!-- //cont usps -->


	<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class("innr"); ?>>

		<div class="prod-images_prod-summary">

			<div class="prod_images">
			<?php
				/**
				 * woocommerce_before_single_product_summary hook
				 *
				 * @hooked woocommerce_show_product_sale_flash - 10
				 * @hooked woocommerce_show_product_images - 20
				 */
				do_action( 'woocommerce_before_single_product_summary' );
			?>
			</div><!-- //prod_images -->

			<div class="prod_summary">
				<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>

				<?php /* SKU */
					global $post, $product;
					if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) :
				?>
					<p class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'n/a', 'woocommerce' ); ?></span>.</p>
				<?php endif; ?>

				<?php /* In Stock? */
					$availability = $product->get_availability();
					if ( $availability['availability'] ) echo apply_filters( 'woocommerce_stock_html', '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>', $availability['availability'] );
				?>

				<ul id="tabsTop" class="tabs">
					<li class="active" rel="tab1">Product Info</li>
					<li rel="tab2">Delivery</li>
				</ul>
				<div class="tab_container">
					<h3 class="d_active tab_drawer_heading" rel="tab1">Product Info</h3>
					<div id="tab1" class="tab_content">
						<?php
							global $post;
							if ( ! $post->post_excerpt ) return;
							echo apply_filters( 'woocommerce_short_description', $post->post_excerpt )
						?>
						<p><a class="desc_readmore" href="#prod_information">Read More &gt;</a></p>
					</div>
					<!-- //tab1 -->
					<h3 class="tab_drawer_heading" rel="tab2">Delivery</h3>
					<div id="tab2" class="tab_content">
						<p>Delivery info here...</p>
					</div>
					<!-- //tab2 -->
				</div><!-- //tab_container -->

				<div class="cart_elements clrfx">
					<?php /* Product price */ ?>
					<p class="price"><?php echo $product->get_price_html(); ?></p>

					<?php /* Quantity selector */
						if ( ! $product->is_sold_individually() ) woocommerce_quantity_input( array(
							'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
							'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
						) );
					?>

					<?php /* Add to Basket button */
						if ( $product->is_in_stock() ) :
					?>
						<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
						<form class="cart" method="post" enctype='multipart/form-data'>
							<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>
							<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />
							<button class="btn btn_addtobasket" type="submit">Add to Basket</button>
							<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
						</form>
						<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
					<?php endif; ?>
				</div><!-- //cart_elements clrfx -->
			</div><!-- //prod_summary -->

		</div><!-- //prod-images_prod-summary -->

		<div class="prod-info_prod-reviews">

			<div id="prod_information" class="prod_information">
				<h2>Product Information</h2>
				<?php
					global $woocommerce, $post;
					the_content();
				?>
			</div><!-- //prod_information -->

			<div class="product_reviews">

				<?php /* ?>
				<h2>Product Reviews</h2>
				<?php // tabs have disabled in functions.php
					woocommerce_output_product_data_tabs();
				?>
				<?php */ ?>

				<div class="prod_research_news">
					<h3>Product Research and News</h3>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ut gravida dolor. Phasellus posuere ante ante, eget ultrices nulla venenatis eu. Integer pharetra vulputate</p>
					<p><a class="readmore" href="/latest-news/">Read More &gt;</a></p>
				</div><!-- //prod_research_news -->

			</div><!-- //product_reviews -->

		</div><!-- //prod-info_prod-reviews -->

		<?php /* Related Products */
			global $product, $woocommerce_loop;
			$related = $product->get_related( $posts_per_page );
			//if ( sizeof( $related ) == 0 ) return;  /* commented out, as creating extra div when no products... bizarre */
			$args = apply_filters( 'woocommerce_related_products_args', array(
				'post_type' => 'product',
				'ignore_sticky_posts' => 1,
				'no_found_rows' => 1,
				'posts_per_page' => $posts_per_page,
				'orderby' => $orderby,
				'post__in' => $related,
				'post__not_in' => array( $product->id )
			) );
			$products = new WP_Query( $args );
			$woocommerce_loop['columns'] = $columns;
			if ( $products->have_posts() ) :
		?><!-- //related products, if products -->
		<div class="related_products">
			<h2>Related Products</h2>

			<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
				<?php wc_get_template_part( 'content', 'product' ); ?>
			<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

		</div><!-- //related_products -->
		<?php endif; wp_reset_postdata(); ?>

		<meta itemprop="url" content="<?php the_permalink(); ?>" />

	</div><!-- //itemscope #product-<?php the_ID(); ?> -->

<?php
	/* ==========================================================================
	End If/Else Statement
	========================================================================== */
		}
	}
?>

<?php do_action( 'woocommerce_after_single_product' ); ?>
