<?php include locate_template("theme-parts/sticky-question.php");
$rows_per_page = get_option('posts_per_page');
$paged         = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
$offset        = ($paged -1) * $rows_per_page;
$current       = max(1,$paged);
if (is_tax("question-category") && isset($category_id) && $category_id > 0) {
	$post_display = "single_category";
	$all_tax_updated = $category_id;
}
$sticky_questions = apply_filters("discy_sticky_questions",get_option('sticky_questions'));
$sticky_questions = (is_array($sticky_questions) && !empty($sticky_questions)?"AND $wpdb->posts.ID NOT IN (".implode(",",$sticky_questions).")":"");
$specific_date = (isset($specific_date) && $specific_date != "" && $specific_date != "all"?$specific_date.' ago':"");
$date = ($specific_date != ""?"AND ($wpdb->posts.post_date > '".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s"). $specific_date))."')":"");
$post_display = (isset($display_r) && $display_r?$display_r:$post_display);
$custom_catagories_updated = (isset($custom_catagories_updated) && is_array($custom_catagories_updated) && !empty($custom_catagories_updated)?$custom_catagories_updated:(isset($custom_catagories_updated) && !is_array($custom_catagories_updated) && $custom_catagories_updated != ""?array($custom_catagories_updated):""));
$include_posts = (isset($post_display) && $post_display == "custom_posts"?"AND $wpdb->posts.ID IN (".$custom_posts_updated.")":"");
$custom_catagories_query = (isset($post_display) && ($post_display == "single_category" || $post_display == "categories") && is_array($custom_catagories_updated) && !empty($custom_catagories_updated)?" AND $wpdb->term_relationships.term_taxonomy_id IN (".implode(",",$custom_catagories_updated).")":(isset($post_display) && $post_display == "exclude_categories" && is_array($custom_catagories_updated) && !empty($custom_catagories_updated)?" NOT IN (".implode(",",$custom_catagories_updated).")":""));
$custom_catagories_updated = (isset($custom_catagories_updated) && $custom_catagories_updated != ""?"AND $wpdb->term_relationships.term_taxonomy_id".$custom_catagories_query:"");
$custom_catagories_updated = (isset($all_tax_updated) && $all_tax_updated != ""?"AND $wpdb->term_relationships.term_taxonomy_id IN (".$all_tax_updated.")":$custom_catagories_query);
$custom_catagories_where = ($custom_catagories_updated != ""?"LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) ":"");
$query = $wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS $wpdb->posts.*,COALESCE((SELECT MAX(comment_date) FROM $wpdb->comments wpc WHERE wpc.comment_post_id = $wpdb->posts.id),$wpdb->posts.post_date) AS mcomment_date FROM $wpdb->posts $custom_catagories_where LEFT JOIN $wpdb->postmeta AS mt1 ON ($wpdb->posts.ID = mt1.post_id AND mt1.meta_key = 'user_id') WHERE mt1.post_id IS NULL AND post_type = %s $custom_catagories_updated $sticky_questions $include_posts $date AND post_status = 'publish' ORDER BY mcomment_date $order_post LIMIT $post_number OFFSET $offset","question");
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
}?>