<?php

/* @author    2codeThemes
*  @package   WPQA/widgets
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Tabs */
add_action( 'widgets_init', 'wpqa_widget_tabs_widget' );
function wpqa_widget_tabs_widget() {
	register_widget( 'Widget_Tabs' );
}
class Widget_Tabs extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'tabs-widget' );
		$control_ops = array( 'id_base' => 'tabs-widget' );
		parent::__construct( 'tabs-widget',wpqa_widgets.' - Tabs', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$rand_w              = $args['widget_id'];
		$title               = apply_filters('widget_title', $instance['title'] );
		$post_or_question    = esc_attr($instance['post_or_question']);
		$display             = esc_attr($instance['display']);
		$category            = esc_attr($instance['category']);
		$categories          = (isset($instance['categories'])?$instance['categories']:array());
		$e_categories        = (isset($instance['exclude_categories'])?$instance['exclude_categories']:array());
		$custom_posts        = (isset($instance['custom_posts'])?$instance['custom_posts']:"");
		$display_question    = esc_attr($instance['display_question']);
		$category_question   = esc_attr($instance['category_question']);
		$categories_question = (isset($instance['categories_question'])?$instance['categories_question']:array());
		$e_cats_question     = (isset($instance['exclude_categories_question'])?$instance['exclude_categories_question']:array());
		$custom_questions    = (isset($instance['custom_questions'])?$instance['custom_questions']:"");
		$excerpt_post        = (isset($instance['excerpt_post'])?$instance['excerpt_post']:"");
		$specific_date       = (isset($instance['specific_date'])?$instance['specific_date']:"");

		if ($post_or_question == "question") {
			$tabs            = $instance['tabs_questions'];
			$posts_per_page  = esc_attr((int)$instance['questions_per_page']);
			$display_comment = esc_attr($instance['display_answer_meta']);
			$display_date    = esc_attr($instance['display_date']);
			$show_images     = esc_attr($instance['show_images']);
			$orderby         = esc_attr($instance['orderby']);
			$excerpt_title   = esc_attr((int)$instance['excerpt_title']);
			$images_comment  = esc_attr($instance['images_answer']);
			$excerpt_comment = esc_attr((int)$instance['excerpt_answer']);
			$comments_number = esc_attr((int)$instance['answers_number']);
			$post_style      = (isset($instance['question_style'])?$instance['question_style']:"");
			$display_image   = (isset($instance['display_image_question'])?$instance['display_image_question']:"");
			$display_video   = (isset($instance['display_video_question'])?$instance['display_video_question']:"");
			$display_date_2  = (isset($instance['display_date_2_question'])?$instance['display_date_2_question']:"");
			$date_comments   = (isset($instance['specific_date_answers'])?$instance['specific_date_answers']:"");
		}else {
			$tabs            = $instance['tabs'];
			$posts_per_page  = esc_attr((int)$instance['posts_per_page']);
			$display_comment = esc_attr($instance['display_comment_meta']);
			$display_date    = esc_attr($instance['display_date_post']);
			$show_images     = esc_attr($instance['show_images_post']);
			$orderby         = esc_attr($instance['orderby_post']);
			$excerpt_title   = esc_attr((int)$instance['excerpt_title_post']);
			$images_comment  = esc_attr($instance['images_comment']);
			$excerpt_comment = esc_attr((int)$instance['excerpt_comment']);
			$comments_number = esc_attr((int)$instance['comments_number']);
			$post_style      = (isset($instance['post_style'])?$instance['post_style']:"");
			$display_image   = (isset($instance['display_image'])?$instance['display_image']:"");
			$display_video   = (isset($instance['display_video'])?$instance['display_video']:"");
			$display_date_2  = (isset($instance['display_date_2'])?$instance['display_date_2']:"");
			$date_comments   = (isset($instance['specific_date_comments'])?$instance['specific_date_comments']:"");
		}
		
		if (isset($tabs) && is_array($tabs) && !empty($tabs)) {?>
			<div class='widget tabs-wrap widget-tabs'>
				<div class="widget-title widget-title-tabs">
					<ul class="tabs tabs<?php echo esc_attr($rand_w);?>">
						<?php foreach ($tabs as $key => $value) {
							if (isset($value["value"])) {
								if ($value["value"] == "display_posts") {?>
									<li class="tab"><a href="#"><?php if ($orderby == "no_response") {echo ($post_or_question == "question"?esc_html__('No answers','wpqa'):esc_html__('No comments','wpqa'));}elseif ($orderby == "most_voted") {esc_html_e('Most voted','wpqa');}elseif ($orderby == "most_visited") {esc_html_e('Most visited','wpqa');}elseif ($orderby == "most_rated") {esc_html_e('Most rated','wpqa');}elseif ($orderby == "popular") {esc_html_e('Popular','wpqa');}elseif ($orderby == "random") {esc_html_e('Random','wpqa');}else {esc_html_e('Recent','wpqa');}?></a></li>
								<?php }else if ($value["value"] == "display_comments") {?>
									<li class="tab"><a href="#"><?php echo ($post_or_question == "question"?esc_html__('Answers','wpqa'):esc_html__('Comments','wpqa'))?></a></li>
								<?php }else if ($value["value"] == "display_tags") {?>
									<li class="tab"><a href="#"><?php esc_html_e('Tags','wpqa')?></a></li>
								<?php }
							}
						}?>
					</ul>
					<div class="clearfix"></div>
				</div>
				<div class="widget-wrap">
					<?php foreach ($tabs as $key => $value) {
						if (isset($value["value"])) {
							if ($value["value"] == "display_posts") {
								echo "<div class='widget-posts tab-inner-wrap tab-inner-wrap".esc_attr($rand_w)."'>";
									$args = array(
										"posts_per_page"      => $posts_per_page,
										"orderby"             => $orderby,
										"excerpt_title"       => $excerpt_title,
										"show_images"         => $show_images,
										"post_or_question"    => $post_or_question,
										"display_comment"     => $display_comment,
										"display"             => $display,
										"category"            => $category,
										"categories"          => $categories,
										"e_categories"        => $e_categories,
										"custom_posts"        => $custom_posts,
										"display_question"    => $display_question,
										"category_question"   => $category_question,
										"categories_question" => $categories_question,
										"e_cats_question"     => $e_cats_question,
										"custom_questions"    => $custom_questions,
										"post_style"          => $post_style,
										"display_date"        => $display_date_2,
										"display_image"       => $display_image,
										"display_video"       => $display_video,
										"excerpt_post"        => $excerpt_post,
										"specific_date"       => $specific_date
									);
									echo wpqa_posts($args);
								echo "</div>";
							}else if ($value["value"] == "display_comments") {
								echo "<div class='tab-inner-wrap tab-inner-wrap".esc_attr($rand_w)."'>";
									$args = array(
										'post_or_question' => $post_or_question,
										'comments_number'  => $comments_number,
										'comment_excerpt'  => $excerpt_comment,
										'show_images'      => $images_comment,
										'display_date'     => $display_date,
										'specific_date'    => $date_comments,
									);
									wpqa_comments($args);
								echo "</div>";
							}else if ($value["value"] == "display_tags") {
								echo "<div class='tab-inner-wrap tab-inner-wrap".esc_attr($rand_w)."'><div class='tagcloud'>";
									if ($post_or_question == 'question') {
										$tag_type = array('taxonomy' => 'question_tags');
										$tag_tax = "question_tags";
									}else {
										$tag_type = array();
										$tag_tax = "post_tag";
									}
									$args = array_merge(array('smallest' => 8,'largest' => 22,'unit' => 'pt','number' => 0,'topic_count_text_callback' => 'wpqa_'.$tag_tax.'_callback'),$tag_type);
									wp_tag_cloud($args);
								echo "</div></div>";
							}
						}
					}
					wp_enqueue_script("v_tabs");?>
					<script type='text/javascript'>
						jQuery(document).ready(function(){
							jQuery("ul.tabs<?php echo esc_js($rand_w);?>").tabs(".tab-inner-wrap<?php echo esc_js($rand_w)?>",{tabs: "li",effect:"slide",fadeInSpeed:100});
						});
					</script>
				</div>
			</div>
			<?php
		}
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>