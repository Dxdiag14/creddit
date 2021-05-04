<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Head content */
add_action('wpqa_head_content','wpqa_head_content');
if (!function_exists('wpqa_head_content')) :
	function wpqa_head_content($login = "",$its_not_login = false) {
		$subscriptions_payment = wpqa_options("subscriptions_payment");?>
		<div class="put-wrap-pop">
			<?php if (!is_user_logged_in()) {
				$activate_need_to_login = wpqa_options("activate_need_to_login");
				if (is_page()) {
					global $post;
					$login_only = get_post_meta($post->ID,prefix_meta."login_only",true);
				}
				if ($activate_need_to_login == "on" && !is_home() && !is_front_page() && ((is_page() && $login_only != "on") || is_single()) && isset($_COOKIE[wpqa_options("uniqid_cookie").'wpqa_locked_content'])) {
					$show_pop_login = true;
				}
				if ($login == "" && isset($show_pop_login)) {?>
					<div class='wrap-pop wrap-pop-not-close'></div>
				<?php }
			}?>
		</div>
		<?php $user_id = get_current_user_id();
		if (is_singular("post") || is_singular("question")) {
			$active_popup_share = wpqa_options("active_popup_share");
			$popup_share_pages  = wpqa_options("popup_share_pages");
			$popup_share_users  = wpqa_options("popup_share_users");
			$popup_share_type   = wpqa_options("popup_share_type");
			$popup_share_visits = wpqa_options("popup_share_visits");
			$popup_share_shows  = wpqa_options("popup_share_shows");
			if ($active_popup_share == "on" && ((isset($popup_share_pages["questions"]) && $popup_share_pages["questions"] == "questions" && is_singular("question")) || (isset($popup_share_pages["posts"]) && $popup_share_pages["posts"] == "posts" && is_singular("post"))) && (($popup_share_users == "unlogged" && !is_user_logged_in()) || ($popup_share_users == "logged" && is_user_logged_in()) || $popup_share_users == "both")) {
				global $post;
				if (($popup_share_type == "owner" && isset($post->post_author) && $post->post_author > 0 && $post->post_author == $user_id) || $popup_share_type == "all") {
					if ($popup_share_shows == "day" || $popup_share_shows == "week" || $popup_share_shows == "month") {
						if ($user_id > 0) {
							$wpqa_share_shows = get_transient('wpqa_share_shows'.$user_id.$post->ID);
							if ($wpqa_share_shows === false) {
								$share_shows = "show_it";
							}
						}
						if ($popup_share_shows == "day") {
							$time_in_seconds = DAY_IN_SECONDS;
						}else if ($popup_share_shows == "week") {
							$time_in_seconds = WEEK_IN_SECONDS;
						}else if ($popup_share_shows == "month") {
							$time_in_seconds = MONTH_IN_SECONDS;
						}
						if ($user_id > 0) {
							set_transient('wpqa_share_shows'.$user_id.$post->ID,"share_done",$time_in_seconds);
						}else if (!is_user_logged_in()) {
							if (isset($_COOKIE[wpqa_options("uniqid_cookie").'wpqa_share_shows'.$user_id.$post->ID])) {
								unset($_COOKIE[wpqa_options("uniqid_cookie").'wpqa_share_shows'.$user_id.$post->ID]);
								setcookie(wpqa_options("uniqid_cookie").'wpqa_share_shows'.$user_id.$post->ID,null,-1,COOKIEPATH,COOKIE_DOMAIN);
							}else {
								$share_shows = "show_it";
							}
							setcookie(wpqa_options("uniqid_cookie").'wpqa_share_shows'.$user_id.$post->ID,"share_done",time()+$time_in_seconds,COOKIEPATH,COOKIE_DOMAIN);
						}
					}
					if (($popup_share_shows != "forever" && isset($share_shows) && $share_shows == "show_it") || $popup_share_shows == "forever") {
						$share_popup = $popup_share_visits;
					}
				}
			}
		}
		if (is_user_logged_in()) {
			$user_is_login = get_userdata($user_id);
			$user_login_group = (is_array($user_is_login->caps)?key($user_is_login->caps):"");
			$roles = $user_is_login->allcaps;
			if ($login == "") {
				$badges_style = wpqa_options("badges_style");
				$active_points_category = wpqa_options("active_points_category");
				if ($badges_style != "by_groups") {
					if ($active_points_category == "on") {
						$categories_user_points = get_user_meta($user_id,"categories_user_points",true);
						if (is_array($categories_user_points) && !empty($categories_user_points)) {
							$category_with_points = array();
							foreach ($categories_user_points as $category) {
								$category_with_points[$category] = (int)get_user_meta($user_id,"points_category".$category,true);
							}
							arsort($category_with_points);
							foreach ($category_with_points as $category => $points) {
								$show_badge_today = get_user_meta($user_id,"show_badge_today",true);
								if ($show_badge_today != date("j_n_Y")) {
									$get_badge = wpqa_get_badge($user_id,"name",$points);
									$badge_category = wpqa_get_badge($user_id,"key",$points)."_".$category;
									$wpqa_get_badge = get_user_meta($user_id,"badge_".$badge_category,true);
									update_user_meta($user_id,"badge_".$badge_category,$badge_category);
									if ($wpqa_get_badge == "" || ($badge_category != $wpqa_get_badge)) {
										$get_term = get_term($category,'question-category');
										$new_badge = $get_badge;
										$get_badge_color = wpqa_get_badge($user_id,"color",$points);
										update_user_meta($user_id,"show_badge_today",date("j_n_Y"));
										break;
									}
								}
							}
						}
					}else {
						$first_key = wpqa_get_badge($user_id,"first_key");
						if ($first_key === "") {
							$get_badge = wpqa_get_badge($user_id,"name");
							$wpqa_get_badge = get_user_meta($user_id,"badge_".$get_badge,true);
							if ($wpqa_get_badge == "" || ($get_badge != $wpqa_get_badge)) {
								$new_badge = $get_badge;
								$get_badge_color = wpqa_get_badge($user_id,"color");
								update_user_meta($user_id,"badge_".$get_badge,$get_badge);
							}
						}
					}
					if (isset($new_badge) && $new_badge != "") {
						do_action("wpqa_action_get_new_badge",$user_id,$new_badge,(isset($category) && $category > 0?$category:0),(isset($points) && $points > 0?$points:0));
					}
				}
				$_pop_notification = (int)get_user_meta($user_id,$user_id."_pop_notification",true);
				if ($_pop_notification > 0) {
					global $post;
					$pop_notification = get_user_meta($user_id,$user_id."_pop_notification_".$_pop_notification,true);
					$pop_notification_pages = explode(",",$pop_notification["pages"]);
					if (!isset($pop_notification["end"]) || (isset($pop_notification["end"]) && $pop_notification["end"] != "end")) {
						if ((isset($pop_notification["post_id"]) && $pop_notification["post_id"] != "" && (is_page() || is_single()) && $pop_notification["post_id"] == $post->ID) || (((is_front_page() || is_home()) && $pop_notification["home_pages"] == "home_page") || $pop_notification["home_pages"] == "all_pages" || ($pop_notification["home_pages"] == "all_posts" && is_singular("post")) || ($pop_notification["home_pages"] == "all_questions" && is_singular("question")) || ($pop_notification["home_pages"] == "custom_pages" && is_page() && isset($pop_notification_pages) && is_array($pop_notification_pages) && isset($post->ID) && in_array($post->ID,$pop_notification_pages)))) {
							$show_pop_notification = true;
							if (isset($pop_notification["notification_time"]) && $pop_notification["notification_time"] == "one_time") {
								$pop_notification["end"] = "end";
								update_user_meta($user_id,$user_id."_pop_notification_".$_pop_notification,$pop_notification);
							}
						}
					}
				}
			}
		}

		if ($login != "login" || $login == "") {
			$confirm_email = wpqa_options("confirm_email");
			if ($login != "login" && is_user_logged_in() && $confirm_email == "on") {
				$if_user_id = get_user_by("id",$user_id);
				if (isset($if_user_id->caps["activation"]) && $if_user_id->caps["activation"] == 1) {
					$site_users_only = "yes";
				}
			}
			
			if (($login == "" && isset($show_pop_login)) || ($login == "" && isset($show_pop_notification)) || ($login == "" && isset($new_badge) && $new_badge != "") || ($login != "login" && isset($_POST["form_type"]) && (($_POST["form_type"] == "add_question" && isset($_POST["question_popup"]) && $_POST["question_popup"] == "popup") || ($_POST["form_type"] == "add_post" && isset($_POST["post_popup"]) && $_POST["post_popup"] == "popup") || ($_POST["form_type"] == "add_message" && isset($_POST["message_popup"]) && $_POST["message_popup"] == "popup") || $_POST["form_type"] == "wpqa-signup" || $_POST["form_type"] == "wpqa-login" || $_POST["form_type"] == "wpqa-forget"))) {?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						function wrap_pop() {
							if (!jQuery(".wrap-pop").hasClass("wrap-pop-not-close")) {
								jQuery(".wrap-pop").on("click",function () {
									jQuery.when(jQuery(".panel-pop").fadeOut(200)).done(function() {
										jQuery(this).css({"top":"-100%","display":"none"});
										jQuery(".wrap-pop").remove();
									});
								});
							}
						}
						
						<?php if ($login == "" && isset($show_pop_login)) {
							$form_type = "wpqa-login";
							$pop_up = "login-panel";
						}else if (!isset($_REQUEST) && isset($share_popup) && $share_popup == "visit") {
							$pop_up = $form_type = "wpqa-share";
						}else if ($login == "" && isset($show_pop_notification)) {
							$pop_up = $form_type = "wpqa-notification";
						}else if ($login == "" && isset($new_badge) && $new_badge != "") {
							$pop_up = $form_type = "wpqa-badge";
						}else if ($login != "login") {
							$form_type = (isset($_POST["form_type"])?esc_html($_POST["form_type"]):"");
							if ($form_type == "wpqa-signup") {
								$pop_up = "signup-panel";
							}else if ($form_type == "wpqa-forget") {
								$pop_up = "lost-password";
							}else if ($form_type == "wpqa-login") {
								$pop_up = "login-panel";
							}else if ($form_type == "add_question" && isset($_POST["user_id"]) && esc_html($_POST["user_id"]) != "" && isset($_POST["question_popup"]) && $_POST["question_popup"] == "popup") {
								$pop_up = "wpqa-question-user";
							}else if ($form_type == "add_question" && isset($_POST["question_popup"]) && $_POST["question_popup"] == "popup") {
								$pop_up = "wpqa-question";
							}else if ($form_type == "add_post" && isset($_POST["post_popup"]) && $_POST["post_popup"] == "popup") {
								$pop_up = "wpqa-post";
							}else if ($form_type == "add_message" && isset($_POST["message_popup"]) && $_POST["message_popup"] == "popup") {
								$pop_up = "wpqa-message";
							}
						}
						if (isset($pop_up) && $pop_up != "") {?>
							panel_pop("#<?php echo esc_js($pop_up)?>","<?php echo esc_js($form_type)?>");
							
							function panel_pop(whatId,fromType) {
								var data_width = jQuery(whatId).attr("data-width");
								jQuery(".panel-pop").css({"top":"-100%","display":"none"});
								if (!jQuery(".wrap-pop-not-close").length) {
									jQuery(".wrap-pop").remove();
								}
								var cssMargin = (jQuery("body.rtl").length?"margin-right":"margin-left");
								var cssValue = "-"+(data_width !== undefined && data_width !== false?data_width/2:"")+"px";
								if (jQuery(whatId).length) {
									jQuery(whatId).css("width",(data_width !== undefined && data_width !== false?data_width:"")+"px").css(cssMargin,cssValue).show().animate({"top":"7%"},200);
									jQuery("html,body").animate({scrollTop:0},200);
									if (!jQuery(".wrap-pop-not-close").length) {
										jQuery(".put-wrap-pop").prepend("<div class='wrap-pop'></div>");
									}
								}
								wrap_pop();
							}
						<?php }?>
					});
				</script>
			<?php }
		}

		if (isset($share_popup)) {
			$site_users_only = wpqa_site_users_only();
			$under_construction = wpqa_under_construction();
			if ($site_users_only != "yes" && $under_construction != "on" && !is_page_template("template-landing.php")) {
				$post_id = (int)$post->ID;
				if (is_singular("question")) {
					$post_share = wpqa_options("question_share");
				}else {
					$post_share = wpqa_options("post_share");
				}
				$custom_page_setting = get_post_meta($post_id,"custom_page_setting",true);
				if ($custom_page_setting == "on") {
					$post_share = get_post_meta($post_id,"post_share",true);
				}
				$share_facebook = (isset($post_share["share_facebook"]["value"])?$post_share["share_facebook"]["value"]:"");
				$share_twitter  = (isset($post_share["share_twitter"]["value"])?$post_share["share_twitter"]["value"]:"");
				$share_linkedin = (isset($post_share["share_linkedin"]["value"])?$post_share["share_linkedin"]["value"]:"");
				$share_whatsapp = (isset($post_share["share_whatsapp"]["value"])?$post_share["share_whatsapp"]["value"]:"");?>
				<div class="panel-pop<?php echo " popup-share-".$share_popup?>" id="wpqa-share" data-width="690">
					<i class="icon-cancel"></i>
					<div class="panel-pop-content">
						<div class="referral-cover-inner">
							<h3><?php esc_html_e("Spread the word.","wpqa")?></h3>
							<?php if ($share_facebook == "share_facebook" || $share_twitter == "share_twitter" || $share_linkedin == "share_linkedin" || $share_whatsapp == "share_whatsapp") {?>
								<div class="referral-share">
									<p><?php esc_html_e("Share the link on social media.","wpqa")?></p>
									<?php wpqa_share($post_share,$share_facebook,$share_twitter,$share_linkedin,$share_whatsapp,"style_1","","","","",$post_id,"send_email");?>
								</div>
							<?php }?>
							<div class="referral-invitation">
								<div><input type="text" value="<?php the_permalink()?>"><a title="Copy" href="<?php the_permalink()?>"><i class="icon-clipboard"></i></a></div>
							</div>
						</div>
					</div><!-- End panel-pop-content -->
				</div><!-- End wpqa-share -->
			<?php }
		}
		
		if (is_user_logged_in()) {
			if (isset($show_pop_notification)) {?>
				<div class="panel-pop" id="wpqa-notification" data-width="690">
					<i class="icon-cancel"></i>
					<div class="panel-pop-content">
						<?php echo ($pop_notification["text"]);
						if ((isset($pop_notification["button_text"]) && $pop_notification["button_text"] != "") && (isset($pop_notification["button_url"]) && $pop_notification["button_url"] != "")) {?>
							<a target="<?php echo esc_attr($pop_notification["button_target"])?>" class="button-default" href="<?php echo esc_url($pop_notification["button_url"])?>"><?php echo esc_html($pop_notification["button_text"])?></a>
						<?php }?>
					</div><!-- End panel-pop-content -->
				</div><!-- End wpqa-notification -->
			<?php }
			if (isset($new_badge) && $new_badge != "") {
				$site_users_only = wpqa_site_users_only();
				$under_construction = wpqa_under_construction();
				if ($site_users_only != "yes" && $under_construction != "on" && !is_page_template("template-landing.php")) {?>
					<div class="panel-pop" id="wpqa-badge" data-width="690">
						<i class="icon-cancel"></i>
						<div class="panel-pop-content">
							<div class="new_badge" style='color: <?php echo esc_attr($get_badge_color)?>'><span class="wings-shape"><i class="icon-bucket"></i></span></div>
							<h3><?php esc_html_e("You just unlocked a new badge!","wpqa")?></h3>
							<p><?php echo sprintf(esc_html__("Woohoo! You've earned the %s badge%s, here's a new badge to celebrate! Looking for more? Browse the complete list of questions, or popular tags. Help us answer unanswered questions.","wpqa"),"\"<span style='color: ".$get_badge_color."'>".$new_badge."</span>\"",(isset($category) && $category > 0 && isset($get_term) && isset($get_term->slug)?" ".esc_html__("in the","wpqa")." <a href='".get_term_link($get_term->slug,'question-category')."'>".$get_term->name."</a>"." ".esc_html__("category","wpqa"):""))?></p>
							<?php $pages = get_pages(array('meta_key' => '_wp_page_template','meta_value' => 'template-badges.php'));
							if (isset($pages) && isset($pages[0]) && isset($pages[0]->ID)) {?>
								<a class="button-default" href="<?php echo get_the_permalink($pages[0]->ID)?>"><?php esc_html_e("Earn More Points!","wpqa")?></a>
							<?php }?>
						</div><!-- End panel-pop-content -->
					</div><!-- End wpqa-badge -->
				<?php }
			}
		}else {
			$login_popup = wpqa_options("login_popup");
			$register_popup = wpqa_options("register_popup");
			$lost_pass_popup = wpqa_options("lost_pass_popup");
			if ($register_popup == "on" || $its_not_login == true) {
				$signup_style = wpqa_options("signup_style");?>
				<div class="<?php echo ($its_not_login == true?'panel-signup panel-un-login'.(wpqa_is_signup()?"":""):'panel-pop'.($its_not_login != true && $signup_style == "style_2"?" panel-pop-image":"")).($login == "" && isset($show_pop_login)?" pop-not-close":"")?>"<?php echo($its_not_login != true && $signup_style == "style_2"?' data-width="770"':'')?> id="signup-panel">
					<?php echo ($its_not_login == true || ($login == "" && isset($show_pop_login))?'':'<i class="icon-cancel"></i>');
					$signup_details = wpqa_options("signup_details");
					if ($its_not_login == true || $signup_style != "style_2") {
						$logo_signup = wpqa_image_url_id(wpqa_options("logo_signup"));
						$logo_signup_retina = wpqa_image_url_id(wpqa_options("logo_signup_retina"));
						$logo_signup_height = wpqa_options("logo_signup_height");
						$logo_signup_width = wpqa_options("logo_signup_width");
						$text_signup = wpqa_options("text_signup");
					}?>
					<div class="pop-border-radius">
						<?php if ($its_not_login == true || $signup_style != "style_2") {?>
							<div class="pop-header">
								<h3>
									<?php if ($logo_signup != "" || $logo_signup_retina != "") {
										if ($logo_signup != "" || ($logo_signup_retina == "" && $logo_signup != "")) {?>
											<img width="<?php echo esc_attr($logo_signup_width)?>" height="<?php echo esc_attr($logo_signup_height)?>" class="signup-logo <?php echo ($logo_signup_retina == "" && $logo_signup != ""?"retina_screen":"default_screen")?>" alt="<?php esc_attr_e("Sign Up","wpqa")?>" src="<?php echo esc_url($logo_signup)?>">
										<?php }
										if ($logo_signup_retina != "") {?>
											<img width="<?php echo esc_attr($logo_signup_width)?>" height="<?php echo esc_attr($logo_signup_height)?>" class="signup-logo retina_screen" alt="<?php esc_attr_e("Sign Up","wpqa")?>" src="<?php echo esc_url($logo_signup_retina)?>">
										<?php }
									}else {
										esc_html_e("Sign Up","wpqa");
									}?>
								</h3>
								<?php if ($text_signup != "") {?>
									<p><?php echo wpqa_kses_stip($text_signup)?></p>
								<?php }?>
							</div><!-- End pop-header -->
						<?php }
						if ($its_not_login != true && $signup_style == "style_2") {?>
							<div class="panel-image-content">
								<div class="panel-image-opacity"></div>
								<div class="panel-image-inner">
									<h3><?php esc_html_e("Sign Up","wpqa");?></h3>
									<?php if ($signup_details != "") {?>
										<p><?php echo wpqa_kses_stip($signup_details)?></p>
									<?php }?>
								</div><!-- End panel-image-inner -->
								<?php echo ' <a href="'.wpqa_login_permalink().'" class="'.($its_not_login == true?"login-panel-un":"login-panel").apply_filters('wpqa_pop_up_class_login','').' button-default">'.esc_html__( 'Have an account?', 'wpqa' ).' '.esc_html__( 'Sign In', 'wpqa' ).'</a>';?>
							</div><!-- End panel-image-content -->
						<?php }?>
						<div class="panel-pop-content">
							<?php echo do_shortcode("[wpqa_signup]");
							if ($login == "" && isset($show_pop_login) && $subscriptions_payment == "on" && $signup_style != "style_2") {
								echo '<div class="pop-footer-subscriptions-2">'.wpqa_login_in_popup_subscriptions($its_not_login).'</div>';
							}?>
						</div><!-- End pop-border-radius -->
					</div><!-- End pop-border-radius -->
					<?php if (($login == "" && isset($show_pop_login)) || $its_not_login == true || $signup_style != "") {?>
						<div class="pop-footer<?php echo($login == "" && isset($show_pop_login) && $subscriptions_payment == "on"?" pop-footer-subscriptions":"").($signup_style != "style_2" || ($login == "" && isset($show_pop_login)) || $its_not_login == true?"":" wpqa_hide")?>">
							<?php if ($login == "" && isset($show_pop_login) && $subscriptions_payment == "on") {
								echo wpqa_paid_subscriptions();
							}else if ($signup_style == "style_2" || ($subscriptions_payment != "on" && $signup_style != "style_2")) {
								echo wpqa_login_in_popup_subscriptions($its_not_login);
							}else if ($signup_style != "style_2") {
								echo wpqa_login_in_popup_subscriptions($its_not_login);
							}?>
						</div><!-- End pop-footer -->
					<?php }?>
				</div><!-- End signup -->
			<?php }
			
			if ($login_popup == "on" || $its_not_login == true) {
				$login_style = wpqa_options("login_style");?>
				<div class="<?php echo ($its_not_login == true?'panel-login panel-un-login'.(wpqa_is_signup()?" wpqa_hide":""):'panel-pop'.($its_not_login != true && $login_style == "style_2"?" panel-pop-image":"")).($login == "" && isset($show_pop_login)?" pop-not-close":"")?>"<?php echo($its_not_login != true && $login_style == "style_2"?' data-width="770"':'')?> id="login-panel">
					<?php echo ($its_not_login == true || ($login == "" && isset($show_pop_login))?'':'<i class="icon-cancel"></i>');
					$login_details = wpqa_options("login_details");
					if ($its_not_login == true || $login_style != "style_2") {
						$logo_login = wpqa_image_url_id(wpqa_options("logo_login"));
						$logo_login_retina = wpqa_image_url_id(wpqa_options("logo_login_retina"));
						$logo_login_height = wpqa_options("logo_login_height");
						$logo_login_width = wpqa_options("logo_login_width");
						$text_login = wpqa_options("text_login");
					}?>
					<div class="pop-border-radius">
						<?php if ($its_not_login == true || $login_style != "style_2") {?>
							<div class="pop-header">
								<h3>
									<?php if ($logo_login != "" || $logo_login_retina != "") {
										if ($logo_login != "" || ($logo_login_retina == "" && $logo_login != "")) {?>
											<img width="<?php echo esc_attr($logo_login_width)?>" height="<?php echo esc_attr($logo_login_height)?>" class="login-logo <?php echo ($logo_login_retina == "" && $logo_login != ""?"retina_screen":"default_screen")?>" alt="<?php esc_attr_e("Sign In","wpqa")?>" src="<?php echo esc_url($logo_login)?>">
										<?php }
										if ($logo_login_retina != "") {?>
											<img width="<?php echo esc_attr($logo_login_width)?>" height="<?php echo esc_attr($logo_login_height)?>" class="login-logo retina_screen" alt="<?php esc_attr_e("Sign In","wpqa")?>" src="<?php echo esc_url($logo_login_retina)?>">
										<?php }
									}else {
										esc_html_e("Sign In","wpqa");
									}?>
								</h3>
								<?php if ($text_login != "") {?>
									<p><?php echo wpqa_kses_stip($text_login)?></p>
								<?php }?>
							</div><!-- End pop-header -->
						<?php }
						if ($its_not_login != true && $login_style == "style_2") {?>
							<div class="panel-image-content">
								<div class="panel-image-opacity"></div>
								<div class="panel-image-inner">
									<h3><?php esc_html_e("Sign In","wpqa");?></h3>
									<?php if ($login_details != "") {?>
										<p><?php echo wpqa_kses_stip($login_details)?></p>
									<?php }?>
								</div><!-- End panel-image-inner -->
								<?php echo ' <a href="'.wpqa_signup_permalink().'" class="'.($its_not_login == true?"signup-panel-un":"signup-panel").apply_filters('wpqa_pop_up_class_signup','').' button-default">'.esc_html__( 'Sign Up Here', 'wpqa' ).'</a>';?>
							</div><!-- End panel-image-content -->
						<?php }?>
						<div class="panel-pop-content">
							<?php echo do_shortcode("[wpqa_login".($its_not_login == true?" un-login='true'":"")."]");
							if ($login == "" && isset($show_pop_login) && $subscriptions_payment == "on" && $login_style != "style_2") {
								echo '<div class="pop-footer-subscriptions-2">'.wpqa_signup_in_popup_subscriptions($its_not_login).'</div>';
							}?>
						</div><!-- End panel-pop-content -->
					</div><!-- End pop-border-radius -->
					<?php if (($login == "" && isset($show_pop_login)) || $its_not_login == true || $login_style != "") {?>
						<div class="pop-footer<?php echo($login == "" && isset($show_pop_login) && $subscriptions_payment == "on"?" pop-footer-subscriptions":"").($login_style != "style_2" || ($login == "" && isset($show_pop_login)) || $its_not_login == true?"":" wpqa_hide")?>">
							<?php if ($login == "" && isset($show_pop_login) && $subscriptions_payment == "on") {
								echo wpqa_paid_subscriptions();
							}else if ($login_style == "style_2" || ($subscriptions_payment != "on" && $login_style != "style_2")) {
								echo wpqa_signup_in_popup_subscriptions($its_not_login);
							}else if ($login_style != "style_2") {
								echo wpqa_signup_in_popup($its_not_login);
							}?>
						</div><!-- End pop-footer -->
					<?php }?>
				</div><!-- End login-panel -->
			<?php }
			
			if ($lost_pass_popup == "on" || $its_not_login == true) {
				$pass_style = wpqa_options("pass_style");?>
				<div class="<?php echo ($its_not_login == true?'panel-password panel-un-login':'panel-pop'.($its_not_login != true && $pass_style == "style_2"?" panel-pop-image":"")).($login == "" && isset($show_pop_login)?" pop-not-close":"")?>"<?php echo($its_not_login != true && $pass_style == "style_2"?' data-width="770"':'')?> id="lost-password">
					<?php echo ($its_not_login == true || ($login == "" && isset($show_pop_login))?'':'<i class="icon-cancel"></i>');
					$pass_details = wpqa_options("pass_details");
					if ($its_not_login == true || $pass_style != "style_2") {
						$logo_pass = wpqa_image_url_id(wpqa_options("logo_pass"));
						$logo_pass_retina = wpqa_image_url_id(wpqa_options("logo_pass_retina"));
						$logo_pass_height = wpqa_options("logo_pass_height");
						$logo_pass_width = wpqa_options("logo_pass_width");
						$text_pass = wpqa_options("text_pass");
					}?>
					<div class="pop-border-radius">
						<?php if ($its_not_login == true || $pass_style != "style_2") {?>
							<div class="pop-header">
								<h3>
									<?php if ($logo_pass != "" || $logo_pass_retina != "") {
										if ($logo_pass != "" || ($logo_pass_retina == "" && $logo_pass != "")) {?>
											<img width="<?php echo esc_attr($logo_pass_width)?>" height="<?php echo esc_attr($logo_pass_height)?>" class="pass-logo <?php echo ($logo_pass_retina == "" && $logo_pass != ""?"retina_screen":"default_screen")?>" alt="<?php esc_attr_e("Forgot Password","wpqa")?>" src="<?php echo esc_url($logo_pass)?>">
										<?php }
										if ($logo_pass_retina != "") {?>
											<img width="<?php echo esc_attr($logo_pass_width)?>" height="<?php echo esc_attr($logo_pass_height)?>" class="pass-logo retina_screen" alt="<?php esc_attr_e("Forgot Password","wpqa")?>" src="<?php echo esc_url($logo_pass_retina)?>">
										<?php }
									}else {
										esc_html_e("Forgot Password","wpqa");
									}?>
								</h3>
								<?php if ($text_pass != "") {?>
									<p><?php echo wpqa_kses_stip($text_pass)?></p>
								<?php }?>
							</div><!-- End pop-header -->
						<?php }
						if ($its_not_login != true && $pass_style == "style_2") {?>
							<div class="panel-image-content">
								<div class="panel-image-opacity"></div>
								<div class="panel-image-inner">
									<h3><?php esc_html_e("Forgot Password","wpqa");?></h3>
									<?php if ($pass_details != "") {?>
										<p><?php echo wpqa_kses_stip($pass_details)?></p>
									<?php }?>
								</div><!-- End panel-image-inner -->
							</div><!-- End panel-image-content -->
						<?php }?>
						<div class="panel-pop-content">
							<?php echo do_shortcode("[wpqa_lost_pass".($its_not_login != true && $pass_style == "style_2"?" text='true'":"")."]");?>
						</div><!-- End panel-pop-content -->
					</div><!-- End pop-border-radius -->
					<?php if ($its_not_login == true || ($login == "" && isset($show_pop_login)) || $pass_style != "") {?>
						<div class="pop-footer<?php echo($pass_style != "style_2" || ($login == "" && isset($show_pop_login))?"":" wpqa_hide")?>">
							<?php echo wpqa_login_in_popup_subscriptions($its_not_login)?>
						</div><!-- End pop-footer -->
					<?php }?>
				</div><!-- End lost-password -->
			<?php }
		}
		
		$confirm_email = wpqa_users_confirm_mail(false);
		if ($confirm_email != "yes" && $login != "login") {
			$pay_ask = wpqa_options("pay_ask");
			$custom_permission = wpqa_options("custom_permission");
			$ask_question_no_register = wpqa_options("ask_question_no_register");
			$ask_question = wpqa_options("ask_question");
			if (($custom_permission == "on" && is_user_logged_in() && !is_super_admin($user_id) && empty($roles["ask_question"])) || ($custom_permission == "on" && !is_user_logged_in() && $ask_question != "on")) {
				if (!is_user_logged_in()) {
					$register = true;
				}
			}else if (!is_user_logged_in() && $ask_question_no_register != "on") {
				$register = true;
			}else {
				if (!is_user_logged_in() && $pay_ask == "on") {
					$register = true;
				}
			}

			$popup_class = (is_user_logged_in()?"panel-pop-login":"panel-pop-not-login");
			$ask_question_popup = wpqa_options("ask_question_popup");
			if ($ask_question_popup == "on") {?>
				<div class="panel-pop <?php echo esc_attr($popup_class)?>" id="wpqa-question"<?php echo (isset($register) && $register == true?"":' data-width="690"')?>>
					<i class="icon-cancel"></i>
					<div class="panel-pop-content">
						<?php echo do_shortcode("[wpqa_question popup='popup']");
						if ($subscriptions_payment == "on" && !is_user_logged_in()) {
							echo '<div class="pop-footer-subscriptions-2">'.wpqa_signup_in_popup_subscriptions($its_not_login).'</div>';
						}?>
					</div><!-- End panel-pop-content -->
					<?php if (isset($register) && $register == true) {?>
						<div class="pop-footer<?php echo($subscriptions_payment == "on"?" pop-footer-subscriptions":"")?>">
							<?php if ($subscriptions_payment == "on" && !is_user_logged_in()) {
								echo wpqa_paid_subscriptions();
							}else {
								echo wpqa_signup_in_popup($its_not_login);
							}?>
						</div><!-- End pop-footer -->
					<?php }?>
				</div><!-- End wpqa-question -->
				
				<?php $ask_question_to_users = wpqa_options("ask_question_to_users");
				if ($ask_question_to_users == "on" && wpqa_is_user_profile()) {?>
					<div class="panel-pop <?php echo esc_attr($popup_class)?>" id="wpqa-question-user"<?php echo (isset($register) && $register == true?"":' data-width="690"')?>>
						<i class="icon-cancel"></i>
						<div class="panel-pop-content">
							<?php echo do_shortcode("[wpqa_question type='user' popup='popup']");
							if ($subscriptions_payment == "on" && !is_user_logged_in()) {
								echo '<div class="pop-footer-subscriptions-2">'.wpqa_signup_in_popup_subscriptions($its_not_login).'</div>';
							}?>
						</div><!-- End panel-pop-content -->
						<?php if (isset($register) && $register == true) {?>
							<div class="pop-footer<?php echo($subscriptions_payment == "on"?" pop-footer-subscriptions":"")?>">
							<?php if ($subscriptions_payment == "on" && !is_user_logged_in()) {
								echo wpqa_paid_subscriptions();
							}else {
								echo wpqa_signup_in_popup($its_not_login);
							}?>
						</div><!-- End pop-footer -->
						<?php }?>
					</div><!-- End wpqa-question-user -->
				<?php }
			}
			
			$active_post_popup = wpqa_options("active_post_popup");
			if ($active_post_popup == "on") {
				$custom_permission = wpqa_options("custom_permission");
				$add_post_no_register = wpqa_options("add_post_no_register");
				$add_post = wpqa_options("add_post");
				if (($custom_permission == "on" && is_user_logged_in() && !is_super_admin($user_id) && empty($roles["add_post"])) || ($custom_permission == "on" && !is_user_logged_in() && $add_post != "on")) {
					if (!is_user_logged_in()) {
						$register = true;
					}
				}else if (!is_user_logged_in() && $add_post_no_register != "on") {
					$register = true;
				}?>
				<div class="panel-pop <?php echo esc_attr($popup_class)?>" id="wpqa-post"<?php echo (isset($register) && $register == true?"":' data-width="690"')?>>
					<i class="icon-cancel"></i>
					<div class="panel-pop-content">
						<?php echo do_shortcode("[wpqa_add_post popup='popup']");
						if ($subscriptions_payment == "on" && !is_user_logged_in()) {
							echo '<div class="pop-footer-subscriptions-2">'.wpqa_signup_in_popup_subscriptions($its_not_login).'</div>';
						}?>
					</div><!-- End panel-pop-content -->
					<?php if (isset($register) && $register == true) {?>
						<div class="pop-footer<?php echo($subscriptions_payment == "on"?" pop-footer-subscriptions":"")?>">
							<?php if ($subscriptions_payment == "on" && !is_user_logged_in()) {
								echo wpqa_paid_subscriptions();
							}else {
								echo wpqa_signup_in_popup($its_not_login);
							}?>
						</div><!-- End pop-footer -->
					<?php }?>
				</div><!-- End wpqa-post -->
			<?php }
			
			$active_message = wpqa_options("active_message");
			$active_message_filter = apply_filters('wpqa_active_message_filter',false);
			if ($active_message == "on" && (wpqa_is_user_profile() || $active_message_filter == true)) {
				$user_block_message = array();
				$send_message_no_register = wpqa_options("send_message_no_register");
				$wpqa_user_id = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
				$wpqa_user_id = apply_filters('wpqa_user_id_message_filter',$wpqa_user_id);
				
				if (isset($wpqa_user_id) && $wpqa_user_id > 0) {
					$received_message = esc_attr(get_the_author_meta('received_message',$wpqa_user_id));
					$user_block_message = get_user_meta($wpqa_user_id,"user_block_message",true);
				}
				$block_message = esc_attr(get_the_author_meta('block_message',$wpqa_user_id));
				
				if (((!is_user_logged_in() && $send_message_no_register == "on") || (is_user_logged_in() && (empty($user_block_message) || (isset($user_block_message) && is_array($user_block_message) && !in_array($user_id,$user_block_message))) && ($block_message != "on" || is_super_admin($wpqa_user_id)) && isset($received_message) && $received_message == "on"))) {
					$custom_permission = wpqa_options("custom_permission");
					$send_message = wpqa_options("send_message");
					if (($custom_permission != "on" && ((isset($user_login_group) && $user_login_group == "wpqa_under_review") || (isset($user_login_group) && $user_login_group == "activation"))) || ($custom_permission == "on" && (is_user_logged_in() && !is_super_admin($user_id) && empty($roles["send_message"])) || (!is_user_logged_in() && $send_message != "on"))) {
						$register = true;
					}else if (!is_user_logged_in() && $send_message_no_register != "on") {
						$register = true;
					}?>
					<div class="panel-pop <?php echo esc_attr($popup_class)?>" id="wpqa-message"<?php echo (isset($register) && $register == true?"":' data-width="690"')?>>
						<i class="icon-cancel"></i>
						<div class="panel-pop-content">
							<?php echo do_shortcode("[wpqa_send_message popup='popup']");
							if ($subscriptions_payment == "on" && !is_user_logged_in()) {
								echo '<div class="pop-footer-subscriptions-2">'.wpqa_signup_in_popup_subscriptions($its_not_login).'</div>';
							}?>
						</div><!-- End panel-pop-content -->
						<?php if (isset($register) && $register == true) {?>
							<div class="pop-footer<?php echo($subscriptions_payment == "on"?" pop-footer-subscriptions":"")?>">
								<?php if ($subscriptions_payment == "on" && !is_user_logged_in()) {
									echo wpqa_paid_subscriptions();
								}else {
									echo wpqa_signup_in_popup($its_not_login);
								}?>
							</div><!-- End pop-footer -->
						<?php }?>
					</div><!-- End wpqa-message -->
				<?php }
			}
			
			$active_reports = wpqa_options("active_reports");
			$active_logged_reports = wpqa_options("active_logged_reports");
			if ($active_reports == "on" && (is_user_logged_in() || (!is_user_logged_in() && $active_logged_reports != "on"))) {
				global $post;?>
				<div class="panel-pop <?php echo esc_attr($popup_class)?>" id="wpqa-report">
					<i class="icon-cancel"></i>
					<div class="panel-pop-content">
						<p><?php esc_html_e("Please briefly explain why you feel this answer should be reported.","wpqa")?></p>
						<form class="wpqa_form submit-report" method="post">
							<div class="wpqa_error"></div>
							<div class="wpqa_success"></div>
							<div class="form-inputs clearfix">
								<p class="login-text">
									<label for="explain-reported"><?php esc_html_e("Explain","wpqa")?><span class="required">*</span></label>
									<textarea id="explain-reported" name="explain"></textarea>
									<i class="icon-pencil"></i>
								</p>
							</div>
							<p class="form-submit">
								<span class="load_span"><span class="loader_2"></span></span>
								<?php wp_nonce_field('wpqa_report_nonce','wpqa_report_nonce',false)?>
								<input type="submit" value="<?php esc_attr_e("Report","wpqa")?>" class="button-default button-hide-click">
							</p>
							<input type="hidden" name="form_type" value="wpqa-report">
							<input type="hidden" name="post_id" value="<?php echo (isset($post->ID)?esc_attr($post->ID):"")?>">
						</form>
					</div><!-- End panel-pop-content -->
				</div><!-- End wpqa-report -->
			<?php }
		}
	}
