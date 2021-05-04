<?php $custom_permission = discy_options("custom_permission");
$user_id = get_current_user_id();
if (is_user_logged_in()) {
	$user_is_login = get_userdata($user_id);
	$roles = $user_is_login->allcaps;
}

$wpqa_server = apply_filters('wpqa_server','SCRIPT_FILENAME');
if (!empty($wpqa_server) && 'comments.php' == basename($wpqa_server)) :
	die (esc_html__('Please do not load this page directly. Thanks!',"discy"));
endif;

if ( post_password_required() ) : ?>
    <p class="no-comments">
    	<?php if ($post->post_type == 'question') {
    		esc_html_e("This question is password protected. Enter the password to view answers.","discy");
    	}else {
    		esc_html_e("This post is password protected. Enter the password to view comments.","discy");
    	}?>
    </p>
    <?php return;
endif;

if ( have_comments() ) :
	$k_ad = 1;?>
	<div id="comments" class="post-section">
		<div class="post-inner">
			<?php $filter_show_comments = apply_filters("discy_filter_show_comments",true,$post->post_type,$post->ID);
			if ($filter_show_comments == true) {
				if ($post->post_type == 'question') {
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
						
						if (isset($first_one) && $first_one !== "") {
							$first_one = $answers_tabs[$answers_tabs_keys[$first_one]]["value"];
						}
						
						if (isset($_GET["show"]) && $_GET["show"] != "") {
							$first_one = $_GET["show"];
						}
					}
					if (isset($first_one) && $first_one !== "") {
						$wpqa_answers_tabs_foreach = apply_filters("wpqa_answers_tabs_foreach",true,$answers_tabs,$first_one);
					}
					if (isset($wpqa_answers_tabs_foreach) && $wpqa_answers_tabs_foreach == true && $post->post_type == 'question' && isset($first_one) && $first_one !== "") {?>
						<div class="answers-tabs">
					<?php }
				}
					$count_post_all = (int)(has_wpqa()?wpqa_count_comments($post->ID):get_comments_number());?>
					<h3 class="section-title"><span><?php echo ($post->post_type == 'question'?sprintf(_n("%s Answer","%s Answers",$count_post_all,"discy"),$count_post_all):sprintf(_n("%s Comment","%s Comments",$count_post_all,"discy"),$count_post_all));?></h3>
				<?php if (isset($wpqa_answers_tabs_foreach) && $wpqa_answers_tabs_foreach == true && $post->post_type == 'question' && isset($first_one) && $first_one !== "") {?>
						<div class="answers-tabs-inner">
							<ul>
								<?php foreach ($answers_tabs as $key => $value) {
									if ($key == "votes" && isset($answers_tabs["votes"]["value"]) && $answers_tabs["votes"]["value"] == "votes") {?>
										<li<?php echo ((isset($_GET["show"]) && $_GET["show"] === "votes") || $first_one === "votes"?" class='active-tab'":"")?>><a href="<?php echo esc_url_raw(add_query_arg(array("show" => "votes")))?>#comments"><?php esc_html_e("Voted","discy")?></a></li>
									<?php }else if ($key == "oldest" && isset($answers_tabs["oldest"]["value"]) && $answers_tabs["oldest"]["value"] == "oldest") {?>
										<li<?php echo ((isset($_GET["show"]) && $_GET["show"] === "oldest") || $first_one === "oldest"?" class='active-tab'":"")?>><a href="<?php echo esc_url_raw(add_query_arg(array("show" => "oldest")))?>#comments"><?php esc_html_e("Oldest","discy")?></a></li>
									<?php }else if ($key == "recent" && isset($answers_tabs["recent"]["value"]) && $answers_tabs["recent"]["value"] == "recent") {?>
										<li<?php echo ((isset($_GET["show"]) && $_GET["show"] === "recent") || $first_one === "recent"?" class='active-tab'":"")?>><a href="<?php echo esc_url_raw(add_query_arg(array("show" => "recent")))?>#comments"><?php esc_html_e("Recent","discy")?></a></li>
									<?php }else if ($key == "random" && isset($answers_tabs["random"]["value"]) && $answers_tabs["random"]["value"] == "random") {?>
										<li<?php echo ((isset($_GET["show"]) && $_GET["show"] === "random") || $first_one === "random"?" class='active-tab'":"")?>><a href="<?php echo esc_url_raw(add_query_arg(array("show" => "random")))?>#comments"><?php esc_html_e("Random","discy")?></a></li>
									<?php }
								}?>
							</ul>
						</div><!-- End answers-tabs-inner -->
						<div class="clearfix"></div>
					</div><!-- End answers-tabs -->
				<?php }
				if ($post->post_type == 'question' && isset($first_one) && $first_one !== "" && isset($answers_tabs)) {
					do_action("wpqa_answers_after_tabs",$answers_tabs,$first_one);
				}
				$show_answer = discy_options("show_answer");
				if ($post->post_type != 'question' || (($post->post_type == 'question') && (is_super_admin($user_id)) || $custom_permission != "on" || (is_user_logged_in() && $custom_permission == "on" && isset($roles["show_answer"]) && $roles["show_answer"] == 1) || (!is_user_logged_in() && $show_answer == "on"))) {
					if ($post->post_type == 'question') {
						if (isset($first_one) && $first_one !== "") {
							if ($user_id > 0) {
								$include_unapproved = array($user_id);
							}else {
								$unapproved_email = wp_get_unapproved_comment_author_email();
								if ($unapproved_email) {
									$include_unapproved = array($unapproved_email);
								}
							}
							$include_unapproved_args = (isset($include_unapproved)?array('include_unapproved' => $include_unapproved):array());
							$get_comments_args = array_merge($include_unapproved_args,array('post_id' => $post->ID,'status' => 'approve'));
							if ($first_one == 'votes') {
								$comments_args = get_comments(array_merge($get_comments_args,array('orderby' => array('meta_value_num' => 'DESC','comment_date' => 'ASC'),'meta_key' => 'comment_vote','order' => 'DESC')));
							}else if ($first_one == 'oldest') {
								$comments_args = get_comments(array_merge($get_comments_args,array('orderby' => 'comment_date','order' => 'ASC')));
							}else if ($first_one == 'recent') {
								$comments_args = get_comments(array_merge($get_comments_args,array('orderby' => 'comment_date','order' => 'DESC')));
							}else if ($first_one == 'random') {
								$comments_args = get_comments(array_merge($get_comments_args,array('orderby' => 'rand','order' => 'DESC')));
								shuffle($comments_args);
							}
						}
					}?>
					<ol class="commentlist clearfix">
					    <?php if ($post->post_type == 'question' && isset($first_one) && $first_one !== "") {
					    	$comments_args = (isset($comments_args)?$comments_args:array());
						    $comments_args = apply_filters("wpqa_comments_args",$comments_args,$first_one,$post->ID);
						}
						$read_more_answer = discy_options("read_more_answer");
						$comment_read_more = ($post->post_type == 'question' && $read_more_answer == "on"?array('comment_read_more' => true):array());
					    $list_comments_args = array_merge(array('callback' => 'discy_comment'),$comment_read_more);
					    if (isset($comments_args) && is_array($comments_args) && !empty($comments_args)) {
					    	$comment_order = get_option('comment_order');
					    	if ($comment_order == "desc") {
					    		$comments_args = array_reverse($comments_args);
					    	}
					    	wp_list_comments($list_comments_args,$comments_args);
					    }else {
					    	$wpqa_show_comments = apply_filters("wpqa_show_comments",true);
					    	if ($wpqa_show_comments == true) {
						    	wp_list_comments($list_comments_args);
						    }
					    }?>
					</ol><!-- End commentlist -->
				<?php }else {
					echo '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, you do not have a permission to show this answers.","discy").' '.(has_wpqa()?wpqa_paid_subscriptions():'').'</p></div>';
				}
			}?>
			<div class="clearfix"></div>
		</div><!-- End post-inner -->
	</div><!-- End post -->
	
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<div class="pagination comments-pagination">
		    <?php paginate_comments_links(array('prev_text' => '<i class="icon-left-open"></i>', 'next_text' => '<i class="icon-right-open"></i>'))?>
		</div><!-- End comments-pagination -->
		<div class="clearfix"></div>
    <?php endif;
endif;

$comments_open = apply_filters("discy_comments_open",true);
if ($comments_open == true && comments_open()) {
	$can_add_answer = apply_filters("wpqa_can_add_answer",true,$user_id,$custom_permission,(isset($roles)?$roles:array()),$post);
	if ($can_add_answer == true) {
		echo '<div id="respond-all"'.(isset($edit_delete)?' class="respond-edit-delete discy_hide"':'').'>';
			$comment_editor = discy_options(($post->post_type == 'question'?'answer_editor':'comment_editor'));
			include locate_template("theme-parts/comment-form.php");
		echo '</div>';
	}
}else {
	do_action("discy_action_if_comments_closed");
}?>