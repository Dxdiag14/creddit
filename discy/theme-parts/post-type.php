<?php $post_head_style = "";
if ($what_post == "quote" || $what_post == "link" || $what_post == "twitter" || $what_post == "facebook" || $what_post == "instagram" || $what_post == "soundcloud") {
	$discy_padding_top = discy_post_meta("padding_top");
	$discy_padding_right = discy_post_meta("padding_right");
	$discy_padding_bottom = discy_post_meta("padding_bottom");
	$discy_padding_left = discy_post_meta("padding_left");
	$post_head_background_transparent = discy_post_meta("post_head_background_transparent");
	$post_head_background = discy_post_meta("post_head_background");
	$post_head_background_img = discy_post_meta("post_head_background_img");
	$post_head_background_repeat = discy_post_meta("post_head_background_repeat");
	$post_head_background_fixed = discy_post_meta("post_head_background_fixed");
	$post_head_background_position_x = discy_post_meta("post_head_background_position_x");
	$post_head_background_position_y = discy_post_meta("post_head_background_position_y");
	$post_head_background_full = discy_post_meta("post_head_background_full");
	$discy_link_color = discy_post_meta("link_color");
	$discy_quote_color = discy_post_meta("quote_color");
	if ((isset($discy_padding_top) && $discy_padding_top != "") || (isset($discy_padding_right) && $discy_padding_right != "") || (isset($discy_padding_bottom) && $discy_padding_bottom != "") || (isset($discy_padding_left) && $discy_padding_left != "") || (isset($post_head_background) && $post_head_background != "") || $post_head_background_transparent == "on" || (isset($post_head_background_img) && $post_head_background_img != "") || (isset($discy_link_color) && $discy_link_color != "") || (isset($discy_quote_color) && $discy_quote_color != "")) {
		$post_head_style .= " style='";
		$post_head_style .= (isset($discy_padding_top) && $discy_padding_top != ""?"padding-top:".$discy_padding_top."px;":"");
		$post_head_style .= (isset($discy_padding_right) && $discy_padding_right != ""?"padding-right:".$discy_padding_right."px;":"");
		$post_head_style .= (isset($discy_padding_bottom) && $discy_padding_bottom != ""?"padding-bottom:".$discy_padding_bottom."px;":"");
		$post_head_style .= (isset($discy_padding_left) && $discy_padding_left != ""?"padding-left:".$discy_padding_left."px;":"");
		$post_head_style .= (isset($discy_link_color) && $discy_link_color != ""?"color:".$discy_link_color.";":"");
		$post_head_style .= (isset($discy_quote_color) && $discy_quote_color != ""?"color:".$discy_quote_color.";":"");
		if ($post_head_background_transparent == "on") {
			$post_head_style .= "background-color: transparent !important;";
		}else {
			$post_head_style .= (isset($post_head_background) && $post_head_background != ""?"background-color:".$post_head_background.";":"");
		}
		if (isset($post_head_background_img) && $post_head_background_img != "") {
			$post_head_style .= (isset($post_head_background_img) && $post_head_background_img != ""?"background-image:url(".$post_head_background_img.");":"");
			$post_head_style .= (isset($post_head_background_repeat) && $post_head_background_repeat != ""?"background-repeat:".$post_head_background_repeat.";":"");
			$post_head_style .= (isset($post_head_background_fixed) && $post_head_background_fixed != ""?"background-attachment:".$post_head_background_fixed.";":"");
			$post_head_style .= (isset($post_head_background_position_x) && $post_head_background_position_x != ""?"background-position-x:".$post_head_background_position_x.";":"");
			$post_head_style .= (isset($post_head_background_position_y) && $post_head_background_position_y != ""?"background-position-y:".$post_head_background_position_y.";":"");
			$post_head_style .= (isset($post_head_background_full) && $post_head_background_full == "on"?"-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;":"");
		}
		$post_head_style .= "'";
	}
}
$discy_quote_author = discy_post_meta("quote_author");
$discy_quote_content = discy_post_meta("quote_content");
$discy_quote_style = discy_post_meta("quote_style");

$discy_link_target = discy_post_meta("link_target");
$discy_link = discy_post_meta("link");
$discy_link_title = discy_post_meta("link_title");
$discy_link_style = discy_post_meta("link_style");

if ($what_post == "quote") {?>
	<div class="post-inner-quote" <?php echo ($post_head_style)?>>
		<div class="quote">
			<blockquote>
				<div class="quote-inner">
					<div class="post-type"><i class="fa fa-quote-left"></i></div>
					
					<div class="post-inner-content"><p><?php echo esc_attr($discy_quote_content);?></p></div>
					<?php if ($discy_quote_author != "") {?>
						<cite class="author">- <?php echo esc_attr($discy_quote_author)?></cite>
					<?php }?>
				</div>
			</blockquote>
		</div><!-- End quote -->
		<div class="clearfix"></div>
	</div><!-- End post-inner-quote -->
<?php }else if ($what_post == "link") {?>
	<a <?php echo ($post_head_style)?> href="<?php echo esc_url($discy_link)?>" <?php echo ($discy_link_target == "style_2"?"target='_blank'":"")?> class="post-inner-link link">
		<div class="post-type"><i class="fa fa-link"></i></div>
		<div class="post-link-inner">
			<?php echo esc_attr($discy_link_title)?>
			<span><?php echo esc_url($discy_link)?></span>
		</div><!-- End post-link-inner -->
	</a><!-- End post-inner-link -->
<?php }?>