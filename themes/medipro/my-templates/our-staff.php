<?php
/**
 * Template Name: Our Staff
 *
 */
 get_header(); ?>

<div class="cont">
	<div class="innr brdcrmbs">
		<ul class="breadcrumbs">
			<li><a href="<?php bloginfo('url'); ?>">Home</a></li>
			<li>&gt;</li>
			<li><?php the_title(); ?></li>
		</ul>
	</div><!-- //innr brdcrmbs -->
</div><!-- //cont nav -->


<div class="cont usps">
	<div class="innr">
		<?php if( get_field( "usp_left", 15 ) ): ?>
		<h5 class="usp"><?php the_field('usp_left', 15); ?></h5>
		<?php endif; ?>
		<?php if( get_field( "usp_center", 15 ) ): ?>
		<h5 class="usp"><?php the_field('usp_center', 15); ?></h5>
		<?php endif; ?>
		<?php if( get_field( "usp_right", 15 ) ): ?>
		<h5 class="usp"><?php the_field('usp_right', 15); ?></h5>
		<?php endif; ?>
	</div><!-- //innr -->
</div><!-- //cont usps -->


<div class="cont main">

	<div class="innr sldr page_hero">
		<div class="slides">
			<div class="slide clrfx">
				<?php if( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail() ) { ?>
				<?php the_post_thumbnail( array(725,725) ); //define your max-width or max-height here ?>
				<?php } ?>
				<div class="slide_content">
					<h1><?php the_title(); ?></h1>
					<?php if( get_field( 'hero_text' ) ): ?>
					<p><?php the_field('hero_text'); ?></p>
					<?php endif; ?>
				</div>
			</div><!-- //slide -->
		</div><!-- //slides -->
	</div><!-- //innr sldr -->

	<div class="innr our_staff">
	<?php if( have_rows('our_staff') ): while ( have_rows('our_staff') ) : the_row(); ?>
		
		<div class="staff_block">
			<img class="staff_photo" src="<?php the_sub_field('staff_photo'); ?>" alt="" />
			<h3><?php the_sub_field('staff_name'); ?></h3>
			<h5><?php the_sub_field('staff_job_title'); ?></h5>
			<p><?php the_sub_field('staff_bio'); ?></p>
		</div><!-- //staff_block -->

	<?php endwhile; else : endif; ?>
		
	</div><!-- // innr our_staff -->

	<div class="innr ltst_nws clrfx">
	<?php global $post; $args = array('posts_per_page' => 1); $custom_posts = get_posts($args); 
			foreach($custom_posts as $post) : setup_postdata($post); 
	?>
		<a class="btn btn_readinfull" href="<?php the_permalink(); ?>">Read In Full</a>
		<h2>Latest News</h2>
		<h4><?php the_title(); ?></h4>
		<?php the_excerpt(); ?>
	<?php endforeach; wp_reset_query(); ?>
	</div><!-- // innr ltst_nws -->

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

</div><!-- //cont main -->

<?php get_footer(); ?>