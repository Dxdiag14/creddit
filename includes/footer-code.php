						<?php $confirm_email = (has_wpqa()?wpqa_users_confirm_mail():"");
						if ($confirm_email != "yes" && $site_users_only != "yes") {
							$adv_404 = discy_options("adv_404");
							if (is_404() && $adv_404 == "on") {
								$adv_404 = "on";
							}else {
								$adv_404 = "";
							}
							if (($adv_404 != "on" && is_404()) || !is_404()) {
								$after_content_adv_filter = apply_filters("discy_after_content_adv_filter",true);
								if ($after_content_adv_filter == true) {
									echo discy_ads("content_adv_type","content_adv_code","content_adv_href","content_adv_img","","on","discy-ad-footer","on");
								}
							}
						}?>
						
					</div><!-- End the-main-inner -->
					<div class="hide-main-inner"></div>
					<?php $custom_page_setting = discy_post_meta("custom_page_setting");
					if ((is_single() || is_page()) && isset($custom_page_setting) && $custom_page_setting == "on") {
						$sticky_sidebar = discy_post_meta("sticky_sidebar_s");
					}else {
						$sticky_sidebar = discy_options("sticky_sidebar");
					}
					$footer_style = discy_options("footer_style");
					$footer_copyrights = discy_options("footer_copyrights");
					$widget_icons = discy_options("widget_icons");
					if ($confirm_email != "yes" && $site_users_only != "yes") {
						$sidebar_where = discy_sidebars("sidebar_where");
						if ($sidebar_where == "menu_sidebar" || $sidebar_where == "sidebar") {?>
							<div class="hide-sidebar sidebar-width"><div class="hide-sidebar-inner"></div></div>
							<aside class="sidebar<?php echo ($footer_style == "sidebar"?" footer-sidebar":"")?> sidebar-width float_l<?php echo ($widget_icons != "on"?" no-widget-icons":"").($sticky_sidebar == "sidebar" || $sticky_sidebar == "side_menu_bar"?" fixed-sidebar":"")?>">
								<h3 class="screen-reader-text"><?php esc_html_e('Sidebar','discy')?></h3>
								<div class="inner-sidebar">
									<?php get_sidebar();
									if ($footer_style == "sidebar") {
										$footer_menu = discy_options("footer_menu");?>
										<section class="widget-footer">
											<?php wp_nav_menu(array('container' => '','container_class' => '','menu_id' => 'footer_menu','menu' => $footer_menu));?>
											<div class="clearfix"></div>
											<p class="credits"><?php echo stripslashes($footer_copyrights)?></p>
										</section>
									<?php }?>
								</div>
							</aside><!-- End sidebar -->
						<?php }
					}?>
				</main><!-- End discy-site-content -->
				
				<?php $tabs_menu = get_option("tabs_menu");
				$site_style = discy_options("site_style");
				if ($site_style != "style_3" && $site_style != "style_4" && $confirm_email != "yes" && $site_users_only != "yes" && ($tabs_menu === "on" || $sidebar_where == "menu_sidebar" || $sidebar_where == "menu_left")) {
					$left_area = discy_options("left_area");
					if ($left_area == "sidebar") {?>
						<div class="nav_menu_sidebar float_r<?php echo ($sticky_sidebar == "nav_menu" || $sticky_sidebar == "side_menu_bar"?" fixed_nav_menu":"")?>">
							<div class="nav_menu">
								<?php get_sidebar("left");?>
							</div><!-- End nav_menu -->
						</div><!-- End nav_menu_sidebar -->
					<?php }else {
						$left_menu_style = discy_options("left_menu_style");?>
						<nav class="nav_menu float_r<?php echo ($sticky_sidebar == "nav_menu" || $sticky_sidebar == "side_menu_bar"?" fixed_nav_menu":"").($left_menu_style == "style_2"?" nav_menu_2":"").($left_menu_style == "style_3"?" nav_menu_3":"")?>">
							<h3 class="screen-reader-text"><?php esc_html_e('Explore','discy')?></h3>
							<?php $left_menu = apply_filters("discy_left_menu",true);
							if ($left_menu == true) {
								if ($tabs_menu === "on") {
									$home_page_id = get_option("home_page_id");
									$discy_home_tabs = discy_post_meta("home_tabs",$home_page_id);
									$first_one = discy_home_setting($discy_home_tabs);
									if (isset($discy_home_tabs) && is_array($discy_home_tabs)) {
										if (isset($first_one) && $first_one != "") {?>
											<ul>
												<?php discy_home_tabs($discy_home_tabs,$first_one,"",$home_page_id);?>
											</ul>
										<?php }
									}
								}else {
									$left_menu_s = (is_single() || is_page()?discy_post_meta("left_menu"):"");
									$left_menu   = (is_user_logged_in()?"discy_explore_login":"discy_explore");
									$left_menu_s = ($left_menu_s != "" && $left_menu_s != 0?$left_menu_s:"");
									wp_nav_menu(array('container' => '','container_class' => 'nav_menu float_r',($left_menu_s != ""?"menu":"theme_location") => ($left_menu_s != "" && $left_menu_s != 0?$left_menu_s:$left_menu)));
								}
							}
							echo discy_ads("left_menu_adv_type","left_menu_adv_code","left_menu_adv_href","left_menu_adv_img","","on","discy-ad-left-menu","on");?>
						</nav><!-- End nav_menu -->
					<?php }
				}?>
			</div><!-- End the-main-container -->
		</div><!-- End discy-inner-content -->
	</div><!-- End main-content -->
	
	<?php $blog_h_where = discy_options("blog_h_where");
	if ($blog_h_where == "footer") {
		include locate_template("includes/blog-header-footer.php");
	}
	$sort_footer_elements = discy_options("sort_footer_elements");
	if ($footer_style != "sidebar") {
		$footer_skin = discy_options("footer_skin");
		
		$top_footer = discy_options("top_footer");
		$footer_widget_icons = discy_options("footer_widget_icons");
		$top_footer_padding_top = discy_options("top_footer_padding_top");
		$top_footer_padding_bottom = discy_options("top_footer_padding_bottom");
		$footer_layout = discy_options("footer_layout");
		
		$add_footer = discy_options("add_footer");
		
		$bottom_footer = discy_options("bottom_footer");
		$footer_padding_top = discy_options("footer_padding_top");
		$footer_padding_bottom = discy_options("footer_padding_bottom");
		$footer_mail = discy_options("footer_mail");
		$footer_phone = discy_options("footer_phone");
		
		$top_footer_padding = "";
		if ((isset($top_footer_padding_top) && $top_footer_padding_top != "" && $top_footer_padding_top > 0) || (isset($top_footer_padding_bottom) && $top_footer_padding_bottom != "" && $top_footer_padding_bottom > 0)) {
			$top_footer_padding .= " style='";
			if (isset($top_footer_padding_top) && $top_footer_padding_top != "" && $top_footer_padding_top > 0) {
				$top_footer_padding .= "padding-top:".$top_footer_padding_top."px;";
			}
			if (isset($top_footer_padding_bottom) && $top_footer_padding_bottom != "" && $top_footer_padding_bottom > 0) {
				$top_footer_padding .= "padding-bottom:".$top_footer_padding_bottom."px;";
			}
			$top_footer_padding .= "'";
		}
		
		$footer_padding = "";
		if ((isset($footer_padding_top) && $footer_padding_top != "" && $footer_padding_top > 0) || (isset($footer_padding_bottom) && $footer_padding_bottom != "" && $footer_padding_bottom > 0)) {
			$footer_padding .= " style='";
			if (isset($footer_padding_top) && $footer_padding_top != "" && $footer_padding_top > 0) {
				$footer_padding .= "padding-top:".$footer_padding_top."px;";
			}
			if (isset($footer_padding_bottom) && $footer_padding_bottom != "" && $footer_padding_bottom > 0) {
				$footer_padding .= "padding-bottom:".$footer_padding_bottom."px;";
			}
			$footer_padding .= "'";
		}
		
		if (empty($sort_footer_elements) || count($sort_footer_elements) <> 2 || (isset($sort_footer_elements[0]) && isset($sort_footer_elements[0]["value"]) && $sort_footer_elements[0]["value"] != "top_footer" && $sort_footer_elements[0]["value"] != "bottom_footer")) {
			$sort_footer_elements = array(array("value" => "top_footer","name" => "Top footer"),array("value" => "bottom_footer","name" => "Bottom footer"));
		}
		
		if ($top_footer == "on" || $bottom_footer == "on") {?>
			<footer class="footer<?php echo ($footer_widget_icons != "on"?" no-widget-icons":"").($footer_skin == "light"?" footer-light":"")?>" itemscope="" itemtype="https://schema.org/WPFooter">
				<?php do_action("discy_before_inner_footer")?>
				<div id="inner-footer" class="wrap clearfix">
					<?php if (isset($sort_footer_elements) && is_array($sort_footer_elements)) {
						foreach ($sort_footer_elements as $key_r => $value_r) {
							if ($confirm_email != "yes" && $site_users_only != "yes" && isset($value_r["value"]) && $value_r["value"] == "top_footer" && $top_footer == "on") {?>
								<div class="top-footer"<?php echo ($top_footer_padding)?>>
									<div class="the-main-container">
										<aside>
											<h3 class="screen-reader-text"><?php esc_html_e('Footer','discy')?></h3>
											<div class="<?php echo ($footer_layout == "footer_1c"?"col12":"").($footer_layout == "footer_2c"?"col6":"").($footer_layout == "footer_3c"?"col4":"").($footer_layout == "footer_4c"?"col3":"").($footer_layout == "footer_5c"?"col4":"")?>">
												<?php dynamic_sidebar('footer_1c_sidebar');?>
											</div>
											
											<?php if ($footer_layout != "footer_1c") {?>
												<div class="<?php echo ($footer_layout == "footer_2c"?"col6":"").($footer_layout == "footer_3c"?"col4":"").($footer_layout == "footer_4c"?"col3":"").($footer_layout == "footer_5c"?"col2":"")?>">
													<?php dynamic_sidebar('footer_2c_sidebar');?>
												</div>
											<?php }
											
											if ($footer_layout != "footer_1c" && $footer_layout != "footer_2c") {?>
												<div class="<?php echo ($footer_layout == "footer_3c"?"col4":"").($footer_layout == "footer_4c"?"col3":"").($footer_layout == "footer_5c"?"col2":"")?>">
													<?php dynamic_sidebar('footer_3c_sidebar');?>
												</div>
											<?php }
											
											if ($footer_layout != "footer_1c" && $footer_layout != "footer_2c" && $footer_layout != "footer_3c") {?>
												<div class="<?php echo ($footer_layout == "footer_4c"?"col3":"").($footer_layout == "footer_5c"?"col2":"")?>">
													<?php dynamic_sidebar('footer_4c_sidebar');?>
												</div>
											<?php }
											
											if ($footer_layout == "footer_5c") {?>
												<div class="col2">
													<?php dynamic_sidebar('footer_5c_sidebar');?>
												</div>
											<?php }?>
										</aside>
										<div class="clearfix"></div>
									</div><!-- End the-main-container -->
								</div><!-- End top-footer -->
								<?php if (isset($add_footer) && is_array($add_footer) && !empty($add_footer)) {
									$k_footer = 0;
									foreach ($add_footer as $add_footer_k => $add_footer_v) {
										$k_footer++;
										$background_color = $add_footer_v["background_color"];
										$padding_bottom = $add_footer_v["padding_bottom"];
										$padding_top = $add_footer_v["padding_top"];
										$layout = $add_footer_v["layout"];
										$first_column = $add_footer_v["first_column"];
										$second_column = $add_footer_v["second_column"];
										$third_column = $add_footer_v["third_column"];
										$fourth_column = $add_footer_v["fourth_column"];
										$fifth_column = $add_footer_v["fifth_column"];
										$top_footer_style = "";
										if ((isset($padding_top) && $padding_top != "" && $padding_top > 0) || (isset($padding_bottom) && $padding_bottom != "" && $padding_bottom > 0) || (isset($background_color) && $background_color != "")) {
											$top_footer_style .= " style='";
											if (isset($padding_top) && $padding_top != "" && $padding_top > 0) {
												$top_footer_style .= "padding-top:".$padding_top."px;";
											}
											if (isset($padding_bottom) && $padding_bottom != "" && $padding_bottom > 0) {
												$top_footer_style .= "padding-bottom:".$padding_bottom."px;";
											}
											if (isset($background_color) && $background_color != "") {
												$top_footer_style .= "background-color:".$background_color.";";
											}
											$top_footer_style .= "'";
										}?>
										<div class="top-footer"<?php echo ($top_footer_style)?>>
											<div class="the-main-container">
												<aside>
													<h3 class="screen-reader-text"><?php echo esc_html__('Footer','discy')." ".$k_footer?></h3>
													<div class="<?php echo ($layout == "footer_1c"?"col12":"").($layout == "footer_2c"?"col6":"").($layout == "footer_3c"?"col4":"").($layout == "footer_4c"?"col3":"").($layout == "footer_5c"?"col4":"")?>">
														<?php dynamic_sidebar(sanitize_title($first_column));?>
													</div>
													
													<?php if ($layout != "footer_1c") {?>
														<div class="<?php echo ($layout == "footer_2c"?"col6":"").($layout == "footer_3c"?"col4":"").($layout == "footer_4c"?"col3":"").($layout == "footer_5c"?"col2":"")?>">
															<?php dynamic_sidebar(sanitize_title($second_column));?>
														</div>
													<?php }
													
													if ($layout != "footer_1c" && $layout != "footer_2c") {?>
														<div class="<?php echo ($layout == "footer_3c"?"col4":"").($layout == "footer_4c"?"col3":"").($layout == "footer_5c"?"col2":"")?>">
															<?php dynamic_sidebar(sanitize_title($third_column));?>
														</div>
													<?php }
													
													if ($layout != "footer_1c" && $layout != "footer_2c" && $layout != "footer_3c") {?>
														<div class="<?php echo ($layout == "footer_4c"?"col3":"").($layout == "footer_5c"?"col2":"")?>">
															<?php dynamic_sidebar(sanitize_title($fourth_column));?>
														</div>
													<?php }
													
													if ($layout == "footer_5c") {?>
														<div class="col2">
															<?php dynamic_sidebar(sanitize_title($fifth_column));?>
														</div>
													<?php }?>
												</aside>
												<div class="clearfix"></div>
											</div><!-- End the-main-container -->
										</div><!-- End top-footer -->
									<?php }
								}
							}else if (isset($value_r["value"]) && $value_r["value"] == "bottom_footer" && $bottom_footer == "on" && isset($footer_copyrights) && $footer_copyrights != "") {?>
								<div class="bottom-footer"<?php echo ($footer_padding)?>>
									<div class="the-main-container">
										<p class="credits"><?php echo stripslashes($footer_copyrights)?></p>
									</div><!-- End the-main-container -->
								</div><!-- End bottom-footer -->
							<?php }
						}
					}?>
				</div><!-- End inner-footer -->
			</footer><!-- End footer -->
		<?php }
	}?>
</div><!-- End wrap -->
<?php do_action("discy_after_wrap");
$go_up_button = discy_options("go_up_button");
if ($go_up_button == "on") {?>
	<div class="go-up"><i class="icon-up-open-big"></i></div>
<?php }
$ask_button = discy_options("ask_button");
if ($ask_button == "on") {?>
	<a href="<?php echo (has_wpqa()?wpqa_add_question_permalink():"#")?>" title="<?php esc_attr_e("Ask a question","discy")?>" class="ask-button wpqa-question<?php echo apply_filters('wpqa_pop_up_class','').apply_filters('wpqa_pop_up_class_question','')?>"><i class="icon-pencil"></i></a>
<?php }?>