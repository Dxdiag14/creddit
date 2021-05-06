<?php
/* About */
add_action( 'widgets_init', 'discy_widget_about_widget' );
function discy_widget_about_widget() {
	register_widget( 'Widget_About' );
}
class Widget_About extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'about-widget' );
		$control_ops = array( 'id_base' => 'about-widget' );
		parent::__construct( 'about-widget',discy_theme_name.' - About', $widget_ops, $control_ops );
	}
	
	public function widget( $args, $instance ) {
		extract( $args );
		$title        = apply_filters('widget_title', $instance['title'] );
		$logo         = esc_attr(discy_image_url_id($instance['logo']));
		$margin_logo  = esc_attr((int)$instance['margin_logo']);
		$text         = discy_kses_stip($instance['text'],"","yes");
		$padding_text = esc_attr((int)$instance['padding_text']);

		echo ($before_widget);?>
			<div class="widget-wrap">
				<div class="about-image<?php echo (isset($text) && $text != ""?" about-image-text":"")?>"<?php echo (isset($margin_logo) && $margin_logo > 0?" style='margin-top:".$margin_logo."px'":"")?>>
					<?php if ($logo != "") {?>
						<img src="<?php echo ($logo)?>" alt="<?php echo get_bloginfo('name')?>">
					<?php }?>
				</div>
				<div class="about-text"<?php echo (isset($padding_text) && $padding_text > 0?" style='padding-top:".$padding_text."px'":"")?>>
					<?php if ($title) {
						echo ($title == "empty"?"<div class='empty-title'>":"").($before_title.($title == "empty"?"":esc_attr($title)).$after_title).($title == "empty"?"</div>":"");
					}else {
						echo "<h3 class='screen-reader-text'>".esc_html__("About","discy")."</h3>";
					}
					echo ($text)?>
				</div>
			</div>
		<?php echo ($after_widget);
	}

	public function form( $instance ) {
		/* Save Button */
	}
}?>