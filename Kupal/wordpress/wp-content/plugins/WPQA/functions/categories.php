<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action("wpqa_category_cover","wpqa_category_cover");
function wpqa_category_cover () {
	if (is_tax("question-category")) {
		$user_id          = get_current_user_id();
		$tax_id           = (int)get_query_var('wpqa_term_id');
		$custom_cat_cover = get_term_meta($tax_id,prefix_terms."custom_cat_cover",true);
		if ($custom_cat_cover == "on") {
			$cat_cover = get_term_meta($tax_id,prefix_terms."cat_cover",true);
			$cat_share = get_term_meta($tax_id,prefix_terms."cat_share",true);
		}else {
			$cat_cover = wpqa_options("active_cover_category");
			$cat_share = wpqa_options("cat_share");
		}
		if ($cat_cover == "on") {
			$follow_category      = wpqa_options("follow_category");
			$wpqa_term_name       = esc_html(get_query_var('wpqa_term_name'));
			$category_description = category_description();
			$cover_category_fixed = wpqa_options("cover_category_fixed");
			$cat_follow           = get_term_meta($tax_id,"cat_follow",true);
			$cats_follwers        = (int)(is_array($cat_follow)?count($cat_follow):0);
			$category_icon        = get_term_meta($tax_id,prefix_terms."category_icon",true);
			$category_small_image = get_term_meta($tax_id,prefix_terms."category_small_image",true);
			$questions            = (int)wpqa_count_posts_by_category("question","question-category",$tax_id);
			$answers              = (int)wpqa_count_comments_by_category("question-category",$tax_id);
			$share_facebook       = (isset($cat_share["share_facebook"]["value"])?$cat_share["share_facebook"]["value"]:"");
			$share_twitter        = (isset($cat_share["share_twitter"]["value"])?$cat_share["share_twitter"]["value"]:"");
			$share_linkedin       = (isset($cat_share["share_linkedin"]["value"])?$cat_share["share_linkedin"]["value"]:"");
			$share_whatsapp       = (isset($cat_share["share_whatsapp"]["value"])?$cat_share["share_whatsapp"]["value"]:"");
			echo "<div class='wpqa-profile-cover wpqa-cat-cover".($cover_category_fixed == "fixed"?" wpqa-cover-fixed":"")."'>
			<div class='wpqa-cover-background".($cover_category_fixed == "fixed"?" the-main-container":"")."'>
				<div class='cover-opacity'></div>
				<div class='wpqa-cover-inner".($cover_category_fixed == "fixed"?"":" the-main-container")."'>
					<div class='wpqa-cover-content'>
						<div class='cat-cover-left'>";
							if ($category_icon != "" || (is_array($category_small_image) && !empty($category_small_image) && ((isset($category_small_image['url']) && $category_small_image['url'] != "") || ( isset($category_small_image['id']) && $category_small_image['id'] != "" && $category_small_image['id'] > 0)))) {
								echo "<span class='cover-cat-span'>";
									if ($category_icon != "") {
										echo "<i class='".$category_icon."'></i>";
									}else if (is_array($category_small_image) && !empty($category_small_image) && ((isset($category_small_image['url']) && $category_small_image['url'] != "") || ( isset($category_small_image['id']) && $category_small_image['id'] != "" && $category_small_image['id'] > 0))) {
										echo "<img alt='".$wpqa_term_name."' src='".wpqa_image_url_id($category_small_image)."'>";
									}
								echo "</span>";
							}
							$breadcrumbs = wpqa_options("breadcrumbs");
							$breadcrumbs_style = wpqa_options("breadcrumbs_style");
							echo "<div class='cover-cat-right'>
								<".($breadcrumbs == "on" && $breadcrumbs_style == "style_2"?"h2":"h1").">".$wpqa_term_name."</".($breadcrumbs == "on" && $breadcrumbs_style == "style_2"?"h2":"h1").">";
								if ($category_description != "") {
									echo "<div class='cover-cat-desc'>".$category_description."</div>";
								}
							echo "</div>";
							wpqa_share($cat_share,$share_facebook,$share_twitter,$share_linkedin,$share_whatsapp,"style_1",0,$tax_id);
						echo "</div>
						<div class='wpqa-cover-right'>".
							wpqa_follow_cat_button($tax_id,$user_id,'cat',true,'','wpqa-cat-cover','follow-cover-count');
							if ($follow_category == "on") {
								echo "<div class='wpqa-cover-buttons wpqa-cover-followers'><i class='icon-users'></i><span class='cover-count follow-cover-count'>".wpqa_count_number($cats_follwers)."</span>"._n("Follower","Followers",$cats_follwers,"wpqa")."</div>";
							}
							echo "<div class='wpqa-cover-buttons wpqa-cover-answers'><i class='icon-comment'></i><span class='cover-count'>".wpqa_count_number($answers)."</span>"._n("Answer","Answers",$answers,"wpqa")."</a></div>
							<div class='wpqa-cover-buttons wpqa-cover-questions'><i class='icon-book-open'></i><span class='cover-count'>".wpqa_count_number($questions)."</span>"._n("Question","Questions",$questions,"wpqa")."</a></div>
						</div>
					</div>
					<div class='clearfix'></div>
				</div>
			</div>
			</div><!-- End wpqa-profile-cover -->";
		}
	}
}
/* Update for you */
if (!function_exists('wpqa_update_for_you')) :
	function wpqa_update_for_you($user_id,$post_id) {
		$might_like = wpqa_options("might_like");
		if ($might_like == "on") {
			$wpqa_for_you_cats = get_user_meta($user_id,"wpqa_for_you_cats",true);
			$get_cats = wp_get_post_terms($post_id,'question-category',array('fields' => 'ids'));
			$user_cat_follow = get_user_meta($user_id,"user_cat_follow",true);
			if (empty($wpqa_for_you_cats)) {
				if (!is_array($user_cat_follow) || (is_array($user_cat_follow) && !array_intersect($user_cat_follow,$get_cats))) {
					$update = update_user_meta($user_id,"wpqa_for_you_cats",$get_cats);
				}
			}else if (is_array($wpqa_for_you_cats) && !array_intersect($get_cats,$wpqa_for_you_cats)) {
				if (!is_array($user_cat_follow) || (is_array($user_cat_follow) && !array_intersect($user_cat_follow,$get_cats))) {
					$update = update_user_meta($user_id,"wpqa_for_you_cats",array_merge($wpqa_for_you_cats,$get_cats));
				}
			}

			$wpqa_for_you_tags = get_user_meta($user_id,"wpqa_for_you_tags",true);
			$get_tags = wp_get_post_terms($post_id,'question_tags',array('fields' => 'ids'));
			$user_tag_follow = get_user_meta($user_id,"user_tag_follow",true);
			if (empty($wpqa_for_you_tags)) {
				if (!is_array($user_tag_follow) || (is_array($user_tag_follow) && !array_intersect($user_tag_follow,$get_tags))) {
					$update = update_user_meta($user_id,"wpqa_for_you_tags",$get_tags);
				}
			}else if (is_array($wpqa_for_you_tags) && !array_intersect($get_tags,$wpqa_for_you_tags)) {
				if (!is_array($user_tag_follow) || (is_array($user_tag_follow) && !array_intersect($user_tag_follow,$get_tags))) {
					$update = update_user_meta($user_id,"wpqa_for_you_tags",array_merge($wpqa_for_you_tags,$get_tags));
				}
			}
		}
	}
