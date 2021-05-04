<?php $updated_answers = discy_options("updated_answers");
$show_the_content = apply_filters("discy_show_the_content",true,(isset($wp_page_template)?$wp_page_template:""));
if ((is_single() || is_page()) && $show_the_content == true && isset($wp_page_template) && $wp_page_template != "" && $wp_page_template != "template-contact.php" && $wp_page_template != "template-faqs.php" && $wp_page_template != "template-landing.php" && $wp_page_template != "template-users.php" && $wp_page_template != "template-tags.php" && $wp_page_template != "template-categories.php") {
	include locate_template("theme-parts/the-content.php");
}
$k_ad_p                = -1;
$not_fount_error       = true;
$post_id_main          = (isset($post_id_main)?$post_id_main:"");
$pagination_show       = "yes";
$ask_question_to_users = discy_options("ask_question_to_users");
$pay_ask               = discy_options("pay_ask");
$first_one             = (isset($first_one) && $first_one != ""?$first_one:"");
$last_one              = (isset($last_one) && $last_one != ""?$last_one:"");
$get_user_var          = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
$user_id               = get_current_user_id();
$is_super_admin        = is_super_admin($user_id);
$question_bump         = discy_options("question_bump");
$active_points         = discy_options("active_points");
$custom_category       = (isset($tab_category) && $tab_category == true && isset($custom_args)?$custom_args:array());

include locate_template("includes/slugs.php");

