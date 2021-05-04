<?php

/* @author    2codeThemes
*  @package   WPQA/widgets
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Related questions */
add_action( 'widgets_init', 'wpqa_widget_related_widget' );
function wpqa_widget_related_widget() {
	register_widget( 'Widget_Related' );
}
class Widget_Related extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'related-widget' );
		$control_ops = array( 'id_base' => 'related-widget' );
		parent::__construct( 'related-widget',wpqa_widgets.' - Related Questions', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		if (is_singular("question")) {
			global $post;
			extract( $args );
			$title           = apply_filters('widget_title', $instance['title'] );
			$related_number  = esc_attr($instance['related_number']);
			$show_images     = esc_attr($instance['show_images']);
			$excerpt_title   = esc_attr($instance['excerpt_title']);
			$display_answers = esc_attr($instance['display_answers']);
			$query_related   = esc_attr($instance['query_related']);
			$question_style  = (isset($instance['question_style'])?$instance['question_style']:"");
			$display_image   = (isset($instance['display_image'])?$instance['display_image']:"");
			$display_video   = (isset($instance['display_video'])?$instance['display_video']:"");
			$display_date    = (isset($instance['display_date'])?$instance['display_date']:"");
			$related_number  = ($related_number > 0?$related_number:5);
			$excerpt_title   = ($excerpt_title > 0?$excerpt_title:10);
			
			$get_question_user_id = get_post_meta($post->ID,"user_id",true);
			$taxonomy = "question-category";
			$term_list = wp_get_post_terms($post->ID, 'question_tags', array("fields" => "ids"));
			if (isset($term_list) && !empty($term_list) && $query_related == "tags") {
				$related_query_ = array('tax_query' => array(array('taxonomy' => 'question_tags','field' => 'id','terms'  => $term_list,'operator' => 'IN')));
			}else if ($query_related == "author" || esc_html($get_question_user_id) != "") {
				$related_query_ = (esc_html($get_question_user_id) != ""?array():array('author' => $post->post_author));
			}else {
				$categories = get_the_terms($post->ID,$taxonomy);
				$category_ids = array();
				if (isset($categories) && is_array($categories)) {
					foreach ($categories as $l_category) {
						$category_ids[] = $l_category->term_id;
					}
				}
				$related_query_ = array('tax_query' => array(array('taxonomy' => $taxonomy,'field' => 'id','terms'  => $category_ids,'operator' => 'IN')));
			}
			
			$ask_question_to_users = wpqa_options("ask_question_to_users");
			$question_meta_query = ($ask_question_to_users == "on"?array("key" => "user_id","compare" => "NOT EXISTS"):array());
			
			$args = array_merge($related_query_,array('post_type' => 'question','post__not_in' => array($post->ID),'posts_per_page'=> $related_number,'cache_results' => false,'no_found_rows' => true,"meta_query" => array((esc_html($get_question_user_id) != ""?array("type" => "numeric","key" => "user_id","value" => (int)$get_question_user_id,"compare" => "="):$question_meta_query))));
			
			$args = array(
				"excerpt_title"    => $excerpt_title,
				"show_images"      => $show_images,
				"post_or_question" => "question",
				"display_comment"  => $display_answers,
				"custom_args"      => $args,
				"post_style"       => $question_style,
				"display_image"    => $display_image,
				"display_video"    => $display_video,
				"display_date"     => $display_date,
				"no_query"         => "no_query"
			);
			$wpqa_posts = wpqa_posts($args);
			
			if (($query_related == "tags" || $query_related == "author") && $wpqa_posts == "no_query") {
				$categories = get_the_terms($post->ID,$taxonomy);
				$category_ids = array();
				if (isset($categories) && !empty($categories)) {
					foreach ($categories as $l_category) {
						$category_ids[] = (isset($l_category->term_id)?$l_category->term_id:"");
					}
				}
				$related_query_ = array('tax_query' => array(array('taxonomy' => $taxonomy,'field' => 'id','terms'  => $category_ids,'operator' => 'IN')));
				
				$args = array_merge($related_query_,array('post__not_in' => array($post->ID),'posts_per_page'=> $related_number,'cache_results' => false,'no_found_rows' => true));
				
				$args = array(
					"excerpt_title"    => $excerpt_title,
					"show_images"      => $show_images,
					"post_or_question" => "question",
					"display_comment"  => $display_answers,
					"custom_args"      => $args,
					"post_style"       => $question_style,
					"display_image"    => $display_image,
					"display_video"    => $display_video,
					"display_date"     => $display_date,
					"no_query"         => "no_query"
				);
				$wpqa_posts = wpqa_posts($args);
			}
			
			if ($wpqa_posts != "no_query") {
				echo ($before_widget);
					if ($title) {
						echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
					}else {
						echo "<h3 class='screen-reader-text'>".esc_html__("Related Questions","wpqa")."</h3>";
					}
					echo ($wpqa_posts);
					
				echo ($after_widget);
			}
		}
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>