endif;
/* Remove for you */
if (!function_exists('wpqa_remove_for_you')) :
	function wpqa_remove_for_you($user_id,$post_id) {
		$might_like = wpqa_options("might_like");
		if ($might_like == "on") {
			$wpqa_for_you_cats = get_user_meta($user_id,"wpqa_for_you_cats",true);
			$get_cats = wp_get_post_terms($post_id,'question-category',array('fields' => 'ids'));
			foreach ($get_cats as $key => $value) {
				$wpqa_for_you_cats = wpqa_remove_item_by_value($wpqa_for_you_cats,$value);
			}
			$update = update_user_meta($user_id,"wpqa_for_you_cats",$wpqa_for_you_cats);
			
			$wpqa_for_you_tags = get_user_meta($user_id,"wpqa_for_you_tags",true);
			$get_tags = wp_get_post_terms($post_id,'question_tags',array('fields' => 'ids'));
			foreach ($get_tags as $key => $value) {
				$wpqa_for_you_tags = wpqa_remove_item_by_value($wpqa_for_you_tags,$value);
			}
			$update = update_user_meta($user_id,"wpqa_for_you_tags",$wpqa_for_you_tags);
		}
	}
endif;
/* Follow cat */
if (!function_exists('wpqa_follow_cat')) :
	function wpqa_follow_cat() {
		$tax_id = (int)$_POST['tax_id'];
		$tax_type = esc_html($_POST['tax_type']);
		$user_id = get_current_user_id();
		$term_count_key = ($tax_type == "tag"?"tag_follow_count":"cat_follow_count");
		$term_key = ($tax_type == "tag"?"tag_follow":"cat_follow");
		$term_user_key = ($tax_type == "tag"?"user_tag_follow":"user_cat_follow");

		$user_cat_follow = get_user_meta($user_id,$term_user_key,true);
		if (empty($user_cat_follow)) {
			update_user_meta($user_id,$term_user_key,array($tax_id));
			$update = true;
		}else if (is_array($user_cat_follow) && !in_array($tax_id,$user_cat_follow)) {
			update_user_meta($user_id,$term_user_key,array_merge($user_cat_follow,array($tax_id)));
			$update = true;
		}
		
		$cat_follow = get_term_meta($tax_id,$term_key,true);
		if (empty($cat_follow)) {
			update_term_meta($tax_id,$term_key,array($user_id));
			$update = true;
		}else if (is_array($cat_follow) && !in_array($user_id,$cat_follow)) {
			update_term_meta($tax_id,$term_key,array_merge($cat_follow,array($user_id)));
			$update = true;
		}

		if (isset($update)) {
			$count_cat_follow = get_term_meta($tax_id,$term_count_key,true);
			$count_cat_follow = ($count_cat_follow != "" || $count_cat_follow > 0?$count_cat_follow:0);
			$count_cat_follow++;
			$count_cat_follow = update_term_meta($tax_id,$term_count_key,$count_cat_follow);
		}

		if ($tax_type == "tag") {
			$wpqa_for_you_tags = get_user_meta($user_id,"wpqa_for_you_tags",true);
			if (is_array($wpqa_for_you_tags) && !empty($wpqa_for_you_tags)) {
				$remove_tag_follow = wpqa_remove_item_by_value($wpqa_for_you_tags,$tax_id);
				update_user_meta($user_id,"wpqa_for_you_tags",$remove_tag_follow);
			}
		}else {
			$wpqa_for_you_cats = get_user_meta($user_id,"wpqa_for_you_cats",true);
			if (is_array($wpqa_for_you_cats) && !empty($wpqa_for_you_cats)) {
				$remove_cat_follow = wpqa_remove_item_by_value($wpqa_for_you_cats,$tax_id);
				update_user_meta($user_id,"wpqa_for_you_cats",$remove_cat_follow);
			}
		}
		
		if (!isset($_POST["mobile"])) {
			$cat_follow = get_term_meta($tax_id,$term_key,true);
			echo (is_array($cat_follow) && is_array($cat_follow) && isset($cat_follow)?wpqa_count_number(count($cat_follow)):0);
			die();
		}
	}
