<?php

/* @author    2codeThemes
*  @package   WPQA/shortcodes
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Edit profile */
if (!function_exists('wpqa_edit_profile')) :
	function wpqa_edit_profile($atts, $content = null) {
		$a = shortcode_atts( array(
		    'type'  => ''
		), $atts );
		$out = '';
		if (!is_user_logged_in()) {
			if ($a['type'] == "delete") {
				$out .= '<div class="alert-message"><i class="icon-lamp"></i><p>'.esc_html__("Please login to delete account.","wpqa").'</p></div>'.do_shortcode("[wpqa_login]");
			}else {
				$out .= '<div class="alert-message"><i class="icon-lamp"></i><p>'.esc_html__("Please login to edit profile.","wpqa").'</p></div>'.do_shortcode("[wpqa_login]");
			}
		}else {
			if ($a['type'] != "delete") {
				$breadcrumbs = wpqa_options("breadcrumbs");

				$user_meta_avatar = wpqa_avatar_name();
				$user_meta_cover = wpqa_cover_name();

				$edit_profile_items_1 = wpqa_options("edit_profile_items_1");
				$edit_profile_items_2 = wpqa_options("edit_profile_items_2");
				$edit_profile_items_3 = wpqa_options("edit_profile_items_3");
				
				$first_name_required = wpqa_options("first_name_required");
				$last_name_required = wpqa_options("last_name_required");
				$display_name_required = wpqa_options("display_name_required");
				$profile_picture_required = wpqa_options("profile_picture_required");
				$profile_cover_required = wpqa_options("profile_cover_required");
				$country_required = wpqa_options("country_required");
				$city_required = wpqa_options("city_required");
				$phone_required = wpqa_options("phone_required");
				$gender_required = wpqa_options("gender_required");
				$age_required = wpqa_options("age_required");

				$profile_credential_required = wpqa_options("profile_credential_required");
				$question_schedules = wpqa_options("question_schedules");
				$question_schedules_groups = wpqa_options("question_schedules_groups");
				$send_email_new_question = wpqa_options("send_email_new_question");
				$send_email_question_groups = wpqa_options("send_email_question_groups");

				$custom_left_menu = wpqa_options("custom_left_menu");
			}
			$edit_profile_sections = array(
				array(
					'name'    => esc_html__('Basic Information','wpqa'),
					'value'   => 'basic',
					'default' => 'yes'
				),
				array(
					'name'    => esc_html__('About Me','wpqa'),
					'value'   => 'about',
					'default' => 'yes'
				),
				array(
					'name'    => esc_html__('Social Profiles','wpqa'),
					'value'   => 'social',
					'default' => 'yes'
				),
				array(
					'name'    => esc_html__('Custom Left Menu With Categories','wpqa'),
					'value'   => 'categories',
					'default' => 'yes'
				),
				array(
					'name'    => esc_html__('Mails Setting','wpqa'),
					'value'   => 'mails_setting',
					'default' => 'yes'
				),
				array(
					'name'    => esc_html__('Privacy','wpqa'),
					'value'   => 'privacy',
					'default' => 'yes'
				),
				array(
					'name'    => esc_html__('Financial','wpqa'),
					'value'   => 'financial',
					'default' => 'yes'
				),
				array(
					'name'    => esc_html__('Delete Account','wpqa'),
					'value'   => 'delete_account',
					'default' => 'yes'
				),
			);
			$edit_profile_items_4  = wpqa_options("edit_profile_items_4");
			$edit_profile_items_5  = wpqa_options("edit_profile_items_5");
			$privacy_account       = wpqa_options("privacy_account");
			$delete_account        = wpqa_options("delete_account");
			$user_profile_pages = array("edit","password","mails","privacy","financial","withdrawals","delete");
			if ($privacy_account != "on") {
				$user_profile_pages = array_diff($user_profile_pages,array("privacy"));
			}
			$activate_pay_to_users = wpqa_options("activate_pay_to_users");
			if ($activate_pay_to_users != "on") {
				$user_profile_pages = array_diff($user_profile_pages,array("financial","withdrawals"));
			}
			$delete_account = wpqa_options("delete_account");
			if ($delete_account != "on") {
				$user_profile_pages = array_diff($user_profile_pages,array("delete"));
			}
			$user_profile_pages = apply_filters("wpqa_user_edit_profile_pages",$user_profile_pages);
			$out .= '<form class="edit-profile-form wpqa_form wpqa-readonly" method="post" enctype="multipart/form-data">'.apply_filters('wpqa_edit_profile_form','edit_profile');
				$user_id = get_current_user_id();
				$user_info = get_userdata($user_id);
				$profile_user_id = $user_info->ID;
				if ($a['type'] != "delete") {
					$profile_credential = get_the_author_meta('profile_credential',$profile_user_id);
					$url = get_the_author_meta('url',$profile_user_id);
					$twitter = get_the_author_meta('twitter',$profile_user_id);
					$facebook = get_the_author_meta('facebook',$profile_user_id);
					$youtube = get_the_author_meta('youtube',$profile_user_id);
					$vimeo = get_the_author_meta('vimeo',$profile_user_id);
					$linkedin = get_the_author_meta('linkedin',$profile_user_id);
					$display_name = get_the_author_meta('display_name',$profile_user_id);
					$country = get_the_author_meta('country',$profile_user_id);
					$city = get_the_author_meta('city',$profile_user_id);
					$age = get_the_author_meta('age',$profile_user_id);
					$phone = get_the_author_meta('phone',$profile_user_id);
					$gender = get_the_author_meta('gender',$profile_user_id);
					$instagram = get_the_author_meta('instagram',$profile_user_id);
					$pinterest = get_the_author_meta('pinterest',$profile_user_id);
					$show_point_favorite = get_the_author_meta('show_point_favorite',$profile_user_id);
					$question_schedules_user = get_the_author_meta('question_schedules',$profile_user_id);
					$received_email = get_the_author_meta('received_email',$profile_user_id);
					$active_message = wpqa_options("active_message");
					$received_message = get_the_author_meta('received_message',$profile_user_id);
					$unsubscribe_mails = get_the_author_meta('unsubscribe_mails',$profile_user_id);
					$new_payment_mail = get_the_author_meta('new_payment_mail',$profile_user_id);
					$send_message_mail = get_the_author_meta('send_message_mail',$profile_user_id);
					$answer_on_your_question = get_the_author_meta('answer_on_your_question',$profile_user_id);
					$answer_question_follow = get_the_author_meta('answer_question_follow',$profile_user_id);
					$notified_reply = get_the_author_meta('notified_reply',$profile_user_id);
					$your_avatar = get_the_author_meta($user_meta_avatar,$profile_user_id);
					$your_cover = get_the_author_meta($user_meta_cover,$profile_user_id);

					$categories_left_menu = get_the_author_meta("categories_left_menu",$profile_user_id);
				}
				$rand_e = rand(1,1000);
				if ($a['type'] != "password" && $a['type'] != "mails" && $a['type'] != "financial" && $a['type'] != "privacy" && $a['type'] != "delete") {
					$save_available = true;
				}
				$out .= '<div class="form-inputs clearfix">
					<div class="page-sections'.($a['type'] == "password" || $a['type'] == "mails" || $a['type'] == "financial" || $a['type'] == "privacy"?" wpqa_hide":"").'" id="edit-profile">';
						if ($a['type'] != "delete" && isset($edit_profile_sections) && is_array($edit_profile_sections)) {
							if (isset($edit_profile_items_1["names"]) && isset($edit_profile_items_1["names"]["value"]) && $edit_profile_items_1["names"]["value"] == "names") {
								$edit_profile_items_1["nickname"] = array("sort" => esc_html__("Nickname","wpqa"),"value" => "nickname");
								$edit_profile_items_1["first_name"] = array("sort" => esc_html__("First Name","wpqa"),"value" => "first_name");
								$edit_profile_items_1["last_name"] = array("sort" => esc_html__("Last Name","wpqa"),"value" => "last_name");
								$edit_profile_items_1["display_name"] = array("sort" => esc_html__("Display Name","wpqa"),"value" => "display_name");
							}
							foreach ($edit_profile_sections as $key_sections => $value_sections) {
								if (isset($value_sections["value"]) && $value_sections["value"] == "basic" && isset($edit_profile_items_1) && is_array($edit_profile_items_1)) {
									$out .= '<div class="page-section page-section-'.$value_sections["value"].'">
										<div class="page-wrap-content">
											<h2 class="post-title-2"><i class="icon-vcard"></i>'.esc_html__("Basic Information","wpqa").'</h2>';
											$out .= apply_filters('wpqa_edit_profile_before_email',false,$profile_user_id).
											'<p class="email_field">
												<label for="email_'.$rand_e.'">'.esc_html__("E-Mail","wpqa").'<span class="required">*</span></label>
												<input readonly="readonly" type="text" name="email" id="email_'.$rand_e.'" value="'.(isset($_POST["email"])?esc_attr($_POST["email"]):$user_info->user_email).'">
												<i class="icon-mail"></i>
											</p>';
											foreach ($edit_profile_items_1 as $key_items_1 => $value_items_1) {
												$out = apply_filters("wpqa_edit_profile_sort",$out,"edit_profile_items_1",$edit_profile_items_1,$key_items_1,$value_items_1,"edit",$_POST,$profile_user_id);
												$out .= wpqa_register_edit_fields($key_items_1,$value_items_1,"edit",$rand_e,$user_info);
											}
										$out .= '</div>
										<div class="clearfix"></div>
									</div><!-- End page-section -->';
								}else if (isset($value_sections["value"]) && $value_sections["value"] == "social" && isset($edit_profile_items_2) && !empty($edit_profile_items_2) && is_array($edit_profile_items_2)) {
									$p_count = 0;
									$edit_profile_items_2_keys = array_keys($edit_profile_items_2);
									while ($p_count < count($edit_profile_items_2)) {
										if (isset($edit_profile_items_2[$edit_profile_items_2_keys[$p_count]]["value"]) && $edit_profile_items_2[$edit_profile_items_2_keys[$p_count]]["value"] != "" && $edit_profile_items_2[$edit_profile_items_2_keys[$p_count]]["value"] != "0") {
											$profile_one_2 = $p_count;
											break;
										}
										$p_count++;
									}
									if (isset($profile_one_2)) {
										$out .= '<div class="page-section page-section-'.$value_sections["value"].'">
											<div class="page-wrap-content">
												<h2 class="post-title-2"><i class="icon-globe"></i>'.esc_html__("Social Profiles","wpqa").'</h2>
												<div class="wpqa_form_2">';
													foreach ($edit_profile_items_2 as $key_items_2 => $value_items_2) {
														if ($key_items_2 == "facebook" && isset($value_items_2["value"]) && $value_items_2["value"] == "facebook") {
															$out .= '<p class="facebook_field">
																<label for="facebook_'.$rand_e.'">'.esc_html__("Facebook","wpqa").'  '.esc_html__("(Put the full URL)","wpqa").'</label>
																<input readonly="readonly" type="text" name="facebook" id="facebook_'.$rand_e.'" value="'.esc_url(isset($_POST["facebook"])?$_POST["facebook"]:$facebook).'">
																<i class="icon-facebook"></i>
															</p>';
														}else if ($key_items_2 == "twitter" && isset($value_items_2["value"]) && $value_items_2["value"] == "twitter") {
															$out .= '<p class="twitter_field">
																<label for="twitter_'.$rand_e.'">'.esc_html__("Twitter","wpqa").'  '.esc_html__("(Put the full URL)","wpqa").'</label>
																<input readonly="readonly" type="text" name="twitter" id="twitter_'.$rand_e.'" value="'.esc_url(isset($_POST["twitter"])?$_POST["twitter"]:$twitter).'">
																<i class="icon-twitter"></i>
															</p>';
														}else if ($key_items_2 == "youtube" && isset($value_items_2["value"]) && $value_items_2["value"] == "youtube") {
															$out .= '<p class="youtube_field">
																<label for="youtube_'.$rand_e.'">'.esc_html__("Youtube","wpqa").'  '.esc_html__("(Put the full URL)","wpqa").'</label>
																<input readonly="readonly" type="text" name="youtube" id="youtube_'.$rand_e.'" value="'.esc_url(isset($_POST["youtube"])?$_POST["youtube"]:$youtube).'">
																<i class="icon-play"></i>
															</p>';
														}else if ($key_items_2 == "vimeo" && isset($value_items_2["value"]) && $value_items_2["value"] == "vimeo") {
															$out .= '<p class="vimeo_field">
																<label for="vimeo_'.$rand_e.'">'.esc_html__("Vimeo","wpqa").'  '.esc_html__("(Put the full URL)","wpqa").'</label>
																<input readonly="readonly" type="text" name="vimeo" id="vimeo_'.$rand_e.'" value="'.esc_url(isset($_POST["vimeo"])?$_POST["vimeo"]:$vimeo).'">
																<i class="icon-vimeo"></i>
															</p>';
														}else if ($key_items_2 == "linkedin" && isset($value_items_2["value"]) && $value_items_2["value"] == "linkedin") {
															$out .= '<p class="linkedin_field">
																<label for="linkedin_'.$rand_e.'">'.esc_html__("Linkedin","wpqa").'  '.esc_html__("(Put the full URL)","wpqa").'</label>
																<input readonly="readonly" type="text" name="linkedin" id="linkedin_'.$rand_e.'" value="'.esc_url(isset($_POST["linkedin"])?$_POST["linkedin"]:$linkedin).'">
																<i class="icon-linkedin"></i>
															</p>';
														}else if ($key_items_2 == "instagram" && isset($value_items_2["value"]) && $value_items_2["value"] == "instagram") {
															$out .= '<p class="instagram_field">
																<label for="instagram_'.$rand_e.'">'.esc_html__("Instagram","wpqa").'  '.esc_html__("(Put the full URL)","wpqa").'</label>
																<input readonly="readonly" type="text" name="instagram" id="instagram_'.$rand_e.'" value="'.esc_url(isset($_POST["instagram"])?$_POST["instagram"]:$instagram).'">
																<i class="icon-instagrem"></i>
															</p>';
														}else if ($key_items_2 == "pinterest" && isset($value_items_2["value"]) && $value_items_2["value"] == "pinterest") {
															$out .= '<p class="pinterest_field">
																<label for="pinterest_'.$rand_e.'">'.esc_html__("Pinterest","wpqa").'  '.esc_html__("(Put the full URL)","wpqa").'</label>
																<input readonly="readonly" type="text" name="pinterest" id="pinterest_'.$rand_e.'" value="'.esc_url(isset($_POST["pinterest"])?$_POST["pinterest"]:$pinterest).'">
																<i class="icon-pinterest"></i>
															</p>';
														}
													}
												$out .= '</div>'.apply_filters("wpqa_filter_after_social_profile",false).'
											</div>
											<div class="clearfix"></div>
										</div><!-- End page-section -->';
									}
								}else if (isset($value_sections["value"]) && $value_sections["value"] == "about" && isset($edit_profile_items_3) && is_array($edit_profile_items_3)) {
									$p_count = 0;
									$edit_profile_items_3_keys = array_keys($edit_profile_items_3);
									while ($p_count < count($edit_profile_items_3)) {
										if (isset($edit_profile_items_3[$edit_profile_items_3_keys[$p_count]]["value"]) && $edit_profile_items_3[$edit_profile_items_3_keys[$p_count]]["value"] != "" && $edit_profile_items_3[$edit_profile_items_3_keys[$p_count]]["value"] != "0") {
											$profile_one_3 = $p_count;
											break;
										}
										$p_count++;
									}
									if (isset($profile_one_3)) {
										$out .= '<div class="page-section page-section-'.$value_sections["value"].'">
											<div class="page-wrap-content">
												<h2 class="post-title-2"><i class="icon-graduation-cap"></i>'.esc_html__("About Me","wpqa").'</h2>';
												foreach ($edit_profile_items_3 as $key_items_3 => $value_items_3) {
													if ($key_items_3 == "profile_credential" && isset($value_items_3["value"]) && $value_items_3["value"] == "profile_credential") {
														$out .= '<p class="profile_credential_field">
															<label for="profile_credential_'.$rand_e.'">'.esc_html__("Add profile credential","wpqa").($profile_credential_required == "on"?'<span class="required">*</span>':'').'</label>
															<input readonly="readonly" type="text" name="profile_credential" id="profile_credential_'.$rand_e.'" value="'.esc_attr(isset($_POST["profile_credential"])?$_POST["profile_credential"]:$profile_credential).'">
															<i class="icon-info"></i>
														</p>';
													}else if ($key_items_3 == "website" && isset($value_items_3["value"]) && $value_items_3["value"] == "website") {
														$out .= '<p class="website_field">
															<label for="url_'.$rand_e.'">'.esc_html__("Website","wpqa").'</label>
															<input readonly="readonly" type="text" name="url" id="url_'.$rand_e.'" value="'.esc_url(isset($_POST["url"])?$_POST["url"]:$url).'">
															<i class="icon-link"></i>
														</p>';
													}else if ($key_items_3 == "bio" && isset($value_items_3["value"]) && $value_items_3["value"] == "bio") {
														$bio_editor = wpqa_options("bio_editor");
														if ($bio_editor == "on") {
															$settings = array("textarea_name" => "description","media_buttons" => true,"textarea_rows" => 10);
															$settings = apply_filters('wpqa_description_editor_setting',$settings);
															ob_start();
															wp_editor((isset($_POST["description"])?wpqa_kses_stip(stripslashes($_POST["description"]),"yes"):wpqa_kses_stip(stripslashes($user_info->description),"yes")),"description_".$rand_e,$settings);
															$editor_contents = ob_get_clean();
															$out .= '<div class="the-description wpqa_textarea the-textarea">'.$editor_contents.'</div>';
														}else {
															$out .= '<p class="bio_field">
																<label for="description_'.$rand_e.'">'.esc_html__("Professional Bio","wpqa").'</label>
																<textarea name="description" id="description_'.$rand_e.'" cols="58" rows="8">'.(isset($_POST["description"])?stripslashes(sanitize_textarea_field($_POST["description"])):stripslashes(sanitize_textarea_field($user_info->description))).'</textarea>
																<i class="icon-pencil"></i>
															</p>';
														}
													}else if ($key_items_3 == "private_pages" && isset($value_items_3["value"]) && $value_items_3["value"] == "private_pages") {
														$out .= '<p class="show_point_favorite_field normal_label">
															<label for="show_point_favorite_'.$rand_e.'">
																<span class="wpqa_checkbox"><input type="checkbox" name="show_point_favorite" id="show_point_favorite_'.$rand_e.'" value="on" '.checked(esc_attr(isset($_POST["show_point_favorite"])?$_POST["show_point_favorite"]:(!empty($_POST) && empty($_POST["show_point_favorite"])?"":$show_point_favorite)),"on",false).'></span>
																<span class="wpqa_checkbox_span">'.esc_html__("Show your private pages for all the users?","wpqa").'</span><span> '.esc_html__("(Points, favorite and followed pages).","wpqa").'</span>
															</label>
														</p>';
													}else if ($key_items_3 == "received_message" && isset($value_items_3["value"]) && $value_items_3["value"] == "received_message" && $active_message == "on") {
														$out .= '<p class="received_message_field normal_label">
															<label for="received_message_'.$rand_e.'">
																<span class="wpqa_checkbox"><input type="checkbox" name="received_message" id="received_message_'.$rand_e.'" value="on" '.checked(esc_attr(isset($_POST["received_message"])?$_POST["received_message"]:(!empty($_POST) && empty($_POST["received_message"])?"":$received_message)),"on",false).'></span>
																<span class="wpqa_checkbox_span">'.esc_html__("Do you like to receive message from other users?","wpqa").'</span>
															</label>
														</p>';
													}
												}
											$out .= '</div>
											<div class="clearfix"></div>
										</div><!-- End page-section -->';
									}
								}else if (isset($value_sections["value"]) && $value_sections["value"] == "categories" && $custom_left_menu == "on") {
									$exclude = apply_filters('wpqa_exclude_question_category',array());
									$out .= '<div class="page-section page-section-'.$value_sections["value"].'">
										<div class="page-wrap-content">
											<h2 class="post-title-2"><i class="icon-folder"></i>'.esc_html__("Custom Categories","wpqa").'</h2>
											<p class="custom_categories_field">
												<span class="styled-select">'.
													wp_dropdown_categories(array_merge($exclude,array(
														'taxonomy'     => 'question-category',
													    'orderby'      => 'name',
													    'echo'         => 0,
													    'hide_empty'   => 0,
													    'hierarchical' => 1,
													    'id'           => "add_categories_left_menu",
													    'name'         => "",
													))).'
												</span>
												<i class="icon-folder"></i>
											</p>
											<div class="clearfix"></div>
											<a data-name="categories_left_menu" data-id="categories_left_menu_items" class="button-default-3 add_categories_left_menu">'.esc_html__("Add category","wpqa").'</a>
											<ul class="profile_items" id="categories_left_menu_items">';
											if ((isset($_POST["categories_left_menu"]) && is_array($_POST["categories_left_menu"]) && !empty($_POST["categories_left_menu"])) || (is_array($categories_left_menu) && !empty($categories_left_menu))) {
												$categories_left_menu = (isset($_POST["categories_left_menu"]) && is_array($_POST["categories_left_menu"]) && !empty($_POST["categories_left_menu"])?$_POST["categories_left_menu"]:$categories_left_menu);
												foreach ($categories_left_menu as $key => $value) {
													$cat_id = (isset($value["value"]) && $value["value"] != ""?(int)$value["value"]:0);
													if ($cat_id > 0) {
														$get_term = get_term($cat_id,'question-category');
														if (isset($get_term->name)) {
															$out .= '<li class="categories" id="categories_left_menu_items_'.$cat_id.'">
																<label>'.$get_term->name.'</label>
																<input name="categories_left_menu[cat-'.$cat_id.'][value]" value="'.$cat_id.'" type="hidden">
																<div>
																	<div class="del-item-li"><i class="icon-cancel"></i></div>
																	<div class="move-poll-li"><i class="icon-menu"></i></div>
																</div>
															</li>';
														}
													}
												}
											}
											$out .= '</ul>
										</div>
										<div class="clearfix"></div>
									</div><!-- End page-section -->';
								}
							}
						}
					$out .= '</div><!-- End page-sections -->';

					if ($a['type'] == "financial" && isset($edit_profile_sections) && is_array($edit_profile_sections) && isset($edit_profile_items_5) && is_array($edit_profile_items_5)) {
						foreach ($edit_profile_sections as $key_sections => $value_sections) {
							if (isset($value_sections["value"]) && $value_sections["value"] == "financial") {
								$p_count = 0;
								$edit_profile_items_5_keys = array_keys($edit_profile_items_5);
								while ($p_count < count($edit_profile_items_5)) {
									if (isset($edit_profile_items_5[$edit_profile_items_5_keys[$p_count]]["value"]) && $edit_profile_items_5[$edit_profile_items_5_keys[$p_count]]["value"] != "" && $edit_profile_items_5[$edit_profile_items_5_keys[$p_count]]["value"] != "0") {
										$profile_one_5 = $p_count;
										break;
									}
									$p_count++;
								}
								if (isset($profile_one_5)) {
									$activate_pay_to_users = wpqa_options("activate_pay_to_users");
									if ($activate_pay_to_users == "on") {
										$save_available = true;
										$out .= '<div class="page-sections" id="financial-profile">
											<div class="payment_content page-section page-section-'.$value_sections["value"].'">
												<h2 class="post-title-2"><i class="icon-vcard"></i>'.esc_html__("Financial","wpqa").'</h2>';
												$financial_payments = get_the_author_meta('financial_payments',$profile_user_id);
												$last_financial_payments = (isset($_POST["financial_payments"]) && $_POST["financial_payments"]?esc_attr($_POST["financial_payments"]):($financial_payments != ""?esc_attr($financial_payments):$edit_profile_items_5_keys[$profile_one_5]));
												$paypal_email = get_the_author_meta('paypal_email',$profile_user_id);
												$payoneer_email = get_the_author_meta('payoneer_email',$profile_user_id);
												$bank_account_holder = get_the_author_meta('bank_account_holder',$profile_user_id);
												$bank_your_address = get_the_author_meta('bank_your_address',$profile_user_id);
												$bank_name = get_the_author_meta('bank_name',$profile_user_id);
												$bank_address = get_the_author_meta('bank_address',$profile_user_id);
												$bank_swift_iban = get_the_author_meta('bank_swift_iban',$profile_user_id);
												$bank_account_number = get_the_author_meta('bank_account_number',$profile_user_id);
												$out .= '<div class="financial_payments">
													<p class="financial_payments_field wpqa_radio_p"><label>'.esc_html__("Financial Payments","wpqa").'<span class="required">*</span></label></p>
													<div class="wpqa_radio_div">';
														foreach ($edit_profile_items_5 as $key_edit_profile_items_5 => $value_edit_profile_items_5) {
															if (isset($value_edit_profile_items_5["value"]) && $value_edit_profile_items_5["value"] == "paypal") {
																$out .= '<p>
																	<span class="wpqa_radio"><input id="financial_payments_paypal_'.$rand_e.'" name="financial_payments" type="radio" value="paypal"'.($last_financial_payments == "paypal"?' checked="checked"':'').'></span>
																	<label for="financial_payments_paypal_'.$rand_e.'">'.esc_html__("PayPal","wpqa").'</label>
																</p>';
															}else if (isset($value_edit_profile_items_5["value"]) && $value_edit_profile_items_5["value"] == "payoneer") {
																$out .= '<p>
																	<span class="wpqa_radio"><input id="financial_payments_payoneer_'.$rand_e.'" name="financial_payments" type="radio" value="payoneer"'.($last_financial_payments == "payoneer"?' checked="checked"':'').'></span>
																	<label for="financial_payments_payoneer_'.$rand_e.'">'.esc_html__("Payoneer","wpqa").'</label>
																</p>';
															}else if (isset($value_edit_profile_items_5["value"]) && $value_edit_profile_items_5["value"] == "bank") {
																$out .= '<p>
																	<span class="wpqa_radio"><input id="financial_payments_bank_'.$rand_e.'" name="financial_payments" type="radio" value="bank"'.($last_financial_payments == "bank"?' checked="checked"':'').'></span>
																	<label for="financial_payments_bank_'.$rand_e.'">'.esc_html__("Bank Transfer","wpqa").'</label>
																</p>';
															}
														}
														$out .= '<div class="clearfix"></div>
													</div>
												</div>';
												foreach ($edit_profile_items_5 as $key_items_5 => $value_items_5) {
													if ($key_items_5 == "paypal" && isset($value_items_5["value"]) && $value_items_5["value"] == "paypal") {
														$out .= '<div class="financial_payments_forms paypal_form'.($last_financial_payments == "paypal"?"":" wpqa_hide").'">
															<h2 class="post-title-2"><i class="icon-paypal"></i>'.esc_html__("PayPal Information","wpqa").'</h2>
															<p class="paypal_email_field normal_label">
																<label for="paypal_email_'.$rand_e.'">'.esc_html__("PayPal E-Mail","wpqa").'<span class="required">*</span></label>
																<input type="text" name="paypal_email" id="paypal_email_'.$rand_e.'" value="'.(isset($_POST["paypal_email"])?esc_attr($_POST["paypal_email"]):$paypal_email).'">
																<i class="icon-mail"></i>
															</p>
														</div>';
													}else if ($key_items_5 == "payoneer" && isset($value_items_5["value"]) && $value_items_5["value"] == "payoneer") {
														$out .= '<div class="financial_payments_forms payoneer_form'.($last_financial_payments == "payoneer"?"":" wpqa_hide").'">
															<h2 class="post-title-2"><i class="icon-credit-card"></i>'.esc_html__("Payoneer Information","wpqa").'</h2>
															<p class="payoneer_email_field normal_label">
																<label for="payoneer_email_'.$rand_e.'">'.esc_html__("Payoneer E-Mail","wpqa").'<span class="required">*</span></label>
																<input type="text" name="payoneer_email" id="payoneer_email_'.$rand_e.'" value="'.(isset($_POST["payoneer_email"])?esc_attr($_POST["payoneer_email"]):$payoneer_email).'">
																<i class="icon-mail"></i>
															</p>
														</div>';
													}else if ($key_items_5 == "bank" && isset($value_items_5["value"]) && $value_items_5["value"] == "bank") {
														$out .= '<div class="financial_payments_forms bank_form'.($last_financial_payments == "bank"?"":" wpqa_hide").'">
															<h2 class="post-title-2"><i class="icon-briefcase"></i>'.esc_html__("Bank Transfer","wpqa").'</h2>
															<strong>'.esc_html__("Account Holder","wpqa").'</strong>
															<p class="bank_account_holder_field normal_label">
																<label for="bank_account_holder_'.$rand_e.'">'.esc_html__("Name of the Account Holder","wpqa").'<span class="required">*</span></label>
																<input type="text" name="bank_account_holder" id="bank_account_holder_'.$rand_e.'" value="'.(isset($_POST["bank_account_holder"])?esc_attr($_POST["bank_account_holder"]):$bank_account_holder).'">
																<i class="icon-user"></i>
															</p>
															<p class="bank_your_address_field normal_label">
																<label for="bank_your_address_'.$rand_e.'">'.esc_html__("Your Address","wpqa").'<span class="required">*</span></label>
																<input type="text" name="bank_your_address" id="bank_your_address_'.$rand_e.'" value="'.(isset($_POST["bank_your_address"])?esc_attr($_POST["bank_your_address"]):$bank_your_address).'">
																<i class="icon-map"></i>
															</p>
															<strong>'.esc_html__("Bank Information","wpqa").'</strong>
															<p class="bank_name_field normal_label">
																<label for="bank_name_'.$rand_e.'">'.esc_html__("Bank Name","wpqa").'<span class="required">*</span></label>
																<input type="text" name="bank_name" id="bank_name_'.$rand_e.'" value="'.(isset($_POST["bank_name"])?esc_attr($_POST["bank_name"]):$bank_name).'">
																<i class="icon-flag"></i>
															</p>
															<p class="bank_address_field normal_label">
																<label for="bank_address_'.$rand_e.'">'.esc_html__("Bank Address","wpqa").'<span class="required">*</span></label>
																<input type="text" name="bank_address" id="bank_address_'.$rand_e.'" value="'.(isset($_POST["bank_address"])?esc_attr($_POST["bank_address"]):$bank_address).'">
																<i class="icon-location"></i>
															</p>
															<p class="bank_swift_iban_field normal_label">
																<label for="bank_swift_iban_'.$rand_e.'">'.esc_html__("SWIFT/IBAN Code","wpqa").'<span class="required">*</span></label>
																<input type="text" name="bank_swift_iban" id="bank_swift_iban_'.$rand_e.'" value="'.(isset($_POST["bank_swift_iban"])?esc_attr($_POST["bank_swift_iban"]):$bank_swift_iban).'">
																<i class="icon-pencil"></i>
															</p>
															<p class="bank_account_number_field normal_label">
																<label for="bank_account_number_'.$rand_e.'">'.esc_html__("Account Number","wpqa").'<span class="required">*</span></label>
																<input type="text" name="bank_account_number" id="bank_account_number_'.$rand_e.'" value="'.(isset($_POST["bank_account_number"])?esc_attr($_POST["bank_account_number"]):$bank_account_number).'">
																<i class="icon-credit-card"></i>
															</p>
														</div>';
													}
												}
											$out .= '</div><!-- End page-section -->
										</div><!-- End page-sections -->';
									}else {
										$out .= '<div class="alert-message error"><i class="icon-cancel"></i><p>'.esc_html__("Sorry, this page is not available.","wpqa").'</p></div>';
									}
								}
							}
						}
					}

					if ($a['type'] == "mails" && isset($edit_profile_sections) && is_array($edit_profile_sections) && isset($edit_profile_items_4) && is_array($edit_profile_items_4)) {
						$p_count = 0;
						$edit_profile_items_4_keys = array_keys($edit_profile_items_4);
						while ($p_count < count($edit_profile_items_4)) {
							if (isset($edit_profile_items_4[$edit_profile_items_4_keys[$p_count]]["value"]) && $edit_profile_items_4[$edit_profile_items_4_keys[$p_count]]["value"] != "" && $edit_profile_items_4[$edit_profile_items_4_keys[$p_count]]["value"] != "0") {
								$profile_one_4 = $p_count;
								break;
							}
							$p_count++;
						}
						if (isset($profile_one_4)) {
							$save_available = true;
						}
						foreach ($edit_profile_sections as $key_sections => $value_sections) {
							if (isset($value_sections["value"]) && $value_sections["value"] == "mails_setting") {
								$unsubscribe_mails_value = (esc_attr(isset($_POST["unsubscribe_mails"])?$_POST["unsubscribe_mails"]:(!empty($_POST) && empty($_POST["unsubscribe_mails"])?"":$unsubscribe_mails)));
								if (isset($profile_one_4)) {
									$payment_available = wpqa_payment_available();
									$out .= '<div class="page-sections" id="mails-profile">
										<div class="page-section page-section-'.$value_sections["value"].'">
											<div class="page-wrap-content">
												<h2 class="post-title-2"><i class="icon-mail"></i>'.esc_html__("Mail Settings","wpqa").'</h2>';
												foreach ($edit_profile_items_4 as $key_items_4 => $value_items_4) {
													if ($key_items_4 == "question_schedules" && isset($value_items_4["value"]) && $value_items_4["value"] == "question_schedules" && $question_schedules == "on" && is_array($question_schedules_groups) && isset($user_info->roles[0]) && in_array($user_info->roles[0],$question_schedules_groups)) {
														$out .= '<p class="question_schedules_field normal_label'.($unsubscribe_mails_value == "on"?" wpqa_hide":"").'">
															<label for="question_schedules_'.$rand_e.'">
																<span class="wpqa_checkbox"><input type="checkbox" name="question_schedules" id="question_schedules_'.$rand_e.'" value="on" '.checked(esc_attr(isset($_POST["question_schedules"])?$_POST["question_schedules"]:(!empty($_POST) && empty($_POST["question_schedules"])?"":$question_schedules_user)),"on",false).'></span>
																<span class="wpqa_checkbox_span">'.esc_html__("Do you like to get scheduled mails for the recent questions?","wpqa").'</span>
															</label>
														</p>';
													}else if ($key_items_4 == "send_emails" && isset($value_items_4["value"]) && $value_items_4["value"] == "send_emails" && $send_email_new_question == "on" && is_array($send_email_question_groups) && isset($user_info->roles[0]) && in_array($user_info->roles[0],$send_email_question_groups)) {
														$out .= '<p class="received_email_field normal_label'.($unsubscribe_mails_value == "on"?" wpqa_hide":"").'">
															<label for="received_email_'.$rand_e.'">
																<span class="wpqa_checkbox"><input type="checkbox" name="received_email" id="received_email_'.$rand_e.'" value="on" '.checked(esc_attr(isset($_POST["received_email"])?$_POST["received_email"]:(!empty($_POST) && empty($_POST["received_email"])?"":$received_email)),"on",false).'></span>
																<span class="wpqa_checkbox_span">'.esc_html__("Do you like to get mails when new questions are added?","wpqa").'</span>
															</label>
														</p>';
													}else if ($key_items_4 == "unsubscribe_mails" && isset($value_items_4["value"]) && $value_items_4["value"] == "unsubscribe_mails") {
														$out .= '<p class="unsubscribe_mails_field normal_label">
															<label for="unsubscribe_mails_'.$rand_e.'">
																<span class="wpqa_checkbox"><input type="checkbox" name="unsubscribe_mails" class="unsubscribe_mails" id="unsubscribe_mails_'.$rand_e.'" value="on" '.checked($unsubscribe_mails_value,"on",false).'></span>
																<span class="wpqa_checkbox_span">'.esc_html__("Do you like to unsubscribe from all the mails?","wpqa").'</span>
															</label>
														</p>';
													}else if ($key_items_4 == "new_payment_mail" && isset($value_items_4["value"]) && $value_items_4["value"] == "new_payment_mail" && $payment_available == true) {
														$out .= '<p class="new_payment_mail_field normal_label'.($unsubscribe_mails_value == "on"?" wpqa_hide":"").'">
															<label for="new_payment_mail_'.$rand_e.'">
																<span class="wpqa_checkbox"><input type="checkbox" name="new_payment_mail" class="new_payment_mail" id="new_payment_mail_'.$rand_e.'" value="on" '.checked(esc_attr(isset($_POST["new_payment_mail"])?$_POST["new_payment_mail"]:(!empty($_POST) && empty($_POST["new_payment_mail"])?"":$new_payment_mail)),"on",false).'></span>
																<span class="wpqa_checkbox_span">'.esc_html__("Do you like to get mails for the new payments?","wpqa").'</span>
															</label>
														</p>';
													}else if ($key_items_4 == "send_message_mail" && isset($value_items_4["value"]) && $value_items_4["value"] == "send_message_mail" && $active_message == "on") {
														$out .= '<p class="send_message_mail_field normal_label'.($unsubscribe_mails_value == "on"?" wpqa_hide":"").'">
															<label for="send_message_mail_'.$rand_e.'">
																<span class="wpqa_checkbox"><input type="checkbox" name="send_message_mail" class="send_message_mail" id="send_message_mail_'.$rand_e.'" value="on" '.checked(esc_attr(isset($_POST["send_message_mail"])?$_POST["send_message_mail"]:(!empty($_POST) && empty($_POST["send_message_mail"])?"":$send_message_mail)),"on",false).'></span>
																<span class="wpqa_checkbox_span">'.esc_html__("Do you like to get mails when you receive new messages?","wpqa").'</span>
															</label>
														</p>';
													}else if ($key_items_4 == "answer_on_your_question" && isset($value_items_4["value"]) && $value_items_4["value"] == "answer_on_your_question") {
														$out .= '<p class="answer_on_your_question_field normal_label'.($unsubscribe_mails_value == "on"?" wpqa_hide":"").'">
															<label for="answer_on_your_question_'.$rand_e.'">
																<span class="wpqa_checkbox"><input type="checkbox" name="answer_on_your_question" class="answer_on_your_question" id="answer_on_your_question_'.$rand_e.'" value="on" '.checked(esc_attr(isset($_POST["answer_on_your_question"])?$_POST["answer_on_your_question"]:(!empty($_POST) && empty($_POST["answer_on_your_question"])?"":$answer_on_your_question)),"on",false).'></span>
																<span class="wpqa_checkbox_span">'.esc_html__("Do you like to get mails when new answers are added to your questions?","wpqa").'</span>
															</label>
														</p>';
													}else if ($key_items_4 == "answer_question_follow" && isset($value_items_4["value"]) && $value_items_4["value"] == "answer_question_follow") {
														$out .= '<p class="answer_question_follow_field normal_label'.($unsubscribe_mails_value == "on"?" wpqa_hide":"").'">
															<label for="answer_question_follow_'.$rand_e.'">
																<span class="wpqa_checkbox"><input type="checkbox" name="answer_question_follow" class="answer_question_follow" id="answer_question_follow_'.$rand_e.'" value="on" '.checked(esc_attr(isset($_POST["answer_question_follow"])?$_POST["answer_question_follow"]:(!empty($_POST) && empty($_POST["answer_question_follow"])?"":$answer_question_follow)),"on",false).'></span>
																<span class="wpqa_checkbox_span">'.esc_html__("Do you like to get mails when new answers are added to the question that you follow?","wpqa").'</span>
															</label>
														</p>'.apply_filters('wpqa_after_answer_question_follow',false,$profile_user_id);
													}else if ($key_items_4 == "notified_reply" && isset($value_items_4["value"]) && $value_items_4["value"] == "notified_reply") {
														$out .= '<p class="notified_reply_field normal_label'.($unsubscribe_mails_value == "on"?" wpqa_hide":"").'">
															<label for="notified_reply_'.$rand_e.'">
																<span class="wpqa_checkbox"><input type="checkbox" name="notified_reply" class="notified_reply" id="notified_reply_'.$rand_e.'" value="on" '.checked(esc_attr(isset($_POST["notified_reply"])?$_POST["notified_reply"]:(!empty($_POST) && empty($_POST["notified_reply"])?"":$notified_reply)),"on",false).'></span>
																<span class="wpqa_checkbox_span">'.esc_html__("Do you like to get mails when new replies are added to your answers?","wpqa").'</span>
															</label>
														</p>';
													}
												}
											$out .= '</div>
											<div class="clearfix"></div>
										</div><!-- End page-section -->
									</div><!-- End page-sections -->';
								}
							}
						}
					}

					if ($a['type'] == "privacy" && isset($edit_profile_sections) && is_array($edit_profile_sections) && isset($edit_profile_items_4) && is_array($edit_profile_items_4)) {
						$save_available = true;
						$register_items = wpqa_options("register_items");
						foreach ($edit_profile_sections as $key_sections => $value_sections) {
							if (isset($value_sections["value"]) && $value_sections["value"] == "privacy") {
								$out .= '<div class="page-sections" id="privacy-profile">
									<div class="page-section page-section-'.$value_sections["value"].'">
										<div class="page-wrap-content">
											<h2 class="post-title-2"><i class="icon-lock-open"></i>'.esc_html__("Privacy Settings","wpqa").'</h2>
											<p>'.esc_html__("Select who may see your profile details","wpqa").'</p>';
											$privacy_array = array(
												"email" => array("value" => esc_html__("Email","wpqa"),"icon" => "icon-mail")
											);
											if ((isset($edit_profile_items_1["country"]) && isset($edit_profile_items_1["country"]["value"]) && $edit_profile_items_1["country"]["value"] == "country") || (isset($register_items["country"]) && isset($register_items["country"]["value"]) && $register_items["country"]["value"] == "country")) {
												$privacy_array["country"] = array("value" => esc_html__("Country","wpqa"),"icon" => "icon-location");
											}
											if ((isset($edit_profile_items_1["city"]) && isset($edit_profile_items_1["city"]["value"]) && $edit_profile_items_1["city"]["value"] == "city") || (isset($register_items["city"]) && isset($register_items["city"]["value"]) && $register_items["city"]["value"] == "city")) {
												$privacy_array["city"] = array("value" => esc_html__("City","wpqa"),"icon" => "icon-address");
											}
											if ((isset($edit_profile_items_1["phone"]) && isset($edit_profile_items_1["phone"]["value"]) && $edit_profile_items_1["phone"]["value"] == "phone") || (isset($register_items["phone"]) && isset($register_items["phone"]["value"]) && $register_items["phone"]["value"] == "phone")) {
												$privacy_array["phone"] = array("value" => esc_html__("Phone","wpqa"),"icon" => "icon-phone");
											}
											if ((isset($edit_profile_items_1["gender"]) && isset($edit_profile_items_1["gender"]["value"]) && $edit_profile_items_1["gender"]["value"] == "gender") || (isset($register_items["gender"]) && isset($register_items["gender"]["value"]) && $register_items["gender"]["value"] == "gender")) {
												$privacy_array["gender"] = array("value" => esc_html__("Gender","wpqa"),"icon" => "icon-heart");
											}
											if ((isset($edit_profile_items_1["age"]) && isset($edit_profile_items_1["age"]["value"]) && $edit_profile_items_1["age"]["value"] == "age") || (isset($register_items["age"]) && isset($register_items["age"]["value"]) && $register_items["age"]["value"] == "age")) {
												$privacy_array["age"] = array("value" => esc_html__("Age","wpqa"),"icon" => "icon-progress-2");
											}
											$p_count = 0;
											$edit_profile_items_2_keys = array_keys($edit_profile_items_2);
											while ($p_count < count($edit_profile_items_2)) {
												if (isset($edit_profile_items_2[$edit_profile_items_2_keys[$p_count]]["value"]) && $edit_profile_items_2[$edit_profile_items_2_keys[$p_count]]["value"] != "" && $edit_profile_items_2[$edit_profile_items_2_keys[$p_count]]["value"] != "0") {
													$profile_one_2 = $p_count;
													break;
												}
												$p_count++;
											}
											if (isset($profile_one_2)) {
												$privacy_array["social"] = array("value" => esc_html__("Social links","wpqa"),"icon" => "icon-globe");
											}
											if (isset($edit_profile_items_3["website"]) && isset($edit_profile_items_3["website"]["value"]) && $edit_profile_items_3["website"]["value"] == "website") {
												$privacy_array["website"] = array("value" => esc_html__("Website","wpqa"),"icon" => "icon-link");
											}
											if (isset($edit_profile_items_3["bio"]) && isset($edit_profile_items_3["bio"]["value"]) && $edit_profile_items_3["bio"]["value"] == "bio") {
												$privacy_array["bio"] = array("value" => esc_html__("Biography","wpqa"),"icon" => "icon-pencil");
											}
											if (isset($edit_profile_items_3["profile_credential"]) && isset($edit_profile_items_3["profile_credential"]["value"]) && $edit_profile_items_3["profile_credential"]["value"] == "profile_credential") {
												$privacy_array["credential"] = array("value" => esc_html__("Profile credential","wpqa"),"icon" => "icon-info");
											}
											foreach ($privacy_array as $key_privacy => $value_privacy) {
												$meta_value = get_user_meta($profile_user_id,"privacy_".$key_privacy,true);
												$selected_value = esc_html(isset($_POST["privacy_".$key_privacy]) && $_POST["privacy_".$key_privacy] != ""?$_POST["privacy_".$key_privacy]:($meta_value != ""?$meta_value:""));
												$out .= '<p class="'.$key_privacy.'_field">
													<label for="'.$key_privacy.'_'.$rand_e.'">'.$value_privacy["value"].'</label>
													<span class="styled-select">
														<select name="privacy_'.$key_privacy.'" id="'.$key_privacy.'_'.$rand_e.'">
															<option value="">'.esc_html__('Visibility','wpqa').'</option>
															<option '.selected($selected_value,($selected_value == ""?"":"public"),false).' value="public">'.esc_html__('Public','wpqa').'</option>
															<option '.selected($selected_value,"members",false).' value="members">'.esc_html__('All members','wpqa').'</option>
															<option '.selected($selected_value,"me",false).' value="me">'.esc_html__('Only me','wpqa').'</option>
														</select>
													</span>
													<i class="'.$value_privacy["icon"].'"></i>
												</p>';
											}
										$out .= '</div>
										<div class="clearfix"></div>
									</div><!-- End page-section -->
								</div><!-- End page-sections -->';
							}
						}
					}
					
					if ($a['type'] == "delete" && isset($edit_profile_sections) && is_array($edit_profile_sections) && $delete_account == "on") {
						$save_available = true;
						foreach ($edit_profile_sections as $key_sections => $value_sections) {
							if (isset($value_sections["value"]) && $value_sections["value"] == "delete_account") {
								$out .= '<div class="page-sections" id="delete-profile">
									<div class="page-section page-section-'.$value_sections["value"].'">
										<div class="page-wrap-content">
											<h2 class="post-title-2"><i class="icon-trash"></i>'.esc_html__("Delete account","wpqa").'</h2>
											<p class="delete_account_field normal_label">
												<label for="delete_account_'.$rand_e.'">
													<span class="wpqa_checkbox"><input type="checkbox" name="delete_account" class="delete_account" id="delete_account_'.$rand_e.'"></span>
													<span class="wpqa_checkbox_span">'.esc_html__("Delete your account?","wpqa").'</span>
												</label>
											</p>
										</div>
										<div class="clearfix"></div>
									</div><!-- End page-section -->
								</div><!-- End page-sections -->';
							}
						}
					}

					if ($a['type'] == "password") {
						$save_available = true;
					}
					
					$out .= '<div class="page-sections'.($a['type'] != "password"?" wpqa_hide":"").'" id="change-password">
						<div class="page-section">
							<div class="page-wrap-content">
								<h2 class="post-title-2"><i class="icon-lock"></i>'.esc_html__("Change password","wpqa").'</h2>
								<p class="login-password">
									<label for="newpassword_'.$rand_e.'">'.esc_html__("New Password","wpqa").'<span class="required">*</span></label>
									<input readonly="readonly" id="newpassword_'.$rand_e.'" class="required-item" type="password" name="pass1">
									<i class="icon-lock-open"></i>
								</p>
								<p class="login-password">
									<label for="newpassword2_'.$rand_e.'">'.esc_html__("Confirm Password","wpqa").'<span class="required">*</span></label>
									<input readonly="readonly" id="newpassword2_'.$rand_e.'" class="required-item" type="password" name="pass2">
									<i class="icon-lock-open"></i>
								</p>
							</div>
						</div><!-- End page-section -->
					</div><!-- End page-sections -->
				</div>';
				
				if (isset($save_available)) {
					$out .= '<p class="form-submit">
						<span class="load_span"><span class="loader_2"></span></span>
						<input type="hidden" name="user_action" value="edit_profile">
						<input type="hidden" name="action" value="update">
						<input type="hidden" name="admin_bar_front" value="1">
						<input type="hidden" name="user_id" id="user_id" value="'.esc_attr($profile_user_id).'">
						<input type="hidden" name="user_login" id="user_login" value="'.esc_attr($user_info->user_login).'">
						'.wp_nonce_field('wpqa_profile_nonce','wpqa_profile_nonce',true,false).'
						<input type="submit" value="'.($a['type'] == "delete"?esc_attr__("Delete","wpqa"):esc_attr__("Save","wpqa")).'" class="button-default button-hide-click login-submit submit">
					</p>';
				}
			
			$out .= '</form>';
		}
		return $out;
	}
