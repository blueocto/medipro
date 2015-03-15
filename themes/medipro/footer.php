<div class="cont ftr">
	<div class="innr ftr_cls clrfx">
		<div class="ftr_training">
			<h4>Training</h4>
			<h6>Information //</h6>
			<ul>
				<li><a href="/about-our-facilities/">About Our Facilities</a></li>
				<li><a href="/our-staff/">Our Staff</a></li>
				<li><a href="/who-we-are/">Who We Are</a></li>
				<li><a href="/our-approving-bodies/">Our Approving Bodies</a></li>
				<li><a href="/latest-news/">Latest News</a></li>
			</ul>
			<h6>Courses //</h6>
			<ul>
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
										foreach($row as $sub_category) { ?>
				<li><a href="/product-category/courses/<?php //echo $sub_category->slug; ?>"><?php echo $sub_category->cat_name; ?></a></li>
				<?php 
										
										} //endforeach
									} //endforeach
								} //endif
						} //endif
					} //endforeach 
					wp_reset_query(); 
				?>					
			</ul>
		</div><!-- //ftr_training -->
		<div class="ftr_equipment">
			<h4>Equipment</h4>
			<h6>Categories //</h6>
			<ul>
				<?php 
					$args = array( 
						'taxonomy' => 'product_cat', 
						'orderby' => 'name',
						'pad_counts' => 0, 
						'hierarchical' => 1, 
						'title_li' => '', 
						'hide_empty' => 0, 
						'exclude' => '13' //courses cat ID
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
										foreach($row as $sub_category) { ?>
					<li><a href="/product-category/equipment/<?php echo $sub_category->slug; ?>"><?php echo $sub_category->cat_name; ?></a></li>
				<?php 
										
										} //endforeach
									} //endforeach
								} //endif
						} //endif
					} //endforeach 
					wp_reset_query(); 
				?>					
			</ul>
		</div><!-- //ftr_equipment -->
		<div class="ftr_getintouch">
			<h4>Get In Touch</h4>
			<p>MediPro Training Ltd<br />Prospect House<br />24 Ellerbeck Court, Stokesley<br />North Yorkshire<br />TS9 5PT</p>
			<p>Work Tel. 0845 8387322<br />Fax. 0845 8387344<br /><a href="mailto:contact@mediprotraining.co.uk">contact@mediprotraining.co.uk</a></p>
		</div><!-- //ftr_getintouch -->
	</div><!-- //innr ftr_cls -->
</div><!-- //cont ftr -->


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="<?php bloginfo("template_url"); ?>/js/jquery-1.10.2.min.js"><\/script>')</script>
<script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
<?php if ( is_page( 15, 63 ) ) { ?>
<script src="<?php bloginfo('template_url'); ?>/js/jquery.cycle.min.js"></script>
<script>
	$(document).ready(function(){
		// Homepage and Equipment slider
		$('#slides').cycle({
			fx:     'fade',
			speed:   3000,
			timeout: 1000,
			next:   '.next',
			prev:   '.prev',
			pager:  '.coins',
			pagerAnchorBuilder: function(idx, slide) {
				return '<a href=""></a>';
			}
		});
	});
</script>
<?php } else {} ?>
<script src="<?php bloginfo('template_url'); ?>/js/jquery.placeholder.min.js"></script>
<script src="<?php bloginfo('template_url'); ?>/js/jquery.colorbox-min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="<?php bloginfo('template_url'); ?>/js/script.js"></script>

<?php wp_footer(); ?>
</body>
</html>