endif;
add_action('wp_ajax_wpqa_follow_cat','wpqa_follow_cat');
add_action('wp_ajax_nopriv_wpqa_follow_cat','wpqa_follow_cat');
/* Unfollow cat */
if (!function_exists('wpqa_unfollow_cat')) :
	function wpqa_unfollow_cat() {
		$tax_id = (int)$_POST['tax_id'];
		$tax_type = esc_html($_POST['tax_type']);
		$user_id = get_current_user_id();
		$term_count_key = ($tax_type == "tag"?"tag_follow_count":"cat_follow_count");
		$term_key = ($tax_type == "tag"?"tag_follow":"cat_follow");
		$term_user_key = ($tax_type == "tag"?"user_tag_follow":"user_cat_follow");
		
		$user_cat_follow = get_user_meta($user_id,$term_user_key,true);
		if (isset($user_cat_follow) && !empty($user_cat_follow)) {
			$remove_user_cat_follow = wpqa_remove_item_by_value($user_cat_follow,$tax_id);
			update_user_meta($user_id,$term_user_key,$remove_user_cat_follow);
			$update = true;
		}
		
		$cat_follow = get_term_meta($tax_id,$term_key,true);
		if (isset($cat_follow) && !empty($cat_follow)) {
			$remove_cat_follow = wpqa_remove_item_by_value($cat_follow,$user_id);
			update_term_meta($tax_id,$term_key,$remove_cat_follow);
			$update = true;
		}

		if (isset($update)) {
			$count_cat_follow = get_term_meta($tax_id,$term_count_key,true);
			$count_cat_follow = ($count_cat_follow != "" || $count_cat_follow > 0?$count_cat_follow:0);
			$count_cat_follow--;
			$count_cat_follow = update_term_meta($tax_id,$term_count_key,$count_cat_follow);
		}
		
		if (!isset($_POST["mobile"])) {
			$cat_follow = get_term_meta($tax_id,$term_key,true);
			echo (is_array($cat_follow) && is_array($cat_follow) && isset($cat_follow)?wpqa_count_number(count($cat_follow)):0);
			die();
		}
	}