include locate_template("includes/".(isset($its_question) && "question" == $its_question?"question":"loop")."-setting.php");
if (empty($blog_h) && ((isset($tab_category) && $tab_category == true && ($first_one == $answers_slug || $first_one == $answers_slug_2))) || (isset($wp_page_template) && (($wp_page_template == "template-home.php" && is_user_logged_in() && isset($first_one) && $first_one != "" && ($first_one == $answers_might_like_slug || $first_one == $answers_for_you_slug || $first_one == $answers_might_like_slug_2 || $first_one == $answers_for_you_slug_2))) || (isset($wp_page_template) && (($wp_page_template == "template-home.php" && isset($first_one) && $first_one != "" && ($first_one == $answers_slug || $first_one == $answers_slug_2 || (!is_user_logged_in() && ($first_one == $answers_might_like_slug || $first_one == $answers_for_you_slug || $first_one == $answers_might_like_slug_2 || $first_one == $answers_for_you_slug_2)))) || $wp_page_template == "template-comments.php")))) {
	include locate_template("includes/templates.php");
	$paged     = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
	$current   = max(1,$paged);
	$cat_posts = $tag_posts = $posts_array = array();
	if ((isset($tab_category) && $tab_category == true && ($first_one == $answers_slug || $first_one == $answers_slug_2)) || (isset($wp_page_template) && ($wp_page_template == "template-home.php" && is_user_logged_in() && isset($first_one) && $first_one != "" && ($first_one == $answers_might_like_slug || $first_one == $answers_for_you_slug || $first_one == $answers_might_like_slug_2 || $first_one == $answers_for_you_slug_2)))) {
		$rows_per_page = get_option('posts_per_page');
		$paged         = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
		$offset        = ($paged -1) * $rows_per_page;
		$current       = max(1,$paged);
		if (isset($wp_page_template) && (($wp_page_template == "template-home.php" && isset($first_one) && $first_one != "" && ($first_one == $answers_might_like_slug || $first_one == $answers_might_like_slug_2)))) {
			$user_cat_follow = get_user_meta($user_id,"user_cat_follow",true);
			$category_list = (is_array($user_cat_follow) && !empty($user_cat_follow)?$user_cat_follow:array());
			$user_tag_follow = get_user_meta($user_id,"user_tag_follow",true);
			$tag_list = (is_array($user_tag_follow) && !empty($user_tag_follow)?$user_tag_follow:array());
			$tag_posts = get_objects_in_term($tag_list,'question_tags');
		}else if (isset($wp_page_template) && (($wp_page_template == "template-home.php" && isset($first_one) && $first_one != "" && ($first_one == $answers_for_you_slug || $first_one == $answers_for_you_slug_2)))) {
			$category_list = get_user_meta($user_id,"wpqa_for_you_cats",true);
			$tag_list = get_user_meta($user_id,"wpqa_for_you_tags",true);
			$tag_posts = get_objects_in_term($tag_list,'question_tags');
		}else {
			$exclude       = apply_filters('wpqa_exclude_question_category',array());
			$categories    = get_terms('question-category',array_merge($exclude,array('child_of' => $category_id,'hide_empty' => false)));
			$category_list =  array($category_id);
			foreach ($categories as $term) {
				$category_list[] = (int)$term->term_id;
			}
		}

		$cat_posts = get_objects_in_term($category_list,'question-category');
		$posts = array_merge($tag_posts,$cat_posts);
		$posts_array = array("post__in" => $posts);
	}
	
	if ($orderby_answers == 'votes' && $post_type == 'question') {
		$args = array('order' => (isset($order_post)?$order_post:'DESC'),'orderby' => 'meta_value_num','meta_key' => 'comment_vote');
	}else if ($orderby_answers == 'oldest') {
		$args = array('order' => 'ASC','orderby' => 'comment_date');
	}else {
		$args = array('order' => (isset($order_post) && $orderby_answers == 'date'?$order_post:'DESC'),'orderby' => 'comment_date');
	}
	if (!function_exists('discy_comments_clauses')) :
		function discy_comments_clauses($clauses) {
			global $wpdb;
			$clauses["groupby"] = "{$wpdb->comments}.comment_post_ID";
			return $clauses;
		};
	endif;
	add_filter('comments_clauses','discy_comments_clauses');
	$comments_all = (isset($posts) && is_array($posts) && !empty($posts)?get_comments(array_merge($specific_date_array,$posts_array,$args,array('status' => 'approve','post_type' => $post_type,'meta_query' => array(array('key' => 'answer_question_user','compare' => 'NOT EXISTS'))))):array());
	$max_num_pages = $total = ceil(sizeof($comments_all)/$post_number);
	if (!empty($comments_all)) {
		if (isset($post_pagination) && ($post_pagination == "pagination" || $post_pagination == "standard")) {
			$pagination_args = array(
				'total'     => $total,
				'current'   => $current,
				'show_all'  => false,
				'prev_text' => ($post_pagination == 'standard'?'<span>'.esc_html__('New Answers',"discy").'</span><i class="icon-right-thin"></i>':'<i class="icon-left-open"></i>'),
				'next_text' => ($post_pagination == 'standard'?'<i class="icon-left-thin"></i><span>'.esc_html__('Old Answers',"discy").'</span>':'<i class="icon-left-open"></i>'),
			);
			if (!get_option('permalink_structure')) {
				$pagination_args['base'] = esc_url_raw(add_query_arg('paged','%#%'));
			}
		}
		
		$k_ad  = -1;
		$start = ($current - 1) * $post_number;
		$end   = $start + $post_number;?>
		<div class="page-content commentslist">
			<ol class="commentlist clearfix">
				<?php $end = (sizeof($comments_all) < $end) ? sizeof($comments_all) : $end;
				for ($k = $start;$k < $end ;++$k ) {$k_ad++;
					$comment = $comments_all[$k];
					$yes_private = (has_wpqa()?wpqa_private($comment->comment_post_ID,get_post($comment->comment_post_ID)->post_author,$user_id):1);
					if ($yes_private == 1) {
							$comment_id = esc_attr($comment->comment_ID);
							discy_comment($comment,"","",($post_type == "post"?"comment":"answer"),"",$k_ad,"",
								array(
									"answer_question_id" => (isset($post_id_main)?$post_id_main:""),
									"custom_home_answer" => (isset($wp_page_template) && $wp_page_template == "template-home.php" && isset($first_one) && $first_one != "" && ($first_one == $answers_slug || $first_one == $answers_might_like_slug || $first_one == $answers_for_you_slug || $first_one == $answers_slug_2 || $first_one == $answers_might_like_slug_2 || $first_one == $answers_for_you_slug_2)?discy_post_meta("custom_home_answer",$post_id_main):""),
									"custom_answers"     => (isset($wp_page_template) && $wp_page_template == "template-comments.php"?discy_post_meta("custom_answers",$post_id_main):""),
									"comment_with_title" => true
								)
							);?>
						</li>
					<?php }else {?>
						<li class="comment">
							<div class="comment-body clearfix">
								<?php echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("Sorry it is a private answer.","discy").'</p></div>';?>
							</div>
						</li>
					<?php }
				}?>
			</ol>
		</div>
	<?php }else {
		echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.($post_type == 'question'?esc_html__("There are no answers yet.","discy"):esc_html__("There are no comments yet.","discy")).'</p></div>';
	}
	if (isset($post_pagination) && ($post_pagination == "pagination" || $post_pagination == "standard") && $comments_all && $pagination_args["total"] > 1) {?>
		<div class="main-pagination"><div class='comment-pagination <?php echo ($post_pagination == "standard"?"standard-pagination page-navigation page-navigation-before":"pagination")?>'><?php echo (paginate_links($pagination_args) != ""?paginate_links($pagination_args):"")?></div></div>
	<?php }else if (isset($post_pagination) && ($post_pagination == "infinite_scroll" || $post_pagination == "load_more")) {
		$it_answer_pagination = true;
		if (has_wpqa()) {
			wpqa_pagination_load((isset($post_pagination)?$post_pagination:"pagination"),(isset($max_num_pages)?$max_num_pages:""),(isset($it_answer_pagination)?$it_answer_pagination:false),(isset($its_question)?$its_question:false),(isset($wpqa_query)?$wpqa_query:null),(isset($post_type) && $post_type == "post"?true:false));
		}
	}
	remove_filter('comments_clauses','discy_comments_clauses');
}else {
	if ((has_wpqa() && wpqa_is_user_profile()) || (isset($wp_page_template) && $wp_page_template == "template-home.php")) {
		$show_custom_error = true;
	}
	
	$array_data = array();
	
	$question_meta_query = ($ask_question_to_users == "on"?array("key" => "user_id","compare" => "NOT EXISTS"):array());
	$advanced_queries = discy_options("advanced_queries");
	if ($advanced_queries == "on" && !$is_super_admin) {
		$question_meta_query = array(
			$question_meta_query,array(
				'relation' => 'OR',
				array("key" => "private_question","compare" => "NOT EXISTS"),
				array("key" => "private_question","compare" => "=","value" => 0),
				array(
					'relation' => 'AND',
					array("key" => "private_question","compare" => "EXISTS"),
					array("key" => "private_question_author","compare" => "=","value" => $user_id),
				)
			)
		);
	}
	
	$tax_filter = apply_filters("discy_before_question_category",false);
	$tax_question = apply_filters("discy_question_category","question-category");
	if (isset($blog_h) && $blog_h == "blog_h") {
		$array_data = array("posts_per_page" => $post_number,"post_type" => "post");
	}else if ((isset($tab_category) && $tab_category == true) || (isset($wp_page_template) && ($wp_page_template == "template-question.php" || $wp_page_template == "template-blog.php" || $wp_page_template == "template-home.php"))) {
		include locate_template("includes/templates.php");
		$loop_query = apply_filters("discy_before_loop_query",false,(isset($first_one) && $first_one != ""?$first_one:false));
		if ($loop_query == true) {
			$array_data    = apply_filters("discy_loop_array_data",false,(isset($first_one) && $first_one != ""?$first_one:false));
			$active_sticky = false;
			$show_sticky   = false;
			$post_not_true = false;
		}else if (((isset($tab_category) && $tab_category == true) || (isset($wp_page_template) && ($wp_page_template == "template-question.php" || $wp_page_template == "template-home.php"))) && isset($orderby_post) && ($orderby_post == "popular" || $orderby_post == "most_visited" || $orderby_post == "most_voted")) {
			$active_sticky = false;
			$array_data    = array_merge($custom_category,$orderby_array,(isset($cats_post) && is_array($cats_post)?$cats_post:array()),$specific_date_array,array("post_type" => $post_type,"ignore_sticky_posts" => 1,"paged" => $paged,"posts_per_page" => $post_number));
		}else if (isset($wp_page_template) && $wp_page_template == "template-home.php" && isset($first_one) && $first_one == "all") {
			$active_sticky = true;
			$custom_args   = array("post_type" => "question");
			$show_sticky   = true;
			$post_not_true = true;
			$array_data    = array_merge($orderby_array,array("post_type" => $post_type,"ignore_sticky_posts" => 1,"paged" => $paged,"posts_per_page" => $post_number));
		}else if (isset($wp_page_template) && $wp_page_template == "template-home.php" && isset($first_one) && $first_one != "" && is_string($first_one) && isset($get_tax->term_id) && $get_tax->term_id > 0) {
			$active_sticky = true;
			$custom_args   = array("post_type" => "question","tax_query" => array(array("taxonomy" => "question-category","field" => "id","terms" => $get_tax->term_id)));
			$show_sticky   = true;
			$post_not_true = true;
			$array_data    = array_merge($orderby_array,$cats_post,array("post_type" => $post_type,"ignore_sticky_posts" => 1,"paged" => $paged,"posts_per_page" => $post_number,"tax_query" => array(array("taxonomy" => "question-category","field" => "slug","terms" => $first_one))));
		}else if ((isset($wp_page_template) && $wp_page_template == "template-question.php" && isset($orderby_post) && $orderby_post == "sticky") || (((isset($tab_category) && $tab_category == true) || (isset($wp_page_template) && $wp_page_template == "template-home.php")) && isset($first_one) && $first_one != "" && ($first_one == $question_sticky_slug || $first_one == $question_sticky_slug_2))) {
			$active_sticky = true;
			$sticky_only   = true;
			$custom_args   = array_merge($custom_category,$specific_date_array,array("post_type" => "question"));
			$show_sticky   = true;
		}else if ((isset($wp_page_template) && $wp_page_template == "template-question.php" && isset($orderby_post) && $orderby_post == "polls") || (((isset($tab_category) && $tab_category == true) || (isset($wp_page_template) && $wp_page_template == "template-home.php")) && isset($first_one) && $first_one != "" && ($first_one == $question_polls_slug || $first_one == $question_polls_slug_2))) {
			$active_sticky = true;
			$custom_args   = array("post_type" => "question");
			$show_sticky   = true;
			$post_not_true = true;
			$poll_array    = array("ignore_sticky_posts" => 1,"meta_query" => array('relation' => 'AND',$question_meta_query,array("key" => "question_poll","value" => "on","compare" => "LIKE")));
			$array_data    = array_merge($custom_category,$specific_date_array,$poll_array,array("post_type" => "question","paged" => $paged,"posts_per_page" => $post_number));
		}else if ((isset($wp_page_template) && $wp_page_template == "template-question.php" && isset($orderby_post) && $orderby_post == "followed") || (((isset($tab_category) && $tab_category == true) || (isset($wp_page_template) && $wp_page_template == "template-home.php")) && isset($first_one) && $first_one != "" && ($first_one == $question_followed_slug || $first_one == $question_followed_slug_2))) {
			$active_sticky = false;
			$show_sticky   = false;
			$post_not_true = false;
			$no_followed   = true;
			$following_questions_user = get_user_meta($user_id,"following_questions",true);
			if (is_array($following_questions_user) && !empty($following_questions_user) && count($following_questions_user) > 0) {
				$array_data = array_merge($custom_category,$specific_date_array,array("post_type" => "question","paged" => $paged,"post__in" => $following_questions_user,"meta_query" => array($question_meta_query)));
			}
		}else if ((isset($wp_page_template) && $wp_page_template == "template-question.php" && isset($orderby_post) && $orderby_post == "favorites") || (((isset($tab_category) && $tab_category == true) || (isset($wp_page_template) && $wp_page_template == "template-home.php")) && isset($first_one) && $first_one != "" && ($first_one == $question_favorites_slug || $first_one == $question_favorites_slug_2))) {
			$active_sticky = false;
			$show_sticky   = false;
			$post_not_true = false;
			$no_favorites  = true;
			$ask_me         = discy_options("ask_me");
			if ($ask_me == "on") {
				$user_login    = get_userdata($user_id);
				$old_favorites = get_user_meta($user_id,$user_login->user_login."_favorites",true);
				if (isset($old_favorites) && !empty($old_favorites)) {
					update_user_meta($user_id,$user_id."_favorites",$old_favorites);
					delete_user_meta($user_id,$user_login->user_login."_favorites");
				}
			}
			$_favorites    = get_user_meta($user_id,$user_id."_favorites",true);
			if (is_array($_favorites) && !empty($_favorites) && count($_favorites) > 0) {
				$array_data = array_merge($custom_category,$specific_date_array,array("post_type" => "question","paged" => $paged,"post__in" => $_favorites,"meta_query" => array($question_meta_query)));
			}
		}else {
			$post_not_true = true;
			if (($first_one == $feed_slug && is_user_logged_in()) || ($first_one == $feed_slug_2 && is_user_logged_in()) || (isset($wp_page_template) && $wp_page_template == "template-question.php" && $orderby_post == "feed" && is_user_logged_in())) {
				include locate_template("theme-parts/feed-setting.php");
				if ($user_following_if == "yes" && $cat_following_if == "yes" && $tag_following_if == "yes" && ($all_following != "" || $user_following != "")) {
					$user_following = (isset($user_following) && $user_following != ""?$user_following.",".$user_id:$user_id);
					$feed_updated = ($updated_answers == "on"?",COALESCE((SELECT MAX(comment_date) FROM $wpdb->comments wpc WHERE wpc.comment_post_id = $wpdb->posts.id),$wpdb->posts.post_date) AS mcomment_date":"");
					$order_by_updated = ($updated_answers == "on"?"mcomment_date":"$wpdb->posts.post_date");
					$query = $wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS $wpdb->posts.*$feed_updated FROM $wpdb->posts 
						".($all_following != ""?"LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) ":"")."
						".($advanced_queries == "on" && !$is_super_admin?"LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id AND $wpdb->postmeta.meta_key = 'user_id' ) LEFT JOIN $wpdb->postmeta AS mt1 ON ($wpdb->posts.ID = mt1.post_id AND mt1.meta_key = 'private_question' ) LEFT JOIN $wpdb->postmeta AS mt2 ON ( $wpdb->posts.ID = mt2.post_id ) LEFT JOIN $wpdb->postmeta AS mt3 ON ( $wpdb->posts.ID = mt3.post_id ) LEFT JOIN $wpdb->postmeta AS mt4 ON ( $wpdb->posts.ID = mt4.post_id )":"LEFT JOIN $wpdb->postmeta AS mt1 ON ($wpdb->posts.ID = mt1.post_id AND mt1.meta_key = 'user_id' )")."
						WHERE %s=1 AND ( 
						".($all_following != ""?"$wpdb->term_relationships.term_taxonomy_id IN (".$all_following.")".($user_following != ""?" OR":"")." ":"")."
						".($user_following != ""?"$wpdb->posts.post_author IN (".$user_following.") ":"")."
						)
						".($advanced_queries == "on" && !$is_super_admin?"AND ( ( $wpdb->postmeta.post_id IS NULL AND ( mt1.post_id IS NULL OR ( mt2.meta_key = 'private_question' AND mt2.meta_value = '0' ) OR ( mt3.meta_key = 'private_question' AND ( mt4.meta_key = 'private_question_author' AND mt4.meta_value = '1' ) ) ) ) )":"AND ( mt1.post_id IS NULL )")."
						".($specific_date_feed != ""?"AND ( $wpdb->posts.post_date >= '".$specific_date_feed."' ) ":"")."AND $wpdb->posts.post_type = 'question' AND ($wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'private') GROUP BY $wpdb->posts.ID ORDER BY $order_by_updated DESC LIMIT $post_number OFFSET $offset",1);
					$query = $wpdb->get_results($query);
					$total_query = $wpdb->get_var('SELECT FOUND_ROWS()');
					if (is_array($query) && !empty($query)) :?>
						<div class='post-articles question-articles'>
							<?php foreach ($query as $post) {
								setup_postdata($post->ID);
								$k_ad_p++;
								include locate_template("theme-parts/content".(isset($its_question) && "question" == $its_question?"-question":"").".php");
							}?>
						</div>
					<?php else :
						include locate_template("theme-parts/content-none.php");
					endif;
					if (has_wpqa()) {
						wpqa_pagination_load((isset($post_pagination)?$post_pagination:"pagination"),(isset($total_query)?ceil($total_query/$post_number):""),(isset($it_answer_pagination)?$it_answer_pagination:false),(isset($its_question)?$its_question:false),(isset($wpqa_query)?$wpqa_query:null));
					}
				}else {
					include locate_template("theme-parts/feed.php");
				}
				$custom_sql = true;
			}else if (($first_one == $feed_slug && !is_user_logged_in()) || $first_one == $recent_questions_slug || ($first_one == $feed_slug_2 && !is_user_logged_in()) || (isset($wp_page_template) && $wp_page_template == "template-question.php" && $orderby_post == "feed" && !is_user_logged_in()) || $first_one == $recent_questions_slug_2 || (!is_user_logged_in() && ($first_one == $questions_for_you_slug || $first_one == $questions_for_you_slug_2))) {
				if (isset($wp_page_template) && $wp_page_template == "template-question.php" && $orderby_post == "feed") {
					$show_login = discy_post_meta("login_feed",$post_id_main);
				}else {
					$show_login = discy_post_meta("login_home_feed",$post_id_main);
				}
				if ($show_login != "on" && (($first_one == $feed_slug && !is_user_logged_in()) || ($first_one == $feed_slug_2 && !is_user_logged_in()) || (isset($wp_page_template) && $wp_page_template == "template-question.php" && $orderby_post == "feed" && !is_user_logged_in()))) {
					$no_feed_questions = true;
					echo '<div class="wpqa-default-template"><div class="alert-message warning"><i class="icon-flag"></i><p>'.esc_html__("You must login to see your feed.","discy").'</p></div>'.do_shortcode("[wpqa_login]").'</div>';
				}else {
					$active_sticky = true;
					$custom_args   = array_merge((is_array($custom_category)?$custom_category:array()),(isset($cats_post) && is_array($cats_post)?$cats_post:array()),$specific_date_array,array("post_type" => "question"));
					$show_sticky   = true;
					if ($updated_answers == "on") {
						$show_loop_updated = true;
						$custom_sql = true;
					}
				}
			}else if (is_user_logged_in() && ($first_one == $questions_for_you_slug || $first_one == $questions_for_you_slug_2)) {
				$category_list = get_user_meta($user_id,"wpqa_for_you_cats",true);
				$tag_list = get_user_meta($user_id,"wpqa_for_you_tags",true);
				$active_sticky = true;
				$cats_post     = array('tax_query' => array('relation' => 'OR',array('taxonomy' => "question-category",'field' => 'id','terms' => $category_list,'operator' => 'IN'),array('taxonomy' => "question_tags",'field' => 'id','terms' => $tag_list,'operator' => 'IN')));
				$custom_args   = array_merge($cats_post,$specific_date_array,array("post_type" => "question"));
				$show_sticky   = true;
				if ($updated_answers == "on") {
					$category_list_updated = (is_array($category_list) && !empty($category_list)?implode(",",$category_list):"");
					$tag_list_updated = (is_array($tag_list) && !empty($tag_list)?implode(",",$tag_list):"");
					$all_tax_updated = ($category_list_updated != ""?$category_list_updated:"").($category_list_updated != "" && $tag_list_updated != ""?",":"").($tag_list_updated != ""?$tag_list_updated:"");
					$show_loop_updated = true;
					$custom_sql = true;
				}
			}else if (isset($wp_page_template) && $wp_page_template == "template-question.php" && $orderby_post == "recent") {
				$show_sticky = true;
				if ($updated_answers == "on") {
					$show_loop_updated = true;
					$custom_sql = true;
				}
			}
			$sticky_posts = ($post_type == "post"?array():array("ignore_sticky_posts" => 1));
			$array_data = apply_filters("discy_last_query_loop_page",array_merge((is_array($custom_category)?$custom_category:array()),$orderby_array,(isset($cats_post) && is_array($cats_post)?$cats_post:array()),$specific_date_array,$sticky_posts,array("post_type" => $post_type,"paged" => $paged,"posts_per_page" => $post_number)),$first_one);
		}
		if ((((isset($tab_category) && $tab_category == true) || (isset($wp_page_template) && $wp_page_template == "template-question.php") || (isset($wp_page_template) && $wp_page_template == "template-home.php"))) && ($orderby_post == "no_answer" || ($question_bump == "on" && $active_points == "on" && $orderby_post == "question_bump"))) {
			$array_data = array_merge($array_data,array("comment_count" => "0"));
		}
		$pagination_show   = "yes";
		$show_custom_error = true;
	}else if (is_category()) {
		$array_data = array("posts_per_page" => $post_number,"cat" => $category_id,"post_type" => "post","paged" => $paged);
	}else if (is_post_type_archive("question") && is_archive("question")) {
		$poll_array = array("meta_query" => array($question_meta_query));
		if (isset($_GET["type"]) && $_GET["type"] == "poll") {
			$poll_array = array("ignore_sticky_posts" => 1,"meta_query" => array('relation' => 'AND',$question_meta_query,array("key" => "question_poll","value" => "on","compare" => "LIKE")));
		}
		$poll_array = apply_filters("discy_poll_array",$poll_array,$question_meta_query);
		$post_not_true = true;
		$array_data = array_merge($poll_array,array("post_type" => "question","paged" => $paged));
	}else if (is_tax("question-category") || $tax_filter == true) {
		$question_numbers = array();
		if (isset($post_number) && $post_number > 0) {
			$question_numbers = array("posts_per_page" => $post_number);
		}
		$post_not_true = true;
		$array_data = apply_filters("discy_args_question_category",array_merge($question_numbers,array("ignore_sticky_posts" => 1,"post_type" => "question","paged" => $paged,"tax_query" => array(array("taxonomy" => $tax_question,"field" => "id","terms" => $category_id,'operator' => 'IN')),"meta_query" => array($question_meta_query))),$tax_question,$paged,$question_numbers,$category_id,$question_meta_query);
	}else if (is_tax("question_tags")) {
		$post_not_true = true;
		$array_data = array_merge(array("ignore_sticky_posts" => 1,"post_type" => "question","paged" => $paged,"tax_query" => array(array("taxonomy" => "question_tags","field" => "id","terms" => $category_id,"operator" => "IN")),"meta_query" => array($question_meta_query)));
	}else if ($last_one == "questions" || $last_one == "posts") {
		$array_data = array("author" => $get_user_var,"post_type" => ($last_one == "questions"?"question":"post"),"paged" => $paged,"ignore_sticky_posts" => 1,"meta_query" => array($question_meta_query));
	}else if ($last_one == "polls") {
		$array_data = array("author" => $get_user_var,"post_type" => "question","paged" => $paged,"meta_query" => array('relation' => 'AND',$question_meta_query,array("key" => "question_poll","value" => "on","compare" => "=")));
	}else if ($last_one == "favorites") {
		$ask_me = discy_options("ask_me");
		if ($ask_me == "on") {
			$user_login = get_userdata($get_user_var);
			$old_favorites = get_user_meta($get_user_var,$user_login->user_login."_favorites",true);
			if (isset($old_favorites) && !empty($old_favorites)) {
				update_user_meta($get_user_var,$get_user_var."_favorites",$old_favorites);
				delete_user_meta($get_user_var,$user_login->user_login."_favorites");
			}
		}
		$_favorites = get_user_meta($get_user_var,$get_user_var."_favorites",true);
		if (is_array($_favorites) && !empty($_favorites) && count($_favorites) > 0) {
			$array_data = array("post_type" => "question","paged" => $paged,"post__in" => $_favorites,"meta_query" => array($question_meta_query));
		}
	}else if ($ask_question_to_users == "on" && ($last_one == "asked" || $last_one == "asked-questions")) {
		if ($last_one == "asked") {
			$meta_asked = array("key" => "user_is_comment","value" => true,"compare" => "=");
		}else {
			$meta_asked = array("key" => "user_is_comment","compare" => "NOT EXISTS");
		}
		$array_data = array("post_type" => "question","paged" => $paged,"meta_query" => array(array_merge(array($meta_asked),array(array("type" => "numeric","key" => "user_id","value" => (int)$get_user_var,"compare" => "=")))));
	}else if ($pay_ask == "on" && ($last_one == "paid-questions")) {
		$array_data = array("author" => $get_user_var,"post_type" => "question","paged" => $paged,"meta_query" => array('relation' => 'AND',$question_meta_query,array('type' => 'numeric',"key" => "_paid_question","value" => 'paid',"compare" => "=")));
	}else if ($last_one == "followed") {
		$following_questions_user = get_user_meta($get_user_var,"following_questions",true);
		if (is_array($following_questions_user) && !empty($following_questions_user) && count($following_questions_user) > 0) {
			$array_data = array("post_type" => "question","paged" => $paged,"post__in" => $following_questions_user,"meta_query" => array($question_meta_query));
		}
	}else if ($last_one == "followers-questions" || $last_one == "followers-posts") {
		$following_me = get_user_meta($get_user_var,"following_me",true);
		if (is_array($following_me) && count($following_me) > 0) {
			$array_data = array("post_type" => ($last_one == "followers-questions"?"question":"post"),"paged" => $paged,"author__in" => $following_me,"ignore_sticky_posts" => 1,"meta_query" => array($question_meta_query));
		}
	}else if (has_wpqa() && (wpqa_is_pending_questions() || wpqa_is_pending_posts()) && ($is_super_admin || $active_moderators == "on") && wpqa_is_user_owner() && ($is_super_admin || (isset($moderator_categories) && is_array($moderator_categories) && !empty($moderator_categories)))) {
		$post_type_pending = "question";
		$taxonomy_pending = "question-category";
		if (wpqa_is_pending_posts()) {
			$post_type_pending = "post";
			$taxonomy_pending = "category";
		}
		$array_data = array("post_status" => "draft","post_type" => $post_type_pending,"meta_query" => array($question_meta_query));
		if (is_array($moderator_categories) && !empty($moderator_categories) && in_array("0",$moderator_categories)) {
			$found_key = array_search("0",$moderator_categories);
			$moderator_categories[$found_key+1] = "q-0";
		}
		$last_moderator_categories = wpqa_remove_item_by_value($moderator_categories,"q-0");
		$last_moderator_categories = wpqa_remove_item_by_value($last_moderator_categories,"p-0");
		if (!$is_super_admin) {
			if ($post_type_pending == "question" && !in_array("q-0",$moderator_categories)) {
				$categories_posts = array('tax_query' => array(array('taxonomy' => $taxonomy_pending,'field' => 'id','terms' => $last_moderator_categories,'operator' => 'IN')));
			}else if ($post_type_pending == "post" && !in_array("p-0",$moderator_categories)) {
				$categories_posts = array('tax_query' => array(array('taxonomy' => $taxonomy_pending,'field' => 'id','terms' => $last_moderator_categories,'operator' => 'IN')));
			}
		}
		$categories_posts = (isset($categories_posts) && is_array($categories_posts) && !empty($categories_posts)?$categories_posts:array());
		$array_data = array_merge($array_data,$categories_posts);
	}else {
		if (!isset($no_feed_questions)) {
			$array_data = apply_filters("discy_last_query_for_loop_page",array(),$paged,$post_number);
		}
	}

	$sticky_questions = apply_filters("discy_sticky_questions",get_option('sticky_questions'));
	$post__not_in = array();
	if (!isset($no_feed_questions) && isset($post_not_true) && $post_not_true == true && isset($sticky_questions) && is_array($sticky_questions) && !empty($sticky_questions)) {
		$post__not_in = array("post__not_in" => $sticky_questions);
		$array_data = array_merge($post__not_in,(is_array($array_data)?$array_data:array()));
	}

	if (!isset($no_feed_questions) && isset($array_data) && is_array($array_data) && !empty($array_data)) {
		$wpqa_query = new WP_Query($array_data);
	}?>
	<section<?php echo ((isset($post_style) && $post_style == "style_3") || (isset($question_columns) && $question_columns == "style_2")?" class='section-post-with-columns'":"")?><?php echo ($last_one == "questions" || $last_one == "asked" || $last_one == "asked-questions" || $last_one == "paid-questions" || $last_one == "polls" || $last_one == "followed" || $last_one == "favorites" || $last_one == "followers-questions" || $last_one == "posts" || $last_one == "followers-posts" || (has_wpqa() && (wpqa_is_pending_questions() || wpqa_is_pending_posts()))?" id='section-".wpqa_user_title()."'":"")?>>
		<?php if ((isset($no_favorites) || $last_one == "favorites") && empty($_favorites)) {
			echo "<div class='alert-message warning'><i class='icon-flag'></i><p>".esc_html__("There are no questions at favorite yet.","discy")."</p></div>";
		}else if ((isset($no_followed) || $last_one == "followed") && empty($following_questions_user)) {
			echo "<div class='alert-message warning'><i class='icon-flag'></i><p>".esc_html__("There are no questions you followed yet.","discy")."</p></div>";
		}else {
			if (isset($its_question) && "question" == $its_question) {
				$page_tamplate = true;
			}
			$page_tamplate   = (isset($page_tamplate)?$page_tamplate:'');
			$post_pagination = (isset($post_pagination)?$post_pagination:'');
			if ($page_tamplate != true) {
				$post_pagination = discy_options("post_pagination");
			}
			if ($updated_answers == "on" && isset($show_loop_updated)) {
				include locate_template("theme-parts/loop-updated.php");
			}
			if (!isset($custom_sql)) :
				if (isset($custom_args) || (isset($wpqa_query) && $wpqa_query->have_posts()) || (have_posts() && empty($array_data))) :
					if (isset($blog_h) || empty($wp_page_template) || (isset($wp_page_template) && $wp_page_template != "template-users.php" && $wp_page_template != "template-contact.php" && $wp_page_template != "template-faqs.php" && $wp_page_template != "template-categories.php" && $wp_page_template != "template-tags.php")) :
						$max_num_pages = (isset($wpqa_query->max_num_pages)?$wpqa_query->max_num_pages:"");
						$more_link = get_next_posts_link("",$max_num_pages);?>
						<h2 class="screen-reader-text"><?php echo esc_html__("Discy Latest", "discy")." ";printf("%s",(isset($its_question) && "question" == $its_question?esc_html__("Questions","discy"):esc_html__("Articles","discy")))?></h2>
						<div class="post-articles<?php echo (isset($its_question) && "question" == $its_question?" question-articles".(isset($question_columns) && $question_columns == "style_2"?" row".(isset($masonry_style) && $masonry_style == "on"?" isotope":""):""):"").($post_pagination == "none"?" no-pagination":"").(isset($blog_h) && $blog_h == "blog_h"?" post-articles-blog-h":"").(empty($more_link)?" articles-no-pagination":"").(isset($post_style) && $post_style == "style_3"?" row":"")?>">
							<?php if (isset($show_sticky) && $show_sticky == true) {
								include locate_template("theme-parts/sticky-question.php");
								$active_sticky = false;
							}
					endif;
					if (!isset($sticky_only)) :
						if (isset($wpqa_query)) {
							if ($wpqa_query->have_posts()) :
								$wp_reset_postdata = true;
								while ($wpqa_query->have_posts()) : $wpqa_query->the_post();
									$k_ad_p++;
									if (isset($loop_query) && $loop_query == true) {
										do_action("discy_loop_include_content",(isset($first_one) && $first_one != ""?$first_one:false),$post_id_main);
									}else {
										include locate_template("theme-parts/content".(isset($its_question) && "question" == $its_question?"-question":"").".php");
									}
								endwhile;
							else :
								if (!isset($is_questions_sticky) || (isset($is_questions_sticky) && $is_questions_sticky != true)) {
									include locate_template("theme-parts/content-none.php");
								}
							endif;
						}else {
							if ( have_posts() ) :
								while (have_posts()) : the_post();
									$k_ad_p++;
									include locate_template("theme-parts/content".(isset($its_question) && "question" == $its_question?"-question":"").".php");
								endwhile;
							else :
								if (!isset($is_questions_sticky) || (isset($is_questions_sticky) && $is_questions_sticky != true)) {
									include locate_template("theme-parts/content-none.php");
								}
							endif;
						}
					endif;
					if (empty($wp_page_template) || (isset($wp_page_template) && $wp_page_template != "template-users.php" && $wp_page_template != "template-contact.php" && $wp_page_template != "template-faqs.php" && $wp_page_template != "template-categories.php" && $wp_page_template != "template-tags.php")) :?>
						</div><!-- End post-articles -->
						<?php if (has_wpqa()) {
							wpqa_pagination_load((isset($post_pagination)?$post_pagination:"pagination"),(isset($max_num_pages)?$max_num_pages:""),(isset($it_answer_pagination)?$it_answer_pagination:false),(isset($its_question)?$its_question:false),(isset($wpqa_query)?$wpqa_query:null));
						}
					endif;
				else :
					if (!isset($no_feed_questions)) {
						if ((isset($blog_h) && $blog_h == "blog_h") || (isset($show_custom_error) && $show_custom_error == true && (!isset($is_questions_sticky) || (isset($is_questions_sticky) && $is_questions_sticky != true)))) {
							echo "<div class='alert-message warning'><i class='icon-flag'></i><p>".(isset($its_question) && $its_question == "question"?esc_html__("There are no questions yet.","discy"):esc_html__("There are no posts yet.","discy"))."</p></div>";
						}else {
							if (!is_author() && (!isset($is_questions_sticky) || (isset($is_questions_sticky) && $is_questions_sticky != true))) {
								include locate_template("theme-parts/content-none.php");
							}
						}
					}
				endif;
			endif;
		}
		
		$GLOBALS['wp_query'] = $GLOBALS['wp_the_query'];
		
		if (isset($wp_reset_postdata)) {
			wp_reset_postdata();
		}else {
			wp_reset_query();
		}?>
	</section><!-- End section -->
<?php }?>