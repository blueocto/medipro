<?php get_header(); ?>

<h1 style="position:absolute;top:20px;right:20px;border:2px solid red;">index.php</h1>

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
	<div class="innr clrx">

		<?php if( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail() ) { ?>
		<div class="hero">
			<?php the_post_thumbnail( array(960,960) ); ?>
		</div><!-- //hero -->
		<?php } ?>

		<!-- Start The Loop -->
		<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<h1><?php the_title(); ?></h1>

			<?php edit_post_link('Edit this page', '<p>', '</p>'); ?>

			<div class="entry_content wysiwyg">
				<?php the_content(); ?>
			</div><!-- //entry_content -->

			<?php edit_post_link('Edit this page', '<p>', '</p>'); ?>

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