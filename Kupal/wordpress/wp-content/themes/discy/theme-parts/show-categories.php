<?php $term_list .= '<div class="col '.$tax_col.'">';
	if (($cats_tax == 'question' || $cats_tax == 'question-category') && ($cat_style == "with_icon" || $cat_style == "icon_color" || $cat_style == "with_icon_1" || $cat_style == "with_icon_2" || $cat_style == "with_icon_3" || $cat_style == "with_icon_4" || $cat_style == "with_cover_1" || $cat_style == "with_cover_2" || $cat_style == "with_cover_3" || $cat_style == "with_cover_4" || $cat_style == "with_cover_5" || $cat_style == "with_cover_6")) {
	$questions = (has_wpqa()?(int)wpqa_count_posts_by_category("question","question-category",$tax_id):0);
		if ($cat_style == "icon_color" || $cat_style == "with_icon_2" || $cat_style == "with_icon_3" || $cat_style == "with_icon_4") {
			$category_color = get_term_meta($tax_id,prefix_terms."category_color",true);
		}
		if ($cat_style == "with_cover_1" || $cat_style == "with_cover_2" || $cat_style == "with_cover_3" || $cat_style == "with_cover_4" || $cat_style == "with_cover_5" || $cat_style == "with_cover_6") {
			$custom_cat_cover = get_term_meta($tax_id,prefix_terms."custom_cat_cover",true);
			if ($custom_cat_cover == "on") {
				$cat_cover = get_term_meta($tax_id,prefix_terms."cat_cover",true);
				$cat_share = get_term_meta($tax_id,prefix_terms."cat_share",true);
			}else {
				$cat_cover = discy_options("active_cover_category");
				$cat_share = discy_options("cat_share");
			}
			if (has_wpqa() && $cat_cover == "on") {
				$cover_link = wpqa_get_cat_cover_link(array("tax_id" => $tax_id,"cat_name" => $term->name));
				if ($cover_link != "") {
					$cover_link = discy_get_aq_resize_url($cover_link,500,200);
					$custom_css = ' style="background-image: url('.$cover_link.');"';
				}
			}
		}
		$term_list .= '<div class="cat-sections cat-sections-icon cat-section-'.$cat_style.(isset($cover_link) && $cover_link != ""?" cat-section-cover":"").'"'.(isset($category_color) && $category_color != "" && ($cat_style == "icon_color" || $cat_style == "with_icon_3" || $cat_style == "with_icon_4")?" style='background-color: rgba(".implode(",",discy_hex2rgb($category_color)).",0.1);border-color: rgba(".implode(",",discy_hex2rgb($category_color)).",0.4)'":"").(isset($custom_css)?$custom_css:"").'>';
			if (isset($cover_link) && $cover_link != "") {
				$term_list .= '<div class="cover-opacity"></div><div class="wpqa-cover-inner">';
			}
			if ($cat_style != "with_cover_1" && $cat_style != "with_cover_4") {
				$term_list .= '<span class="cat-section-icon"'.(isset($category_color) && $category_color != "" && ($cat_style == "icon_color" || $cat_style == "with_icon_2" || $cat_style == "with_icon_3" || $cat_style == "with_icon_4")?" style='".($cat_style == "with_icon_4"?"":"background-")."color: ".$category_color."'":"").'><i class="'.($category_icon != ""?esc_html($category_icon):"icon-folder").'"></i></span>';
			}
			$term_list .= '<h6><a href="'.esc_url(get_term_link($term)).'" title="'.esc_attr(sprintf(esc_html__('View all questions under %s','discy'),$term->name)).'">'.$term->name.'</a></h6>
			<div class="count-cat-question"><span>'.$questions.'</span>'._n("Question","Questions",$questions,"discy").'</a></div>';
			if ($follow_category == "on") {
				$cats_follwers = (int)(is_array($cat_follow)?count($cat_follow):0);
				$term_list .= '<div class="count-cat-follow">, <span class="follow-cat-count">'.discy_count_number($cats_follwers)."</span>"._n("Follower","Followers",$cats_follwers,"discy").'</div>
				'.(($cat_style == "with_icon_1" || $cat_style == "with_icon_2" || $cat_style == "with_icon_3" || $cat_style == "with_icon_4" || $cat_style == "with_cover_1" || $cat_style == "with_cover_2" || $cat_style == "with_cover_3" || $cat_style == "with_cover_4" || $cat_style == "with_cover_5" || $cat_style == "with_cover_6") && has_wpqa()?wpqa_follow_cat_button($tax_id,$user_id,'cat',true,'','cat-sections-icon','follow-cat-count'):"");
			}
			if (isset($cover_link) && $cover_link != "") {
				$term_list .= '</div>';
			}
		$term_list .= '</div>';
	}else {
		$term_list .= (($cats_tax == 'question' || $cats_tax == 'question-category') && $cat_style == "simple_follow" && $follow_category == "on"?"<div class='cat-sections-follow'>":"").'
		<div class="cat-sections">
			<a href="'.esc_url(get_term_link($term)).'" title="'.esc_attr(sprintf(($cats_tax == 'post' || $cats_tax == 'category'?esc_html__('View all posts under %s','discy'):esc_html__('View all questions under %s','discy')),$term->name)).'"><i class="'.($category_icon != ""?esc_html($category_icon):"icon-folder").'"></i>'.$term->name.'</a>
		</div>';
		if (($cats_tax == 'question' || $cats_tax == 'question-category') && $cat_style == "simple_follow" && $follow_category == "on") {
			$cats_follwers = (int)(is_array($cat_follow)?count($cat_follow):0);
			$term_list .= '<div class="cat-section-follow">
				<div class="cat-follow-button"><i class="icon-users"></i><span class="follow-cat-count">'.discy_count_number($cats_follwers)."</span>"._n("Follower","Followers",$cats_follwers,"discy").'</div>
				'.(has_wpqa()?wpqa_follow_cat_button($tax_id,$user_id,'cat',true,'button-default-4','cat-section-follow','follow-cat-count'):"").'
				<div class="clearfix"></div>
			</div></div>';
		}
	}
$term_list .= '</div>';?>