endif;
add_action('wp_ajax_wpqa_unfollow_cat','wpqa_unfollow_cat');
add_action('wp_ajax_nopriv_wpqa_unfollow_cat','wpqa_unfollow_cat');
/* Following cat */
if (!function_exists('wpqa_follow_cat_button')) :
	function wpqa_follow_cat_button($tax_id,$user_id,$type = 'cat',$follow_text = '',$button_class = 'button-default',$closest_class = '',$count_class = '') {
		$out = '';
		$follow_category = wpqa_options("follow_category");
		if (is_user_logged_in() && $follow_category == "on") {
			$user_follow_key = ($type == 'cat'?'user_cat_follow':'user_tag_follow');
			$user_cat_follow = get_user_meta($user_id,$user_follow_key,true);
			$user_cat_follow = (is_array($user_cat_follow) && !empty($user_cat_follow)?$user_cat_follow:array());
			$cat_follow_true = (!empty($user_cat_follow) && in_array($tax_id,$user_cat_follow)?true:false);
			$out .= '<div class="cat_follow'.($cat_follow_true == true?" cat_follow_done":"").'">
				<div class="small_loader loader_2"></div>
				<a href="#"'.($closest_class != ""?" data-closest='".$closest_class."'":"").($count_class != ""?" data-count='".$count_class."'":"").' class="'.($button_class != ""?$button_class:"button-default").' follow-cat-button '.($cat_follow_true == true?"unfollow_cat":"follow_cat").($follow_text == true?"":" tooltip-n").'" data-id="'.$tax_id.'" data-type="'.$type.'"'.($follow_text == true?' original-title="'.($cat_follow_true == true?esc_html__("Unfollow","wpqa"):esc_html__("Follow","wpqa")).'"':'').' title="'.($cat_follow_true == true?esc_html__("Unfollow","wpqa"):esc_html__("Follow","wpqa")).'">
					<span class="'.($follow_text == true?'follow-cat-value':'follow-cat-icon').'">'.($follow_text == true?($cat_follow_true == true?esc_html__("Unfollow","wpqa"):esc_html__("Follow","wpqa")):($cat_follow_true == true?'<i class="icon-minus"></i>':'<i class="icon-plus"></i>')).'</span>
				</a>
			</div>';
		}
		return $out;
	}
endif;
/* Count posts by category */
if (!function_exists('wpqa_count_posts_by_category')) :
	function wpqa_count_posts_by_category( $post_type = "post", $tax_type = "category", $category = 0 ) {
		$args = array(
			'tax_query' => array(array('taxonomy' => $tax_type,'field' => 'term_id','terms' => $category)),
			'post_type' => $post_type
		);
		$the_query = new WP_Query($args);
		return $the_query->found_posts;
		wp_reset_postdata();
	}
endif;
/* Count comments by category */
if (!function_exists('wpqa_count_comments_by_category')) :
	function wpqa_count_comments_by_category( $tax_type = "category", $category = 0 ) {
		global $wpdb;
		$exclude = apply_filters('wpqa_exclude_question_category',array());
		$categories = get_terms(array(
			'hide_empty'       => 0,
			'hierarchical'     => 0,
			'term_taxonomy_id' => $category,
			'taxonomy'         => $tax_type
		));
		$category_ids = implode(',',array_map(function($cat) {
			return $cat->term_id;
		},$categories));

		$query = "SELECT SUM(p.comment_count) AS count, t.name FROM $wpdb->posts p JOIN $wpdb->term_relationships tr ON tr.object_id = p.ID JOIN $wpdb->term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id JOIN $wpdb->terms t ON t.term_id = tt.term_id WHERE t.term_id in ($category_ids) AND p.post_status = 'publish' GROUP BY t.term_id";

		$categories = $wpdb->get_results($query);
		return wpqa_count_number(isset($categories[0]->count)?$categories[0]->count:0);
	}
endif;
/* Get cat cover link */
if (!function_exists('wpqa_get_cat_cover_link')) :
	function wpqa_get_cat_cover_link ($args = array()) {
		$defaults = array(
			'tax_id'   => '',
			'size'     => '',
			'cat_name' => '',
		);
		
		$args = wp_parse_args($args,$defaults);
		
		$tax_id   = $args['tax_id'];
		$size     = $args['size'];
		$cat_name = $args['cat_name'];

		$category_image = apply_filters("wpqa_category_image",prefix_terms."category_image");
		$category_image = get_term_meta($tax_id,$category_image,true);
		if ((($category_image && !is_array($category_image)) || (is_array($category_image) && isset($category_image["id"]) && $category_image["id"] != 0)) && $tax_id > 0) {
			$cover = wpqa_get_cover_url($category_image,$size,$cat_name);
		}else {
			$default_cover_cat_active = wpqa_options("default_cover_cat_active");
			if ($default_cover_cat_active == "on") {
				$default_cover = wpqa_image_url_id(wpqa_options("default_cover_cat"));
				
				if ($default_cover_cat_active == "on" && $default_cover != "") {
					$cover = wpqa_get_aq_resize_url($default_cover,$size,$size);
				}
			}
		}
		if (isset($cover)) {
			return $cover;
		}
	}
endif;?>