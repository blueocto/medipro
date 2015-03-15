<?php get_header(); ?>

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

	<?php if( get_field( 'hero_text' ) ): ?>
	<div class="innr sldr page_hero">
		<div class="slides">
			<div class="slide clrfx">
				<?php if( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail() ) { ?>
				<?php the_post_thumbnail( array(725,725) ); //define your max-width or max-height here ?>
				<?php } ?>
				<div class="slide_content">
					<h1><?php the_title(); ?></h1>
					<p><?php the_field('hero_text'); ?></p>
					
				</div>
			</div><!-- //slide -->
		</div><!-- //slides -->
	</div><!-- //innr sldr -->

	<div class="innr page_content clrfx">
	
	<?php else : ?>

	<div class="innr page_content clrfx">

		<h1><?php the_title(); ?></h1>

	<?php endif; ?>

		<?php if( get_field( 'left_column' ) ): ?>
		<div class="left_col wysiwyg">
			<?php the_field('left_column'); ?>
		</div><!-- //left_col -->
		<?php endif; ?>

		<?php if( get_field( 'right_column' ) ): ?>
		<div class="right_col wysiwyg">
			<?php the_field('right_column'); ?>
		</div><!-- //right_col -->
		<?php endif; ?>

		<!-- Start The Loop -->
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php //edit_post_link('Edit this page', '<p>', '</p>'); ?>

			<div class="entry_content wysiwyg">
				<?php the_content(); ?>
			</div><!-- //entry_content -->

			<?php //edit_post_link('Edit this page', '<p>', '</p>'); ?>

		</div><!-- //post-id -->
		
		<?php endwhile; ?>

		<div class="navigation">
			<p class="alignleft"><?php next_posts_link('&lt; Previous Page') ?></p>
			<p class="alignright"><?php previous_posts_link('Next Page &gt;') ?></p>
		</div>

		<?php else : ?>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="entry_content">
				<p class="no-posts"><?php _e('Sorry, no posts matched your criteria'); ?></p>
			</div><!-- //entry_content -->
		</div><!-- //post-id -->

		<?php endif; ?>
		<!-- End The Loop -->


	</div><!-- //innr -->
</div><!-- //cont main -->

<?php get_footer(); ?>