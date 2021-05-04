<?php get_header(); ?>

<?php get_template_part('inc/page-title'); ?>

<div class="content">
	<div class="content-inner group">
		<?php if ( have_posts() ) : ?>
				
			<?php while ( have_posts() ): the_post(); ?>
				<?php get_template_part('content'); ?>
			<?php endwhile; ?>
			
			<?php get_template_part('inc/pagination'); ?>
			
		<?php endif; ?>
	</div>
</div><!--/.content-->

<div id="move-sidebar-content"></div>

<?php get_footer(); ?>