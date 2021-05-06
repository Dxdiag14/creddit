<?php if (isset($GLOBALS['post'])) {
	$post_data = $post = $GLOBALS['post'];
}else {
	$post_data = $post;
}
$discoura_style           = discy_options("discoura_style");
$questions_position       = discy_options("between_questions_position");
$adv_type_repeat          = discy_options("between_adv_type_repeat");
$custom_permission        = discy_options("custom_permission");
$show_question            = discy_options("show_question");
$question_delete          = discy_options("question_delete");
$question_edit            = discy_options("question_edit");
$active_vote              = discy_options("active_vote");
$question_meta_vote       = discy_options("question_meta_vote");
$ask_question_items       = discy_options("ask_question_items");
$active_moderators        = discy_options("active_moderators");
$video_desc               = discy_options("video_desc");
$active_reports           = discy_options("active_reports");
$active_logged_reports    = discy_options("active_logged_reports");
$private_question_content = discy_options("private_question_content");
$featured_position        = discy_options("featured_position");
$poll_position            = discy_options("poll_position");
$question_poll            = discy_post_meta("question_poll","",false);
$poll_position            = ($question_poll == "on"?$poll_position:"after");
$user_id                  = get_current_user_id();
$is_super_admin           = is_super_admin($user_id);
$get_question_user_id     = discy_post_meta("user_id","",false);
$closed_question          = discy_post_meta("closed_question","",false);
$anonymously_user         = discy_post_meta("anonymously_user","",false);
$video_desc_active        = (isset($ask_question_items["video_desc_active"]["value"]) && $ask_question_items["video_desc_active"]["value"] == "video_desc_active"?"on":"");
$yes_private              = (has_wpqa()?wpqa_private($post_data->ID,$post_data->post_author,$user_id):1);
$question_sticky          = (has_wpqa()?wpqa_question_sticky($post_data->ID):"");
$pending_questions        = (has_wpqa() && wpqa_is_pending_questions() && ($is_super_admin || $active_moderators == "on") && wpqa_is_user_owner() && ($is_super_admin || (isset($moderator_categories) && is_array($moderator_categories) && !empty($moderator_categories)))?true:false);
$pending_questions_page   = (has_wpqa() && wpqa_is_pending_questions()?true:false);
$moderators_permissions   = (has_wpqa() && $active_moderators == "on"?wpqa_user_moderator($user_id):"");
$count_post_all           = (int)(has_wpqa()?wpqa_count_comments($post_data->ID):get_comments_number());
$question_link_target     = apply_filters("discy_question_link_target","");
if ($discoura_style == "on") {
	$profile_credential = get_user_meta($post_data->post_author,"profile_credential",true);
	$privacy_credential = (has_wpqa() && wpqa_plugin_version >= 4.2?wpqa_check_user_privacy($post_data->post_author,"credential"):"");
	if (!is_single()) {
		$question_simple = "on";
	}
	$question_meta_vote = "on";
}
if ($question_columns == "style_2") {
	$asked_to = "";
	$question_meta_vote = $question_meta_icon = "on";
}
if (isset($k_ad_p) && (($k_ad_p == $questions_position) || ($adv_type_repeat == "on" && $k_ad_p != 0 && $k_ad_p % $questions_position == 0))) {
	echo discy_ads("between_adv_type","between_adv_code","between_adv_href","between_adv_img","","","discy-ad-inside".($question_columns == "style_2"?" post-with-columns article-question col ".($discy_sidebar == "full"?"col4":"col6"):""),"on","style_2");
}
if (((!is_single() && $vote_question_loop == "on") || (is_single() && $vote_question_single == "on")) && $active_vote == "on") {
	$wpqa_vote = true;
}
if (isset($wpqa_vote) && $question_meta_vote == "on") {
	$wpqa_vote_meta = true;
}
if (((!is_single() && $author_image == "on") || (is_single() && $author_image_single == "on"))) {
	$wpqa_image = true;
}

if (is_user_logged_in()) {
	$user_is_login = get_userdata($user_id);
	$roles = $user_is_login->allcaps;
}