endif;
/* Process edit profile form */
if (!function_exists('wpqa_process_edit_profile_form')) :
	function wpqa_process_edit_profile_form() {
		if (isset($_POST['wpqa_profile_nonce']) && wp_verify_nonce($_POST['wpqa_profile_nonce'],'wpqa_profile_nonce')) {
			$user_meta_avatar = wpqa_avatar_name();
			$user_meta_cover = wpqa_cover_name();

			$edit_profile_items_1 = wpqa_options("edit_profile_items_1");
			$edit_profile_items_3 = wpqa_options("edit_profile_items_3");
			
			$profile_credential_register = (isset($edit_profile_items_3["profile_credential"]["value"]) && $edit_profile_items_3["profile_credential"]["value"] == "profile_credential"?"on":0);
			$nickname = (isset($edit_profile_items_1["nickname"]["value"]) && $edit_profile_items_1["nickname"]["value"] == "nickname"?"on":0);
			
			$profile_credential_required = wpqa_options("profile_credential_required");
			$profile_credential_maximum = wpqa_options("profile_credential_maximum");
			$user_id = get_current_user_id();
			$get_your_avatar = get_user_meta($user_id,$user_meta_avatar,true);
			$get_your_cover = get_user_meta($user_id,$user_meta_cover,true);
			
			require_once(ABSPATH . 'wp-admin/includes/user.php');
			
			$errors = new WP_Error();
			$posted = array(
				'email'                   => (isset($_POST['email']) && $_POST['email'] != ""?esc_html($_POST['email']):""),
				'pass1'                   => (wpqa_is_user_password_profile() && isset($_POST['pass1']) && $_POST['pass1'] != ""?esc_html($_POST['pass1']):""),
				'pass2'                   => (wpqa_is_user_password_profile() && isset($_POST['pass2']) && $_POST['pass2'] != ""?esc_html($_POST['pass2']):""),
				'first_name'              => (isset($_POST['first_name']) && $_POST['first_name'] != ""?esc_html($_POST['first_name']):""),
				'last_name'               => (isset($_POST['last_name']) && $_POST['last_name'] != ""?esc_html($_POST['last_name']):""),
				'nickname'                => (isset($_POST['nickname']) && $_POST['nickname'] != ""?esc_html($_POST['nickname']):""),
				'display_name'            => (isset($_POST['display_name']) && $_POST['display_name'] != ""?esc_html($_POST['display_name']):""),
				'country'                 => (isset($_POST['country']) && $_POST['country'] != ""?esc_html($_POST['country']):""),
				'city'                    => (isset($_POST['city']) && $_POST['city'] != ""?esc_html($_POST['city']):""),
				'phone'                   => (isset($_POST['phone']) && $_POST['phone'] != ""?esc_html($_POST['phone']):""),
				'gender'                  => (isset($_POST['gender']) && $_POST['gender'] != ""?esc_html($_POST['gender']):""),
				'age'                     => (isset($_POST['age']) && $_POST['age'] != ""?esc_html($_POST['age']):""),
				'profile_credential'      => (isset($_POST['profile_credential']) && $_POST['profile_credential'] != ""?esc_html($_POST['profile_credential']):""),
				'facebook'                => (isset($_POST['facebook']) && $_POST['facebook'] != ""?esc_url($_POST['facebook']):""),
				'twitter'                 => (isset($_POST['twitter']) && $_POST['twitter'] != ""?esc_url($_POST['twitter']):""),
				'youtube'                 => (isset($_POST['youtube']) && $_POST['youtube'] != ""?esc_url($_POST['youtube']):""),
				'vimeo'                   => (isset($_POST['vimeo']) && $_POST['vimeo'] != ""?esc_url($_POST['vimeo']):""),
				'linkedin'                => (isset($_POST['linkedin']) && $_POST['linkedin'] != ""?esc_url($_POST['linkedin']):""),
				'instagram'               => (isset($_POST['instagram']) && $_POST['instagram'] != ""?esc_url($_POST['instagram']):""),
				'pinterest'               => (isset($_POST['pinterest']) && $_POST['pinterest'] != ""?esc_url($_POST['pinterest']):""),
				'show_point_favorite'     => (isset($_POST['show_point_favorite']) && $_POST['show_point_favorite'] != ""?esc_html($_POST['show_point_favorite']):""),
				'question_schedules'      => (isset($_POST['question_schedules']) && $_POST['question_schedules'] != ""?esc_html($_POST['question_schedules']):""),
				'received_email'          => (isset($_POST['received_email']) && $_POST['received_email'] != ""?esc_html($_POST['received_email']):""),
				'received_message'        => (isset($_POST['received_message']) && $_POST['received_message'] != ""?esc_html($_POST['received_message']):""),
				'unsubscribe_mails'       => (isset($_POST['unsubscribe_mails']) && $_POST['unsubscribe_mails'] != ""?esc_html($_POST['unsubscribe_mails']):""),
				'new_payment_mail'        => (isset($_POST['new_payment_mail']) && $_POST['new_payment_mail'] != ""?esc_html($_POST['new_payment_mail']):""),
				'send_message_mail'       => (isset($_POST['send_message_mail']) && $_POST['send_message_mail'] != ""?esc_html($_POST['send_message_mail']):""),
				'answer_on_your_question' => (isset($_POST['answer_on_your_question']) && $_POST['answer_on_your_question'] != ""?esc_html($_POST['answer_on_your_question']):""),
				'answer_question_follow'  => (isset($_POST['answer_question_follow']) && $_POST['answer_question_follow'] != ""?esc_html($_POST['answer_question_follow']):""),
				'notified_reply'          => (isset($_POST['notified_reply']) && $_POST['notified_reply'] != ""?esc_html($_POST['notified_reply']):""),
				'delete_account'          => (isset($_POST['delete_account']) && $_POST['delete_account'] != ""?esc_html($_POST['delete_account']):""),
				'url'                     => (isset($_POST['url']) && $_POST['url'] != ""?esc_url($_POST['url']):""),
				'description'             => (isset($_POST['description']) && $_POST['description'] != ""?esc_html($_POST['description']):""),
				'categories_left_menu'    => (isset($_POST['categories_left_menu']) && $_POST['categories_left_menu'] != ""?$_POST['categories_left_menu']:""),
				'wpqa_profile_nonce'      => (isset($_POST['wpqa_profile_nonce']) && $_POST['wpqa_profile_nonce'] != ""?esc_html($_POST['wpqa_profile_nonce']):""),
				'financial_payments'      => (isset($_POST['financial_payments']) && $_POST['financial_payments'] != ""?esc_html($_POST['financial_payments']):""),
				'paypal_email'            => (isset($_POST['paypal_email']) && $_POST['paypal_email'] != ""?esc_html($_POST['paypal_email']):""),
				'payoneer_email'          => (isset($_POST['payoneer_email']) && $_POST['payoneer_email'] != ""?esc_html($_POST['payoneer_email']):""),
				'bank_account_holder'     => (isset($_POST['bank_account_holder']) && $_POST['bank_account_holder'] != ""?esc_html($_POST['bank_account_holder']):""),
				'bank_your_address'       => (isset($_POST['bank_your_address']) && $_POST['bank_your_address'] != ""?esc_html($_POST['bank_your_address']):""),
				'bank_name'               => (isset($_POST['bank_name']) && $_POST['bank_name'] != ""?esc_html($_POST['bank_name']):""),
				'bank_address'            => (isset($_POST['bank_address']) && $_POST['bank_address'] != ""?esc_html($_POST['bank_address']):""),
				'bank_swift_iban'         => (isset($_POST['bank_swift_iban']) && $_POST['bank_swift_iban'] != ""?esc_html($_POST['bank_swift_iban']):""),
				'bank_account_number'     => (isset($_POST['bank_account_number']) && $_POST['bank_account_number'] != ""?esc_html($_POST['bank_account_number']):""),
				'privacy_email'           => (isset($_POST['privacy_email']) && $_POST['privacy_email'] != ""?esc_html($_POST['privacy_email']):""),
				'privacy_country'         => (isset($_POST['privacy_country']) && $_POST['privacy_country'] != ""?esc_html($_POST['privacy_country']):""),
				'privacy_city'            => (isset($_POST['privacy_city']) && $_POST['privacy_city'] != ""?esc_html($_POST['privacy_city']):""),
				'privacy_phone'           => (isset($_POST['privacy_phone']) && $_POST['privacy_phone'] != ""?esc_html($_POST['privacy_phone']):""),
				'privacy_gender'          => (isset($_POST['privacy_gender']) && $_POST['privacy_gender'] != ""?esc_html($_POST['privacy_gender']):""),
				'privacy_age'             => (isset($_POST['privacy_age']) && $_POST['privacy_age'] != ""?esc_html($_POST['privacy_age']):""),
				'privacy_social'          => (isset($_POST['privacy_social']) && $_POST['privacy_social'] != ""?esc_html($_POST['privacy_social']):""),
				'privacy_website'         => (isset($_POST['privacy_website']) && $_POST['privacy_website'] != ""?esc_html($_POST['privacy_website']):""),
				'privacy_bio'             => (isset($_POST['privacy_bio']) && $_POST['privacy_bio'] != ""?esc_html($_POST['privacy_bio']):""),
				'privacy_credential'      => (isset($_POST['privacy_email']) && $_POST['privacy_email'] != ""?esc_html($_POST['privacy_email']):""),
			);
			$posted = apply_filters("wpqa_edit_profile_posted",$posted);

			if (isset($_POST['delete_account']) && $_POST['delete_account'] == "on") {
				wp_delete_user($user_id,0);
				wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Profile has been deleted.","wpqa").'</p></div>','wpqa_session');
				wp_safe_redirect(esc_url(home_url('/')));
				exit;
			}

			if (!isset($_POST['wpqa_profile_nonce']) || !wp_verify_nonce($_POST['wpqa_profile_nonce'],'wpqa_profile_nonce')) {
				$errors->add('nonce-error','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There is an error, Please reload the page and try again.","wpqa"));
			}
			
			if (wpqa_is_user_edit_profile()) {
				if (empty($_POST['email'])) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (Email).","wpqa"));
				}
			}

			if (wpqa_is_user_password_profile()) {
				if (empty($_POST['pass1']) || empty($_POST['pass1'])) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (Password).","wpqa"));
				}
				if ($_POST['pass1'] !== $_POST['pass2']) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Password does not match.","wpqa"));
				}
			}else {
				unset($_POST['pass1']);
				unset($_POST['pass2']);
			}

			if (wpqa_is_user_financial_profile()) {
				if (empty($_POST['financial_payments'])) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (Financial Payments).","wpqa"));
				}
				if (isset($_POST['financial_payments'])) {
					if ($_POST['financial_payments'] == "paypal" && empty($_POST['paypal_email'])) {
						$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (PayPal E-Mail).","wpqa"));
					}else if ($_POST['financial_payments'] == "payoneer" && empty($_POST['payoneer_email'])) {
						$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (Payoneer E-Mail).","wpqa"));
					}else if ($_POST['financial_payments'] == "bank") {
						if (empty($_POST['bank_account_holder'])) {
							$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (Name of the Account Holder).","wpqa"));
						}
						if (empty($_POST['bank_your_address'])) {
							$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (Your Address).","wpqa"));
						}
						if (empty($_POST['bank_name'])) {
							$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (Bank Name).","wpqa"));
						}
						if (empty($_POST['bank_address'])) {
							$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (Bank Address).","wpqa"));
						}
						if (empty($_POST['bank_swift_iban'])) {
							$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (SWIFT/IBAN Code).","wpqa"));
						}
						if (empty($_POST['bank_account_number'])) {
							$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (Account Number).","wpqa"));
						}
					}
				}
			}else {
				unset($_POST['financial_payments']);
				unset($_POST['paypal_email']);
				unset($_POST['payoneer_email']);
				unset($_POST['bank_account_holder']);
				unset($_POST['bank_your_address']);
				unset($_POST['bank_name']);
				unset($_POST['bank_address']);
				unset($_POST['bank_swift_iban']);
				unset($_POST['bank_account_number']);
			}

			if (wpqa_is_user_edit_profile()) {
				do_action('wpqa_edit_profile_errors_main',$errors,$posted,$edit_profile_items_1,"edit",$user_id);
				
				if (empty($_POST['profile_credential']) && $profile_credential_register === "on" && $profile_credential_required == "on") {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("There are required fields (Profile credential).","wpqa"));
				}
				if (isset($_POST['profile_credential']) && $profile_credential_maximum > 0 && strlen($_POST['profile_credential']) > $profile_credential_maximum) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Sorry, The maximum characters for the profile credential is","wpqa")." ".$profile_credential_maximum);
				}
				if (isset($_POST['facebook']) && $_POST['facebook'] != "" && filter_var($_POST['facebook'],FILTER_VALIDATE_URL) === FALSE) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Not a valid URL (Facebook).","wpqa"));
				}
				if (isset($_POST['twitter']) && $_POST['twitter'] != "" && filter_var($_POST['twitter'],FILTER_VALIDATE_URL) === FALSE) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Not a valid URL (Twitter).","wpqa"));
				}
				if (isset($_POST['youtube']) && $_POST['youtube'] != "" && filter_var($_POST['youtube'],FILTER_VALIDATE_URL) === FALSE) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Not a valid URL (Youtube).","wpqa"));
				}
				if (isset($_POST['vimeo']) && $_POST['vimeo'] != "" && filter_var($_POST['vimeo'],FILTER_VALIDATE_URL) === FALSE) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Not a valid URL (Vimeo).","wpqa"));
				}
				if (isset($_POST['linkedin']) && $_POST['linkedin'] != "" && filter_var($_POST['linkedin'],FILTER_VALIDATE_URL) === FALSE) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Not a valid URL (Linkedin).","wpqa"));
				}
				if (isset($_POST['instagram']) && $_POST['instagram'] != "" && filter_var($_POST['instagram'],FILTER_VALIDATE_URL) === FALSE) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Not a valid URL (Instagram).","wpqa"));
				}
				if (isset($_POST['pinterest']) && $_POST['pinterest'] != "" && filter_var($_POST['pinterest'],FILTER_VALIDATE_URL) === FALSE) {
					$errors->add('required-field','<strong>'.esc_html__("Error","wpqa").' :&nbsp;</strong> '.esc_html__("Not a valid URL (Pinterest).","wpqa"));
				}
			}

			do_action('wpqa_edit_profile_errors',$errors,$posted,$edit_profile_items_1,"edit",$user_id);
			
			if ($errors->get_error_code()) {
				return $errors;
			}
			
			if (wpqa_is_user_edit_profile() || wpqa_is_user_password_profile()) {
				isset($_POST['admin_bar_front']) ? 'true' : 'false';
				if ($nickname !== 'on' && isset($_POST['nickname'])) {
					$_POST['nickname'] = get_the_author_meta("user_login",$user_id);
				}
				if (isset($_POST) && !isset($_POST['nickname'])) {
					$nicename_nickname = (isset($_POST['nickname']) && $_POST['nickname'] != ""?sanitize_text_field($_POST['nickname']):(isset($_POST['user_name']) && $_POST['user_name'] != ""?sanitize_text_field($_POST['user_name']):get_the_author_meta('user_login',$user_id)));
					$_POST['nickname'] = get_the_author_meta("user_login",$user_id);
				}
				$errors_user = edit_user($user_id);
				if (is_wp_error($errors_user)) {
					return $errors_user;
				}
			}

			do_action('wpqa_personal_update_profile',$user_id,$posted,isset($_FILES)?$_FILES:array(),"edit");

			if (sizeof($errors->errors) > 0) {
				return $errors;
			}

			$update_profile = get_user_meta($user_id,"update_profile",true);
			if ($update_profile == "yes") {
				delete_user_meta($user_id,"update_profile");
	  			wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Profile has been updated.","wpqa").'</p></div>','wpqa_session');
				wp_safe_redirect(esc_url(home_url('/')));
				exit;
			}
			return;
		}
	}