endif;
/* Login link in popup with subscriptions */
if (!function_exists('wpqa_login_in_popup_subscriptions')) :
	function wpqa_login_in_popup_subscriptions($its_not_login = false) {
		return esc_html__( 'Have an account?', 'wpqa' ).' <a href="'.wpqa_login_permalink().'" class="'.($its_not_login == true?'login-panel-un':'login-panel').apply_filters('wpqa_pop_up_class_login','').'">'.esc_html__( 'Sign In Now', 'wpqa' ).'</a>';
	}
endif;
/* Signup link in popup */
if (!function_exists('wpqa_signup_in_popup')) :
	function wpqa_signup_in_popup($its_not_login = false) {
		return esc_html__( 'Need An Account,', 'wpqa' ).' <a href="'.wpqa_signup_permalink().'" class="'.($its_not_login == true?'signup-panel-un':'signup-panel').''.apply_filters('wpqa_pop_up_class_signup','').'">'.esc_html__( 'Sign Up Here', 'wpqa' ).'</a>';
	}
endif;
/* Signup link in popup with subscriptions */
if (!function_exists('wpqa_signup_in_popup_subscriptions')) :
	function wpqa_signup_in_popup_subscriptions($its_not_login = false) {
		return esc_html__( "Don't have account,", "wpqa" ).' <a href="'.wpqa_signup_permalink().'" class="'.($its_not_login == true?'signup-panel-un':'signup-panel').apply_filters('wpqa_pop_up_class_signup','').'">'.esc_html__( 'Sign Up Here', 'wpqa' ).'</a>';
	}
endif;?>