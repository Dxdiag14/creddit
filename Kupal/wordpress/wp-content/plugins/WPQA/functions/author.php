<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Author */
if (!function_exists('wpqa_author')) :
	function wpqa_author($author_id,$author_page = "",$owner = "",$type_post = "",$widget = "",$class = "",$cover = "",$category = "",$show_icon = "",$specific_time = "",$group = "",$group_type = "approve_decline",$blocked_users = array(),$group_moderators = array(),$group_author = "") {
		$type_post = ($category !== ""?"points":$type_post);
		if (isset($author_id) && $author_id > 0) {
			if ($cover == "") {
				$active_points = wpqa_options("active_points");
				
				if ($author_page == "grid" || $author_page == "grid_pop" || $author_page == "small" || $author_page == "simple_follow" || $author_page == "columns" || $author_page == "columns_pop") {
					/* questions */
					$questions_count = wpqa_count_posts_by_user($author_id,"question","publish",($category !== ""?$category:0));

					/* answers */
					$answers_count = count(get_comments(array("post_type" => "question","status" => "approve","user_id" => $author_id)));
					
					/* the_best_answer */
					$the_best_answer = count(get_comments(array('user_id' => $author_id,"status" => "approve",'post_type' => 'question',"meta_query" => array('relation' => 'AND',array("key" => "best_answer_comment","compare" => "=","value" => "best_answer_comment"),array("key" => "answer_question_user","compare" => "NOT EXISTS")))));
					
					/* points */
					$start_of_week = get_option("start_of_week");
					if ($start_of_week == 0) {
						$start_of_week = "Sunday";
					}else if ($start_of_week == 1) {
						$start_of_week = "Monday";
					}else if ($start_of_week == 2) {
						$start_of_week = "Tuesday";
					}else if ($start_of_week == 3) {
						$start_of_week = "Wednesday";
					}else if ($start_of_week == 4) {
						$start_of_week = "Thursday";
					}else if ($start_of_week == 5) {
						$start_of_week = "Friday";
					}else if ($start_of_week == 6) {
						$start_of_week = "Saturday";
					}
					$points_type = "points";
					if ($specific_time == "day") {
						$points_type = "points_date_".date("j-n-Y");
					}else if ($specific_time == "week") {
						$points_type = "points_date_".date("Y-m-d H:i:s",strtotime($start_of_week.' this week'));
					}else if ($specific_time == "month") {
						$points_type = "points_date_".date("n-Y");
					}else if ($specific_time == "year") {
						$points_type = "points_date_".date("Y");
					}
					$points = (int)get_user_meta($author_id,$points_type,true);
					if ($category !== "") {
						$points_type = "points_category".$category;
						if ($specific_time == "day") {
							$points_type = "points_category".$category."_date_".date("j-n-Y");
						}else if ($specific_time == "week") {
							$points_type = "points_category".$category."_date_".date("Y-m-d H:i:s",strtotime($start_of_week.' this week'));
						}else if ($specific_time == "month") {
							$points_type = "points_category".$category."_date_".date("n-Y");
						}else if ($specific_time == "year") {
							$points_type = "points_category".$category."_date_".date("Y");
						}
						$points_category_user = ($category !== ""?(int)get_user_meta($author_id,$points_type,true):"");
						$points = $points_category_user;
					}
					
					/* posts */
					$posts_count = wpqa_count_posts_by_user($author_id,"post");
					
					/* comments */
					$comments_count = count(get_comments(array("post_type" => "post","status" => "approve","user_id" => $author_id)));
				}
			}
			$author_display_name = get_the_author_meta("display_name",$author_id);
			$out = '<div class="post-section user-area user-area-'.$author_page.($author_page == "advanced"?" user-advanced":"").($class != ""?" ".$class:"").'">
				<div class="post-inner">';
					if ($cover == "") {
						if ($author_page == "advanced") {
							$out .= '<div class="user-head-area">';
						}
						if ($author_page == "advanced") {
							$message_button = wpqa_message_button($author_id,$cover,$owner);
							$out .= $message_button;
						}
						
						$out .= wpqa_get_avatar_link(array("user_id" => $author_id,"user_name" => $author_display_name,"size" => apply_filters("wpqa_filter_avatar_size",($author_page == "small"?42:($author_page == "columns" || $author_page == "columns_pop"?70:84)),$author_page,$class),"span" => "span"));
						if ($author_page == "advanced" && (isset($message_button) && $message_button != "") && $owner == false) {
							$out .= wpqa_following($author_id,"",$owner,"login");
						}
						if ($author_page == "advanced") {
							$out .= '</div>';
						}
					}
					$credential = get_the_author_meta('profile_credential',$author_id);
					$privacy_credential = wpqa_check_user_privacy($author_id,"credential");
					$out .= '<div class="user-content">
						<div class="user-inner">';
							if ($author_page == "columns" || $author_page == "columns_pop") {
								$out .= '<div class="user-data-columns">';
							}
							
							if ($cover == "") {
								$out .= '<h4><a href="'.esc_url(wpqa_profile_url($author_id)).'">'.$author_display_name.'</a>'.wpqa_verified_user($author_id).'</h4>';
								
								if ($privacy_credential == true && $credential != "" && $author_page != "small_grid" && $author_page != "grid" && $author_page != "grid_pop" && $author_page != "small" && $author_page != "simple_follow" && $author_page != "columns" && $author_page != "columns_pop") {
									$out .= '<span class="profile-credential">'.esc_html($credential).'</span>';
								}
								
								if ($author_page != "grid_pop" && $author_page != "small" && $author_page != "columns_pop") {
									$active_points_category = wpqa_options("active_points_category");
									if ($active_points_category != "on") {
										$out .= wpqa_get_badge($author_id);
									}
								}
								
								if ($author_page == "columns_pop") {
									$country = get_the_author_meta('country',$author_id);
									$city    = get_the_author_meta('city',$author_id);
									$get_countries = apply_filters('wpqa_get_countries',false);
									$privacy_country = wpqa_check_user_privacy($author_id,"country");
									$privacy_city = wpqa_check_user_privacy($author_id,"city");
									$privacy_credential = wpqa_check_user_privacy($author_id,"credential");
									if (($privacy_credential == true && isset($credential) && $credential != "") || ($privacy_city == true && isset($city) && $city != "") || ($privacy_country == true && isset($country) && $country != "" && isset($get_countries[$country]))) {
										$out .= '<div class="user-data">
											<ul>';
												if ($privacy_credential == true && isset($credential) && $credential != "") {
													$out .= '<li class="profile-credential">
														'.esc_html($credential).'
													</li>';
												}else if (($privacy_city == true && isset($city) && $city != "") || ($privacy_country == true && isset($country) && $country != "" && isset($get_countries[$country]))) {
													$out .= '<li class="city-country">
														<i class="icon-location"></i>
														'.(isset($city) && $city != ""?esc_html($city).", ":"").(isset($country) && $country != "" && isset($get_countries[$country])?$get_countries[$country]:"").'
													</li>';
												}
											$out .= '</ul>
										</div>';
									}
								}
								
								if ($author_page == "columns" || $author_page == "columns_pop") {
									$out .= '</div>';
								}
							}
							
							if ($author_page != "small_grid" && $author_page != "grid" && $author_page != "grid_pop" && $author_page != "small" && $author_page != "simple_follow" && $author_page != "columns" && $author_page != "columns_pop") {
								$meta_description = get_user_meta($author_id,"description",true);
								if ($meta_description != "") {
									$privacy_bio = wpqa_check_user_privacy($author_id,"bio");
									if ($privacy_bio != "") {
										$bio_editor = wpqa_options("bio_editor");
										if ($bio_editor == "on") {
											$out .= '<div class="bio_editor">'.wpqa_kses_stip(nl2br(stripslashes($meta_description),"yes")).'</div>';
										}else {
											$out .= '<p>'.nl2br($meta_description).'</p>';
										}
									}
								}
							}
							
							if ($author_page == "advanced") {
								/* user data */
								$country    = get_the_author_meta('country',$author_id);
								$city       = get_the_author_meta('city',$author_id);
								$age        = get_the_author_meta('age',$author_id);
								$phone      = get_the_author_meta('phone',$author_id);
								$gender     = get_the_author_meta('gender',$author_id);
								$url        = get_the_author_meta('url',$author_id);
								$credential = get_the_author_meta('profile_credential',$author_id);
								$privacy_country = wpqa_check_user_privacy($author_id,"country");
								$privacy_city = wpqa_check_user_privacy($author_id,"city");
								$privacy_age = wpqa_check_user_privacy($author_id,"age");
								$privacy_phone = wpqa_check_user_privacy($author_id,"phone");
								$privacy_gender = wpqa_check_user_privacy($author_id,"gender");
								$privacy_credential = wpqa_check_user_privacy($author_id,"credential");
								$privacy_website = wpqa_check_user_privacy($author_id,"website");
								if (($privacy_credential == true && isset($credential) && $credential != "") || ($privacy_city == true && isset($city) && $city != "") || ($privacy_country == true && isset($country) && $country != "") || ($privacy_phone == true && isset($phone) && $phone != "") || ($privacy_website == true && isset($url) && $url != "") || ($privacy_gender == true && isset($gender) && $gender != "") || ($privacy_age == true && isset($age) && $age != "")) {
									$out .= '<div class="user-data">
										<ul>';
											$out .= apply_filters("wpqa_add_user_data_filter",false,$author_id);
											$get_countries = apply_filters('wpqa_get_countries',false);
											if (($privacy_city == true && isset($city) && $city != "") || ($privacy_country == true && isset($country) && $country != "" && isset($get_countries[$country]))) {
												$out .= '<li class="city-country">
													<i class="icon-location"></i>
													'.($privacy_city == true && isset($city) && $city != ""?$city.", ":"").($privacy_country == true && isset($country) && $country != "" && isset($get_countries[$country])?$get_countries[$country]:"").'
												</li>';
											}
											$show_phone_profile = apply_filters("wpqa_show_phone_profile",true);
											if ($privacy_phone == true && isset($phone) && $phone != "" && $show_phone_profile == true) {
												$out .= '<li class="user-phone">
													<i class="icon-phone"></i>
													'.apply_filters("wpqa_show_phone",esc_attr($phone),$author_id).'
												</li>';
											}
											if ($privacy_website == true && isset($url) && $url != "") {
												$out .= '<li class="user-url">
													<a href="'.esc_url($url).'">
														<i class="icon-link"></i>
														'.esc_html__("Visit site","wpqa").'
													</a>
												</li>';
											}
											if ($privacy_gender == true && isset($gender) && $gender != "") {
												$out .= '<li class="user-gender">
													<i class="icon-heart"></i>
													'.($gender == "male" || $gender == 1?esc_html__("Male","wpqa"):"").($gender == "female" || $gender == 2?esc_html__("Female","wpqa"):"").($gender == "other" || $gender == 3?esc_html__("Other","wpqa"):"").'
												</li>';
											}
											if ($privacy_age == true && isset($age) && $age != "") {
												$age = (date_create($age)?date_diff(date_create($age),date_create('today'))->y:"");
												$out .= '<li class="user-age">
													<i class="icon-globe"></i>
													'.esc_attr($age)." ".esc_html__("years old","wpqa").'
												</li>';
											}
										$out .= '</ul>
									</div><!-- End user-data -->';
								}
								$out .= apply_filters("wpqa_profile_advanced_filter",false,$author_id);
							}
							
							if ($author_page == "grid" || $author_page == "grid_pop" || $author_page == "small" || $author_page == "simple_follow") {
								$out .= '<div class="user-data">
									<ul>';
										if ($type_post == "post" || $type_post == "comments") {
											if ((($show_icon == 'on' && $widget == 'widget' && $type_post == "post") || $show_icon != "on")) {
												$out .= '<li class="user-posts">
													<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"posts")).'">
														'.($widget == 'widget' && $show_icon != 'on'?'':'<i class="icon-book-open"></i>').'
														'.wpqa_count_number($posts_count).' '._n("Post","Posts",$questions_count,"wpqa").'
													</a>
												</li>';
											}
											if ((($show_icon == 'on' && $widget == 'widget' && $type_post == "comments") || $show_icon != "on")) {
												$out .= '<li class="user-comments">
													<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"comments")).'">
														'.($widget == 'widget' && $show_icon != 'on'?'':'<i class="icon-comment"></i>').'
														'.wpqa_count_number($comments_count).' '._n("Comment","Comments",$comments_count,"wpqa").'
													</a>
												</li>';
											}
										}else {
											if ((($show_icon == 'on' && $widget == 'widget' && $type_post == "question_count") || $show_icon != "on")) {
												$out .= '<li class="user-questions">
													<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"questions")).'">
														'.($widget == 'widget' && $show_icon != 'on'?'':'<i class="icon-book-open"></i>').'
														'.wpqa_count_number($questions_count).' '._n("Question","Questions",$questions_count,"wpqa").'
													</a>
												</li>';
											}
											if ($type_post == "the_best_answer" && (($show_icon == 'on' && $widget == 'widget') || $show_icon != "on")) {
												$out .= '<li class="user-best-answers">
													<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"best_answers")).'">
														'.($widget == 'widget' && $show_icon != 'on'?'':'<i class="icon-graduation-cap"></i>').'
														'.($the_best_answer == ""?0:wpqa_count_number($the_best_answer)).' '._n("Best Answer","Best Answers",$the_best_answer,"wpqa").'
													</a>
												</li>';
											}else if ($type_post == "points" && $active_points == "on" && (($show_icon == 'on' && $widget == 'widget') || $show_icon != "on")) {
												$out .= '<li class="user-points">
													<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"points")).'">
														'.($widget == 'widget' && $show_icon != 'on'?'':'<i class="icon-bucket"></i>').'
														'.($points == ""?0:wpqa_count_number($points)).' '._n("Point","Points",$points,"wpqa").'
													</a>
												</li>';
											}else if ((($show_icon == 'on' && $widget == 'widget' && $type_post == "answers") || $show_icon != "on")) {
												$out .= '<li class="user-answers">
													<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"answers")).'">
														'.($widget == 'widget' && $show_icon != 'on'?'':'<i class="icon-comment"></i>').'
														'.wpqa_count_number($answers_count).' '._n("Answer","Answers",$answers_count,"wpqa").'
													</a>
												</li>';
											}
										}
									$out .= '</ul>
								</div><!-- End user-data -->';
								
								if ($widget == "widget") {
									if ($category !== "") {
										$out .= apply_filters("wpqa_widget_before_badge",false,$category);
									}
									$out .= wpqa_get_badge($author_id,"",(isset($points_category_user) && $points_category_user !== ""?$points_category_user:""));
								}
							}
							
							if (($author_page == "small_grid" || $author_page == "simple_follow") && $owner == false) {
								$out .= wpqa_following($author_id,($author_page == "small_grid"?"style_4":""),$owner);
							}
						$out .= '</div>';
						
						if ($author_page != "small_grid" && $author_page != "grid" && $author_page != "grid_pop" && $author_page != "small" && $author_page != "simple_follow" && $author_page != "columns" && $author_page != "columns_pop") {
							$twitter    = get_the_author_meta('twitter',$author_id);
							$facebook   = get_the_author_meta('facebook',$author_id);
							$linkedin   = get_the_author_meta('linkedin',$author_id);
							$youtube    = get_the_author_meta('youtube',$author_id);
							$vimeo      = get_the_author_meta('vimeo',$author_id);
							$pinterest  = get_the_author_meta('pinterest',$author_id);
							$instagram  = get_the_author_meta('instagram',$author_id);
							$user_email = get_the_author_meta('email',$author_id);
							$privacy_email = wpqa_check_user_privacy($author_id,"email");
							$privacy_social = wpqa_check_user_privacy($author_id,"social");

							$get_current_user_id = get_current_user_id();
							$is_super_admin      = is_super_admin($get_current_user_id);
							$active_moderators   = wpqa_options("active_moderators");
							if ($active_moderators == "on") {
								$moderator_categories = get_user_meta($get_current_user_id,prefix_author."moderator_categories",true);
								$moderator_categories = (is_array($moderator_categories) && !empty($moderator_categories)?$moderator_categories:array());
								$pending_posts = (($is_super_admin || $active_moderators == "on") && ($is_super_admin || (isset($moderator_categories) && is_array($moderator_categories) && !empty($moderator_categories)))?true:false);
								$moderators_permissions = wpqa_user_moderator($get_current_user_id);
								$if_user_id = get_user_by("id",$author_id);
							}
							if (($active_moderators == "on" && isset($if_user_id->roles[0]) && $if_user_id->roles[0] != "administrator" && $pending_posts == true && ($is_super_admin || (isset($moderators_permissions['ban']) && $moderators_permissions['ban'] == "ban"))) || ($privacy_email == true && $user_email != "") || ($privacy_social == true && ($facebook || $twitter || $linkedin || $youtube || $vimeo || $pinterest || $instagram)) || ($cover == "" && (!isset($message_button) || (isset($message_button) && $message_button == "")))) {
								$out .= '<div class="social-ul">
									<ul>';
										if ($author_page != "single-author" && ($cover == "" && (!isset($message_button) || (isset($message_button) && $message_button == ""))) && $owner == false) {
											$out .= '<li class="social-follow">'.wpqa_following($author_id,"style_3",$owner).'</li>';
										}
										if ($author_page != "single-author" && $cover == "" && $active_moderators == "on" && isset($if_user_id->roles[0]) && $if_user_id->roles[0] != "administrator" && $pending_posts == true && ($is_super_admin || (isset($moderators_permissions['ban']) && $moderators_permissions['ban'] == "ban"))) {
											$out .= "<li class='ban-unban-user'><span class='small_loader loader_2'></span><a class='".(isset($if_user_id->caps["ban_group"]) && $if_user_id->caps["ban_group"] == 1?"unban-user":"ban-user")."' data-nonce='".wp_create_nonce("ban_nonce")."' href='#' data-id='".$author_id."'><span>".(isset($if_user_id->caps["ban_group"]) && $if_user_id->caps["ban_group"] == 1?esc_html__("Unban user","wpqa"):esc_html__("Ban user","wpqa"))."</span></a></li>";
										}
										if ($privacy_social == true) {
											if ($facebook) {
												$out .= '<li class="social-facebook"><a title="Facebook" class="tooltip-n" href="'.esc_url($facebook).'" target="_blank"><i class="icon-facebook"></i></a></li>';
											}
											if ($twitter) {
												$out .= '<li class="social-twitter"><a title="Twitter" class="tooltip-n" href="'.esc_url($twitter).'" target="_blank"><i class="icon-twitter"></i></a></li>';
											}
											if ($linkedin) {
												$out .= '<li class="social-linkedin"><a title="Linkedin" class="tooltip-n" href="'.esc_url($linkedin).'" target="_blank"><i class="icon-linkedin"></i></a></li>';
											}
											if ($pinterest) {
												$out .= '<li class="social-pinterest"><a title="Pinterest" class="tooltip-n" href="'.esc_url($pinterest).'" target="_blank"><i class="icon-pinterest"></i></a></li>';
											}
											if ($instagram) {
												$out .= '<li class="social-instagram"><a title="Instagram" class="tooltip-n" href="'.esc_url($instagram).'" target="_blank"><i class="icon-instagrem"></i></a></li>';
											}
											if ($youtube) {
												$out .= '<li class="social-youtube"><a title="Youtube" class="tooltip-n" href="'.esc_url($youtube).'" target="_blank"><i class="icon-play"></i></a></li>';
											}
											if ($vimeo) {
												$out .= '<li class="social-vimeo"><a title="Vimeo" class="tooltip-n" href="'.esc_url($vimeo).'" target="_blank"><i class="icon-vimeo"></i></a></li>';
											}
										}
										if ($privacy_email == true && $user_email != "") {
											$out .= '<li class="social-email"><a title="'.esc_html__("Email","wpqa").'" class="tooltip-n" href="mailto:'.esc_attr($user_email).'" target="_blank" rel="nofollow"><i class="icon-mail"></i></a></li>';
										}
									$out .= '</ul>
								</div><!-- End social-ul -->';
							}
						}
					$out .= '</div><!-- End user-content -->';
					
					if ($author_page == "grid_pop" && $owner == false) {
						$out .= wpqa_following($author_id,"",$owner);
					}
					
					if ($cover == "" && $owner == false && $author_page != "single-author" && $author_page != "small_grid" && $author_page != "grid" && $author_page != "grid_pop" && $author_page != "small" && $author_page != "simple_follow" && $author_page != "columns" && $author_page != "columns_pop") {
						$ask_question_to_users = wpqa_options("ask_question_to_users");
						$breadcrumbs = wpqa_options("breadcrumbs");
						if ($ask_question_to_users == "on" && $breadcrumbs != "on") {
							$out .= '<div class="ask-question ask-user-after-social"><a href="'.esc_url(wpqa_add_question_permalink("user")).'" class="button-default ask-question-user">'.esc_html__("Ask","wpqa")." ".$author_display_name.'</a></div>';
						}
					}
					
					if ($author_page == "columns" || $author_page == "columns_pop") {
						$out .= '<div class="user-columns-data">
							<ul>';
								if ($type_post == "post" || $type_post == "comments") {
									$out .= '<li class="user-columns-posts">
										<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"posts")).'">
											<i class="icon-book-open"></i>'.($posts_count == ""?0:wpqa_count_number($posts_count)).' '._n("Post","Posts",$posts_count,"wpqa").'
										</a>
									</li>
									<li class="user-columns-comments">
										<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"comments")).'">
											<i class="icon-comment"></i>'.($comments_count == ""?0:wpqa_count_number($comments_count)).' '._n("Comment","Comments",$comments_count,"wpqa").'
										</a>
									</li>';
								}else {
									$out .= '<li class="user-columns-questions">
										<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"questions")).'">
											<i class="icon-book-open"></i>'.($questions_count == ""?0:wpqa_count_number($questions_count)).' '._n("Question","Questions",$questions_count,"wpqa").'
										</a>
									</li>
									<li class="user-columns-answers">
										<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"answers")).'">
											<i class="icon-comment"></i>'.($answers_count == ""?0:wpqa_count_number($answers_count)).' '._n("Answer","Answers",$answers_count,"wpqa").'
										</a>
									</li>';
								}
								$out .= '<li class="user-columns-best-answers">
									<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"best_answers")).'">
										<i class="icon-graduation-cap"></i>'.($the_best_answer == ""?0:wpqa_count_number($the_best_answer)).' '._n("Best Answer","Best Answers",$the_best_answer,"wpqa").'
									</a>
								</li>';
								if ($active_points == "on") {
									$out .= '<li class="user-columns-points">
										<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"points")).'">
											<i class="icon-bucket"></i>'.($points == ""?0:wpqa_count_number($points)).' '._n("Point","Points",$points,"wpqa").'
										</a>
									</li>';
								}
							$out .= '</ul>
						</div><!-- End user-columns-data -->';
						$out .= '<div class="user-follow-profile">';
							if ($group > 0) {
								if ($group_type == "approve_decline") {
									$out .= '<div class="group_approve_decline">
										<div class="cover_loader wpqa_hide"><div class="small_loader loader_2"></div></div>
										<a href="#" class="button-default approve_request_group" data-group="'.$group.'" data-user="'.$author_id.'">'.esc_html__("Approve","wpqa").'</a>
										<a href="#" class="button-default decline_request_group" data-group="'.$group.'" data-user="'.$author_id.'">'.esc_html__("Decline","wpqa").'</a>
									</div>';
								}else if ($group_type == "block" || $group_type == "profile") {
									if ($group_type == "profile" || (isset($group_moderators) && is_array($group_moderators) && in_array($author_id,$group_moderators))) {
										$out .= '<a href="'.wpqa_profile_url($author_id).'">'.esc_html__("View Profile","wpqa").'</a>';
									}else {
										if (isset($blocked_users) && is_array($blocked_users) && in_array($author_id,$blocked_users)) {
											$out .= '<div class="group_unblock">
												<div class="cover_loader wpqa_hide"><div class="small_loader loader_2"></div></div>
												<a href="#" class="button-default unblock_user_group" data-group="'.$group.'" data-user="'.$author_id.'">'.esc_html__("Unblock","wpqa").'</a>';
										}else {
											$out .= '<div class="group_block_remove">
												<div class="cover_loader wpqa_hide"><div class="small_loader loader_2"></div></div>
												<a href="#" class="button-default remove_user_group" data-group="'.$group.'" data-user="'.$author_id.'">'.esc_html__("Remove","wpqa").'</a>
												<a href="#" class="button-default block_user_group" data-group="'.$group.'" data-user="'.$author_id.'">'.esc_html__("Block","wpqa").'</a>';
										}
										$out .= '</div>';
									}
								}else if ($group_type == "moderators") {
									if (!is_super_admin($author_id) && $group_author != $author_id && isset($group_moderators) && is_array($group_moderators) && in_array($author_id,$group_moderators)) {
										$out .= '<a href="#" class="button-default remove_moderator_group" data-group="'.$group.'" data-user="'.$author_id.'">'.esc_html__("Remove moderator","wpqa").'</a>';
									}else {
										$out .= '<a href="'.wpqa_profile_url($author_id).'">'.esc_html__("View Profile","wpqa").'</a>';
									}
								}
							}else {
								if ($owner == false) {
									$out .= wpqa_following($author_id,"style_2",$owner);
								}
								$out .= '<a href="'.wpqa_profile_url($author_id).'">'.esc_html__("View Profile","wpqa").'</a>';
							}
						$out .= '</div><!-- End user-follow-profile -->';
					}
					
					$out .= '<div class="clearfix"></div>
				</div><!-- End post-inner -->
			</div><!-- End post -->';
			
			if ($author_page == "grid_pop") {
				$out .= '<div class="user-data">
					<ul>
						<li class="user-best-answers">
							<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"best_answers")).'">
								<i class="icon-graduation-cap"></i>
								'.($the_best_answer == ""?0:wpqa_count_number($the_best_answer)).' '._n("Best Answer","Best Answers",$the_best_answer,"wpqa").'
							</a>
						</li>';
						if ($active_points == "on") {
							$out .= '<li class="user-points">
								<a href="'.esc_url(wpqa_get_profile_permalink($author_id,"points")).'">
									<i class="icon-bucket"></i>
									'.($points == ""?0:wpqa_count_number($points)).' '._n("Point","Points",$points,"wpqa").'
								</a>
							</li>';
						}
					$out .= '</ul>
				</div><!-- End user-data -->';
			}
			
			return $out;
		}
	}