endif;
/* Edit profile form */
if (!function_exists('wpqa_edit_profile_form')) :
	function wpqa_edit_profile_form($edit) {
		if (isset($_POST["user_action"]) && $_POST["user_action"] == $edit) :
			$return = wpqa_process_edit_profile_form();
			if (is_wp_error($return)) :
	   			return '<div class="wpqa_error">'.$return->get_error_message().'</div>';
	   		else :
	   			return '<div class="wpqa_success">'.esc_html__("Profile has been updated.","wpqa").'</div>';
	   		endif;
		endif;
	}
endif;
add_filter('wpqa_edit_profile_form','wpqa_edit_profile_form');
/* Show profile fields */
add_action('show_user_profile','wpqa_show_extra_profile_fields');
add_action('edit_user_profile','wpqa_show_extra_profile_fields');
if (!function_exists('wpqa_show_extra_profile_fields')) :
	function wpqa_show_extra_profile_fields( $user ) {?>
		<table class="form-table">
			<tr class="form-terms">
				<th colspan="2" scope="row" valign="top">
					<div class="<?php echo wpqa_theme_name?>_framework">
						<?php wpqa_author_options("author",wpqa_author,"author",$user->ID,wpqa_admin_author($user->ID));?>
					</div>
				</th>
			</tr>
		</table>
	<?php }
