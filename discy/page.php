<?php get_header();
	$discy_sidebar_all = $discy_sidebar = discy_sidebars("sidebar_where");
	include locate_template("includes/loop-setting.php");
	if ( have_posts() ) :?>
		<div class="post-articles">
			<?php while ( have_posts() ) : the_post();
				include locate_template("theme-parts/content.php");
			endwhile;?>
		</div><!-- End post-articles -->
	<?php else :
		include locate_template("theme-parts/content-none.php");
	endif;
get_footer();?>