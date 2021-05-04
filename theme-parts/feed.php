<?php if (is_array($home_feed) && !empty($home_feed)) {
	global $wpdb;
	echo "<div class='feed-sections'>";
		foreach ($home_feed as $key => $value) {
			if ($key == "users" && isset($home_feed["users"]["value"]) && $home_feed["users"]["value"] == "users") {
				echo "<div class='feed-section feed-section-".$key."'>";
					echo "<div class='feed-title'><h4><i class='icon-users'></i>".esc_html__("Discover Popular Users","discy")."</h4></div>";
					if (($number_of_users-$user_count_already) > 0 && ($number_of_users > 0 || $user_following_if != "yes")) {
						echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__("Choose %s or more users to continue.","discy"),($number_of_users-$user_count_already)).'</p></div>';
					}
					if ($user_sort == "points" || $user_sort == "followers") {
						$user_sort = ($user_sort == "followers"?"count_following_you":"points");
						$meta_query = array('meta_query' => array("relation" => "or",array("key" => $user_sort,"compare" => "NOT EXISTS"),array("key" => $user_sort,"value" => 0,"compare" => ">=")));
						$args = array(
							'orderby'        => 'meta_value_num',
							'order'          => $order_feed,
							'number'         => $users_per,
							'fields'         => 'ID',
							'role__not_in'   => array("wpqa_under_review","activation","ban_group"),
							'exclude'        => array($get_current_user_id),
						);
						$get_results = true;
					}else if ($user_sort == "the_best_answer") {
						$query = $wpdb->prepare("SELECT DISTINCT SQL_CALC_FOUND_ROWS $wpdb->users.ID FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ($wpdb->users.ID = $wpdb->usermeta.user_id) LEFT OUTER JOIN (SELECT user_id, COUNT(*) as total FROM $wpdb->comments INNER JOIN $wpdb->commentmeta ON ($wpdb->comments.comment_id = $wpdb->commentmeta.comment_id) WHERE $wpdb->comments.comment_approved = 1 AND $wpdb->commentmeta.meta_key = 'best_answer_comment' GROUP BY user_id) c ON ($wpdb->users.ID = c.user_id) WHERE %s=1 AND $wpdb->users.ID != ".$get_current_user_id." ORDER BY total $order_feed LIMIT 0,$users_per",1);
					}else if ($user_sort == "answers") {
						$query = $wpdb->prepare("SELECT DISTINCT SQL_CALC_FOUND_ROWS $wpdb->users.ID FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ( $wpdb->users.ID = $wpdb->usermeta.user_id ) LEFT OUTER JOIN ( SELECT user_id, COUNT(*) as total FROM $wpdb->comments INNER JOIN $wpdb->posts ON ( $wpdb->comments.comment_post_ID = $wpdb->posts.ID ) WHERE $wpdb->posts.post_type = 'question' AND ( $wpdb->posts.post_status = 'publish' OR $wpdb->posts.post_status = 'private' ) GROUP BY user_id ) c ON ($wpdb->users.ID = c.user_id) WHERE %s=1 AND $wpdb->users.ID != ".$get_current_user_id." ORDER BY total $order_feed LIMIT 0,$users_per",1);
					}else {
						$query = $wpdb->prepare("SELECT DISTINCT SQL_CALC_FOUND_ROWS $wpdb->users.ID FROM $wpdb->users INNER JOIN $wpdb->usermeta ON ( $wpdb->users.ID = $wpdb->usermeta.user_id ) LEFT OUTER JOIN ( SELECT post_author, COUNT(*) as post_count FROM $wpdb->posts WHERE ( ( post_type = 'question' AND ( post_status = 'publish' OR post_status = 'private' ) ) ) GROUP BY post_author ) p ON ($wpdb->users.ID = p.post_author) WHERE %s=1 AND post_author != ".$get_current_user_id." ORDER BY post_count $order_feed LIMIT 0,$users_per",1);
					}
					if (isset($get_results)) {
						$query = new WP_User_Query(array_merge($meta_query,$args));
						$total_query = (int)$query->get_total();
					}else {
						$query = $wpdb->get_results($query);
						$total_query = $wpdb->get_var('SELECT FOUND_ROWS()');
					}
					$total_pages = ceil($total_query/$users_per);
					$user_col = "col6";
					$slider_number = 2;
					if (($user_style == "columns" && ($discy_sidebar == "sidebar" || $discy_sidebar == "centered" || $discy_sidebar == "menu_left")) || ($user_style == "small_grid" && $discy_sidebar != "full")) {
						$user_col = "col4";
						$slider_number = 3;
					}else if ($discy_sidebar == "full") {
						$user_col = "col3";
						$slider_number = 4;
					}
					echo "<div class='user-section user-section-".$user_style.($user_style == "small_grid" || $user_style == "grid" || $user_style == "small" || $user_style == "columns"?" row":"").($user_style != "normal"?" user-not-normal":"")."'>";
						$query = (isset($get_results)?$query->get_results():$query);
						if (isset($query) && !empty($query)) {
							$user_k = 0;
							if ($users_slider == "on") {
								echo "<div class='slider-feed-wrap slider-user-wrap'><ul class='slider-owl'>";
							}
							foreach ($query as $user) {
								$user_k++;
								if ($users_slider == "on" && $user_k == 1) {
									echo "<li class='slider-item'>";
								}
									$user = (isset($user->ID)?$user->ID:$user);
									$owner_user = false;
									if ($get_current_user_id == $user) {
										$owner_user = true;
									}
									echo ($user_style == "small_grid" || $user_style == "grid" || $user_style == "small" || $user_style == "columns"?"<div class='col ".$user_col."'>":"");
										do_action("wpqa_author",array("user_id" => $user,"author_page" => $user_style,"owner" => $owner_user,"type_post" => $user_sort));
									echo ($user_style == "small_grid" || $user_style == "grid" || $user_style == "small" || $user_style == "columns"?"</div>":"");
								if ($users_slider == "on" && $user_k == $slider_number) {
									echo "</li>";
									$user_k = 0;
								}
							}
							if ($users_slider == "on") {
								echo "</ul></div>";
							}
						}
					echo "</div>";
					if ($users_more == "on") {
						if ($custom_link_users == "") {
							$pages = get_pages(array('meta_key' => '_wp_page_template','meta_value' => 'template-users.php'));
						}
						$count_users = count_users();
						$count_all = 0;
						foreach ($count_users["avail_roles"] as $role => $count) {
							if ($role != "wpqa_under_review" && $role != "activation" && $role != "ban_group") {
								$count_all += $count;
							}
						}
						$count_all = (int)($count_all-1);
						
						if ($user_sort == "points" || $user_sort == "followers") {
							$total_query = $total_query;
						}else {
							$count_not_users = count_users();
							$total_query = 0;
							foreach ($count_not_users["avail_roles"] as $role => $count_not) {
								if ($role == "wpqa_under_review" || $role == "activation" || $role == "ban_group") {
									$total_query += $count_not;
								}
							}
						}
						if (($custom_link_users != "" || (isset($pages) && isset($pages[0]) && isset($pages[0]->ID))) && $count_all > $users_per) {
							echo "<div class='clearfix'></div><div class='load-more feed-show-more'><a href='".($custom_link_users != ""?$custom_link_users:get_the_permalink($pages[0]->ID))."'>".esc_html__("More Users","discy")."</a></div>";
						}
					}
				echo "</div>";
			}else if ($key == "cats" && isset($home_feed["cats"]["value"]) && $home_feed["cats"]["value"] == "cats") {
				echo "<div class='feed-section feed-section-".$key."'>
					<div class='feed-title'><h4><i class='icon-folder'></i>".esc_html__("Discover Popular Categories","discy")."</h4></div>";
					if (($number_of_categories-$cat_count_already) > 0 && ($number_of_categories > 0 || $cat_following_if != "yes")) {
						echo "<div class='alert-message warning'><i class='icon-flag'></i><p>".sprintf(esc_html__("Choose %s or more categories to continue.","discy"),($number_of_categories-$cat_count_already))."</p></div>";
					}
					$cats_tax = 'question-category';
					$meta_query = ($tag_sort == "meta_value_num"?array('meta_query' => array("relation" => "or",array("key" => "cat_follow_count","compare" => "NOT EXISTS"),array("key" => "cat_follow_count","value" => 0,"compare" => ">="))):array());
					$exclude = apply_filters('wpqa_exclude_question_category',array());
					$terms = get_terms($cats_tax,array_merge($exclude,$meta_query,array(
						'orderby'    => $cat_sort,
						'order'      => $order_feed,
						'number'     => $cat_per,
						'hide_empty' => 0,
					)));
					if (!empty($terms) && !is_wp_error($terms)) {
						$term_list = '<div class="row cats-sections cat_'.$cat_style.'">';
							$tax_col = "col6";
							$slider_number = 2;
							if ($cats_tax == 'question-category' && ($cat_style == "with_icon_1" || $cat_style == "with_icon_2" || $cat_style == "with_icon_3" || $cat_style == "with_icon_4" || $cat_style == "with_cover_1" || $cat_style == "with_cover_2" || $cat_style == "with_cover_3")) {
								$tax_col = "col4";
								$slider_number = 3;
								if ($discy_sidebar == "full") {
									$tax_col = "col3";
									$slider_number = 4;
								}
							}else if ($discy_sidebar == "full" && $cats_tax == 'question-category' && ($cat_style == "with_cover_4" || $cat_style == "with_cover_5" || $cat_style == "with_cover_6")) {
								$tax_col = "col4";
								$slider_number = 3;
							}else {
								if ($discy_sidebar == "full") {
									$tax_col = "col4";
									$slider_number = 3;
								}
							}
							$cat_k = 0;
							if ($cats_slider == "on") {
								$term_list .= "<div class='slider-feed-wrap slider-cat-wrap'><ul class='slider-owl'>";
							}
							foreach ($terms as $term) {
								$cat_k++;
								if ($cats_slider == "on" && $cat_k == 1) {
									$term_list .= "<li class='slider-item'>";
								}
								$tax_id = $term->term_id;
								$category_icon = get_term_meta($tax_id,prefix_terms."category_icon",true);
								if ($follow_category == "on") {
									$cat_follow = get_term_meta($tax_id,"cat_follow",true);
								}
								include locate_template("theme-parts/show-categories.php");
								if ($cats_slider == "on" && $cat_k == $slider_number) {
									$term_list .= "</li>";
									$cat_k = 0;
								}
							}
							if ($cats_slider == "on") {
								$term_list .= "</ul></div>";
							}
						$term_list .= '</div>';
						echo ($term_list);
						if ($cats_more == "on") {
							if ($custom_link_cats == "") {
								$pages = get_pages(array('meta_key' => '_wp_page_template','meta_value' => 'template-categories.php'));
							}
							$exclude = apply_filters('wpqa_exclude_question_category',array());
							$terms_all = get_terms($cats_tax,array_merge($exclude,array('hide_empty' => 0)));
							if (($custom_link_cats != "" || (isset($pages) && isset($pages[0]) && isset($pages[0]->ID))) && count($terms_all) > count($terms)) {
								echo "<div class='clearfix'></div><div class='load-more feed-show-more'><a href='".($custom_link_cats != ""?$custom_link_cats:get_the_permalink($pages[0]->ID))."'>".esc_html__("More Categories","discy")."</a></div>";
							}
						}
					}
				echo "</div>";
			}else if ($key == "tags" && isset($home_feed["tags"]["value"]) && $home_feed["tags"]["value"] == "tags") {
				echo "<div class='feed-section feed-section-".$key."'>
					<div class='feed-title'><h4><i class='icon-tag'></i>".esc_html__("Discover Popular Tags","discy")."</h4></div>";
					if (($number_of_tags-$tag_count_already) > 0 && ($number_of_tags > 0 || $tag_following_if != "yes")) {
						echo '<div class="alert-message warning"><i class="icon-flag"></i><p>'.sprintf(esc_html__("Choose %s or more tags to continue.","discy"),($number_of_tags-$tag_count_already)).'</p></div>';
					}
					$tags_tax = 'question_tags';
					$meta_query = ($tag_sort == "meta_value_num"?array('meta_query' => array("relation" => "or",array("key" => "tag_follow_count","compare" => "NOT EXISTS"),array("key" => "tag_follow_count","value" => 0,"compare" => ">="))):array());
					$exclude = apply_filters('wpqa_exclude_question_category',array());
					$terms = get_terms($tags_tax,array_merge($exclude,$meta_query,array(
						'orderby'    => $tag_sort,
						'order'      => $order_feed,
						'number'     => $tag_per,
						'hide_empty' => 0,
					)));
					if (!empty($terms) && !is_wp_error($terms)) {
						$term_list = '<div class="row cats-sections">';
							$tax_col = "col6";
							$slider_number = 2;
							if ($discy_sidebar == "full") {
								$tax_col = "col4";
								$slider_number = 3;
							}
							$tag_k = 0;
							if ($tags_slider == "on") {
								$term_list .= "<div class='slider-feed-wrap slider-tag-wrap'><ul class='slider-owl'>";
							}
							foreach ($terms as $term) {
								$tag_k++;
								if ($tags_slider == "on" && $tag_k == 1) {
									$term_list .= "<li class='slider-item'>";
								}
								$tax_id = $term->term_id;
								if ($follow_category == "on") {
									$tag_follow = get_term_meta($tax_id,"tag_follow",true);
								}
								$term_list .= '<div class="col '.$tax_col.'">
									'.($follow_category == "on"?"<div class='cat-sections-follow'>":"").'
									<div class="cat-sections">
										<a href="'.esc_url(get_term_link($term)).'" title="'.esc_attr(sprintf(esc_html__('View all questions under %s','discy'),$term->name)).'"><i class="icon-tag"></i>'.$term->name.'</a>
									</div>';
									if ($follow_category == "on") {
										$tags_follwers = (int)(is_array($tag_follow)?count($tag_follow):0);
										$term_list .= '<div class="cat-section-follow">
											<div class="cat-follow-button"><i class="icon-users"></i><span class="follow-cat-count">'.discy_count_number($tags_follwers)."</span>"._n("Follower","Followers",$tags_follwers,"discy").'</div>
											'.(has_wpqa()?wpqa_follow_cat_button($tax_id,$user_id,'tag',true,'button-default-4','cat-section-follow','follow-cat-count'):"").'
											<div class="clearfix"></div>
										</div></div>';
									}
								$term_list .= '</div>';
								if ($tags_slider == "on" && $tag_k == $slider_number) {
									$term_list .= "</li>";
									$tag_k = 0;
								}
							}
							if ($tags_slider == "on") {
								$term_list .= "</ul></div>";
							}
						$term_list .= '</div>';
						echo ($term_list);
						if ($tags_more == "on") {
							if ($custom_link_tags == "") {
								$pages = get_pages(array('meta_key' => '_wp_page_template','meta_value' => 'template-tags.php'));
							}
							$exclude = apply_filters('wpqa_exclude_question_category',array());
							$terms_all = get_terms($tags_tax,array_merge($exclude,array('hide_empty' => 0)));
							if (($custom_link_tags != "" || (isset($pages) && isset($pages[0]) && isset($pages[0]->ID))) && count($terms_all) > count($terms)) {
								echo "<div class='clearfix'></div><div class='load-more feed-show-more'><a href='".($custom_link_tags != ""?$custom_link_tags:get_the_permalink($pages[0]->ID))."'>".esc_html__("More tags","discy")."</a></div>";
							}
						}
					}
				echo "</div>";
			}
		}
	echo "</div>
	<div class='load-more finish-follow".($user_following_if == "yes" && $cat_following_if == "yes" && $tag_following_if == "yes" && ($all_following != "" || $user_following != "")?"":" not-finish-follow")."'>
		<div></div>
		<a href='' data-post='".$post_id_main."'>".($user_following_if == "yes" && $cat_following_if == "yes" && $tag_following_if == "yes" && ($all_following != "" || $user_following != "")?esc_html__("Click here to continue.","discy"):esc_html__("Complete your following above to continue.","discy"))."</a>
	</div>";
}?>