endif;
/* Message button */
if (!function_exists('wpqa_message_button')) :
	function wpqa_message_button($author_id,$text = "",$owner = "",$return = "") {
		$out = "";
		$active_message = wpqa_options("active_message");
		if ($active_message == "on" && $owner == false) {
			$send_message_no_register = wpqa_options("send_message_no_register");
			$received_message = esc_attr(get_user_meta($author_id,'received_message',true));
			$user_id = get_current_user_id();
			$is_super_admin = is_super_admin($user_id);
			$block_message = esc_attr(get_user_meta($user_id,'block_message',true));
			$user_block_message = array();
			if (is_user_logged_in()) {
				$user_block_message = get_user_meta($author_id,"user_block_message",true);
				$user_is_login = get_userdata($user_id);
				$roles = $user_is_login->allcaps;
			}
			$custom_permission = wpqa_options("custom_permission");
			$send_message = wpqa_options("send_message");
			if ($is_super_admin || $custom_permission != "on" || ($custom_permission == "on" && (is_user_logged_in() && !$is_super_admin && isset($roles["send_message"])) || (!is_user_logged_in() && $send_message == "on"))) {
				if ($is_super_admin || (((!is_user_logged_in() && $send_message_no_register == "on") || is_user_logged_in()) && $block_message != "on" && $received_message == "on" && (empty($user_block_message) || (isset($user_block_message) && is_array($user_block_message) && !in_array($user_id,$user_block_message))))) {
					$out .= '<div class="'.($text != ""?'send_message_text':'send_message_icon').'"><a href="#" title="'.esc_html__("Send Message","wpqa").'" class="wpqa-message tooltip-n'.($text != ""?' button-default':'').'">'.($text != ""?esc_html__("Message","wpqa"):'<i class="icon-mail"></i>').'</a></div>';
				}
			}
		}
		return $out;
	}
