<?php

/* @author    2codeThemes
*  @package   WPQA/widgets
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

/* Questions Categories */
add_action( 'widgets_init', 'wpqa_widget_questions_categories_widget' );
function wpqa_widget_questions_categories_widget() {
	register_widget( 'Widget_Questions_Categories' );
}

function wpqa_term_post_count($taxonomy = 'category',$term = '',$args = array()) {
	$exclude = apply_filters('wpqa_exclude_question_category',array());
	$cat = get_terms(array_merge($exclude,array('taxonomy' => $taxonomy,'term_taxonomy_id' => $term,'hide_empty' => true)));
    $count = (isset($cat[0]->count)?(int)$cat[0]->count:0);
    $args = array('child_of' => $term);
    $args = array_merge($exclude,$args);
    $tax_terms = get_terms($taxonomy,$args);
    foreach ($tax_terms as $tax_term) {
        $count +=$tax_term->count;
    }
	return $count;
}
function wpqa_hierarchical_category($category = 0,$questions_counts,$taxonomy = 'category') {
	$r = '';
	$exclude = apply_filters('wpqa_exclude_question_category',array());
	$args = array(
		'parent' => $category,
		'hide_empty' => false,
	);
	$next = get_terms($taxonomy,array_merge($exclude,$args));
	if ($next) {
		$levels = 0;
		$r .= '<li class="categories-child-child">
			<ul>';
				foreach ($next as $cat) {
					$count = wpqa_term_post_count($taxonomy,$cat->cat_ID,array('post_type' => 'question'));
					$levels ++;
					$r .= '<li class="categories-main-child categories-child-'.$levels.'"><a href="'.get_term_link($cat->slug,$cat->taxonomy).'"'.'>'.$cat->name;
					if ($questions_counts == "on") {
						$r .= '<span class="question-category-main"> <span>(</span> <span class="question-category-span">'.esc_html($count).'</span> <span>'._n("Question","Questions",$count,"wpqa").'</span> <span>)</span> </span>';
					}
					$r .= '</a>';
					$r .= $cat->term_id !== 0?wpqa_hierarchical_category($cat->term_id,$questions_counts,$taxonomy):null;
				}
				$r .= '</li>
			</ul>
		</li>';
	}
	return $r;
}

