<?php
/**
 * Template Name: Our Facilities
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

	<div class="innr our_facilities">

		<?php if( get_field( 'facility_block_1_-_image' ) ): ?>
		<div class="facility_block clrfx">
			<div class="facility_img"><img src="<?php the_field('facility_block_1_-_image'); ?>" alt="" /></div>
			<div class="facility_text clrfx">
				<h2><?php the_field('facility_block_1_-_title'); ?></h2>
				<?php the_field('facility_block_1_-_content'); ?>
			</div>
		</div><!-- //facility_block -->
		<?php endif; ?>

		<?php if( get_field( 'facility_block_2_-_image' ) ): ?>
		<div class="facility_block clrfx">
			<div class="facility_img ext"><img src="<?php the_field('facility_block_2_-_image'); ?>" alt="" /></div>
			<div class="facility_text ext clrfx">
				<h2><?php the_field('facility_block_2_-_title'); ?></h2>
				<?php the_field('facility_block_2_-_content'); ?>
			</div>
		</div><!-- //facility_block -->
		<?php endif; ?>

		<div class="ltst_nws clrfx">
		<?php global $post; $args = array('posts_per_page' => 1); $custom_posts = get_posts($args); 
				foreach($custom_posts as $post) : setup_postdata($post); 
		?>
			<a class="btn btn_readinfull" href="<?php the_permalink(); ?>">Read In Full</a>
			<h2>Latest News</h2>
			<h4><?php the_title(); ?></h4>
			<?php the_excerpt(); ?>
		<?php endforeach; wp_reset_query(); ?>
		</div><!-- //ltst_nws -->

		<?php if( get_field( 'facility_block_3_-_image' ) ): ?>
		<div class="facility_block clrfx">
			<div class="facility_img"><img src="<?php the_field('facility_block_3_-_image'); ?>" alt="" /></div>
			<div class="facility_text clrfx">
				<h2><?php the_field('facility_block_3_-_title'); ?></h2>
				<?php the_field('facility_block_3_-_content'); ?>
			</div>
		</div><!-- //facility_block -->
		<?php endif; ?>

		<?php if( get_field( 'facility_block_4_-_image' ) ): ?>
		<div class="facility_block clrfx">
			<div class="facility_img ext"><img src="<?php the_field('facility_block_4_-_image'); ?>" alt="" /></div>
			<div class="facility_text ext clrfx">
				<h2><?php the_field('facility_block_4_-_title'); ?></h2>
				<?php the_field('facility_block_4_-_content'); ?>
			</div>
		</div><!-- //facility_block -->
		<?php endif; ?>
		
	</div><!-- //our_facilities -->

</div><!-- //cont main -->

<?php get_footer(); ?>