endif;
/* Save user's meta */
add_action('wpqa_personal_update_profile','wpqa_save_extra_profile_fields');
add_action('personal_options_update','wpqa_save_extra_profile_fields');
add_action('edit_user_profile_update','wpqa_save_extra_profile_fields');
if (!function_exists('wpqa_save_extra_profile_fields')) :
	function wpqa_save_extra_profile_fields( $user_id ) {
		if ( !current_user_can( 'edit_user', $user_id ) ) return false;

		$options = wpqa_admin_author($user_id);
		$get_current_user_id = get_current_user_id();
		$points_social = (int)wpqa_options("points_social");
		
		if (isset($_POST['admin']) && $_POST['admin'] == "save") {
			do_action("wpqa_user_register",$user_id);
			
			if (isset($_POST['user_best_answer'])) {
				$user_best_answer = sanitize_text_field($_POST['user_best_answer']);
				update_user_meta( $user_id, 'user_best_answer', $user_best_answer );
			}
		}

		$user_data = get_userdata($user_id);
		$default_group = $user_data->roles;
		if (is_array($default_group)) {
			$default_group = $default_group[0];
		}
		if (isset($_POST['role']) && $_POST['role'] != "" && $default_group != $_POST['role']) {
			$default_group = esc_html($_POST['role']);
		}

		if (is_super_admin($get_current_user_id) && ((isset($_POST['remove_subscription']) && $_POST['remove_subscription'] == "on") || (isset($_POST['add_subscription']) && $_POST['add_subscription'] == "on" && isset($_POST['subscription_plan']) && $_POST['subscription_plan'] != "") || (isset($_POST['activate_user']) && $_POST['activate_user'] == "on") || (isset($_POST['approve_user']) && $_POST['approve_user'] == "on"))) {
			$default_group = wpqa_options("default_group");
			$default_group = (isset($default_group) && $default_group != ""?$default_group:"subscriber");
			$default_group = apply_filters("wpqa_default_group",$default_group,$user_id);
			if (isset($_POST['add_subscription']) && $_POST['add_subscription'] == "on" && isset($_POST['subscription_plan']) && $_POST['subscription_plan'] != "") {
				wpqa_cancel_subscription($user_id);
				update_user_meta($user_id,"subscribe_from_admin",true);
				$default_group = wpqa_options("subscriptions_group");
				$default_group = ($default_group != ""?$default_group:"author");
				$subscription_plan = esc_html($_POST['subscription_plan']);
				update_user_meta($user_id,"package_subscribe",$subscription_plan);
				if ($subscription_plan != "lifetime") {
					$interval = ($subscription_plan == "yearly" || $subscription_plan == "2years"?"year":"month");
					$interval_count = ($subscription_plan == "monthly" || $subscription_plan == "yearly" || $subscription_plan == "2years"?($subscription_plan == "2years"?2:1):($subscription_plan == "3months"?3:6));
					update_user_meta($user_id,"start_subscribe_time",strtotime(date("Y-m-d H:i:s")));
					update_user_meta($user_id,"end_subscribe_time",strtotime(date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." +$interval_count $interval +7 hour"))));
				}
			}else if (isset($_POST['remove_subscription']) && $_POST['remove_subscription'] == "on") {
				delete_user_meta($user_id,"start_subscribe_time");
				delete_user_meta($user_id,"end_subscribe_time");
				delete_user_meta($user_id,"package_subscribe");
				$trial_subscribe = get_user_meta($user_id,"trial_subscribe",true);
				$points_subscribe = get_user_meta($user_id,"points_subscribe",true);
				if ($trial_subscribe == "" && $points_subscribe == "") {
					wpqa_cancel_subscription($user_id);
				}
			}else {
				$activate_user_meta = (isset($_POST['activate_user']) && $_POST['activate_user'] == "on"?"activate_user":"approve_user");
				$activate_user = get_user_meta($user_id,$activate_user_meta,true);
				if ($activate_user == "") {
					$send_text = wpqa_send_mail(
						array(
							'content' => wpqa_options("email_approve_user"),
							'user_id' => $user_id,
						)
					);
					$email_title = wpqa_options("title_approve_user");
					$email_title = ($email_title != ""?$email_title:esc_html__("Confirm account","wpqa"));
					$email_title = wpqa_send_mail(
						array(
							'content' => $email_title,
							'title'   => true,
							'break'   => '',
							'user_id' => $user_id,
						)
					);
					wpqa_send_mails(
						array(
							'toEmail'     => esc_html($user_data->user_email),
							'toEmailName' => esc_html($user_data->display_name),
							'title'       => $email_title,
							'message'     => $send_text,
						)
					);
					update_user_meta($user_id,$activate_user_meta,"on");
					update_user_meta($user_id,"user_activated","activated");
					do_action("wpqa_user_activated",$user_id);
					if (isset($_POST['activate_user']) && $_POST['activate_user'] == "on") {
						delete_user_meta($user_id,"activation");
					}
				}
			}
			delete_user_meta($user_id,"wpqa_default_group");
			do_action("wpqa_after_registration",$user_id);
		}
		
		if (isset($_POST['from_admin']) && $_POST['from_admin'] == "yes") {
			$active_points = wpqa_options("active_points");
			if (is_super_admin($get_current_user_id) && $active_points == "on") {
				$add_remove_point = "";
				$the_points = "";
				$the_reason = "";
				if (isset($_POST['add_remove_point'])) {
					$add_remove_point = esc_html($_POST['add_remove_point']);
				}
				if (isset($_POST['the_points'])) {
					$the_points = (int)esc_html($_POST['the_points']);
				}
				if (isset($_POST['the_reason'])) {
					$the_reason = esc_html($_POST['the_reason']);
				}
				if ($the_points > 0) {
					if ($add_remove_point == "remove") {
						$add_remove_point_last = "-";
						$the_reason_last = "admin_remove_points";
					}else {
						$add_remove_point_last = "+";
						$the_reason_last = "admin_add_points";
					}
					$the_reason = (isset($the_reason) && $the_reason != ""?$the_reason:"");
					wpqa_add_points($user_id,$the_points,$add_remove_point_last,$the_reason_last);
					if ($get_current_user_id > 0 && $user_id > 0) {
						wpqa_notifications_activities($user_id,$get_current_user_id,"","","",$the_reason_last,"notifications",($the_reason != ""?$the_reason:""));
					}
				}
			}

			$new_moderator_categories = array();
			$moderator_categories = (isset($_POST[prefix_author."moderator_categories"])?$_POST[prefix_author."moderator_categories"]:array());
			$moderator_categories = (is_array($moderator_categories) && !empty($moderator_categories)?$moderator_categories:array());
			foreach ($moderator_categories as $key => $value) {
				$key = str_replace("cat-","",$key);
				$new_moderator_categories[] = $key;
			}
			update_user_meta($user_id,prefix_author."moderator_categories",$new_moderator_categories);
			foreach ($options as $value) {
				if (!isset($value['unset']) && $value['type'] != 'heading' && $value['type'] != "heading-2" && $value['type'] != "heading-3" && $value['type'] != "html" && $value['type'] != 'info' && $value['type'] != 'content') {
					$val = '';
					if (isset($value['std'])) {
						$val = $value['std'];
					}
					
					$field_name = $value['id'];
					if (isset($_POST[$field_name])) {
						$val = $_POST[$field_name];
					}
					
					if (!isset($_POST[$field_name]) && $value['type'] == "checkbox") {
						$val = 0;
					}
					
					if ('' === $val || array() === $val) {
						delete_user_meta($user_id,$field_name);
					}else {
						update_user_meta($user_id,$field_name,$val);
					}
				}
			}
		}else {
			if (wpqa_is_user_mails_profile()) {
				$post_array = array('question_schedules','received_email','unsubscribe_mails','new_payment_mail','send_message_mail','answer_on_your_question','answer_question_follow','notified_reply');
			}else if (wpqa_is_user_privacy_profile()) {
				$post_array = array('privacy_email','privacy_country','privacy_city','privacy_phone','privacy_gender','privacy_age','privacy_social','privacy_website','privacy_bio','privacy_credential');
			}else if (wpqa_is_user_financial_profile()) {
				$post_array = array('financial_payments','paypal_email','payoneer_email','bank_account_holder','bank_your_address','bank_name','bank_address','bank_swift_iban','bank_account_number');
			}else {
				$post_array = array('country','city','phone','gender','age','facebook','twitter','youtube','vimeo','linkedin','instagram','pinterest','show_point_favorite','received_message','profile_credential','categories_left_menu');
			}
			
			$post_array = apply_filters("wpqa_edit_profile_post_array",$post_array);

			if (isset($_POST["categories_left_menu"]) && is_array($_POST["categories_left_menu"]) && !empty($_POST["categories_left_menu"])) {
				foreach ($_POST["categories_left_menu"] as $key => $value) {
					$_POST["categories_left_menu"][$key]["value"] = (int)$value["value"];
				}
			}
			foreach ($post_array as $field_name) {
				$val = '';
				
				if (isset($_POST[$field_name])) {
					$val = $_POST[$field_name];
				}
				
				if ('' === $val || array() === $val) {
					if ($field_name == "facebook" || $field_name == "twitter" || $field_name == "youtube" || $field_name == "vimeo" || $field_name == "linkedin" || $field_name == "instagram" || $field_name == "pinterest") {
						if ($points_social > 0) {
							delete_user_meta($user_id,"add_".$field_name);
							if (get_user_meta($user_id,$field_name,true) != "" && get_user_meta($user_id,"remove_".$field_name,true) != true) {
								wpqa_add_points($user_id,$points_social,"-","remove_".$field_name);
							}
							update_user_meta($user_id,"remove_".$field_name,true);
						}
					}
					delete_user_meta($user_id,$field_name);
				}else {
					update_user_meta($user_id,$field_name,$val);
					if ($field_name == "facebook" || $field_name == "twitter" || $field_name == "youtube" || $field_name == "vimeo" || $field_name == "linkedin" || $field_name == "instagram" || $field_name == "pinterest") {
						if ($points_social > 0) {
							delete_user_meta($user_id,"remove_".$field_name);
							if (get_user_meta($user_id,"add_".$field_name,true) != true) {
								wpqa_add_points($user_id,$points_social,"+","add_".$field_name);
							}
							update_user_meta($user_id,"add_".$field_name,true);
						}
					}
				}
			}
		}

		if (isset($_POST['admin']) && $_POST['admin'] == "save") {
			do_action("wpqa_user_register_after_saved",$user_id);
		}

		if ((isset($_POST['admin']) && $_POST['admin'] == "save") || wpqa_is_user_edit_profile() || wpqa_is_user_password_profile()) {
			$nicename_nickname = (isset($_POST['nickname']) && $_POST['nickname'] != ""?sanitize_text_field($_POST['nickname']):(isset($_POST['user_name']) && $_POST['user_name'] != ""?sanitize_text_field($_POST['user_name']):get_the_author_meta('user_login',$user_id)));
			$show_edit_user = apply_filters("wpqa_show_edit_user",true);
			if ($show_edit_user == true) {
				edit_user($user_id);
			}
			wp_update_user(array('ID' => $user_id,'user_nicename' => $nicename_nickname,'nickname' => $nicename_nickname,'role' => $default_group));
			if (isset($_POST["redirect_to"]) && $_POST["redirect_to"] != "") {
				wp_redirect(esc_url($_POST["redirect_to"]));
				die();
			}
		}
	}
