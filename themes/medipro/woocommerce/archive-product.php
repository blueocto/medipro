<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wp_query;
$page_cat = $wp_query->get_queried_object();

get_header( 'shop' ); ?>

	<div class="cont">
		<div class="innr brdcrmbs">
			<ul class="breadcrumbs">
				<li><a href="/">Home</a></li>
				<li>&gt;</li>
				<li><?php woocommerce_page_title(); ?></li>
			</ul>
		</div><!-- //innr brdcrmbs -->
	</div><!-- //cont nav -->

	<?php
		/* ==========================================================================
		Courses Category page
		========================================================================== */
		if (is_product_category( 'courses' ) ) {
	?>


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
			</div><!-- //innr -->
		</div><!-- //cont usps -->


		<div class="cont main">

			<?php if( get_field( 'hero_title', 72 ) ): ?>
			<div class="innr sldr cat_hero course_hero">
				<div class="slides">
					<div class="slide clrfx">
						<img src="<?php the_field('hero_image', 72); ?>" width="725" height="210" alt="" />
						<div class="slide_content">
							<h2><?php the_field('hero_title', 72); ?></h2>
							<p><?php the_field('hero_content', 72); ?></p>
						</div>
					</div><!-- //slide -->
				</div><!-- //slides -->
			</div><!-- //innr sldr -->
			<?php endif; ?>

			<div class="innr course_list">
			<?php
				$args = array(
					'taxonomy' => 'product_cat',
					'orderby' => 'name',
					'pad_counts' => 0,
					'hierarchical' => 1,
					'title_li' => '',
					'hide_empty' => 0,
					'exclude' => '15' //equipment cat ID
				);
				$all_categories = get_categories( $args );
				foreach ($all_categories as $cat) {
					if($cat->category_parent == 0) {
						$category_id = $cat->term_id;
						$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
						$image = wp_get_attachment_url( $thumbnail_id ); ?>

						<?php
							$args2 = array(
								'taxonomy' => 'product_cat',
								'child_of' => 0,
								'parent' => $category_id,
								'orderby' => 'name',
								'show_count' => 0,
								'pad_counts' => 0,
								'hierarchical' => 1,
								'title_li' => '',
								'hide_empty' => 0
							);
							$sub_cats = get_categories( $args2 );
							if($sub_cats) {
								$rows = array_chunk($sub_cats, 3);
								foreach ($rows as $row) {
									echo "<div class='row'>";
									foreach($row as $sub_category) { ?>
									<div class="course_item_ovrvw" id="<?php echo $sub_category->slug; ?>">
										<img class="course_img" src="http://placehold.it/316x154" alt="" />
										<h2 class="course_cat_title"><?php echo $sub_category->cat_name; ?></h2>
										<p class="course_cat_desc"><?php echo $sub_category->description; ?></p>
										<a class="btn btn_viewcourses" href="#">View Courses</a>
										<div class="course_extd">
											<div class="course_extd_innr">
												<a data-parent="<?php echo $sub_category->slug; ?>" class="close" href="" title="Close"></a>
												<?php if(is_object($sub_cats) && $sub_cats->$sub_category == 0) { ?>
													<?php
														$args = array( 'post_type' => 'product','product_cat' => $sub_category->slug);
														$loop = new WP_Query( $args ); ?>
														<div class="list_of_courses">
														<?php while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
															<h4 class="course_title"><?php the_title(); ?></h4>
															<p class="course_price"><?php echo $product->get_price_html(); ?><br />
																<span class="course_vat">per person inc. VAT</span>
															</p>
															<a class="btn btn_moreinfo" href="<?php echo get_permalink( $loop->post->ID ) ?>">More Information</a>
															<?php /* ?>
															<p>
																<a href="<?php echo get_permalink( $loop->post->ID ) ?>" title="<?php echo esc_attr($loop->post->post_title ? $loop->post->post_title : $loop->post->ID); ?>"><?php the_title(); ?></a>
															</p>
															<?php */ ?>
														<?php endwhile; ?>
														</div><!-- //list_of_courses -->
												<?php } //endif ?>
											</div><!-- //course_extd_innr -->
										</div><!-- //course_extd -->
									</div><!-- //course_item_ovrvw -->
			<?php

									} //endforeach
									echo "</div><!--//row -->";
								} //endforeach
							} //endif
					} //endif
				} //endforeach
				wp_reset_query();
			?>
			</div><!-- //innr course_list -->
		</div><!-- //cont main -->


	<?php
		/* ==========================================================================
		Equipment Category page
		========================================================================== */
		} elseif (is_product_category( 'equipment' ) ) {
	?>


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

		<div class="cont main">
			<div class="innr sldr">
				<div id="slides" class="slides">
					<?php if( have_rows('equip_slides', 63) ): while ( have_rows('equip_slides', 63) ) : the_row(); ?>
					<div class="slide clrfx">
						<img src="<?php the_sub_field('equip_slide'); ?>" width="725" height="366" alt="" />
						<div class="slide_content">
							<h2><?php the_sub_field('equip_title'); ?></h2>
							<p><?php the_sub_field('equip_content'); ?></p>
							<a class="btn btn_arrow_right btn_continue" href="<?php the_sub_field('equip_link'); ?>">Continue</a>
						</div>
					</div><!-- //slide -->
					<?php endwhile; else : endif; ?>
				</div><!-- //slides -->
				<div class="coins"></div>
				<div class="prev_next">
					<a class="prev" href="#" title="Previous"></a>
					<a class="next" href="#" title="Next"></a>
				</div>
			</div><!-- //innr sldr -->

			<div class="innr dlvry_pymnt">
				<div class="free_delivery">
					<h5>FREE Delivery<br />On All Uk Orders</h5>
					<img src="<?php bloginfo('template_url'); ?>/img/icon-van.png" width="132" height="58" alt="" />
				</div><!-- //free_delivery -->
				<div class="payment_options">
					<h5>Available Payment Options...</h5>
					<img src="<?php bloginfo('template_url'); ?>/img/icon-payment-options.png" width="422" height="46" alt="" />
				</div><!-- //payment_options -->
			</div><!-- //innr dlvry_pymnt -->


			<?php if ( have_posts() ) : ?>

				<ul class="innr srvcs equip_cats">

					<?php woocommerce_product_subcategories(); ?>
					<?php while ( have_posts() ) : the_post(); endwhile; ?>

				</ul><!-- //innr srvcs equip_cats -->

			<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

				<?php wc_get_template( 'loop/no-products-found.php' ); ?>

			<?php endif; ?>

			<?php if( get_field( 'equipment_category_title', 63 ) ): ?>
			<div class="innr content_equip">
				<h2><?php the_field('equipment_category_title', 63); ?></h2>
				<p><?php the_field('equipment_category_content', 63); ?></p>
			</div><!-- //innr content_equip -->
			<?php endif; ?>

		</div><!-- //cont main -->

		<div class="cont mlnglst">
			<div class="innr mailing_list">
				<h4>Sign up to our Mailing List</h4>
				<form class="mailinglist_form">
					<input type="text" placeholder="Enter Email Address" />
					<input class="btn_submit_mailing" type="submit" value="" />
				</form>
			</div><!-- //cont mailing_list -->
		</div><!-- //cont mlnglst -->


	<?php
		/* ==========================================================================
		Any other Category page
		========================================================================== */
		} else {
	?>


		<?php if ( have_posts() ) : ?>

			<ul class="innr srvcs equip_cats">

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

				<div class="innr sldr page_hero sub_category_hero">
					<div class="slides">
						<div class="slide clrfx">
							<?php
								global $wp_query;
								$cat = $wp_query->get_queried_object();
								$thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
								$image = wp_get_attachment_url( $thumbnail_id );
								if ( $image ) {
									echo '<img src="' . $image . '" alt="" />';
								}
							?>
							<div class="slide_content">
								<h1><?php woocommerce_page_title(); ?></h1>
                            <?php if ($page_cat) : ?>
								<p><?php echo $page_cat->description ?></p>
                            <?php endif; ?>
							</div>
						</div><!-- //slide -->
					</div><!-- //slides -->
				</div><!-- //innr sldr -->

				<p><?php // var_dump($post); ?></p>

				<?php do_action( 'woocommerce_before_shop_loop' ); ?>

				<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php do_action( 'woocommerce_after_shop_loop' ); ?>

			</ul><!-- //innr srvcs equip_cats -->

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

		<div class="cont mlnglst">
			<div class="innr mailing_list">
				<h4>Sign up to our Mailing List</h4>
				<form class="mailinglist_form">
					<input type="text" placeholder="Enter Email Address" />
					<input class="btn_submit_mailing" type="submit" value="" />
				</form>
			</div><!-- //cont mailing_list -->
		</div><!-- //cont mlnglst -->


	<?php
		/* ==========================================================================
		End all Category templates
		========================================================================== */
		}
	?>


<?php get_footer( 'shop' ); ?>