endif;
/* Following */
if (!function_exists('wpqa_following')) :
	function wpqa_following($author_id,$follow_style = "",$owner = "",$login = "") {
		$out = "";
		if ((is_user_logged_in() && $owner == false) || (!is_user_logged_in() && $login == "login")) {
			if (!is_user_logged_in() && $login == "login") {
				$out .= '<div class="user_follow"><a href="'.wpqa_login_permalink().'" class="login-panel'.apply_filters('wpqa_pop_up_class_login','').' tooltip-n" title="'.esc_attr__("Login","wpqa").'"><i class="icon-plus"></i></a></div>';
			}else {
				$following_me = get_user_meta(get_current_user_id(),"following_me",true);
				if (isset($following_me)) {
					if ($follow_style == "style_2") {
						$following_you = get_user_meta($author_id,"following_you",true);
					}
					$out .= '<div class="user_follow'.($follow_style == "style_2"?"_2":"").($follow_style == "style_3"?"_3":"").($follow_style == "style_4"?"_4":"").(!empty($following_me) && in_array($author_id,$following_me)?($follow_style == "style_4"?" user_follow_done":" user_follow_yes"):"").'">
						<div class="small_loader loader_2'.($follow_style == "style_2"?" user_follow_loader":"").'"></div>';
						if (!empty($following_me) && in_array($author_id,$following_me)) {
							$out .= '<a href="#" class="following_not'.($follow_style == "style_2" || $follow_style == "style_3" || $follow_style == "style_4"?"":" tooltip-n").($follow_style == "style_4"?" button-default":"").'" data-rel="'.(int)$author_id.'" title="'.esc_attr__("Unfollow","wpqa").'">';
								if ($follow_style == "style_2" || $follow_style == "style_3" || $follow_style == "style_4") {
									$out .= '<span class="follow-value">'.esc_html__("Unfollow","wpqa").'</span>';
									if ($follow_style == "style_2") {
										$out .= '<span class="follow-count">'.($following_you == ""?0:wpqa_count_number($following_you)).'</span>';
									}
								}else {
									$out .= '<i class="icon-minus"></i>';
								}
							$out .= '</a>';
						}else {
							$out .= '<a href="#" class="following_you'.($follow_style == "style_2" || $follow_style == "style_3" || $follow_style == "style_4"?"":" tooltip-n").($follow_style == "style_4"?" button-default":"").'" data-rel="'.(int)$author_id.'" title="'.esc_attr__("Follow","wpqa").'">';
							if ($follow_style == "style_2" || $follow_style == "style_3" || $follow_style == "style_4") {
								$out .= '<span class="follow-value">'.esc_html__("Follow","wpqa").'</span>';
								if ($follow_style == "style_2") {
									$out .= '<span class="follow-count">'.($following_you == ""?0:wpqa_count_number($following_you)).'</span>';
								}
							}else {
								$out .= '<i class="icon-plus"></i>';
							}
							$out .= '</a>';
						}
					$out .= '</div>';
				}
			}
		}
		return $out;
	}
