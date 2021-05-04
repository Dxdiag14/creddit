<?php

/* @author    2codeThemes
*  @package   WPQA/templates
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

$live_search       = wpqa_options("live_search");
$user_filter       = wpqa_options('user_filter');
$active_points     = wpqa_options("active_points");
$search_attrs      = wpqa_options("search_attrs");
$search_attrs_keys = (is_array($search_attrs) && !empty($search_attrs)?array_keys($search_attrs):"");
$search_type       = wpqa_search_type();
$search_value      = wpqa_search_terms();
if (isset($search_attrs) && is_array($search_attrs) && !empty($search_attrs)) {
	$i_count = $k_count = 0;
	while ($i_count < count($search_attrs)) {
		if (isset($search_attrs[$search_attrs_keys[$i_count]]["value"]) && $search_attrs[$search_attrs_keys[$i_count]]["value"] != "" && $search_attrs[$search_attrs_keys[$i_count]]["value"] != "0") {
			$first_one_search = $i_count;
			break;
		}
		$i_count++;
	}
	if (isset($first_one_search)) {
		$first_one_search = $search_attrs[$search_attrs_keys[$first_one_search]]["value"];
	}
	foreach ($search_attrs as $key => $value) {
		if (isset($value["value"]) && $value["value"] != "" && $value["value"] != "0") {
			$k_count++;
			$count_search_attrs = $k_count;
			$search_attr_value = $value["value"];
		}
	}
}
if (isset($search_type) && $search_type == "users" && $user_filter == "on" && isset($search_attrs["users"]["value"]) && $search_attrs["users"]["value"] == "users" && isset($first_one_search)) {
	if (isset($count_search_attrs) && $count_search_attrs > 1) {
		$search_class = "col4";
	}else {
		$search_class = "col6";
	}
}else {
	if (isset($count_search_attrs) && $count_search_attrs > 1) {
		$search_class = "col6";
	}else {
		$search_class = "col12";
	}
}?>
<div class="main-search post-search<?php echo ($search_value != ""?"":" search-not-get")?>">
	<form role="search" method="get" class="searchform main-search-form" action="<?php echo esc_url(wpqa_get_search_permalink())?>">
		<div class="row">
			<div class="col<?php echo esc_attr(isset($search_class) && $search_class != ""?" ".$search_class:"")?>">
				<input type="search"<?php echo ($live_search == "on"?" class='live-search' autocomplete='off'":"")?> name="search" value="<?php if ($search_value != "") {echo esc_html($search_value);}else {esc_html_e("Hit enter to search","wpqa");}?>" onfocus="if(this.value=='<?php esc_attr_e("Hit enter to search","wpqa")?>')this.value='';" onblur="if(this.value=='')this.value='<?php esc_attr_e("Hit enter to search","wpqa")?>';">
				<?php if ($live_search == "on") {?>
					<div class="loader_2 search_loader"></div>
					<div class="search-results results-empty"></div>
				<?php }?>
			</div>
			<?php if (isset($search_attrs) && is_array($search_attrs) && !empty($search_attrs) && isset($first_one_search) && isset($count_search_attrs) && $count_search_attrs > 1) {?>
				<div class="col <?php echo (isset($search_type) && $search_type == "users" && $user_filter == "on" && isset($search_attrs["users"]["value"]) && $search_attrs["users"]["value"] == "users"?"col4":"col6")?>">
					<span class="styled-select">
						<select name="search_type" class="search_type<?php echo ($user_filter == "on"?" user_filter_active":"")?>">
							<?php if (isset($count_search_attrs) && $count_search_attrs > 1) {?>
								<option value="-1"><?php esc_html_e("Select kind of search","wpqa")?></option>
							<?php }
							foreach ($search_attrs as $key => $value) {
								do_action("wpqa_search_attrs_options",$search_attrs,$key,$value,$search_type);
								if ($key == "questions" && isset($search_attrs["questions"]["value"]) && $search_attrs["questions"]["value"] == "questions") {?>
									<option <?php selected((isset($search_type) && $search_type != ""?$search_type:""),"questions")?> value="questions"><?php esc_html_e("Questions","wpqa")?></option>
								<?php }else if ($key == "answers" && isset($search_attrs["answers"]["value"]) && $search_attrs["answers"]["value"] == "answers") {?>
									<option <?php selected((isset($search_type) && $search_type != ""?$search_type:""),"answers")?> value="answers"><?php esc_html_e("Answers","wpqa")?></option>
								<?php }else if ($key == "question-category" && isset($search_attrs["question-category"]["value"]) && $search_attrs["question-category"]["value"] == "question-category") {?>
									<option <?php selected((isset($search_type) && $search_type != ""?$search_type:""),"question-category")?> value="question-category"><?php esc_html_e("Question categories","wpqa")?></option>
								<?php }else if ($key == "question_tags" && isset($search_attrs["question_tags"]["value"]) && $search_attrs["question_tags"]["value"] == "question_tags") {?>
									<option <?php selected((isset($search_type) && $search_type != ""?$search_type:""),"question_tags")?> value="question_tags"><?php esc_html_e("Question tags","wpqa")?></option>
								<?php }else if ($key == "posts" && isset($search_attrs["posts"]["value"]) && $search_attrs["posts"]["value"] == "posts") {?>
									<option <?php selected((isset($search_type) && $search_type != ""?$search_type:""),"posts")?> value="posts"><?php esc_html_e("Posts","wpqa")?></option>
								<?php }else if ($key == "comments" && isset($search_attrs["comments"]["value"]) && $search_attrs["comments"]["value"] == "comments") {?>
									<option <?php selected((isset($search_type) && $search_type != ""?$search_type:""),"comments")?> value="comments"><?php esc_html_e("Comments","wpqa")?></option>
								<?php }else if ($key == "category" && isset($search_attrs["category"]["value"]) && $search_attrs["category"]["value"] == "category") {?>
									<option <?php selected((isset($search_type) && $search_type != ""?$search_type:""),"category")?> value="category"><?php esc_html_e("Post categories","wpqa")?></option>
								<?php }else if ($key == "post_tag" && isset($search_attrs["post_tag"]["value"]) && $search_attrs["post_tag"]["value"] == "post_tag") {?>
									<option <?php selected((isset($search_type) && $search_type != ""?$search_type:""),"post_tag")?> value="post_tag"><?php esc_html_e("Post tags","wpqa")?></option>
								<?php }else if ($key == "users" && isset($search_attrs["users"]["value"]) && $search_attrs["users"]["value"] == "users") {?>
									<option <?php selected((isset($search_type) && $search_type != ""?$search_type:""),"users")?> value="users"><?php esc_html_e("Users","wpqa")?></option>
								<?php }else if ($key == "groups" && isset($search_attrs["groups"]["value"]) && $search_attrs["groups"]["value"] == "groups") {?>
									<option <?php selected((isset($search_type) && $search_type != ""?$search_type:""),"groups")?> value="groups"><?php esc_html_e("Groups","wpqa")?></option>
								<?php }
							}?>
						</select>
					</span>
				</div>
			<?php }
			if ($user_filter == "on" && isset($first_one_search) && isset($search_attrs["users"]["value"]) && $search_attrs["users"]["value"] == "users") {
				$user_sort = (isset($_GET["user_filter"]) && $_GET["user_filter"] != ""?esc_html($_GET["user_filter"]):"user_registered");
				echo '<div class="col '.($search_class == "col6"?"col6":"col4").' user-filter-div'.(isset($search_type) && $search_type == "users"?" user-filter-show":"").'">
					<span class="styled-select user-filter">
						<select'.(isset($search_type) && $search_type == "users"?' name="user_filter"':'').'>
							<option value="user_registered" '.selected((isset($user_sort) && $user_sort != ""?$user_sort:""),"user_registered",false).'>'.esc_html__('Date Registered','wpqa').'</option>
							<option value="display_name" '.selected((isset($user_sort) && $user_sort != ""?$user_sort:""),"display_name",false).'>'.esc_html__('Name','wpqa').'</option>
							<option value="ID" '.selected((isset($user_sort) && $user_sort != ""?$user_sort:""),"ID",false).'>'.esc_html__('ID','wpqa').'</option>
							<option value="question_count" '.selected((isset($user_sort) && $user_sort != ""?$user_sort:""),"question_count",false).'>'.esc_html__('Questions','wpqa').'</option>
							<option value="answers" '.selected((isset($user_sort) && $user_sort != ""?$user_sort:""),"answers",false).'>'.esc_html__('Answers','wpqa').'</option>
							<option value="the_best_answer" '.selected((isset($user_sort) && $user_sort != ""?$user_sort:""),"the_best_answer",false).'>'.esc_html__('Best Answers','wpqa').'</option>';
							if ($active_points == "on") {
								echo '<option value="points" '.selected((isset($user_sort) && $user_sort != ""?$user_sort:""),"points",false).'>'.esc_html__('Points','wpqa').'</option>';
							}
							echo '<option value="post_count" '.selected((isset($user_sort) && $user_sort != ""?$user_sort:""),"post_count",false).'>'.esc_html__('Posts','wpqa').'</option>
							<option value="comments" '.selected((isset($user_sort) && $user_sort != ""?$user_sort:""),"comments",false).'>'.esc_html__('Comments','wpqa').'</option>
						</select>
					</span>
				</div>';
			}?>
		</div>
		<div class="wpqa_form">
			<input type="submit" class="button-default" value="<?php esc_attr_e('Search','wpqa')?>">
		</div>
	</form>
</div>
<?php do_action("wpqa_after_search")?>