<?php get_header(); ?>


<?php include("my-templates/nav.php"); ?>


<div class="cont">
	<div class="innr brdcrmbs">
		<ul class="breadcrumbs">
			<li><a href="<?php bloginfo('url'); ?>">Home</a></li>
			<li>&gt;</li>
			<li>404 Not Found</li>
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
	<div class="innr clrx">

<h1 style="position:absolute;top:20px;right:20px;border:2px solid red;">404.php</h1>

		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<h1>Not found <span>:(</span></h1>
			<p>Sorry, but the page you were trying to view does not exist.</p>
			<p>It looks like this was the result of either:</p>
			<ul>
				<li>a mistyped address</li>
				<li>an out-of-date link</li>
			</ul>
			<script>var GOOG_FIXURL_LANG = (navigator.language || '').slice(0,2),GOOG_FIXURL_SITE = location.host;</script>
			<script src="//linkhelp.clients.google.com/tbproxy/lh/wm/fixurl.js"></script>

		</div><!-- //post-id -->

	</div><!-- //innr -->
</div><!-- //cont main -->

<?php get_footer(); ?>