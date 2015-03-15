<?php
/**
 * Default Events Template
 * This file is the basic wrapper template for all the views if 'Default Events Template' 
 * is selected in Events -> Settings -> Template -> Events Template.
 * 
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/default-template.php
 *
 * @package TribeEventsCalendar
 * @since  3.0
 * @author Modern Tribe Inc.
 *
 */

if ( !defined('ABSPATH') ) { die('-1'); } ?>

<?php get_header(); ?>

	<div class="cont">
		<div class="innr brdcrmbs">
			<ul class="breadcrumbs">
				<li><a href="/">Home</a></li>
				<li>&gt;</li>
				<li><a href="/product-category/courses/">Courses</a></li>
				<li>&gt;</li>
				<li class="course_subs">First Aid</li>
				<li>&gt;</li>
				<li><?php the_title(); ?></li>
			</ul>
		</div><!-- //innr brdcrmbs -->
	</div><!-- //cont nav -->

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
			<div class="sharing_btns">[sharing buttons]</div>
		</div><!-- //innr -->
	</div><!-- //cont usps -->

	
	<?php tribe_events_before_html(); ?>
	<!-- #tribe_events_before_html -->
	<?php tribe_get_view(); ?>
	<!-- #tribe_get_view -->

	<?php tribe_events_after_html(); ?>
	<!-- #tribe_events_after_html -->

<?php get_footer(); ?>