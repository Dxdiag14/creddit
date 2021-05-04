<?php

/* @author    2codeThemes
*  @package   WPQA/widgets
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Stats */
add_action( 'widgets_init', 'wpqa_widget_stats_widget' );
function wpqa_widget_stats_widget() {
	register_widget( 'Widget_Stats' );
}

class Widget_Stats extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'stats-widget' );
		$control_ops = array( 'id_base' => 'stats-widget' );
		parent::__construct( 'stats-widget',wpqa_widgets.' - Stats', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title   = apply_filters('widget_title', $instance['title'] );
		$stats   = $instance['stats'];
		$style   = $instance['style'];
		$divider = $instance['divider'];
		
		if (empty($divider) || $divider != "on") {
			$before_widget = str_replace('class="','class="widget-no-divider ',$before_widget);
		}
		  
		echo ($before_widget);
			if ($title) {
				echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
			}else {
				echo "<h3 class='screen-reader-text'>".esc_html__("Stats","wpqa")."</h3>";
			}?>
			<div class="widget-wrap">
				<ul class="<?php echo ($style == "style_2"?"stats-inner-2":"stats-inner")?>">
					<?php if (isset($stats) && is_array($stats) && !empty($stats)) {
						foreach ($stats as $key => $value) {
							if (isset($value["value"]) && $value["value"] == $key) {?>
								<li class="stats-<?php echo ($value["value"])?>">
									<div>
										<?php if ($style == "style_2") {
											if ($value["value"] == "questions") {
												echo '<i class="icon-book-open"></i>';
											}else if ($value["value"] == "posts") {
												echo '<i class="icon-user"></i>';
											}else if ($value["value"] == "answers") {
												echo '<i class="icon-comment"></i>';
											}else if ($value["value"] == "comments") {
												echo '<i class="icon-chat"></i>';
											}else if ($value["value"] == "best_answers") {
												echo '<i class="icon-graduation-cap"></i>';
											}else if ($value["value"] == "users") {
												echo '<i class="icon-users"></i>';
											}
											do_action("wpqa_widget_stats_icons",$value);
										}?>
										<span class="<?php echo ($style == "style_2"?"stats-text-2":"stats-text")?>">
											<?php if ($value["value"] == "questions") {
												$question_count = wp_count_posts("question");
												$questions_count = (isset($question_count->publish)?$question_count->publish:0);
												echo _n("Question","Questions",$questions_count,"wpqa");
											}else if ($value["value"] == "posts") {
												$posts_count = wp_count_posts("post")->publish;
												echo _n("Post","Posts",$posts_count,"wpqa");
											}else if ($value["value"] == "answers") {
												$answers_count = count(get_comments(array("post_type" => "question")));
												echo _n("Answer","Answers",$answers_count,"wpqa");
											}else if ($value["value"] == "comments") {
												$comments_count = count(get_comments(array("post_type" => "post")));
												echo _n("Comment","Comments",$comments_count,"wpqa");
											}else if ($value["value"] == "best_answers") {
												$best_answer_option = count(get_comments(array("status" => "approve",'post_type' => 'question',"meta_query" => array('relation' => 'AND',array("key" => "best_answer_comment","compare" => "=","value" => "best_answer_comment"),array("key" => "answer_question_user","compare" => "NOT EXISTS")))));
												$best_answers_count = (isset($best_answer_option) && $best_answer_option != "" && $best_answer_option > 0?$best_answer_option:0);
												echo _n("Best Answer","Best Answers",$best_answers_count,"wpqa");
											}else if ($value["value"] == "users") {
												$count_users = count_users();
												$users_count = 0;
												foreach ($count_users["avail_roles"] as $role => $count) {
													if ($role != "wpqa_under_review" && $role != "activation" && $role != "ban_group") {
														$users_count += $count;
													}
												}
												$users_count = (int)$users_count;
												echo _n("User","Users",$users_count,"wpqa");
											}
											do_action("wpqa_widget_stats_text",$value);
											echo ($style == "style_2"?" : ":"")?>
										</span>
										<span class="<?php echo ($style == "style_2"?"stats-value-2":"stats-value")?>">
											<?php if ($value["value"] == "questions") {
												echo wpqa_count_number($questions_count);
											}else if ($value["value"] == "posts") {
												echo wpqa_count_number($posts_count);
											}else if ($value["value"] == "answers") {
												echo wpqa_count_number($answers_count);
											}else if ($value["value"] == "comments") {
												echo wpqa_count_number($comments_count);
											}else if ($value["value"] == "best_answers") {
												echo wpqa_count_number($best_answers_count);
											}else if ($value["value"] == "users") {
												echo wpqa_count_number($users_count);
											}
											do_action("wpqa_widget_stats_count",$value);?>
										</span>
									</div>
								</li>
							<?php }
						}
					}?>
				</ul>
			</div>
		<?php echo ($after_widget);
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>