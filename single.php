<?php get_header();
	$page_id = $post_id_main = $post->ID;
	$discy_sidebar_all = $discy_sidebar = discy_sidebars("sidebar_where");
	$remove_question_slug = discy_options("remove_question_slug");
	if ($remove_question_slug == "on" && is_singular("post")) {
		$array_data = array("p" => $page_id);
		$discy_query = new WP_Query($array_data);
	}
	include locate_template("includes/".(is_singular("question")?'question':'loop')."-setting.php");
	if ( ($remove_question_slug == "on" && isset($discy_query) && $discy_query->have_posts()) || have_posts() ) :?>
		<div class="post-articles<?php echo (is_singular("question")?" question-articles".(isset($question_columns) && $question_columns == "style_2" && isset($masonry_style) && $masonry_style == "on"?" isotope":""):"")?>">
			<?php if ($remove_question_slug == "on" && isset($discy_query) && $discy_query->have_posts()) :
				while ($discy_query->have_posts()) : $discy_query->the_post();
					do_action("discy_action_before_post_content",$post->ID);
					include locate_template("theme-parts/content.php");
					do_action("discy_action_after_post_content",$post->ID);
					do_action("wpqa_action_after_post_content",$post->ID,$post->post_author);
				endwhile;
			else :
				while ( have_posts() ) : the_post();
					do_action("discy_action_before_post_content",$post->ID);
					include locate_template("theme-parts/content".(is_singular("question")?"-question":"").".php");
					do_action("discy_action_after_post_content",$post->ID);
					do_action("wpqa_action_after_post_content",$post->ID,$post->post_author);
				endwhile;
			endif;?>
		</div><!-- End post-articles -->
	<?php else :
		include locate_template("theme-parts/content-none.php");
	endif;
	if ($remove_question_slug == "on" && isset($discy_query)) {
		wp_reset_postdata();
	}
get_footer();?>