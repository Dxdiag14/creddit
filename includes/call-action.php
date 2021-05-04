<?php $custom_call_action = (is_page() || is_single()?discy_post_meta("custom_call_action"):"");
if ($custom_call_action == "on") {
	$action_button = discy_post_meta("action_button");
	$action_logged = discy_post_meta("action_logged");
}else {
	$action_button = discy_options("action_button");
	$action_logged = discy_options("action_logged");
}
$action_logged = apply_filters("discy_action_logged",$action_logged);
if ((is_user_logged_in() && ($action_logged == "logged" || $action_logged == "both")) || (!is_user_logged_in() && ($action_logged == "unlogged" || $action_logged == "both"))) {
	if ($custom_call_action == "on") {
		$call_action = discy_post_meta("call_action");
		$action_image_video = discy_post_meta("action_image_video");
		$video_id = discy_post_meta("action_video_id");
		$video_type = discy_post_meta("action_video_type");
		$video_mp4 = discy_post_meta("action_video_mp4");
		$video_m4v = discy_post_meta("action_video_m4v");
		$video_webm = discy_post_meta("action_video_webm");
		$video_ogv = discy_post_meta("action_video_ogv");
		$video_wmv = discy_post_meta("action_video_wmv");
		$video_flv = discy_post_meta("action_video_flv");
		$custom_embed = discy_post_meta("action_custom_embed");
		$action_skin = discy_post_meta("action_skin");
		$action_style = discy_post_meta("action_style");
		$action_headline = discy_post_meta("action_headline");
		$action_paragraph = discy_post_meta("action_paragraph");
	}else {
		$call_action = discy_options("call_action");
		$action_image_video = discy_options("action_image_video");
		$video_id = discy_options("action_video_id");
		$video_type = discy_options("action_video_type");
		$video_mp4 = discy_options("action_video_mp4");
		$video_m4v = discy_options("action_video_m4v");
		$video_webm = discy_options("action_video_webm");
		$video_ogv = discy_options("action_video_ogv");
		$video_wmv = discy_options("action_video_wmv");
		$video_flv = discy_options("action_video_flv");
		$custom_embed = discy_options("action_custom_embed");
		$action_home_pages = discy_options("action_home_pages");
		$action_pages = discy_options("action_pages");
		$action_pages = explode(",",$action_pages);
		$action_skin = discy_options("action_skin");
		$action_style = discy_options("action_style");
		$action_headline = discy_options("action_headline");
		$action_paragraph = discy_options("action_paragraph");
	}
	if ($video_id != "") {
		$type = (has_wpqa() && wpqa_plugin_version >= 4.4?wpqa_video_iframe($video_type,$video_id):"");
	}
	$video_mp4 = (isset($video_mp4) && $video_mp4 != ""?'<source src="'.$video_mp4.'" type="video/mp4">':"");
	$video_m4v = (isset($video_m4v) && $video_m4v != ""?'<source src="'.$video_m4v.'" type="video/m4v">':"");
	$video_webm = (isset($video_webm) && $video_webm != ""?'<source src="'.$video_webm.'" type="video/webm">':"");
	$video_ogv = (isset($video_ogv) && $video_ogv != ""?'<source src="'.$video_ogv.'" type="video/ogv">':"");
	$video_wmv = (isset($video_wmv) && $video_wmv != ""?'<source src="'.$video_wmv.'" type="video/wmv">':"");
	$video_flv = (isset($video_flv) && $video_flv != ""?'<source src="'.$video_flv.'" type="video/flv">':"");
	$call_action = apply_filters("discy_call_action",$call_action);
	$action_headline = apply_filters("discy_action_headline",$action_headline);
	$action_paragraph = apply_filters("discy_action_paragraph_2",$action_paragraph);
	$action_home_pages = apply_filters("discy_action_home_pages",(isset($action_home_pages)?$action_home_pages:""));
	if ($call_action == "on" && (($custom_call_action == "on") || (((is_front_page() || is_home()) && $action_home_pages == "home_page") || $action_home_pages == "all_pages" || ($action_home_pages == "all_posts" && is_singular("post")) || ($action_home_pages == "all_questions" && is_singular("question")) || ($action_home_pages == "custom_pages" && is_page() && isset($action_pages) && is_array($action_pages) && isset($post->ID) && in_array($post->ID,$action_pages))))) {?>
		<div class="call-action-unlogged call-action-<?php echo esc_attr($action_skin).' call-action-'.esc_attr($action_style)?>">
			<?php if ($action_image_video == "video") {
				if ($video_type == "html5") {
					echo '<div class="call-action-video">
						<video autoplay loop>'.$video_mp4.$video_m4v.$video_webm.$video_ogv.$video_wmv.$video_flv.esc_html__("Your browser does not support the video tag.","discy").'</video>
					</div>';
				}else if ($video_type == "embed" && $custom_embed != "") {
					echo '<div class="call-action-video">'.$custom_embed.'</div>';
				}else if (isset($type) && $type != "") {
					echo '<div class="call-action-video"><iframe frameborder="0" allow="autoplay" height="100%" width="100%" src="'.$type.'?autoplay=1&loop=1'.(isset($video_id) && $video_id != ""?"&playlist=".$video_id:"").'"></iframe></div>';
				}
			}
			if ($action_image_video != "video") {?>
				<div class="call-action-opacity"></div>
			<?php }?>
			<div class="the-main-container">
				<div class="call-action-wrap">
					<div class="<?php echo ($action_style == "style_1"?"col6":"col12")?>">
						<?php if ($action_headline != "") {?>
							<h3><?php echo discy_kses_stip(stripslashes($action_headline))?></h3>
						<?php }
						if ($action_paragraph != "") {?>
							<p><?php echo do_shortcode(discy_kses_stip(nl2br(stripslashes($action_paragraph))))?></p>
						<?php }
					if ($action_style == "style_1") {?>
						</div>
						<div class="col3">
					<?php }
					$show_action = false;
					if (is_user_logged_in()) {
						if (($action_logged == "logged" || $action_logged == "both") && ($action_button != "login" && $action_button != "signup")) {
							$show_action = true;
						}
					}else {
						if (($action_logged == "unlogged" || $action_logged == "both") || ($action_button == "login" || $action_button == "signup")) {
							$show_action = true;
						}
					}
					$discy_signup_call_action = apply_filters('discy_signup_call_action',true);
					if ($show_action == true && $discy_signup_call_action == true) {
						if ($action_button == "question") {
							$filter_class = "question";
							$action_button_class = "wpqa-question";
							$action_button_link = (has_wpqa()?wpqa_add_question_permalink():"#");
							$action_button_text = esc_html__("Ask A Question","discy");
						}else if ($action_button == "post") {
							$filter_class = "post";
							$action_button_class = "wpqa-post";
							$action_button_link = (has_wpqa()?wpqa_add_post_permalink():"#");
							$action_button_text = esc_html__("Add A New Post","discy");
						}else if ($action_button == "login") {
							$filter_class = "login";
							$action_button_class = "login-panel";
							$action_button_link = (has_wpqa()?wpqa_login_permalink():"#");
							$action_button_text = esc_html__("Login","discy");
						}else if ($action_button == "signup") {
							$filter_class = "signup";
							$action_button_class = "signup-panel";
							$action_button_link = (has_wpqa()?wpqa_signup_permalink():"#");
							$action_button_text = esc_html__("Create A New Account","discy");
						}else {
							$filter_class = $action_button_class = "";
							if ($custom_call_action == "on") {
								$action_button_target = discy_post_meta("action_button_target");
								$action_button_link = discy_post_meta("action_button_link");
								$action_button_text = discy_post_meta("action_button_text");
							}else {
								$action_button_target = discy_options("action_button_target");
								$action_button_link = discy_options("action_button_link");
								$action_button_text = discy_options("action_button_text");
							}
						}
						$action_button_target = ($action_button == "custom" && isset($action_button_target) && $action_button_target == "new_page"?"_blank":"_self");?>
						<a target="<?php echo esc_attr($action_button_target)?>" class="<?php echo esc_attr($action_button_class)?> button-default<?php echo ($action_skin != "dark"?"-3":"")?> call-action-button<?php echo apply_filters('wpqa_pop_up_class','').(isset($filter_class) && $filter_class != ''?apply_filters('wpqa_pop_up_class_'.$filter_class,''):'')?>" href="<?php echo esc_url($action_button_link)?>"><?php echo esc_html($action_button_text)?></a>
					<?php }else {
						do_action("discy_after_button_call_action");
					}?>
					</div>
					<?php do_action("discy_after_call_action");?>
				</div>
			</div><!-- End the-main-container -->
		</div><!-- End call-action-unlogged -->
	<?php }
}?>