endif;
/* Exporter data */
add_filter('wp_privacy_personal_data_exporters','wpqa_register_exporter');
if (!function_exists('wpqa_register_exporter')) :
	function wpqa_register_exporter($exporters) {
		$exporters['my-plugin-data'] = array(
			'exporter_friendly_name' => esc_html__('Custom fields','wpqa'),
			'callback' => 'wpqa_exporter_data',
		);
	    return $exporters;
	}
endif;
if (!function_exists('wpqa_exporter_data')) :
	function wpqa_exporter_data($email_address,$page = 1) {
		$export_items = array();
		$user         = get_user_by('email',$email_address);
		$user_id      = $user->ID;

		$profile_credential = get_the_author_meta('profile_credential',$user_id);
		$twitter            = get_the_author_meta('twitter',$user_id);
		$facebook           = get_the_author_meta('facebook',$user_id);
		$youtube            = get_the_author_meta('youtube',$user_id);
		$vimeo              = get_the_author_meta('vimeo',$user_id);
		$linkedin           = get_the_author_meta('linkedin',$user_id);
		$instagram          = get_the_author_meta('instagram',$user_id);
		$pinterest          = get_the_author_meta('pinterest',$user_id);
		$country            = get_the_author_meta('country',$user_id);
		$city               = get_the_author_meta('city',$user_id);
		$age                = get_the_author_meta('age',$user_id);
		$phone              = get_the_author_meta('phone',$user_id);
		$gender             = get_the_author_meta('gender',$user_id);

		$data = array(
			array(
				'name'  => esc_html__('Profile credential','wpqa'),
				'value' => $profile_credential !== ''?esc_html($profile_credential):'',
			),
			array(
				'name'  => esc_html__('Twitter','wpqa'),
				'value' => $twitter !== ''?esc_url($twitter):'',
			),
			array(
				'name'  => esc_html__('Facebook','wpqa'),
				'value' => $facebook !== ''?esc_url($facebook):'',
			),
			array(
				'name'  => esc_html__('Youtube','wpqa'),
				'value' => $youtube !== ''?esc_url($youtube):'',
			),
			array(
				'name'  => esc_html__('Vimeo','wpqa'),
				'value' => $vimeo !== ''?esc_url($vimeo):'',
			),
			array(
				'name'  => esc_html__('Linkedin','wpqa'),
				'value' => $linkedin !== ''?esc_url($linkedin):'',
			),
			array(
				'name'  => esc_html__('Instagram','wpqa'),
				'value' => $instagram !== ''?esc_url($instagram):'',
			),
			array(
				'name'  => esc_html__('Pinterest','wpqa'),
				'value' => $pinterest !== ''?esc_url($pinterest):'',
			),
			array(
				'name'  => esc_html__('Country','wpqa'),
				'value' => $country !== ''?esc_html($country):'',
			),
			array(
				'name'  => esc_html__('City','wpqa'),
				'value' => $city !== ''?esc_html($city):'',
			),
			array(
				'name'  => esc_html__('Age','wpqa'),
				'value' => $age !== ''?esc_html($age):'',
			),
			array(
				'name'  => esc_html__('Phone','wpqa'),
				'value' => $phone !== ''?esc_html($phone):'',
			),
			array(
				'name'  => esc_html__('Gender','wpqa'),
				'value' => $gender !== ''?($gender == "male" || $gender == 1?esc_html__("Male","wpqa"):"").($gender == "female" || $gender == 2?esc_html__("Female","wpqa"):"").($gender == "other" || $gender == 3?esc_html__("Other","wpqa"):""):'',
			),
		);

		$export_items[] = array(
			'group_id'    => 'custom_fields',
			'group_label' => esc_html__('Custom fields','wpqa'),
			'item_id'     => $user_id,
			'data'        => $data,
		);

		return array(
			'data' => $export_items,
			'done' => true,
		);
	}
endif;?>