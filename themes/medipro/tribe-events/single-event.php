<?php
/**
 * Single Event Template
 * A single event. This displays the event title, description, meta, and
 * optionally, the Google map for the event.
 * 
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/single-event.php
 *
 * @package TribeEventsCalendar
 * @since  2.1
 * @author Modern Tribe Inc.
 *
 */

if ( !defined('ABSPATH') ) { die('-1'); }

$event_id = get_the_ID();

?>

	<div class="cont main">

		<?php /* Notices */ tribe_events_the_notices() ?>

		<div class="innr sldr cat_hero course_hero">
			<div class="slides">
				<div class="slide clrfx">
					<?php if( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail() ) { ?>
					<?php the_post_thumbnail( array(725,725) ); //define your max-width or max-height here ?>
					<?php } ?>
					<div class="slide_content">
						<h1><?php the_title(); ?></h1>
					</div>
				</div><!-- //slide -->
			</div><!-- //slides -->
		</div><!-- //innr sldr -->

		<div class="innr">
			
			<div class="course_ovrvw clrfx">
				<div class="course_ovrvw_cntnt">
					<?php if( get_field( "duration" ) ): ?>
					<h3>Duration : <?php the_field('duration'); ?></h3>
					<?php endif; ?>
					<?php if( get_field( "criteria" ) ): ?>
					<p class="criteria"><?php the_field('criteria'); ?></p>
					<?php endif; ?>
					<?php if( get_field( "first_heading" ) ): ?>
					<h4><?php the_field('first_heading'); ?></h4>
					<?php endif; ?>
					<?php if( get_field( "second_heading" ) ): ?>
					<p class="lrg"><?php the_field('second_heading'); ?></p>
					<?php endif; ?>
				</div><!-- //course_ovrvw_cntnt -->
				<a class="viewdates_bookcourse" href="#courseDates">
					<img src="<?php bloginfo('template_url'); ?>/img/icon-viewdates.png" width="59" height="59" alt="" />
					View Dates<br />&amp; Book Course
				</a>
			</div><!-- //course_overview -->
			
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
						<?php if( get_field( "overview" ) ): ?><?php the_field('overview'); ?><?php endif; ?>
					</div>
					<!-- //tab1 -->
					<h3 class="tab_drawer_heading" rel="tab2">Syllabus</h3>
					<div id="tab2" class="tab_content">
						<?php if( get_field( "syllabus" ) ): ?><?php the_field('syllabus'); ?><?php endif; ?>
					</div>
					<!-- //tab2 -->
					<h3 class="tab_drawer_heading" rel="tab3">Certification</h3>
					<div id="tab3" class="tab_content">
						<?php if( get_field( "certification" ) ): ?><?php the_field('certification'); ?><?php endif; ?>
					</div>
					<!-- //tab3 -->
					<h3 class="tab_drawer_heading" rel="tab4">Pre-Requisites</h3>
					<div id="tab4" class="tab_content">
						<?php if( get_field( "pre-requisites" ) ): ?><?php the_field('pre-requisites'); ?><?php endif; ?>
					</div>
					<!-- //tab4 -->
					<h3 class="tab_drawer_heading" rel="tab5">Downloads</h3>
					<div id="tab5" class="tab_content">
						<?php if( get_field( "downloads" ) ): ?><?php the_field('downloads'); ?><?php endif; ?>
					</div>
					<!-- //tab4 -->
					<h3 class="tab_drawer_heading" rel="tab6">Where to Stay</h3>
					<div id="tab6" class="tab_content">
						<?php if( get_field( "where_to_stay" ) ): ?><?php the_field('where_to_stay'); ?><?php endif; ?>
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
					
					<tr>
						<td><?php //the_sub_field('year'); ?></td>
						<td><?php //the_sub_field('start_date_and_month'); ?>, <?php the_sub_field('start_time'); ?></td>
						<td><?php //the_sub_field('end_date_and_month'); ?>, <?php the_sub_field('end_time'); ?></td>
						<td><?php //the_sub_field('status'); ?></td>
						<td><?php //the_sub_field('notes'); ?></td>
						<td><a class="btn btn_selectdate" href="#">Select Date</a></td>
					</tr>
					

				</table>
				<div class="crs_slctd_book clrfx">
					<div class="course_selected">
						<p class="lrg">Your Selected date for</p>
						<h2><?php the_title(); ?></h2>
						<p class="lrg">
							<strong><!-- 2014 --></strong><br />
							<strong>Start -</strong> <?php echo tribe_get_start_date(); ?><br />
							<strong>End -</strong> <?php echo tribe_get_end_date(); ?>
						</p>
					</div><!-- //course_selected -->
					<div class="book_course">
						<p class="lrg">Your Price :</p>
						<p class="course_price">&pound;289<br />
							<span class="course_vat">per person inc. VAT</span>
						</p>
						<!-- Event meta -->
						<?php do_action( 'tribe_events_single_event_before_the_meta' ) ?>
						<?php do_action( 'tribe_events_single_event_after_the_meta' ) ?>
						<?php if( get_post_type() == TribeEvents::POSTTYPE && tribe_get_option( 'showComments','no' ) == 'yes' ) { comments_template(); } ?>
						<a class="btn btn_addtobasket btn_clicktobookcourse" href="#"><?php esc_html_e( 'Click to Book Cart', 'tribe-wootickets' );?></a>
					</div><!-- //book_course -->
				</div>
			</div><!-- //course_dates -->
			
			<div class="course_location">
				<div id="courseMap" class="course_map">
					[map]
				</div>
				<p class="course_address">
					<a class="map_pin" href="<?php echo tribe_get_map_link(); ?>" rel="external">View on Google Maps</a><br /><br />
					<strong><?php echo tribe_get_venue(); ?></strong><br />
					<?php echo tribe_get_full_address(); ?><br />
					Tel. <?php echo tribe_get_phone(); ?>

					<?php /* ?>
					<strong>MediPro Training Ltd.</strong><br />
					Prospect House, 24 Ellerbeck Court, Stokesley<br />
					North Yorkshire<br />
					TS9 5PT<br />
					Tel. 0845 8387322
					<?php */ ?><br /><br />
					This course can also be undertaken at your workplace (UK only/minimum numbers apply). <a href="#">Contact us</a> for more information.
				</p>
			</div><!-- //course_location -->

		</div><!-- //innr -->
	</div><!-- //cont main -->