if (!$is_super_admin && $yes_private != 1 && $private_question_content != "on") {
	echo '<article class="article-question private-question article-post clearfix">
		<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Sorry it's a private question.","discy").'</p></div>
	</article>';
}else {
	if ($custom_permission != "on" || $is_super_admin || (is_user_logged_in() && isset($roles["show_question"]) && $roles["show_question"] == 1) || (!is_user_logged_in() && $show_question == "on") || ($user_id > 0 && $user_id == $post_data->post_author) || ($user_id > 0 && $user_id == $anonymously_user)) {
		$the_best_answer = discy_post_meta("the_best_answer","",false);
		if (is_single()) {
			$share_facebook = (isset($post_share["share_facebook"]["value"])?$post_share["share_facebook"]["value"]:"");
			$share_twitter  = (isset($post_share["share_twitter"]["value"])?$post_share["share_twitter"]["value"]:"");
			$share_linkedin = (isset($post_share["share_linkedin"]["value"])?$post_share["share_linkedin"]["value"]:"");
			$share_whatsapp = (isset($post_share["share_whatsapp"]["value"])?$post_share["share_whatsapp"]["value"]:"");
			if (isset($the_best_answer) && $the_best_answer != "" && $the_best_answer > 0) {
				$get_comment = get_comment($the_best_answer);
				if (empty($get_comment)) {
					delete_post_meta($post_data->ID,"the_best_answer");
					$the_best_answer = "";
				}
			}
			$question_close_admin = discy_options("question_close_admin");
			if (!$is_super_admin && $question_close == "on" && $question_close_admin == "on") {
				$question_close = "0";
			}
		}
		
		$question_poll      = discy_post_meta("question_poll","",false);
		$question_type      = ($question_poll == "on"?" question-type-poll":" question-type-normal");
		$discy_thumbnail_id = discy_post_meta("_thumbnail_id","",false);
		$question_email     = "";
		
		$comment_count = discy_post_meta("comment_count","",false);
		if ($post_data->comment_count > 0 || $comment_count == "") {
			update_post_meta($post_data->ID,"comment_count",$post_data->comment_count);
		}
		
		if ($post_data->post_author > 0) {
			$question_username = get_the_author_meta('display_name',$post_data->post_author);
		}else {
			$anonymously_question = discy_post_meta("anonymously_question","",false);
			if (($anonymously_question == "on" || $anonymously_question == 1) && $anonymously_user != "") {
				$question_username = esc_html__('Anonymous','discy');
			}else {
				$question_email = discy_post_meta("question_email","",false);
				$question_username = discy_post_meta("question_username","",false);
				$question_username = ($question_username != ""?$question_username:esc_html__('[Deleted User]','discy'));
			}
		}
		if ($yes_private != 1 && $private_question_content == "on") {
		}else {
			if (is_single()) {
				$featured_image_question = discy_options("featured_image_single");
				$featured_image_question_width = discy_options("featured_image_inner_question_width");
				$featured_image_question_height = discy_options("featured_image_inner_question_height");
			}else {
				$featured_image_question = discy_options("featured_image_loop");
				$featured_image_question_width = discy_options("featured_image_question_width");
				$featured_image_question_height = discy_options("featured_image_question_height");
			}
			
			if ($featured_image_question == "on") {
				$custom_featured_image_size = discy_post_meta('custom_featured_image_size');
				if ($custom_featured_image_size == "on") {
					$featured_image_question_width = discy_post_meta('featured_image_width');
					$featured_image_question_height = discy_post_meta('featured_image_height');
				}
				$featured_image_question_lightbox = discy_options("featured_image_question_lightbox");
				$featured_image_question_width = ($featured_image_question_width != ""?$featured_image_question_width:260);
				$featured_image_question_height = ($featured_image_question_height != ""?$featured_image_question_height:185);
				$featured_image_question_width = apply_filters("discy_featured_image_".(is_single()?"single_":"")."question_width",$featured_image_question_width);
				$featured_image_question_height = apply_filters("discy_featured_image_".(is_single()?"single_":"")."question_height",$featured_image_question_height);
				$img_lightbox = ($featured_image_question_lightbox == "on"?"lightbox":false);
			}

			if (($pending_questions || !is_single()) && ($featured_image_question == "on" && has_post_thumbnail())) {
				$question_url_1 = ($featured_image_question_lightbox == "on"?"":"<a".$question_link_target." href='".get_permalink($post_data->ID)."'>");
				$question_url_2 = ($featured_image_question_lightbox == "on"?"":"</a>");
			}
		}?>
		<article id="post-<?php echo (int)$post_data->ID?>" <?php post_class('article-question article-post clearfix'.(!is_single() && $question_answer_place == "before"?" question-answer-before":"").($count_post_all > 0?" question-with-comments":" question-no-comments").(!$pending_questions_page && !is_single() && $read_more_question == "on" && $read_jquery_question == "on"?" load-question-jquery":"").(!$pending_questions_page && is_user_logged_in() && !is_single() && $answer_question_jquery == "on"?" answer-question-jquery":" answer-question-not-jquery").($question_columns == "style_2"?" question-2-columns post-with-columns col ".($discy_sidebar == "full"?"col4":"col6").($masonry_style == "on"?" question-masonry":""):"").($discoura_style == "on" || (!isset($wpqa_image) && (!isset($wpqa_vote) || (isset($wpqa_vote) && isset($wpqa_vote_meta))))?" question-full-width":"").(isset($wpqa_vote) && !isset($wpqa_image) && !isset($wpqa_vote_meta)?" question-vote-only":"").(isset($wpqa_vote_meta)?" question-meta-vote":"").($question_simple == "on"?" question-simple":"").($post_data->post_content == " post-no-content"?" post--content":"").(is_single() && ($share_style == "style_2" || $question_simple == "on")?" question-share-2":"").((isset($wpqa_vote) && !isset($wpqa_vote_meta)) || (isset($wpqa_image) && $discoura_style != "on")?" question-vote-image":"").($discoura_style == "on" && is_user_logged_in() && $privacy_credential == true && $profile_credential != ""?" discoura-credential":" discoura-not-credential").$question_type);echo (is_single()?' itemprop="mainEntity" itemscope itemtype="https://schema.org/Question"':'')?>>
			<?php if ($question_columns == "style_2") {?>
				<div class="post-with-columns-border"></div>
			<?php }
			if ((isset($show_sticky) && $show_sticky == true) || is_singular("question")) {
				if ((isset($show_sticky) && $show_sticky == true && !is_singular("question") && $question_sticky == "sticky") || (is_singular("question") && $question_sticky == "sticky")) {?>
					<div class="question-sticky-ribbon"><div><?php esc_html_e("Pinned","discy")?></div></div>
				<?php }
				
				if (is_singular("question")) {
					do_action("wpqa_question_content",$post_data->ID,$user_id,$anonymously_user,$post_data->post_author);
				}else {
					do_action("wpqa_question_content_loop",$post_data->ID,$user_id,$anonymously_user,$post_data->post_author);
				}
			}
			if (is_user_logged_in() && !is_single() && $answer_question_jquery == "on") {?>
				<div class='question-fixed-area discy_hide'><div class='load_span'><span class='loader_2'></span></div></div>
			<?php }
			if ($pending_questions) {?>
				<div class="load_span"><span class="loader_2"></span></div>
			<?php }?>
			<div class="single-inner-content">
				<div class="question-inner">
					<?php $question_vote = discy_post_meta("question_vote","",false);
					if ($question_vote == "") {
						update_post_meta($post_data->ID,"question_vote",0);
					}
					$question_vote = (int)$question_vote;
					if ($discoura_style != "on" && $question_columns != "style_2" && (isset($wpqa_vote) || isset($wpqa_image))) {?>
						<div class="question-image-vote<?php echo (isset($wpqa_vote)?"":" question-image-not-vote")?>">
							<?php if (isset($wpqa_image)) {
								do_action("wpqa_action_avatar_link",array("user_id" => (isset($post_data->post_author) && $post_data->post_author > 0?$post_data->post_author:0),"size" => apply_filters("discy_question_profile_size","42"),"span" => "span","pop" => "pop","post" => $post,"name" => $question_username,"email" => (isset($question_email) && $question_email != ""?$question_email:"")));
							}
							if (isset($wpqa_vote) && !isset($wpqa_vote_meta)) {
								do_action("wpqa_question_vote",$post,$user_id,$anonymously_user,$question_vote,$question_loop_dislike,$question_single_dislike,"question-mobile");
							}?>
						</div><!-- End question-image-vote -->
					<?php }?>
					<div class="question-content question-content-first<?php echo ($discoura_style != "on" && isset($wpqa_image) && (isset($wpqa_vote_meta) || !isset($wpqa_vote))?" question-third-image":"").($question_date != "on" && $category_question != "on"?" no-data-category":"")?>">
						<?php if ($discoura_style != "on" && $question_columns == "style_2" && isset($wpqa_image)) {
							do_action("wpqa_action_avatar_link",array("user_id" => (isset($post_data->post_author) && $post_data->post_author > 0?$post_data->post_author:0),"size" => apply_filters("discy_question_profile_size","42"),"span" => "span","pop" => "pop","post" => $post,"name" => $question_username,"email" => (isset($question_email) && $question_email != ""?$question_email:"")));
						}?>
						<header class="article-header">
							<?php if ($question_poll == "on") {?>
								<a class="question-poll" href="<?php echo esc_url_raw(add_query_arg(array("type" => "poll"),get_post_type_archive_link("question")))?>"><?php esc_html_e("Poll","discy")?></a>
							<?php }
							do_action("discy_after_question_poll_mark",$post_data->ID,$video_desc_active)?>
							<div class="question-header">
								<?php $wpqa_question_top_meta = apply_filters('wpqa_question_top_meta',true);
								if ($discoura_style != "on" && $wpqa_question_top_meta == true && $author_by == "on") {
									if ($post_data->post_author > 0) {
										$get_author_profile = get_author_posts_url($post_data->post_author);
										if (is_singular("question")) {
											echo '<span itemprop="author" itemscope itemtype="http://schema.org/Person">';
										}
										if (isset($get_author_profile)) {
											echo '<a class="post-author" itemprop="url" href="'.esc_url($get_author_profile).'">';
										}
										if (is_singular("question")) {
											echo '<span itemprop="name">';
										}
										echo apply_filters('wpqa_question_before_author',false).esc_html($question_username);
										if (is_singular("question")) {
											echo '</span>';
										}
										if (isset($get_author_profile)) {
											echo '</a>';
										}
										if (is_singular("question")) {
											echo '</span>';
										}
										do_action("wpqa_verified_user",$post_data->post_author);
										$active_points_category = discy_options("active_points_category");
										if ($active_points_category == "on") {
											$get_terms = wp_get_post_terms($post_data->ID,'question-category',array('fields' => 'ids'));
											if (!empty($get_terms) && is_array($get_terms) && isset($get_terms[0])) {
												$points_category_user = (int)get_user_meta($post_data->post_author,"points_category".$get_terms[0],true);
											}
										}
										do_action('discy_action_after_question_author',$post_data->ID);
										do_action("wpqa_get_badge",$post_data->post_author,"",(isset($points_category_user)?$points_category_user:""));
									}else {
										echo '<span class="question-author-un">';
										if (is_singular("question")) {
											echo '<span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">';
										}
										echo apply_filters('wpqa_question_before_author',false).esc_html($question_username);
										if (is_singular("question")) {
											echo '</span></span>';
										}
										echo '</span>';
									}
								}
								if ($wpqa_question_top_meta == true && $question_date == "on" || ($get_question_user_id == "" && $category_question == "on") || $asked_to == "on") {?>
									<div class="post-meta">
										<?php discy_meta(apply_filters("discy_filter_question_date",$question_date),apply_filters("discy_filter_category_question",$category_question),"",$asked_to,"","",$post_data->ID,$post_data);
										do_action("discy_action_after_question_meta",$post_data->ID)?>
									</div>
								<?php }
								do_action("discy_action_after_question_header",$post_data->ID,$post_data->post_author,$anonymously_user)?>
							</div>
						</header>
						<?php $show_title_question = apply_filters("discy_filter_show_title_question",true);
						if ($show_title_question == true) {?>
							<div<?php echo (is_single()?' itemprop="name"':'')?>>
								<?php if (is_single()) {
									$custom_page_setting = discy_post_meta("custom_page_setting");
									if ($custom_page_setting == "on") {
										$breadcrumbs = discy_post_meta("breadcrumbs");
									}else {
										$breadcrumbs = discy_options("breadcrumbs");
									}
									$breadcrumbs_style = discy_options("breadcrumbs_style");
									$breadcrumbs_content_title = discy_options("breadcrumbs_content_title");
									if ($breadcrumbs != "on" || ($breadcrumbs == "on" && ($breadcrumbs_style != "style_2" || ($breadcrumbs_content_title != "on" && $breadcrumbs_style == "style_2")))) {
										the_title( '<'.($breadcrumbs == "on" && $breadcrumbs_style == "style_2"?"h2":"h1").' class="'.(isset($title_post_style) && $title_post_style == "style_2"?"post-title-2":"post-title").'">'.(isset($title_post_style) && $title_post_style == "style_2" && isset($title_post_icon) && $title_post_icon != ""?"<i class='".$title_post_icon."'></i>":""), apply_filters("discy_filter_after_title_question",false).'</'.($breadcrumbs == "on" && $breadcrumbs_style == "style_2"?"h2":"h1").'>' );
									}
								}else {
									if ($pending_questions) {
										the_title( '<h2 class="'.(isset($title_post_style) && $title_post_style == "style_2"?"post-title-2":"post-title").'">'.(isset($title_post_style) && $title_post_style == "style_2" && isset($title_post_icon) && $title_post_icon != ""?"<i class='".$title_post_icon."'></i>":""), apply_filters("discy_filter_after_title_question",false).'</h2>' );
									}else {
										the_title( '<h2 class="post-title"><a'.$question_link_target.' class="post-title" href="' . esc_url( get_permalink() ) . '" rel="bookmark">', apply_filters("discy_filter_after_title_question",false).'</a></h2>' );
									}
								}?>
							</div>
							<?php do_action("discy_action_after_question_title",$post_data->ID);
						}?>
					</div><!-- End question-content-first -->
					<?php if ($discoura_style == "on") {?>
						<div class="question-image-vote<?php echo (isset($wpqa_vote)?"":" question-image-not-vote")?>">
							<?php if (isset($wpqa_image)) {
								do_action("wpqa_action_avatar_link",array("user_id" => (isset($post_data->post_author) && $post_data->post_author > 0?$post_data->post_author:0),"size" => apply_filters("discy_question_profile_size","42"),"span" => "span","pop" => "pop","post" => $post,"name" => $question_username,"email" => (isset($question_email) && $question_email != ""?$question_email:"")));
							}
							if ($post_data->post_author > 0) {
								$get_author_profile = get_author_posts_url($post_data->post_author);
								if (is_singular("question")) {
									echo '<span itemprop="author" itemscope itemtype="http://schema.org/Person">';
								}
								if (isset($get_author_profile)) {?>
									<a class="post-author" itemprop="url" href="<?php echo esc_url($get_author_profile)?>">
								<?php }
								if (is_singular("question")) {
									echo '<span itemprop="name">';
								}
								echo apply_filters('wpqa_question_before_author',false).esc_html($question_username);
								if (is_singular("question")) {
									echo '</span>';
								}
								if (isset($get_author_profile)) {?>
									</a>
								<?php }
								if (is_singular("question")) {
									echo '</span>';
								}
								do_action("wpqa_verified_user",$post_data->post_author);
								$active_points_category = discy_options("active_points_category");
								if ($active_points_category == "on") {
									$get_terms = wp_get_post_terms($post_data->ID,'question-category',array('fields' => 'ids'));
									if (!empty($get_terms) && is_array($get_terms) && isset($get_terms[0])) {
										$points_category_user = (int)get_user_meta($post_data->post_author,"points_category".$get_terms[0],true);
									}
								}
								do_action('discy_action_after_question_author',$post_data->ID);
								do_action("wpqa_get_badge",$post_data->post_author,"",(isset($points_category_user)?$points_category_user:""));
								if ($privacy_credential == true && $profile_credential != "") {?>
	                        		<span class="profile-credential"><?php echo esc_html($profile_credential)?></span>
	                        	<?php }
							}else {
								echo '<span class="question-author-un">';
								if (is_singular("question")) {
									echo '<span itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name">';
								}
								echo apply_filters('wpqa_question_before_author',false).esc_html($question_username);
								if (is_singular("question")) {
									echo '</span></span>';
								}
								echo '</span>';
							}
                        	?>
						</div>
					<?php }
					if (isset($wpqa_vote) || isset($wpqa_image)) { ?>
						<div class="question-not-mobile question-image-vote question-vote-sticky<?php echo (isset($wpqa_vote)?"":" question-image-not-vote")?>">
							<div class="<?php apply_filters('wpqa_question_sticky_image_vote',"question-sticky")?>">
								<?php if (isset($wpqa_vote) && !isset($wpqa_vote_meta)) {
									do_action("wpqa_question_vote",$post,$user_id,$anonymously_user,$question_vote,$question_loop_dislike,$question_single_dislike,"");
								}?>
							</div><!-- End question-sticky -->
						</div><!-- End question-image-vote -->
					<?php }?>
					<div class="question-content question-content-second<?php echo ($discoura_style != "on" && isset($wpqa_image) && (isset($wpqa_vote_meta) || !isset($wpqa_vote))?" question-third-image":"").($question_date != "on" && $category_question != "on"?" no-data-category":"")?>">
						<?php if (is_single()) {?>
							<div class="wpqa_error"></div>
							<div class="wpqa_success"></div>
						<?php }
						if ($yes_private != 1 && $private_question_content == "on") {?>
							<div class="alert-message warning"><i class="icon-flag"></i><p><?php esc_html_e("Sorry it's a private question.","discy");?></p></div>
						<?php }else {?>
							<div class="post-wrap-content">
								<?php do_action("discy_action_before_question_content_1",(isset($question_url_1)?$question_url_1:""),(isset($question_url_2)?$question_url_2:""),$featured_image_question_width,$featured_image_question_height,(isset($img_lightbox)?$img_lightbox:""));?>
								<div class="question-content-text">
									<?php do_action("discy_action_before_question_content",$pending_questions,(isset($title_post_style)?$title_post_style:""),(isset($title_post_icon)?$title_post_icon:""));
									$filter_question_image = apply_filters("discy_filter_question_image",true);
									$filter_question_content = apply_filters("discy_filter_question_content",true,array("post" => $post,"discy_sidebar" => $discy_sidebar,"excerpt_type" => $excerpt_type,"excerpt_questions" => $excerpt_questions,"question_excerpt" => $question_excerpt,"read_more_question" => $read_more_question,"question_date" => $question_date,"category_question" => $category_question,"question_columns" => $question_columns,"question_poll" => $question_poll,"author_by" => $author_by,"get_question_user_id" => $get_question_user_id,"asked_to" => $asked_to,"question_username" => $question_username,"question_email" => $question_email,"wpqa_image" => (isset($wpqa_image)?$wpqa_image:false),"anonymously_user" => $anonymously_user));

									if (($pending_questions || is_single() || (!is_single() && $question_poll_loop == "on")) && $question_poll == "on") {
										if ($filter_question_image == true && $filter_question_content == true && ($pending_questions || is_single())) {
											if ($featured_image_question == "on" && has_post_thumbnail() && $featured_position != "after" && $poll_position == "before") {
												echo "<div class='featured_image_question'>".apply_filters("discy_featured_image_question",discy_get_aq_resize_img($featured_image_question_width,$featured_image_question_height,$img_lightbox),$post_data->ID)."</div>
												<div class='clearfix'></div>";
											}
										}
										if ($filter_question_image == true && ($pending_questions || !is_single()) && ($featured_image_question == "on" && has_post_thumbnail())) {
											if ($featured_position != "after" && $poll_position == "before") {
												echo "<div class='featured_image_question'>".$question_url_1.discy_get_aq_resize_img($featured_image_question_width,$featured_image_question_height,$img_lightbox).$question_url_2."</div>
												<div class='clearfix'></div>";
											}
										}?>
										<div class='all_signle_post_content poll-area<?php echo ($pending_questions?" discy_hide":"")?>'>
											<?php echo (has_wpqa() && wpqa_plugin_version >= '4.2.2'?wpqa_show_poll_results($post_data->ID,$user_id):"")?>
										</div><!-- End poll-area -->
									<?php }

									do_action("discy_action_question_content",array("post" => $post,"discy_sidebar" => $discy_sidebar,"excerpt_type" => $excerpt_type,"excerpt_questions" => $excerpt_questions,"question_excerpt" => $question_excerpt,"read_more_question" => $read_more_question,"question_date" => $question_date,"category_question" => $category_question,"question_columns" => $question_columns,"question_poll" => $question_poll,"author_by" => $author_by,"get_question_user_id" => $get_question_user_id,"asked_to" => $asked_to,"question_username" => $question_username,"question_email" => $question_email,"wpqa_image" => (isset($wpqa_image)?$wpqa_image:false),"anonymously_user" => $anonymously_user));
									if ($filter_question_content == true) {
										if ($pending_questions || is_single()) {
											echo "<div class='all_signle_post_content".($pending_questions?" discy_hide":"")."'>";
												if ($filter_question_image == true && $featured_image_question == "on" && has_post_thumbnail() && $featured_position != "after" && $poll_position == "after") {
													echo "<div class='featured_image_question'>".apply_filters("discy_featured_image_question",discy_get_aq_resize_img($featured_image_question_width,$featured_image_question_height,$img_lightbox),$post_data->ID)."</div>
													<div class='clearfix'></div>";
												}
												$filter_question_video = apply_filters("discy_filter_question_video",true);
												if ($filter_question_video == true) {
													$video_description = discy_post_meta("video_description","",false);
													if ($video_desc_active == "on" && $video_description == "on") {
														$video_id = discy_post_meta("video_id","",false);
														$video_type = discy_post_meta("video_type","",false);
														if ($video_id != "") {
															$type = (has_wpqa() && wpqa_plugin_version >= 4.4?wpqa_video_iframe($video_type,$video_id):"");
															$video_desc_height = discy_options("video_desc_height");
															if ($video_desc_height == "") {
																if ($discy_sidebar == "menu_sidebar") {
																	$video_height = "420";
																}else if ($discy_sidebar == "menu_left") {
																	$video_height = "600";
																}else if ($discy_sidebar == "full") {
																	$video_height = "700";
																}else if ($discy_sidebar == "centered") {
																	$video_height = "510";
																}else {
																	$video_height = "550";
																}
															}else {
																$video_height = $video_desc_height;
															}
															
															$last_video = '<div class="question-video"><iframe frameborder="0" allowfullscreen height="'.$video_height.'" src="'.$type.apply_filters('discy_after_video_type',false,$post_data->ID).'"></iframe></div>';
															
															if ($video_desc == "before" && $video_desc_active == "on" && isset($video_id) && $video_id != "" && $video_description == "on") {
																echo ($last_video);
															}
														}
													}
												}

												$get_the_content = get_the_content();
												$get_the_content = apply_filters('the_content',$get_the_content);?>
												<div class="content-text<?php echo (is_single() && $get_the_content == ""?" discy_hide":"")?>"<?php echo (is_single()?' itemprop="text"':'')?>>
													<?php if (is_single() && $get_the_content == "") {
														the_title();
													}
													echo ($get_the_content)?>
												</div>
												
												<?php if ($filter_question_image == true && $featured_image_question == "on" && has_post_thumbnail() && $featured_position == "after") {
													echo "<div class='featured_image_question featured_image_after'>".apply_filters("discy_featured_image_question",discy_get_aq_resize_img($featured_image_question_width,$featured_image_question_height,$img_lightbox),$post_data->ID)."</div>
													<div class='clearfix'></div>";
												}
												
												if ($filter_question_video == true) {
													if ($video_desc == "after" && $video_desc_active == "on" && isset($video_id) && $video_id != "" && $video_description == "on") {
														echo ($last_video);
													}
												}
												do_action("discy_action_question_video_after");
												
												if (is_singular("question")) {
													do_action("wpqa_after_question_area",$post_data->ID,$user_id,$anonymously_user,$post_data->post_author,$count_post_all,$featured_image_question,$featured_position);
												}
												
												$show_attachment_filter = apply_filters("discy_show_attachment_filter",true);
												if ($show_attachment_filter == true) {
													$added_file = discy_post_meta("added_file","",false);
													$attachment_m = discy_post_meta("attachment_m","",false);
													if ($added_file != "" || (isset($attachment_m) && is_array($attachment_m) && !empty($attachment_m))) {
														echo "<div class='attachment-links'>";
															if ($added_file != "") {
																$img_url = wp_get_attachment_url($added_file);
																$file = get_attached_file($added_file);
																echo "<a class='attachment-link' title='".esc_html(wp_basename($file))."' href='".$img_url."'><i class='icon-link'></i>".esc_html__("Attachment","discy")."</a>";
															}
															
															if (isset($attachment_m) && is_array($attachment_m) && !empty($attachment_m)) {
																foreach ($attachment_m as $key => $value) {
																	$img_url = wp_get_attachment_url($value["added_file"]);
																	$file = get_attached_file($value["added_file"]);
																	echo "<a class='attachment-link' title='".esc_html(wp_basename($file))."' href='".$img_url."'><i class='icon-link'></i>".esc_html__("Attachment","discy")."</a>";
																}
															}
														echo "</div>";
													}
												}
											echo "</div><!-- End all_signle_post_content -->";
										}
										if ($pending_questions || !is_single()) {
											echo "<div class='all_not_signle_post_content'>";
												if ($filter_question_image == true && $featured_image_question == "on" && has_post_thumbnail()) {
													if ($featured_position != "after" && $poll_position == "after") {
														echo "<div class='featured_image_question'>".$question_url_1.discy_get_aq_resize_img($featured_image_question_width,$featured_image_question_height,$img_lightbox).$question_url_2."</div>
														<div class='clearfix'></div>";
													}
												}
												$filter_question_video = apply_filters("discy_filter_question_video",true);
												if ($filter_question_video == true) {
													$video_desc_active_loop = discy_options("video_desc_active_loop");
													if ($video_desc_active_loop == "on") {
														$video_desc_loop = discy_options("video_desc_loop");
														$video_description_width = discy_options("video_description_width");
														$video_desc_100_loop = discy_options("video_desc_100_loop");
														$video_description_height = discy_options("video_description_height");
														
														$video_description = discy_post_meta("video_description","",false);
														if ($video_desc_active == "on" && $video_description == "on") {
															$video_desc = discy_post_meta("video_desc","",false);
															$video_id = discy_post_meta("video_id","",false);
															$video_type = discy_post_meta("video_type","",false);
															if ($video_id != "") {
																$type = (has_wpqa() && wpqa_plugin_version >= 4.4?wpqa_video_iframe($video_type,$video_id):"");
																$last_video = '<div class="question-video-loop'.($video_desc_100_loop == "on"?' question-video-loop-100':'').($video_desc_loop == "after"?' question-video-loop-after':'').'"><iframe frameborder="0" allowfullscreen width="'.$video_description_width.'" height="'.$video_description_height.'" src="'.$type.apply_filters('discy_after_video_type',false,$post_data->ID).'"></iframe></div>';
																if ($video_desc_loop == "before") {
																	echo ($last_video);
																}
															}
														}
													}
												}
												if ($excerpt_questions != "on") {
													$question_excerpt = (isset($question_excerpt) && $question_excerpt != ""?$question_excerpt:40);?>
													<p class="excerpt-question"><?php echo apply_filters("discy_question_excerpt",discy_excerpt($question_excerpt,$excerpt_type,$read_more_question,"return"));?></p>
													<?php if ($read_more_question == "on" && $read_jquery_question == "on") {?>
														<div class="content-question-jquery discy_hide"><?php the_content()?><a class="question-read-less" href="#" title="<?php echo esc_attr__('Read less','discy').' '.get_the_title($post_data->ID)?>"><?php esc_html_e('Read less','discy')?></a></div>
													<?php }
												}
												
												$filter_question_image_loop = apply_filters("discy_filter_question_image_loop",true);
												if ($filter_question_image_loop == true && $filter_question_image == true && $featured_image_question == "on" && has_post_thumbnail() && $featured_position == "after") {
													echo "<div class='featured_image_question featured_image_after'>".$question_url_1.discy_get_aq_resize_img($featured_image_question_width,$featured_image_question_height,$img_lightbox).$question_url_2."</div>
													<div class='clearfix'></div>";
												}
												
												if ($filter_question_video == true) {
													if ($video_desc_active_loop == "on" && isset($video_desc_loop) && $video_desc_loop == "after" && isset($video_desc_active) && $video_desc_active == "on" && isset($video_id) && $video_id != "" && isset($video_description) && $video_description == "on") {
														echo ($last_video);
													}
												}
												do_action("discy_action_question_video_after");
											echo "</div><!-- End all_not_signle_post_content -->";
										}
									}
									do_action("discy_action_after_question_content",$post_data->ID);?>
								</div>
								<?php if (is_single()) {
									wp_link_pages(array('before' => '<div class="pagination post-pagination">','after' => '</div>','link_before' => '<span>','link_after' => '</span>'));
								}
								if ((is_single() && $question_tags == "on") || (!is_single() && $question_tags_loop == "on")) {
									$terms = wp_get_object_terms( $post_data->ID, 'question_tags' );
									if (isset($terms) && is_array($terms) && !empty($terms)) {
										echo '<div class="tagcloud"><div class="question-tags"><i class="icon-tags"></i>';
											$terms_array = array();
											foreach ($terms as $term) :
												if (isset($term->slug) && isset($term->name)) {
													$get_term_link = get_term_link($term->slug, 'question_tags');
													if (is_string($get_term_link)) {
														echo '<a href="'.$get_term_link.'">'.$term->name.'</a>';
													}
												}
											endforeach;
										echo '</div></div>';
									}
								}
								do_action("discy_after_question_tags",$post_data->ID);?>
							</div>
							<?php if (!is_single()) {
								do_action('wpqa_question_after_tags',$post_data->ID,$post,$author_by,$anonymously_user,$question_date,$category_question,$asked_to,$get_question_user_id);?>
								<div class="wpqa_error"></div>
								<div class="wpqa_success"></div>
								<?php if ($question_answer_place == "before") {
									include locate_template("theme-parts/inner-answer.php");
								}
							}
						}
						do_action("wpqa_before_question_meta",$post_data->ID,$post,$anonymously_user,$user_id);
						$following_questions = discy_post_meta("following_questions","",false);
						$following_questions = (is_array($following_questions) && !empty($following_questions)?get_users(array('fields' => 'ID','include' => $following_questions,'orderby' => 'registered')):array());
						if ($pending_questions || $question_answer == "on" || $question_views == "on" || ($question_follow_loop == "on" && !is_single()) || ($question_follow == "on" && is_single()) || ($active_vote == "on" && $vote_question_loop != "on" && !is_single()) || ($question_favorite == "on" && (is_single() || (isset($first_one) && $first_one === "favorites"))) || ($question_answer == "on" && (!is_single() || (is_single() && $user_id != $get_question_user_id && (($user_id == $post_data->post_author && $user_id > 0) || ($anonymously_user != "" && $anonymously_user == $user_id)))))) {?>
							<footer class="question-footer<?php echo ($pending_questions?" pending-post-footer":"").($question_meta_icon == "on"?" question-footer-icons":"")?>">
								<?php if (has_wpqa() && $pending_questions) {
									wpqa_review_post($post,$is_super_admin,$moderators_permissions);
								}else {
									if (isset($wpqa_vote_meta)) {
										do_action("wpqa_question_vote",$post,$user_id,$anonymously_user,$question_vote,$question_loop_dislike,$question_single_dislike);
									}?>
									<ul class="footer-meta">
										<?php if ($question_answer == "on") {?>
											<li class="best-answer-meta<?php echo ($the_best_answer != ""?" meta-best-answer":"")?>"><a<?php echo esc_attr($question_link_target)?> href="<?php echo get_permalink()?>#comments"><i class="icon-comment"></i><?php discy_meta("","",$question_answer,"",$question_meta_icon,"",$post_data->ID,$post_data)?></a></li>
										<?php }
										$active_post_stats = discy_options("active_post_stats");
										if ($question_views == "on" && $active_post_stats == "on") {
											$post_meta_stats = discy_options("post_meta_stats");
											$cache_post_stats = discy_options("cache_post_stats");
											$post_meta_stats = ($post_meta_stats != ""?$post_meta_stats:"post_stats");
											if ($cache_post_stats == "on") {
												$post_stats = get_transient($post_meta_stats.$post_data->ID);
												$post_stats = ($post_stats !== false?$post_stats:discy_post_meta($post_meta_stats,"",false));
											}else {
												$post_stats = discy_post_meta($post_meta_stats,"",false);
											}
											$post_stats = (int)$post_stats;?>
											<li class="view-stats-meta"><i class="icon-eye"></i><?php echo discy_count_number($post_stats)." <span class='question-span'>".($question_meta_icon != "on"?_n("View","Views",$post_stats,"discy")."</span>":"")?></li>
										<?php }

										if ($active_vote == "on" && $vote_question_loop != "on" && !is_single() && $vote_question_single == "on") {?>
											<li class="votes-meta"><i class="icon-chart-bar"></i><?php echo discy_count_number($question_vote).($question_meta_icon != "on"?" <span class='question-span'>"._n("Vote","Votes",$question_vote,"discy")."</span>":"")?></li>
										<?php }
										
										$question_bump = discy_options("question_bump");
										$active_points = discy_options("active_points");
										if ($bump_meta == "on" && $question_bump == "on" && $active_points == "on" && ((is_single()) || (!is_single() && isset($orderby_post) && $orderby_post == "question_bump"))) {
											if ($count_post_all == 0) {
												$question_points = (int)discy_post_meta("question_points","",false);
												if ($question_points > 0) {
													$bump_meta_show = true;?>
													<li class="question-bump-meta"><i class="icon-heart"></i><?php $question_points = (int)discy_post_meta("question_points","",false);
													echo (int)$question_points.($question_meta_icon != "on"?" <span class='question-span'>"._n("Point","Points",$question_points,"discy")."</span>":"")?></li>
												<?php }
											}
										}
										$show_the_follow_question = apply_filters("discy_show_the_follow_question",false);
										if (($question_columns == "style_2" && $question_follow_loop == "on" && !is_single() && $show_the_follow_question == true) || ($question_columns != "style_2" && $question_follow_loop == "on" && !is_single()) || ($question_follow == "on" && is_single())) {
											$follow_meta_show = true;
											$what_follow = $what_unfollow = false;
											$following_questions_number = (int)(isset($following_questions) && is_array($following_questions)?discy_count_number(count($following_questions)):0);
											if ($user_id > 0 && $user_id != $get_question_user_id && is_user_logged_in() && ($user_id != $post_data->post_author || ($anonymously_user != "" && $anonymously_user != $user_id))) {
												$what_follow = true;
											}
											if ((isset($following_questions) && is_array($following_questions) && in_array($user_id,$following_questions))) {
												$what_unfollow = true;
											}?>
											<li class="question-followers<?php echo ($what_follow == true?"":" question-followers-no-link").($what_follow == true && isset($following_questions) && is_array($following_questions) && in_array($user_id,$following_questions)?" li-follow-question":"")?>">
												<?php if ($what_follow == true) {?>
													<div class="small_loader loader_2"></div>
													<a href="#"<?php echo ($what_unfollow == true?' class="unfollow-question"':'')?> title="<?php echo ($what_unfollow == true?esc_attr__("Unfollow the question","discy"):esc_attr__("Follow the question","discy"))?>"><i class="<?php echo ($what_unfollow == true?"icon-minus":"icon-plus")?>"></i>
												<?php }else {?>
													<i class="icon-users"></i>
												<?php }
													echo "<span>".discy_count_number($following_questions_number)."</span> ".($question_meta_icon != "on"?_n("Follower","Followers",$following_questions_number,"discy"):"");
												if ($what_follow == true) {?>
													</a>
												<?php }?>
											</li>
											<?php do_action("discy_end_footer_meta_list",$post_data->ID);
										}

										if ($question_favorite == "on" && (is_single() || (has_wpqa() && wpqa_is_user_favorites()) || (isset($first_one) && $first_one === "favorites"))) {
											$question_favorites = discy_post_meta("question_favorites","",false);
											if (is_user_logged_in() && $user_id != $get_question_user_id && (($user_id != $post_data->post_author && $user_id > 0) || ($anonymously_user != "" && $anonymously_user != $user_id))) {
												$_favorites = get_user_meta($user_id,$user_id."_favorites",true);
												if (isset($_favorites) && is_array($_favorites) && in_array($post_data->ID,$_favorites)) {
													$what_favorite = "remove_favorite";
												}else {
													$what_favorite = "add_favorite";
												}
											}?>
											<li class="question-favorites<?php echo ((isset($what_favorite) && ($what_favorite == "add_favorite" || $what_favorite == "remove_favorite"))?"":" question-favorites-no-link").(isset($what_favorite) && $what_favorite == "remove_favorite"?" active-favorite":"");?>">
												<div class="small_loader loader_2"></div>
												<?php if (isset($what_favorite) && ($what_favorite == "add_favorite" || $what_favorite == "remove_favorite")) {
													echo '<a class="'.($what_favorite == "add_favorite"?'add_favorite':'remove_favorite').'" title="'.($what_favorite == "add_favorite"?esc_html__("Add this question to favorites","discy"):esc_html__("Remove this question of my favorites","discy")).'" href="#">';
												}?>
												<i class="icon-star"></i>
												<span><?php echo ($question_favorites != ""?discy_count_number($question_favorites):0);?></span>
												<?php echo (isset($what_favorite) && ($what_favorite == "add_favorite" || $what_favorite == "remove_favorite")?'</a>':'')?>
											</li>
										<?php }
										
										if (is_single() && $question_simple == "on" && ((is_user_logged_in() && ($question_edit == "on" || $question_delete == "on" || $question_close == "on" || $is_super_admin)) || ($active_reports == "on" && (is_user_logged_in() || (!is_user_logged_in() && $active_logged_reports != "on"))))) {?>
											<li class="question-list-details">
												<i class="icon-dot-3"></i>
												<?php do_action("wpqa_question_list_details",$post,$user_id,$anonymously_user,$question_edit,$question_delete,$question_close,$closed_question,$active_reports,$active_logged_reports,$moderators_permissions);?>
											</li>
										<?php }
										
										if (is_single() && ($share_style == "style_2" || $question_simple == "on") && has_wpqa() && ($share_facebook == "share_facebook" || $share_twitter == "share_twitter" || $share_linkedin == "share_linkedin" || $share_whatsapp == "share_whatsapp")) {?>
											<li class="question-share">
												<i class="icon-share"></i><?php echo ($question_meta_icon != "on"?"<span class='question-span'>".esc_html__("Share","discy")."</span>":"");
												wpqa_share($post_share,$share_facebook,$share_twitter,$share_linkedin,$share_whatsapp,($question_simple == "on"?"style_2":$share_style));?>
											</li>
										<?php }?>
									</ul>
									<?php if (isset($follow_meta_show) && isset($bump_meta_show)) {
										$not_show_answer = true;
									}
									if ($post_data->comment_status == "open" && $question_simple != "on" && $question_answer == "on" && (!is_single() || (is_single() && !isset($not_show_answer)))) {?>
										<a<?php echo esc_attr($question_link_target)?> class="meta-answer meta-answer-a" href="<?php echo get_permalink()?>#respond"><?php esc_html_e("Answer","discy")?></a>
									<?php }
								}?>
							</footer>
						<?php }
						do_action("wpqa_after_question_meta",$post_data->ID,$post,$anonymously_user,$user_id);?>
					</div><!-- End question-content-second -->
					<div class="clearfix"></div>
				</div><!-- End question-inner -->
				<?php if (is_single()) {
					if ($question_simple != "on" && (($question_edit == "on" || $question_delete == "on" || $question_close == "on" || $is_super_admin) || ($active_reports == "on" && (is_user_logged_in() || (!is_user_logged_in() && $active_logged_reports != "on"))) || ($share_style != "style_2" && ($share_facebook == "share_facebook" || $share_twitter == "share_twitter" || $share_linkedin == "share_linkedin" || $share_whatsapp == "share_whatsapp")))) {?>
						<div class="question-bottom">
							<?php if ($share_style != "style_2" && $question_simple != "on" && has_wpqa()) {
								wpqa_share($post_share,$share_facebook,$share_twitter,$share_linkedin,$share_whatsapp);
							}
							do_action("wpqa_question_list_details",$post,$user_id,$anonymously_user,$question_edit,$question_delete,$question_close,$closed_question,$active_reports,$active_logged_reports,$moderators_permissions);?>
							<div class="clearfix"></div>
						</div><!-- End question-bottom -->
					<?php }
				}else if ($question_answer_place != "before") {
					include locate_template("theme-parts/inner-answer.php");
				}?>
			</div><!-- End single-inner-content -->
			<?php if (is_single()) {
				$custom_answer_tabs = discy_post_meta("custom_answer_tabs");
				if ($custom_answer_tabs == "on") {
					$answers_tabs = discy_post_meta('answers_tabs');
				}else {
					$answers_tabs = discy_options('answers_tabs');
				}
				$answers_tabs = apply_filters("wpqa_answers_tabs",$answers_tabs);
				$answers_tabs_keys = array_keys($answers_tabs);
				if (isset($answers_tabs) && is_array($answers_tabs)) {
					$a_count = 0;
					while ($a_count < count($answers_tabs)) {
						if (isset($answers_tabs[$answers_tabs_keys[$a_count]]["value"]) && $answers_tabs[$answers_tabs_keys[$a_count]]["value"] != "" && $answers_tabs[$answers_tabs_keys[$a_count]]["value"] != "0") {
							$first_one = $a_count;
							break;
						}
						$a_count++;
					}
					
					if (isset($first_one) && $first_one != "") {
						$first_one = $answers_tabs[$answers_tabs_keys[$first_one]]["value"];
					}
					
					if (isset($_GET["show"]) && $_GET["show"] != "") {
						$first_one = $_GET["show"];
					}
				}
				if ($question_related == "on") {
					include locate_template("theme-parts/related.php");
				}?>
				<div class="question-adv-comments <?php echo ($count_post_all > 0 && $question_answers == "on"?"question-has-comments":"question-not-comments").(isset($first_one) && $first_one != ""?" question-has-tabs":"")?>">
					<?php echo discy_ads("share_adv_type","share_adv_code","share_adv_href","share_adv_img","","on","discy-ad-inside");
					if ((comments_open() || $count_post_all > 0) && $question_answers == "on") {
						comments_template();
					}?>
				</div>
			<?php }?>
		</article><!-- End article -->
	<?php }else {
		echo '<article class="article-question private-question article-post clearfix">
			<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, you do not have a permission to show the question.","discy").'</p></div>
			'.(has_wpqa()?wpqa_paid_subscriptions(true):'').'
		</article>';
	}
}?>