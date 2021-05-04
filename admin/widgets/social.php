<?php
/* Social */
add_action( 'widgets_init', 'discy_widget_social_widget' );
function discy_widget_social_widget() {
	register_widget( 'Widget_Social' );
}

class Widget_Social extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'social-widget' );
		$control_ops = array( 'id_base' => 'social-widget' );
		parent::__construct( 'social-widget',discy_theme_name.' - Social media', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title  = apply_filters('widget_title', $instance['title'] );
		echo ($before_widget);
			if ($title) {
				echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
			}else {
				echo "<h3 class='screen-reader-text'>".esc_html__("Social media","discy")."</h3>";
			}?>
			<div class="widget-wrap">
				<?php
				$sort_social = discy_options("sort_social");
				$rss_icon_h = discy_options("rss_icon_h");
				$rss_icon_h_other = discy_options("rss_icon_h_other");
				$social = array(
					array("name" => "Facebook",   "value" => "facebook",   "icon" => "facebook"),
					array("name" => "Twitter",    "value" => "twitter",    "icon" => "twitter"),
					array("name" => "Linkedin",   "value" => "linkedin",   "icon" => "linkedin"),
					array("name" => "Dribbble",   "value" => "dribbble",   "icon" => "dribbble"),
					array("name" => "Youtube",    "value" => "youtube",    "icon" => "play"),
					array("name" => "Vimeo",      "value" => "vimeo",      "icon" => "vimeo"),
					array("name" => "Skype",      "value" => "skype",      "icon" => "skype"),
					array("name" => "WhatsApp",   "value" => "whatsapp",   "icon" => "whatsapp"),
					array("name" => "Flickr",     "value" => "flickr",     "icon" => "flickr"),
					array("name" => "Soundcloud", "value" => "soundcloud", "icon" => "soundcloud"),
					array("name" => "Instagram",  "value" => "instagram",  "icon" => "instagrem"),
					array("name" => "Pinterest",  "value" => "pinterest",  "icon" => "pinterest"),
					array("name" => "Rss",        "value" => "rss",        "icon" => "rss")
				);?>
				<ul class="social-ul">
					<?php if (isset($sort_social) && is_array($sort_social)) {
						$k = 0;
						foreach ($sort_social as $key_r => $value_r) {$k++;
							if (isset($sort_social[$key_r]["value"])) {
								$sort_social_value = $sort_social[$key_r]["value"];
								$social_icon_h = discy_options($sort_social_value."_icon_h");
							}else {
								$sort_social_value = $sort_social[$key_r]["icon"]["value"];
								$social_icon_h = $sort_social[$key_r]["url"]["value"];
							}
							
							if (isset($sort_social[$key_r]["default"]) && $sort_social[$key_r]["default"] == "yes") {
								if ($sort_social_value != "rss") {
									if ($social_icon_h != "") {?>
										<li class="social-<?php echo esc_attr($sort_social_value)?>"><a title="<?php echo esc_attr($sort_social[$key_r]["name"])?>" href="<?php echo ($sort_social_value == "skype"?"skype:":"").($sort_social_value == "whatsapp"?"whatsapp://send?abid=":"").($sort_social_value != "skype" && $sort_social_value != "whatsapp"?esc_url($social_icon_h):$social_icon_h).($sort_social_value == "skype"?"?call":"").($sort_social_value == "whatsapp"?"&text=".esc_html__("Hello","discy"):"")?>"<?php echo ($sort_social_value != "skype" && $sort_social_value != "whatsapp"?" target='_blank'":"")?>><i class="icon-<?php echo esc_attr($sort_social[$key_r]["icon"])?>"></i></a></li>
									<?php }
								}else {
									if ($rss_icon_h == "on") {?>
										<li class="social-<?php echo esc_attr($sort_social_value)?>"><a title="<?php esc_attr_e("Feed","discy")?>" href="<?php echo esc_url($rss_icon_h_other != ""?esc_url($rss_icon_h_other):esc_url(bloginfo('rss2_url')))?>" target="_blank"><i class="icon-<?php echo esc_attr($sort_social[$key_r]["icon"])?>"></i></a></li>
									<?php }
								}
							}else {
								$icon = $sort_social[$key_r]["icon"]["value"];
								$social_class = str_ireplace(" ","_",$sort_social_value)?>
								<li class="social-<?php echo esc_attr($social_class)?>"><a title="<?php echo esc_attr($sort_social[$key_r]["name"]["value"])?>" href="<?php echo ($sort_social_value == "skype"?"skype:":"").($sort_social_value == "whatsapp"?"whatsapp://send?abid=":"").($sort_social_value != "skype" && $sort_social_value != "whatsapp"?esc_url($social_icon_h):$social_icon_h).($sort_social_value == "skype"?"?call":"").($sort_social_value == "whatsapp"?"&text=".esc_html__("Hello","discy"):"")?>"<?php echo ($sort_social_value != "skype" && $sort_social_value != "whatsapp"?" target='_blank'":"")?>><i class="<?php echo esc_attr($icon)?>"></i></a></li>
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