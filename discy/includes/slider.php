<?php $custom_sliders = discy_post_meta("custom_sliders");
if ((is_single() || is_page()) && $custom_sliders == "on") {
	$slider_h_logged = apply_filters("discy_slider_logged",discy_post_meta("slider_h_logged"));
}else {
	$slider_h_logged = apply_filters("discy_slider_logged",discy_options("slider_h_logged"));
}
if ((is_user_logged_in() && ($slider_h_logged == "logged" || $slider_h_logged == "both")) || (!is_user_logged_in() && ($slider_h_logged == "unlogged" || $slider_h_logged == "both"))) {
	if ((is_single() || is_page()) && $custom_sliders == "on") {
		$slider_h = apply_filters("discy_slider",discy_post_meta("slider_h"));
	}else {
		$slider_h = apply_filters("discy_slider",discy_options("slider_h"));
		$slider_h_home_pages = apply_filters("discy_slider_home_pages",discy_options("slider_h_home_pages"));
		$slider_h_pages = apply_filters("discy_slider_h_pages",discy_options("slider_h_pages"));
		$slider_h_pages = explode(",",$slider_h_pages);
	}
	$slider_h = apply_filters("discy_slider_h",$slider_h);
	$slider_h_home_pages = apply_filters("discy_slider_home_pages",(isset($slider_h_home_pages)?$slider_h_home_pages:""));
	if ($slider_h == "on" && ($custom_sliders == "on" || (((is_front_page() || is_home()) && $slider_h_home_pages == "home_page") || $slider_h_home_pages == "all_pages" || ($slider_h_home_pages == "all_posts" && is_singular("post")) || ($slider_h_home_pages == "all_questions" && is_singular("question")) || ($slider_h_home_pages == "custom_pages" && is_page() && isset($slider_h_pages) && is_array($slider_h_pages) && isset($post->ID) && in_array($post->ID,$slider_h_pages))))) {
		if ((is_single() || is_page()) && $custom_sliders == "on") {
			$custom_slider = apply_filters("discy_custom_slider",discy_post_meta("custom_slider"));
			$custom_slides = discy_post_meta("custom_slides");
		}else {
			$custom_slider = apply_filters("discy_custom_slider",discy_options("custom_slider"));
			$custom_slides = discy_options("custom_slides");
		}
		$custom_slides = apply_filters("discy_custom_slides",$custom_slides);
		if ($custom_slider == "custom") {
			echo do_shortcode(stripslashes($custom_slides));
		}else {
			if ((is_single() || is_page()) && $custom_sliders == "on") {
				$add_slides = apply_filters("discy_sliders",discy_post_meta("add_slides"));
			}else {
				$add_slides = apply_filters("discy_sliders",discy_options("add_slides"));
			}
			$add_slides = apply_filters("discy_add_slides",$add_slides);
			$first_tag = (is_front_page() || is_home()?"h1":"h2");
			$second_tag = (is_front_page() || is_home()?"h2":"h3");
			if (is_array($add_slides) && !empty($add_slides)) {?>
				<div class="slider-wrap">
					<ul<?php echo (count($add_slides) > 1?" class='slider-owl'":'')?>>
						<?php foreach ($add_slides as $key => $value) {
							$align        = (isset($value["align"])?$value["align"]:"");
							$login        = (isset($value["login"])?$value["login"]:"");
							$title        = (isset($value["title"])?$value["title"]:"");
							$title_2      = (isset($value["title_2"])?$value["title_2"]:"");
							$paragraph    = (isset($value["paragraph"])?$value["paragraph"]:"");
							$button_block = (isset($value["button_block"])?$value["button_block"]:"");
							$block        = (isset($value["block"])?$value["block"]:"");
							$button       = (isset($value["button"])?$value["button"]:"");
							$button_style = (isset($value["button_style"])?$value["button_style"]:"");?>
							<li id="slider-item-<?php echo esc_attr($key)?>" class="slider-item slider-item-<?php echo esc_attr($key)?>">
								<div class="slider-inner<?php echo ($align != ""?" slider-inner-".$align:"")?>">
									<div class="slider-opacity"></div>
									<div class="the-main-container">
										<div class="slider-content">
											<div class="row">
												<div class="slider-colmun <?php echo ($button_block == "block"?"slider-block col8":"col6").($button_block == "block" || $align == "center"?" slider-colmun-3":"")?>">
													<div>
														<?php echo stripslashes($title != ""?"<".$first_tag." class='slider-colmun-h".($button_block == "block"?" slider-block-h":"")."'>".$title."</".$first_tag.">":"").stripslashes($title_2 != ""?"<".$second_tag.">".$title_2."</".$second_tag.">":"").stripslashes($paragraph != ""?"<p>".$paragraph."</p>":"");?>
													</div>
													<?php if ($button_block == "button") {
														if ($button == "question") {
															$filter_class = "question";
															$slider_button_class = "wpqa-question";
															$button_link = (has_wpqa()?wpqa_add_question_permalink():"#");
															$button_text = esc_html__("Ask A Question","discy");
														}else if ($button == "post") {
															$filter_class = "post";
															$slider_button_class = "wpqa-post";
															$button_link = (has_wpqa()?wpqa_add_post_permalink():"#");
															$button_text = esc_html__("Add A New Post","discy");
														}else if (!is_user_logged_in() && $button == "login") {
															$filter_class = "login";
															$slider_button_class = "login-panel";
															$button_link = (has_wpqa()?wpqa_login_permalink():"#");
															$button_text = esc_html__("Login","discy");
														}else if (!is_user_logged_in() && $button == "signup") {
															$filter_class = "signup";
															$slider_button_class = "signup-panel";
															$button_link = (has_wpqa()?wpqa_signup_permalink():"#");
															$button_text = esc_html__("Create A New Account","discy");
														}else if ($button == "custom") {
															$filter_class = "";
															$button_target = (isset($value["button_target"])?$value["button_target"]:"");
															$slider_button_class = "";
															$button_link = (isset($value["button_link"])?$value["button_link"]:"");
															$button_text = (isset($value["button_text"])?$value["button_text"]:"");
														}
														$button_target = ($button == "custom" && isset($button_target) && $button_target == "new_page"?"_blank":"_self");
														if (isset($slider_button_class)) {?>
															<a target="<?php echo esc_attr($button_target)?>" class="<?php echo esc_attr("slider-button-".$button_style." ".$slider_button_class)?> button-default slider-button<?php echo apply_filters('wpqa_pop_up_class','').(isset($filter_class) && $filter_class != ''?apply_filters('wpqa_pop_up_class_'.$filter_class,''):'')?>" href="<?php echo esc_url($button_link)?>"><?php echo esc_html($button_text)?></a>
														<?php }
													}else if ($button_block == "block") {
														if ($block == "search") {
															$live_search = discy_options("live_search");?>
															<div class="slider-form">
																<form role="search" class="searchform main-search-form" method="get" action="<?php do_action("wpqa_search_permalink")?>">
																	<i class="icon-search"></i>
																	<input type="search"<?php echo ($live_search == "on"?" class='live-search live-search-icon' autocomplete='off'":"")?> placeholder="<?php esc_attr_e('Type Search Words','discy')?>" name="search" value="<?php echo do_action("wpqa_get_search")?>">
																	<?php if ($live_search == "on") {?>
																		<div class="loader_2 search_loader"></div>
																		<div class="search-results results-empty"></div>
																	<?php }?>
																	<input type="hidden" name="search_type" class="search_type" value="<?php do_action("wpqa_search_type")?>">
																	<input type="submit" value="<?php esc_attr_e('Search','discy')?>">
																</form>
															</div><!-- End slider-form -->
														<?php }else if ($block == "question") {?>
															<div class="slider-form slider-ask-form">
																<form>
																	<i class="icon-pencil"></i>
																	<input type="text" placeholder="<?php esc_attr_e("What's your question?","discy")?>">
																	<a href="<?php echo (has_wpqa()?wpqa_add_question_permalink():"#")?>" class="ask-click wpqa-question<?php echo apply_filters('wpqa_pop_up_class','').apply_filters('wpqa_pop_up_class_question','')?>"></a>
																	<input type="submit" value="<?php esc_attr_e('Ask Question','discy')?>">
																</form>
															</div><!-- End slider-form -->
														<?php }
													}?>
												</div>
												<?php if (!is_user_logged_in() && $button_block != "block" && ($align == "left" || $align == "right") && ($login == "login" || $login == "signup")) {?>
													<div class="slider-colmun-2 col4">
														<?php if ($login == "login") {?>
															<div class="panel-login panel-un-login" id="login-panel">
																<div class="pop-border-radius">
																	<div class="panel-pop-content">
																		<?php echo do_shortcode("[wpqa_login un-login='true']");?>
																	</div><!-- End panel-pop-content -->
																</div><!-- End pop-border-radius -->
																<div class="pop-footer">
																	<?php echo '<a href="#" class="signup-panel">'.esc_html__( 'Sign Up Here', 'discy' ).'</a>';?>
																</div><!-- End pop-footer -->
															</div><!-- End login-panel -->
														<?php }else {?>
															<div class="panel-signup panel-un-login" id="signup-panel">
																<div class="pop-border-radius">
																	<div class="panel-pop-content">
																		<?php echo do_shortcode("[wpqa_signup]");?>
																	</div><!-- End pop-border-radius -->
																</div><!-- End pop-border-radius -->
																<div class="pop-footer">
																	<?php echo '<a href="#" class="login-panel">'.esc_html__( 'Sign In Now', 'discy' ).'</a>';?>
																</div><!-- End pop-footer -->
															</div><!-- End signup -->
														<?php }?>
													</div>
												<?php }?>
											</div>
										</div><!-- End slider-content -->
									</div><!-- End the-main-container -->
								</div><!-- End slider-inner -->
							</li><!-- End slider-item -->
						<?php }?>
					</ul><!-- End slider-owl -->
				</div><!-- End slider-wrap -->
			<?php }
		}
	}
}?>