endif;
/* Get verified user */
if (!function_exists('wpqa_verified_user')) :
	function wpqa_verified_user($author_id,$return = "") {
		if ($author_id > 0) {
			$verified_user = get_the_author_meta('verified_user',$author_id);
			if ($verified_user == 1 || $verified_user == "on") {
				return '<span class="verified_user tooltip-n" title="'.esc_html__("Verified","wpqa").'"><i class="icon-check"></i></span>';
			}
		}
	}
endif;
/* Get badge */
if (!function_exists('wpqa_get_badge')) :
	function wpqa_get_badge($author_id,$return = "",$points = "",$category_points = "") {
		if ($category_points == "category_points") {
			$active_points_category = wpqa_options("active_points_category");
			if ($active_points_category == "on") {
				$categories_user_points = get_user_meta($author_id,"categories_user_points",true);
				if (is_array($categories_user_points) && !empty($categories_user_points)) {
					foreach ($categories_user_points as $category) {
						$points_category_user[$category] = (int)get_user_meta($author_id,"points_category".$category,true);
					}
					arsort($points_category_user);
					$first_category = (is_array($points_category_user)?key($points_category_user):"");
					$first_points = reset($points_category_user);
				}
			}
			$points = (isset($first_points)?$first_points:$points);
		}
		$badges_style = wpqa_options("badges_style");
		$author_id = (int)$author_id;
		if ($badges_style == "by_groups_points") {
			if ($author_id > 0) {
				$points = (int)($points !== ""?$points:get_user_meta($author_id,"points",true));
				$badges_groups_points = wpqa_options("badges_groups_points");
				$badges_groups_points = (is_array($badges_groups_points) && !empty($badges_groups_points)?$badges_groups_points:array());
				$points_badges = array_column($badges_groups_points,'badge_points');
				$points_badges = (is_array($points_badges) && !empty($points_badges)?$points_badges:array());
				if (is_array($points_badges) && !empty($points_badges) && is_array($badges_groups_points) && !empty($badges_groups_points)) {
	    			array_multisort($points_badges,SORT_ASC,$badges_groups_points);
	    		}
				$user_info = get_userdata($author_id);
				$group_key = (is_array($user_info->caps)?key($user_info->caps):"");
				if (isset($badges_groups_points) && is_array($badges_groups_points)) {
					$badges_groups_points = array_values($badges_groups_points);
					foreach ($badges_groups_points as $badges_k => $badges_v) {
						if ($badges_v["badge_group"] == $group_key) {
							$badges_points[] = $badges_v;
						}
						
					}
					if (isset($badges_points) && is_array($badges_points)) {
						foreach ($badges_points as $key => $badge_point) {
							if ($points >= $badge_point["badge_points"]) {
								$last_key = $key;
							}
						}
					}
					if (isset($last_key)) {
						$badge_key = $last_key;
						if ($return == "points") {
							return (isset($badges_points[$badge_key]["badge_points"])?$badges_points[$badge_key]["badge_points"]:"");
						}else if ($return == "color") {
							return (isset($badges_points[$badge_key]["badge_color"])?$badges_points[$badge_key]["badge_color"]:"");
						}else if ($return == "name") {
							return (isset($badges_points[$badge_key]["badge_name"])?strip_tags(stripslashes($badges_points[$badge_key]["badge_name"]),"<i>"):"");
						}else if ($return == "key") {
							return $badge_key;
						}else if ($return == "first_key") {
							$first_badge = (isset($badges_points) && is_array($badges_points)?reset($badges_points):array());
							return (isset($first_badge['badge_points']) && $first_badge['badge_points'] == $badges_points[$badge_key]["badge_points"]?$badge_key:"");
						}else {
							return apply_filters('wpqa_by_groups_points','<span class="badge-span" style="background-color: '.(isset($badges_points[$badge_key]["badge_color"])?$badges_points[$badge_key]["badge_color"]:"").'">'.(isset($badges_points[$badge_key]["badge_name"])?strip_tags(stripslashes($badges_points[$badge_key]["badge_name"]),"<i>"):"").'</span>',$author_id);
						}
					}
				}
			}
		}else if ($badges_style == "by_groups") {
			if ($author_id > 0) {
				$badges_groups = wpqa_options("badges_groups");
				$badges_groups = (is_array($badges_groups) && !empty($badges_groups)?$badges_groups:array());
				$points_badges = array_column($badges_groups,'badge_points');
				$points_badges = (is_array($points_badges) && !empty($points_badges)?$points_badges:array());
				if (is_array($points_badges) && !empty($points_badges) && is_array($badges_groups) && !empty($badges_groups)) {
	    			array_multisort($points_badges,SORT_ASC,$badges_groups);
	    		}
				$user_info = get_userdata($author_id);
				$group_key = (is_array($user_info->caps)?key($user_info->caps):"");
				if (isset($badges_groups) && is_array($badges_groups)) {
					global $wp_roles;
					$badges_groups = array_values($badges_groups);
					$found_key = array_search($group_key,array_column($badges_groups,'badge_name'));
					$user_group = $user_info->roles[0];
					$user_group = $wp_roles->roles[$user_group]["name"];
					if ($return == "color") {
						return $badges_groups[$found_key]["badge_color"];
					}else if ($return == "name") {
						return $user_group;
					}else if ($return == "key") {
						return $found_key;
					}else if ($return == "first_key") {
						$first_badge = (isset($badges_groups) && is_array($badges_groups)?reset($badges_groups):array());
						return (isset($first_badge['badge_points']) && $first_badge['badge_points'] == $badges_groups[$found_key]["badge_points"]?$found_key:"");
					}else {
						return apply_filters('wpqa_by_groups','<span class="badge-span" style="background-color: '.$badges_groups[$found_key]["badge_color"].'">'.$user_group.'</span>',$author_id);
					}
				}
			}
		}else {
			$active_points = wpqa_options("active_points");
			if ($author_id > 0 && $active_points == "on") {
				$points = (int)($points !== ""?$points:get_user_meta($author_id,"points",true));
				$badges = wpqa_options("badges");
				$badges = (!empty($badges)?array_values($badges):array());
				$points_badges = array_column($badges,'badge_points');
				$points_badges = (is_array($points_badges) && !empty($points_badges)?$points_badges:array());
				if (is_array($points_badges) && !empty($points_badges) && is_array($badges) && !empty($badges)) {
	    			array_multisort($points_badges,SORT_ASC,$badges);
	    		}
				if (isset($badges) && is_array($badges)) {
					foreach ($badges as $badges_k => $badges_v) {
						$badges_points[] = $badges_v["badge_points"];
					}
					if (isset($badges_points) && is_array($badges_points)) {
						foreach ($badges_points as $key => $badge_point) {
							if ($points >= $badge_point) {
								$last_key = $key;
							}
						}
					}
					if (isset($last_key)) {
						$badge_key = $last_key;
						if ($return == "points") {
							return $badges[$badge_key]["badge_points"];
						}else if ($return == "color") {
							return $badges[$badge_key]["badge_color"];
						}else if ($return == "name") {
							return strip_tags(stripslashes($badges[$badge_key]["badge_name"]),"<i>");
						}else if ($return == "key") {
							return $badge_key;
						}else if ($return == "first_key") {
							$first_badge = (isset($badges) && is_array($badges)?reset($badges):array());
							return (isset($first_badge['badge_points']) && $first_badge['badge_points'] == $badges[$badge_key]["badge_points"]?$badge_key:"");
						}else {
							return apply_filters('wpqa_by_points','<span class="badge-span" style="background-color: '.$badges[$badge_key]["badge_color"].'">'.strip_tags(stripslashes($badges[$badge_key]["badge_name"]),"<i>").'</span>',$author_id);
						}
					}
				}
			}
		}
	}
