<?php get_header(); ?>


<div class="cont">
	<div class="innr brdcrmbs">
		<ul class="breadcrumbs">
			<li>Home</li>
		</ul>
	</div><!-- //innr brdcrmbs -->
</div><!-- //cont nav -->


<div class="cont usps">
	<div class="innr">
		<?php if( get_field( "usp_left" ) ): ?>
		<h5 class="usp"><?php the_field('usp_left'); ?></h5>
		<?php endif; ?>
		<?php if( get_field( "usp_center" ) ): ?>
		<h5 class="usp"><?php the_field('usp_center'); ?></h5>
		<?php endif; ?>
		<?php if( get_field( "usp_right" ) ): ?>
		<h5 class="usp"><?php the_field('usp_right'); ?></h5>
		<?php endif; ?>
	</div><!-- //innr -->
</div><!-- //cont usps -->


<div class="cont main">
	<div class="innr sldr">
		<div id="slides" class="slides">
			<?php if( have_rows('slides') ): while ( have_rows('slides') ) : the_row(); ?>
			<div class="slide clrfx">
				<img src="<?php the_sub_field('slide'); ?>" width="725" height="366" alt="" />
				<div class="slide_content">
					<h2><?php the_sub_field('slide_title'); ?></h2>
					<p><?php the_sub_field('slide_content'); ?></p>
					<a class="btn btn_arrow_right btn_continue" href="<?php the_sub_field('slide_link'); ?>">Continue</a>
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

	<div class="innr apprvd">
		<h5>Proudly Approved By And Associated With</h5>
		<img src="<?php bloginfo('template_url'); ?>/img/approved-by.png" width="889" height="46" alt="" />
	</div><!-- //innr apprvd -->

	<div class="innr course_equip">
		<div class="view_courses">
			<h1>View Courses</h1>
			<h5>We Have Over 100 Courses To Choose From</h5>
			<a class="btn btn_arrow_right btn_continue" href="/product-category/courses/">Continue</a>
		</div>
		<div class="view_equip">
			<h1> View Equipment</h1>
			<h5>Sub Heading For This Area Goes Here</h5>
			<a class="btn btn_arrow_right btn_continue" href="/product-category/equipment/">Continue</a>
		</div>
	</div><!-- //innr course_equip -->

	<div class="innr ltst_nws clrfx">
	<?php global $post; $args = array('posts_per_page' => 1); $custom_posts = get_posts($args); 
			foreach($custom_posts as $post) : setup_postdata($post); 
	?>
		<a class="btn btn_readinfull" href="<?php the_permalink(); ?>">Read In Full</a>
		<h2>Latest News</h2>
		<h4><?php the_title(); ?></h4>
		<?php the_excerpt(); ?>
	<?php endforeach; wp_reset_query(); ?>
	</div><!-- //innr ltst_nws -->

	<div class="innr srvcs">
		<a class="servc_blk" href="/about-our-facilities/">
			<img src="<?php bloginfo('template_url'); ?>/img/about-our-facilities.jpg" width="235" height="310" alt="" />
			<h3>About Our Facilities</h3>
			<h5>Sub-Heading Goes Here</h5>
		</a>
		<a class="servc_blk" href="/our-staff/">
			<img src="<?php bloginfo('template_url'); ?>/img/our-staff.jpg" width="235" height="310" alt="" />
			<h3>Our Staff</h3>
			<h5>Sub-Heading Goes Here</h5>
		</a>
		<a class="servc_blk" href="/our-approving-bodies/">
			<img src="<?php bloginfo('template_url'); ?>/img/our-approving-bodies.jpg" width="235" height="310" alt="" />
			<h3>Our Approving Bodies</h3>
			<h5>Sub-Heading Goes Here</h5>
		</a>
		<a class="servc_blk" href="/clinical-governance/">
			<img src="<?php bloginfo('template_url'); ?>/img/clinical-governance.jpg" width="235" height="310" alt="" />
			<h3>Clinical Governance</h3>
			<h5>Sub-Heading Goes Here</h5>
		</a>
	</div><!-- //innr srvcs -->
</div><!-- //cont main -->


<?php get_footer(); ?>