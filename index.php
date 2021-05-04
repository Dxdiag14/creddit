<?php get_header();
	if (isset($_POST) && !empty($_POST)) {
		$show_on_front = get_option("show_on_front");
		if ($show_on_front == "page") {
			$page_on_front = get_option("page_on_front");
			if (is_numeric($page_on_front)) {
				$wp_page_template = discy_post_meta("_wp_page_template",$page_on_front,false);
				$page_id = $post_id_main = $page_on_front;
				if ($wp_page_template == "template-home.php") {
					$is_home_template = true;
				}
			}
		}
	}
	
	if (isset($is_home_template)) {
		include locate_template("theme-parts/tabs.php");
	}else {
		include locate_template("theme-parts/loop.php");
	}
get_footer();?>