endif;
/* Get the user stats */
if (!function_exists('wpqa_get_user_stats')) :
	function wpqa_get_user_stats($wpqa_user_id,$user_stats,$active_points,$show_point_favorite) {
		do_action("wpqa_action_before_user_stats",$wpqa_user_id);
		/* questions */
		$add_questions = wpqa_count_posts_by_user($wpqa_user_id,"question");

		/* answers */
		$add_answer = count(get_comments(array("post_type" => "question","status" => "approve","user_id" => $wpqa_user_id)));

		/* the_best_answer */
		$the_best_answer = count(get_comments(array('user_id' => $wpqa_user_id,"status" => "approve",'post_type' => 'question',"meta_query" => array('relation' => 'AND',array("key" => "best_answer_comment","compare" => "=","value" => "best_answer_comment"),array("key" => "answer_question_user","compare" => "NOT EXISTS")))));

		/* points */
		$points = (int)get_user_meta($wpqa_user_id,"points",true);
		if ($active_points != "on" && isset($user_stats["points"]) && $user_stats["points"] == "points") {
			unset($user_stats["points"]);
		}
		do_action("wpqa_before_user_stats",$wpqa_user_id);
		if ((isset($user_stats["questions"]) && $user_stats["questions"] == "questions") || (isset($user_stats["answers"]) && $user_stats["answers"] == "answers") || (isset($user_stats["best_answers"]) && $user_stats["best_answers"] == "best_answers") || (isset($user_stats["points"]) && $user_stats["points"] == "points")) {
			if (count($user_stats) == 1) {
				$column_user = "col12";
			}else if (count($user_stats) == 2) {
				$column_user = "col6";
			}else if (count($user_stats) == 3) {
				$column_user = "col4";
			}else {
				$column_user = "col3";
			}?>
			<div class="user-stats">
				<ul class="row">
					<?php if (isset($user_stats["questions"]) && $user_stats["questions"] == "questions") {?>
						<li class="col <?php echo esc_attr($column_user)?> user-questions">
							<div>
								<a href="<?php echo esc_url(wpqa_get_profile_permalink($wpqa_user_id,"questions"))?>"></a>
								<i class="icon-book-open"></i>
								<div>
									<span><?php echo ($add_questions == ""?0:wpqa_count_number($add_questions))?></span>
									<h4><?php echo _n("Question","Questions",$add_questions,"wpqa")?></h4>
								</div>
							</div>
						</li>
					<?php }
					if (isset($user_stats["answers"]) && $user_stats["answers"] == "answers") {?>
						<li class="col <?php echo esc_attr($column_user)?> user-answers">
							<div>
								<a href="<?php echo esc_url(wpqa_get_profile_permalink($wpqa_user_id,"answers"))?>"></a>
								<i class="icon-comment"></i>
								<div>
									<span><?php echo ($add_answer == ""?0:wpqa_count_number($add_answer))?></span>
									<h4><?php echo _n("Answer","Answers",$add_answer,"wpqa")?></h4>
								</div>
							</div>
						</li>
					<?php }
					if (isset($user_stats["best_answers"]) && $user_stats["best_answers"] == "best_answers") {?>
						<li class="col <?php echo esc_attr($column_user)?> user-best-answers">
							<div>
								<a href="<?php echo esc_url(wpqa_get_profile_permalink($wpqa_user_id,"best_answers"))?>"></a>
								<i class="icon-graduation-cap"></i>
								<div>
									<span><?php echo ($the_best_answer == ""?0:wpqa_count_number($the_best_answer))?></span>
									<h4><?php echo _n("Best Answer","Best Answers",$the_best_answer,"wpqa")?></h4>
								</div>
							</div>
						</li>
					<?php }
					if (isset($user_stats["points"]) && $user_stats["points"] == "points") {?>
						<li class="col <?php echo esc_attr($column_user)?> user-points">
							<div>
								<?php if ($show_point_favorite == "on" || wpqa_is_user_owner()) {?>
									<a href="<?php echo esc_url(wpqa_get_profile_permalink($wpqa_user_id,"points"))?>"></a>
								<?php }?>
								<i class="icon-bucket"></i>
								<div>
									<span><?php echo ($points == ""?0:wpqa_count_number($points))?></span>
									<h4><?php echo _n("Point","Points",$points,"wpqa")?></h4>
								</div>
							</div>
						</li>
					<?php }?>
				</ul>
				<?php do_action("wpqa_after_user_stats",$wpqa_user_id);
				$active_points_category = wpqa_options("active_points_category");
				if ($active_points_category == "on") {
					$categories_user_points = get_user_meta($wpqa_user_id,"categories_user_points",true);
					if (is_array($categories_user_points) && !empty($categories_user_points)) {
						$display_name = get_the_author_meta('display_name',$wpqa_user_id);
						echo "<ul class='row user-points-categories'>
							<li class='col'>
								<div>
									<h5><i class='icon-graduation-cap'></i>".$display_name." ".(count($categories_user_points) > 1?esc_html__("has been qualified at the following categories","wpqa"):esc_html__("has been qualified at the following category","wpqa"))."</h5>
									<ul>";
										$category_with_points = array();
										foreach ($categories_user_points as $category) {
											$category_with_points[$category] = (int)get_user_meta($wpqa_user_id,"points_category".$category,true);
										}
										arsort($category_with_points);
										foreach ($category_with_points as $category => $points) {
											$get_term = get_term($category,'question-category');
											$term_filter = apply_filters("wpqa_user_points_categories",true,$get_term);
											if ($term_filter == true && isset($get_term->slug)) {
												echo "<li>
													<i class='icon-bucket'></i>
													".apply_filters("wpqa_filter_categories_points","<a href='".get_term_link($get_term->slug,'question-category')."'>".$get_term->name."</a> (".$points." "._n("point","points",$points,"wpqa").") ".wpqa_get_badge($wpqa_user_id,"",$points),$get_term,$points,$wpqa_user_id)."
												</li>";
											}
										}
									echo "</ul>
								</div>
							</li>
						</ul>";
					}
				}?>
			</div><!-- End user-stats -->
		<?php }
	}
endif;
/* Check user privacy */
if (!function_exists('wpqa_check_user_privacy')) :
	function wpqa_check_user_privacy($user_id,$privacy_key) {
		$get_current_user_id = get_current_user_id();
		$is_super_admin = is_super_admin($get_current_user_id);
		$privacy_value = get_user_meta($user_id,"privacy_".$privacy_key,true);
		$privacy_value = ($privacy_value != ""?$privacy_value:wpqa_options("privacy_".$privacy_key));
		$return = false;
		if ($privacy_value == "" || $privacy_value == "public" || $is_super_admin || ($privacy_value == "members" && is_user_logged_in()) || ($privacy_value == "me" && $get_current_user_id > 0 && $get_current_user_id == $user_id)) {
			$return = true;
		}
		return $return;
	}
endif;?>