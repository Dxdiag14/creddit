<?php $blog_h = discy_options("blog_h");
if ($blog_h == "on") {
	$blog_h_home_pages  = discy_options("blog_h_home_pages");
	$blog_h_pages       = discy_options("blog_h_pages");
	$blog_h_pages       = explode(",",$blog_h_pages);
	$blog_h_title       = discy_options("blog_h_title");
	$blog_h_button      = discy_options("blog_h_button");
	$blog_h_button_text = discy_options("blog_h_button_text");
	$blog_h_page        = discy_options("blog_h_page");
	$blog_h_link        = discy_options("blog_h_link");
	$blog_h             = "blog_h";
	$page_tamplate      = true;
	$post_pagination    = "none";
	$orderby_post       = $its_question = "";
	if (((is_front_page() || is_home()) && $blog_h_home_pages == "home_page") || $blog_h_home_pages == "all_pages" || ($blog_h_home_pages == "all_posts" && is_singular("post")) || ($blog_h_home_pages == "all_questions" && is_singular("question")) || ($blog_h_home_pages == "custom_pages" && is_page() && is_array($blog_h_pages) && isset($post->ID) && in_array($post->ID,$blog_h_pages))) {?>
		<div class="blog-post-area <?php echo ($blog_h_where == "header"?"blog-post-header":"blog-post-footer")?>">
			<div class="the-main-container">
				<?php if ($blog_h_title != "") {?>
					<h2 class="post-title blog-post-title"><?php echo esc_html($blog_h_title)?></h2>
				<?php }
				include locate_template("theme-parts/loop.php");
				wp_reset_postdata();
				if ($blog_h_button == "on") {?>
					<div class="blog-post-button"><a href="<?php echo esc_url(($blog_h_link != ""?$blog_h_link:($blog_h_page != "" && $blog_h_page > 0?get_page_link($blog_h_page):"")))?>" class="button-default"><?php echo ($blog_h_button_text != ""?$blog_h_button_text:esc_html__("Explore Our Blog","discy"))?></a></div>
				<?php }?>
			</div><!-- End the-main-container -->
		</div><!-- End blog-post-area -->
	<?php }
}
unset($blog_h);?>