class Widget_Questions_Categories extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'questions_categories-widget' );
		$control_ops = array( 'id_base' => 'questions_categories-widget' );
		parent::__construct( 'questions_categories-widget',wpqa_widgets.' - Questions Categories', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title            = apply_filters('widget_title', $instance['title'] );
		$questions_counts = esc_attr($instance['questions_counts']);
		$show_child       = esc_attr($instance['show_child']);
		$category_type    = (isset($instance['category_type'])?esc_attr($instance['category_type']):"");
		$cat_sort         = (isset($instance['cat_sort'])?esc_attr($instance['cat_sort']):"count");
		$cat_number       = (int)(isset($instance['cat_number'])?$instance['cat_number']:"");
		$cat_type         = "question-category";
		$user_id          = get_current_user_id();
		echo ($before_widget);
			if ($title) {
				echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
			}else {
				echo "<h3 class='screen-reader-text'>".esc_html__("Questions Categories","wpqa")."</h3>";
			}?>
			<div class="widget-wrap">
				<?php if ($category_type == "simple" || $category_type == "simple_follow" || $category_type == "with_icon" || $category_type == "icon_color" || $category_type == "with_icon_1" || $category_type == "with_icon_2" || $category_type == "with_icon_3" || $category_type == "with_icon_4" || $category_type == "with_cover_1" || $category_type == "with_cover_2" || $category_type == "with_cover_3") {
					$follow_category = wpqa_options("follow_category");
					$cat_sort = ($cat_sort == "followers"?"meta_value_num":$cat_sort);
					$meta_query = ($cat_sort == "meta_value_num"?array('meta_query' => array("relation" => "or",array("key" => "cat_follow_count","compare" => "NOT EXISTS"),array("key" => "cat_follow_count","value" => 0,"compare" => ">="))):array());
					$exclude = apply_filters('wpqa_exclude_question_category',array());
					$args = array_merge($exclude,$meta_query,array(
						'order'      => "DESC",
						'orderby'    => $cat_sort,
						'number'     => $cat_number,
						'hide_empty' => false
					));
					$args = apply_filters('wpqa_question_category_widget_args',$args);
					$terms = get_terms($cat_type,$args);
					if (!empty($terms) && !is_wp_error($terms)) {
						$term_list = '<div class="row widget-cats-sections cat_widget_'.$category_type.'">';
							foreach ($terms as $term) {
								$tax_id = $term->term_id;
								$category_icon = get_term_meta($tax_id,prefix_terms."category_icon",true);
								if ($follow_category == "on") {
									$cat_follow = get_term_meta($tax_id,"cat_follow",true);
								}
								$term_list .= '<div class="col col12">';
									if ($category_type == "with_icon" || $category_type == "icon_color" || $category_type == "with_icon_1" || $category_type == "with_icon_2" || $category_type == "with_icon_3" || $category_type == "with_icon_4" || $category_type == "with_cover_1" || $category_type == "with_cover_2" || $category_type == "with_cover_3") {
										$questions = (int)wpqa_count_posts_by_category("question",$cat_type,$tax_id);
										if ($category_type == "icon_color" || $category_type == "with_icon_2" || $category_type == "with_icon_3" || $category_type == "with_icon_4") {
											$category_color = get_term_meta($tax_id,prefix_terms."category_color",true);
										}
										if ($category_type == "with_cover_1" || $category_type == "with_cover_2" || $category_type == "with_cover_3") {
											$custom_cat_cover = get_term_meta($tax_id,prefix_terms."custom_cat_cover",true);
											if ($custom_cat_cover == "on") {
												$cat_cover = get_term_meta($tax_id,prefix_terms."cat_cover",true);
												$cat_share = get_term_meta($tax_id,prefix_terms."cat_share",true);
											}else {
												$cat_cover = wpqa_options("active_cover_category");
												$cat_share = wpqa_options("cat_share");
											}
											if ($cat_cover == "on") {
												$cover_link = wpqa_get_cat_cover_link(array("tax_id" => $tax_id,"cat_name" => $term->name));
												if ($cover_link != "") {
													$cover_link = wpqa_get_aq_resize_url($cover_link,500,200);
													$custom_css = ' style="background-image: url('.$cover_link.');"';
												}
											}
										}
										$term_list .= '<div class="cat-sections cat-sections-icon cat-section-'.$category_type.(isset($cover_link) && $cover_link != ""?" cat-section-cover":"").'"'.(isset($category_color) && $category_color != "" && ($category_type == "icon_color" || $category_type == "with_icon_3" || $category_type == "with_icon_4")?" style='background-color: rgba(".implode(",",wpqa_hex2rgb($category_color)).",0.1);border-color: rgba(".implode(",",wpqa_hex2rgb($category_color)).",0.4)'":"").(isset($custom_css)?$custom_css:"").'>';
											if (isset($cover_link) && $cover_link != "") {
												$term_list .= '<div class="cover-opacity"></div><div class="wpqa-cover-inner">';
											}
											if ($category_type != "with_cover_1") {
												$term_list .= '<span class="cat-section-icon"'.(isset($category_color) && $category_color != "" && ($category_type == "icon_color" || $category_type == "with_icon_2" || $category_type == "with_icon_3" || $category_type == "with_icon_4")?" style='".($category_type == "with_icon_4"?"":"background-")."color: ".$category_color."'":"").'><i class="'.($category_icon != ""?esc_html($category_icon):"icon-folder").'"></i></span>';
											}
											$term_list .= '<div class="widget-categories-div">
												<h6><a href="'.esc_url(get_term_link($term)).'" title="'.esc_attr(sprintf(esc_html__('View all questions under %s','wpqa'),$term->name)).'">'.$term->name.'</a></h6>
												<div class="count-cat-question"><span>'.$questions.'</span>'._n("Question","Questions",$questions,"wpqa").'</a></div>';
												if ($follow_category == "on") {
													$cats_follwers = (int)(is_array($cat_follow)?count($cat_follow):0);
													$term_list .= '<div class="count-cat-follow">, <span class="follow-cat-count">'.wpqa_count_number($cats_follwers)."</span>"._n("Follower","Followers",$cats_follwers,"wpqa").'</div>
													'.($category_type == "with_icon_1" || $category_type == "with_icon_2" || $category_type == "with_icon_3" || $category_type == "with_icon_4" || $category_type == "with_cover_1" || $category_type == "with_cover_2" || $category_type == "with_cover_3"?wpqa_follow_cat_button($tax_id,$user_id,'cat',true,'','cat-sections-icon','follow-cat-count'):"");
												}
											$term_list .= '</div>';
										if (isset($cover_link) && $cover_link != "") {
											$term_list .= '</div>';
										}
										$term_list .= '</div>';
									}else {
										$term_list .= ($category_type == "simple_follow" && $follow_category == "on"?"<div class='cat-sections-follow'>":"").'
										<div class="cat-sections">
											<a href="'.esc_url(get_term_link($term)).'" title="'.esc_attr(sprintf(esc_html__('View all questions under %s','wpqa'),$term->name)).'"><i class="'.($category_icon != ""?esc_html($category_icon):"icon-folder").'"></i>'.$term->name.'</a>
										</div>';
										if ($category_type == "simple_follow" && $follow_category == "on") {
											$cats_follwers = (int)(is_array($cat_follow)?count($cat_follow):0);
											$term_list .= '<div class="cat-section-follow">
												<div class="cat-follow-button"><i class="icon-users"></i><span class="follow-cat-count">'.wpqa_count_number($cats_follwers)."</span>"._n("Follower","Followers",$cats_follwers,"wpqa").'</div>
												'.wpqa_follow_cat_button($tax_id,$user_id,'cat',true,'button-default-4','cat-section-follow','follow-cat-count').'
												<div class="clearfix"></div>
											</div></div>';
										}
									}
								$term_list .= '</div>';
							}
						$term_list .= '</div>';
						echo ($term_list);
					}
				}else {?>
					<div class="widget_questions_categories">
						<?php if ($show_child == "on") {?>
							<div class="widget_child_categories">
								<div class="categories-toggle-accordion">
						<?php }?>
							<ul>
								<?php $exclude = apply_filters('wpqa_exclude_question_category',array());
								$args = array_merge($exclude,array(
								'parent'       => ($show_child == "on"?0:""),
								'orderby'      => 'name',
								'order'        => 'ASC',
								'hide_empty'   => false,
								'hierarchical' => 1,
								'taxonomy'     => $cat_type,
								'pad_counts'   => false,
								'number'       => $cat_number));
								$args = apply_filters('wpqa_question_category_widget_args',$args);
								$options_categories = get_categories($args);
								foreach ($options_categories as $category) {
									$count = wpqa_term_post_count($cat_type,$category->cat_ID,array('post_type' => 'question'));
									if ($show_child == "on") {
										$exclude = apply_filters('wpqa_exclude_question_category',array());
										$children = get_terms($cat_type,array_merge($exclude,array('parent' => $category->cat_ID,'hide_empty' => false)));
									}?>
									<li>
										<?php if ($show_child == "on" && isset($children) && is_array($children) && !empty($children)) {?>
											<h4 class="accordion-title">
										<?php }?>
											<a<?php echo ($show_child == "on"?' class="'.(isset($children) && is_array($children) && !empty($children)?"link-child":"link-not-child").'"':'')?> href="<?php echo get_term_link($category->slug,$cat_type)?>"><?php echo esc_html($category->name);
												if ($questions_counts == "on") {?>
													<span class="question-category-main"> <span>(</span> <span class="question-category-span"><?php echo esc_html($count)."</span> <span>"._n("Question","Questions",$count,"wpqa")?></span> <span>)</span> </span>
												<?php }?>
												<i></i>
											</a>
										<?php if ($show_child == "on" && isset($children) && is_array($children) && !empty($children)) {?>
											</h4>
										<?php }
										if ($show_child == "on" && isset($children) && is_array($children) && !empty($children)) {?>
											<div class="accordion-inner">
												<ul>
													<?php echo wpqa_hierarchical_category($category->cat_ID,$questions_counts,$cat_type)?>
												</ul>
											</div>
										<?php }?>	
									</li>
								<?php }?>
							</ul>
						<?php if ($show_child == "on") {?>
								</div>
							</div>
						<?php }?>
					</div>
				<?php }?>
			</div>
		<?php echo ($after_widget);
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>