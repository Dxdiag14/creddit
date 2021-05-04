<?php

/* @author    2codeThemes
*  @package   WPQA/functions
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* Get options */
if (!function_exists('wpqa_options')):
	function wpqa_options( $name, $default = false ) {
		$options = get_option(wpqa_theme_name."_options");
		if ( isset( $options[$name] ) ) {
			return $options[$name];
		}
		return $default;
	}
endif;
add_filter("the_content","do_shortcode");
add_filter("widget_text","do_shortcode");
/* Captcha */
if (!function_exists('wpqa_add_captcha')) :
	function wpqa_add_captcha($the_captcha,$type,$rand,$comment = "") {
		$captcha_style = wpqa_options("captcha_style");
		$captcha_question = wpqa_options("captcha_question");
		$captcha_answer = wpqa_options("captcha_answer");
		$show_captcha_answer = wpqa_options("show_captcha_answer");
		$out = "";
		$captcha_users = wpqa_options("captcha_users");
		if ($the_captcha == "on" && ($captcha_users == "both" || ($captcha_users == "unlogged" && !is_user_logged_in()))) {
			$out .= "<div class='".($captcha_style == "question_answer" || $captcha_style == "google_recaptcha"?"wpqa_captcha_question":"wpqa_captcha_normal")."'><".($comment == "comment" || $captcha_style == "google_recaptcha"?"div":"p")." class='wpqa_captcha_p".($comment == "comment"?" form-input form-input-full clearfix":"")."'>";
					$out .= ($comment == "comment"?"":"<label for='wpqa_captcha_".$rand."'>".esc_html__('Captcha','wpqa')."<span class='required'>*</span></label>");
				if ($captcha_style != "google_recaptcha") {
					$out .= '<input'.($comment == "comment"?" placeholder='".esc_attr__("Captcha","wpqa")."'":"").' id="wpqa_captcha_'.$rand.'" name="wpqa_captcha" class="wpqa_captcha'.($captcha_style == "google_recaptcha"?" google_recaptcha":"").($captcha_style == "question_answer"?" captcha_answer":"").'" type="text">
					'.($type == 'comment'?'':'<i class="icon-pencil"></i>');
				}
			if ($captcha_style == "google_recaptcha") {
				$out .= "<div class='g-recaptcha' data-sitekey='".wpqa_options("site_key_recaptcha")."'></div><br>";
			}else if ($captcha_style == "question_answer") {
				$out .= "<span class='wpqa_captcha_span'>".$captcha_question.($show_captcha_answer == "on"?" ( ".$captcha_answer." )":"")."</span>";
			}else {
				$out .= "<img class='wpqa_captcha_img' src='".add_query_arg(array("captcha_type" => $type),plugin_dir_url(dirname(__FILE__))."captcha/create_image.php")."' alt='".esc_attr__("Captcha","wpqa")."' title='".esc_attr__("Click here to update the captcha","wpqa")."' onclick=";$out .='"javascript:wpqa_get_captcha';$out .="('".add_query_arg(array("captcha_type" => $type),plugin_dir_url(dirname(__FILE__))."captcha/create_image.php")."', 'wpqa_captcha_img_".$rand."');";$out .='"';$out .=" id='wpqa_captcha_img_".$rand."'>
				<span class='wpqa_captcha_span'>".esc_html__("Click on image to update the captcha.","wpqa")."</span>";
			}
			$out .= "</".($comment == "comment" || $captcha_style == "google_recaptcha"?"div":"p")."></div>";
		}
		return $out;
	}
endif;
/* Check captcha */
if (!function_exists('wpqa_check_captcha')) :
	function wpqa_check_captcha($the_captcha,$type,$posted,$errors) {
		$captcha_users = wpqa_options("captcha_users");
		$captcha_style = wpqa_options("captcha_style");
		$captcha_question = wpqa_options("captcha_question");
		$captcha_answer = wpqa_options("captcha_answer");
		$show_captcha_answer = wpqa_options("show_captcha_answer");
		$the_captcha = (!isset($_POST['mobile']) && !isset($_GET['mobile'])?$the_captcha:0);
		if ($the_captcha === "on" && ($captcha_users == "both" || ($captcha_users == "unlogged" && !is_user_logged_in()))) {
			if ($captcha_style == "google_recaptcha") {
				if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
					$secretKey = wpqa_options("secret_key_recaptcha");
					$data = wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
					if (is_wp_error($data)) {
						$errors->add('required-captcha-error',esc_html__('Robot verification failed, Please try again.','wpqa'));
					}else {
						$json = json_decode($data['body'],true);
					}
					if (isset($json["success"]) && $json["success"] == true) {
						//success
					}else {
						$errors->add('required-captcha-error',esc_html__('Robot verification failed, Please try again.','wpqa'));
					}
				}else {
					$errors->add('required-captcha-error',esc_html__('Please check on the reCAPTCHA box.','wpqa'));
				}
			}else {
				if (empty($posted["wpqa_captcha"])) {
					$errors->add('required-captcha',esc_html__("There are required fields (captcha).","wpqa"));
				}
				else if ($captcha_style == "question_answer") {
					if ($captcha_answer != $posted["wpqa_captcha"]) {
						$errors->add('required-captcha-error',esc_html__('The captcha is incorrect, Please try again.','wpqa'));
					}
				}else {
					if (!session_id() && !headers_sent()) {
						session_start();
					}
					if (isset($_SESSION["wpqa_code_captcha_".$type]) && $_SESSION["wpqa_code_captcha_".$type] != $posted["wpqa_captcha"]) {
						$errors->add('required-captcha-error',esc_html__('The captcha is incorrect, Please try again.','wpqa'));
					}
				}
			}
		}
		return $errors;
	}
endif;
/* Allow the uploads */
add_action('admin_init','wpqa_allow_uploads');
function wpqa_allow_uploads() {
	if (is_user_logged_in()) {
		$user_info = get_userdata(get_current_user_id());
		$custom_permission = wpqa_options("custom_permission");
		$roles = $user_info->allcaps;
		$allow_to_upload = apply_filters('wpqa_allow_to_upload',true);
		if ($allow_to_upload == true && !current_user_can('upload_files') && ($custom_permission != "on" || ($custom_permission == "on" && isset($roles["upload_files"]) && $roles["upload_files"] == 1))) {
			$new_role = get_role(implode(', ',$user_info->roles));
			$new_role->add_cap('upload_files');
		}
	}
}
/* Get images */
if (!function_exists('wpqa_option_images')) :
	function wpqa_option_images($value_id = '',$value_width = '',$value_height = '',$value_options = '',$val = '',$value_class = '',$option_name = '',$name_id = '',$data_attr = '',$add_value_id = '') {
		$output = '';
		$name = $option_name .($add_value_id != 'no'?'['. $value_id .']':'');
		$width = (isset($value_width) && $value_width != ""?" width='".$value_width."' style='box-sizing: border-box;-moz-box-sizing: border-box;-weblit-box-sizing: border-box;'":"");
		$height = (isset($value_height) && $value_height != ""?" height='".$value_height."' style='box-sizing: border-box;-moz-box-sizing: border-box;-weblit-box-sizing: border-box;'":"");
		foreach ( $value_options as $key => $option ) {
			$selected = '';
			if ( $val != '' && ($val == $key) ) {
				$selected = ' of-radio-img-selected';
			}
			$output .= '<div class="of-radio-img-label">' . esc_html( $key ) . '</div>';
			$output .= '<input type="radio" data-attr="' . esc_attr( $data_attr ) . '" class="of-radio-img-radio" value="' . esc_attr( $key ) . '" '.($name_id != "no"?' id="' . esc_attr( $value_id .'_'. $key) . '" name="' . esc_attr( $name ) . '"':'').' '. checked( $val, $key, false ) .'>';
			$output .= '<img'.$width.$height.' src="' . esc_url( $option ) . '" alt="' . $option .'" class="of-radio-img-img '.(isset($value_class)?esc_attr($value_class):'').'' . $selected .'" '.($name_id != "no"?'onclick="document.getElementById(\''. esc_attr($value_id .'_'. $key) .'\').checked=true;"':'').'>';
		}
		return $output;
	}
endif;
/* Sliderui */
if (!function_exists('wpqa_option_sliderui')) :
	function wpqa_option_sliderui($value_min = '',$value_max = '',$value_step = '',$value_edit = '',$val = '',$value_id = '',$option_name = '',$element = '',$bracket = '',$widget = '') {
		$output = $min = $max = $step = $edit = '';
		
		if(!isset($value_min)){ $min  = '0'; }else{ $min = $value_min; }
		if(!isset($value_max)){ $max  = $min + 1; }else{ $max = $value_max; }
		if(!isset($value_step)){ $step  = '1'; }else{ $step = $value_step; }
		
		if (!isset($value_edit)) { 
			$edit  = ' readonly="readonly"'; 
		}else {
			$edit  = '';
		}
		
		if ($val == '') $val = $min;
		
		//values
		$data = 'data-id="'.(isset($element) && $element != ""?$element:$value_id).'" data-val="'.$val.'" data-min="'.$min.'" data-max="'.$max.'" data-step="'.$step.'"';
		
		//html output
		$output .= '<input type="text" name="'.esc_attr( (isset($widget) && $widget == "widget"?$option_name:$option_name . ($bracket != 'remove_it'?'[':'') . $value_id . ']') ).'" id="'.(isset($element) && $element != ""?$element:$value_id).'" value="'. $val .'" class="mini" '. $edit .' />';
		$output .= '<div id="'.(isset($element) && $element != ""?$element:$value_id).'-slider" class="v_sliderui" '. $data .'></div>';
		return $output;
	}
endif;
/* Sessions */
if (!function_exists('wpqa_session')) :
	function wpqa_session($message = "",$session = "") {
		if ($message) {
			$_SESSION[$session] = $message;
		}else {
			if (isset($_SESSION[$session])) {
				$last_message = $_SESSION[$session];
				unset($_SESSION[$session]);
				return $last_message;
			}
		}
	}
endif;
/* Remove HTML tags */
function wpqa_strip_tags_content($string) {
	$string = strip_tags($string);
	$string = wp_strip_all_tags($string,true);
	$string = strip_shortcodes($string);
    $string = preg_replace('/<[^>]*>/',' ',$string);
    $string = str_replace("\r",'',$string);
    $string = str_replace("\n",' ',$string);
    $string = str_replace("\t",' ',$string);
    $string = str_replace("&nbsp;",' ',$string);
    $string = trim(preg_replace('/ {2,}/',' ',$string));
    return $string;
}
/* HTML tags */
if (!function_exists('wpqa_html_tags')) :
	function wpqa_html_tags($p_active = "") {
		global $allowedposttags,$allowedtags;
		$allowedposttags['img'] = array('alt' => true, 'class' => true, 'id' => true, 'title' => true, 'src' => true);
		$allowedposttags['a'] = array('href' => true, 'title' => true, 'target' => true, 'class' => true);
		$allowedposttags['br'] = array();
		$allowedposttags['span'] = array('style' => true);
		$allowedtags['img'] = array('alt' => true, 'class' => true, 'id' => true, 'title' => true, 'src' => true);
		$allowedtags['a'] = array('href' => true, 'title' => true, 'target' => true, 'class' => true);
		$allowedtags['blockquote'] = array('class' => true, 'data-secret' => true, 'style' => true);
		$allowedtags['iframe'] = array('title' => true, 'width' => true, 'height' => true, 'src' => true, 'frameborder' => true, 'allow' => true, 'allowfullscreen' => true);
		$allowedtags['span'] = array('style' => true);
		$allowedtags['\\'] = array();
		$allowedtags['pre'] = array('class' => true, 'data-enlighter-language' => true);
		$array = array('hr','br','ul','ol','li','dl','dt','dd','table','td','tr','th','thead','tbody','h1','h2','h3','h4','h5','h6','cite','em','address','big','ins','sub','sup','tt','var');
		foreach ($array as $value) {
			$allowedtags[$value] = array();
		}
		if ($p_active == "yes") {
			$allowedtags['p'] = array('style' => true);
			$allowedposttags['p'] = array('style' => true);
		}
	}
endif;
add_action('wpqa_init','wpqa_html_tags',10);
/* Kses stip */
if (!function_exists('wpqa_kses_stip')) :
	function wpqa_kses_stip($value,$ireplace = "",$p_active = "") {
		return wpqa_deslash(wp_kses(($ireplace == "yes"?str_ireplace(array("<br />","<br>","<br/>","</p>"), "\r\n",$value):$value),wpqa_html_tags(($p_active == "yes"?$p_active:""))));
	}
endif;
/* Kses stip wpautop */
if (!function_exists('wpqa_kses_stip_wpautop')) :
	function wpqa_kses_stip_wpautop($value,$ireplace = "",$p_active = "") {
		return wpqa_deslash(wpautop(wp_kses((($ireplace == "yes"?str_ireplace(array("<br />","<br>","<br/>","</p>"), "\r\n",$value):$value)),wpqa_html_tags(($p_active == "yes"?$p_active:"")))));
	}
endif;
/* Remove backslash */
function wpqa_deslash($content) {
	$content = preg_replace("/\\\+'/","'",$content);
	$content = preg_replace('/\\\+"/','"',$content);
	return $content;
}
/* Count meta */
function wpqa_meta_count($key,$value = '',$compare = '=') {
	global $wpdb;
	$count = $wpdb->get_row("SELECT COUNT(*) AS THE_COUNT FROM $wpdb->postmeta WHERE meta_key = '$key'".($value !== "" || $compare == "!="?" AND meta_value $compare '$value'":""));
	return $count->THE_COUNT;
}
/* Count number */
if (!function_exists('wpqa_count_number')) :
	function wpqa_count_number($input) {
		$active_separator = wpqa_options("active_separator");
		$number_separator = wpqa_options("number_separator");
		$input = number_format((int)$input,0,'',($active_separator != 'on'?',':$number_separator));
		$input_count = substr_count($input,',');
		if ($active_separator != 'on' && $input_count != '0') {
			if ($input_count == '1') {
				return (int)substr($input,0,-4).esc_html__('k','wpqa');
			}else if ($input_count == '2') {
				return (int)substr($input,0,-8).esc_html__('mil','wpqa');
			}else if ($input_count == '3') {
				return (int)substr($input,0,-12).esc_html__('bil','wpqa');
			}else {
				return;
			}
		}else {
			return $input;
		}
	}
endif;
/* Get resize img url */
if (!function_exists('wpqa_get_aq_resize_img_url')) :
	function wpqa_get_aq_resize_img_url ($img_width_f,$img_height_f,$img_lightbox = "",$thumbs = "",$gif = "no",$title = "") {
		if (empty($thumbs)) {
			$thumb = get_post_thumbnail_id();
		}else {
			$thumb = $thumbs;
		}
		if ($img_lightbox == "lightbox" || $img_width_f == "" || $img_height_f == "") {
			$full_image = wp_get_attachment_image_src($thumb,"full");
			if ($img_lightbox == "lightbox") {
				$img_url = $full_image[0];
			}
			$img_width_f = ($img_width_f != ""?$img_width_f:$full_image[1]);
			$img_height_f = ($img_height_f != ""?$img_height_f:$full_image[2]);
		}
		$image = wpqa_resize($thumb,'',$img_width_f,$img_height_f,true,$gif);
		if (isset($image['url']) && $image['url'] != "") {
			$last_image = $image['url'];
		}else {
			$last_image = "https://placehold.it/".$img_width_f."x".$img_height_f;
		}
		if (isset($last_image) && $last_image != "") {
			return $last_image;
		}
	}
endif;
/* Get resize img */
if (!function_exists('wpqa_get_aq_resize_img')) :
	function wpqa_get_aq_resize_img ($img_width_f,$img_height_f,$img_lightbox = "",$thumbs = "",$gif = "no",$title = "",$srcset = "") {
		$last_image = wpqa_get_aq_resize_img_url($img_width_f,$img_height_f,$img_lightbox,$thumbs,$gif,$title);
		if (isset($last_image) && $last_image != "") {
			if ($srcset != "") {
				$last_image_2 = wpqa_get_aq_resize_img_url($img_width_f*2,$img_height_f*2,$img_lightbox,$thumbs,$gif,$title);
			}
			return ($img_lightbox == "lightbox"?"<a href='".esc_url($img_url)."'>":"")."<img".($srcset != ""?" srcset='".$last_image." 1x, ".$last_image_2." 2x'":"")." alt='".(isset($title) && $title != ""?$title:get_the_title())."' width='".$img_width_f."' height='".$img_height_f."' src='".$last_image."'>".($img_lightbox == "lightbox"?"</a>":"");
		}
	}
endif;
/* Get resize image with URL */
if (!function_exists('wpqa_get_aq_resize_url')) :
	function wpqa_get_aq_resize_url ($url,$img_width_f,$img_height_f,$gif = "no") {
		$image = wpqa_resize("", $url, $img_width_f, $img_height_f, true,$gif);
		if (isset($image['url']) && $image['url'] != "") {
			$last_image = $image['url'];
		}else {
			$last_image = "https://placehold.it/".$img_width_f."x".$img_height_f;
		}
		return $last_image;
	}
endif;
/* Check image id or URL */
if (!function_exists('wpqa_image_url_id')) :
	function wpqa_image_url_id($url_id) {
		if (is_numeric($url_id)) {
			$image = wp_get_attachment_url($url_id);
		}
		
		if (!isset($image)) {
			if (is_array($url_id)) {
				if (isset($url_id['id']) && $url_id['id'] != '' && $url_id['id'] != 0) {
					$image = wp_get_attachment_url($url_id['id']);
				}else if (isset($url_id['url']) && $url_id['url'] != '') {
					$id    = wpqa_get_attachment_id($url_id['url']);
					$image = ($id?wp_get_attachment_url($id):'');
				}
				$image = (isset($image) && $image != ''?$image:$url_id['url']);
			}else {
				if (isset($url_id) && $url_id != '') {
					$id    = wpqa_get_attachment_id($url_id);
					$image = ($id?wp_get_attachment_url($id):'');
				}
				$image = (isset($image) && $image != ''?$image:$url_id);
			}
		}
		if (isset($image) && $image != "") {
			return $image;
		}
	}
endif;
/* Get the attachment ID */
if (!function_exists('wpqa_get_attachment_id')) :
	function wpqa_get_attachment_id($image_url) {
		global $wpdb;
		$components = parse_url($image_url);
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID,guid FROM $wpdb->posts WHERE guid RLIKE '%s';", (isset($components['path']) && $components['path'] != ""?$components['path']:$image_url) ));
		if (isset($attachment[0]) && $attachment[0] != "") {
			return $attachment[0];
		}
	}
endif;
/* Get first image */
if (!function_exists('wpqa_image')) :
	function wpqa_image () {
		global $post;
		ob_start();
		ob_end_clean();
		$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',$post->post_content,$matches);
		if (isset($matches[1][0])) {
			return $matches[1][0];
		}else {
			return false;
		}
	}
endif;
/* Admin bar */
if (!function_exists('wpqa_admin_bar')) :
	function wpqa_admin_bar() {
		global $wp_admin_bar;
		if (is_super_admin()) {
			if (wpqa_is_user_profile()) {
	    		$wpqa_user_id = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
	    		if ($wpqa_user_id > 0) {
					$wp_admin_bar->add_menu( array(
						'parent' => 0,
						'id' => 'edit_user',
						'title' => '<span class="ab-icon dashicons-before dashicons-edit"></span>'.esc_html__("Edit User","wpqa").'</span></span>' ,
						'href' => admin_url('user-edit.php?user_id='.$wpqa_user_id)
					));
				}
			}
			$count_questions_by_type = wpqa_count_posts_by_type( "question", "draft" );
			if ($count_questions_by_type > 0) {
				$wp_admin_bar->add_menu( array(
					'parent' => 0,
					'id' => 'questions_draft',
					'title' => '<span class="ab-icon dashicons-before dashicons-editor-help"></span><span class="count-'.$count_questions_by_type.'"><span>'.$count_questions_by_type.'</span></span>' ,
					'href' => admin_url('edit.php?post_status=draft&post_type=question')
				));
			}
			$count_posts_by_type = wpqa_count_posts_by_type( "post", "draft" );
			if ($count_posts_by_type > 0) {
				$wp_admin_bar->add_menu( array(
					'parent' => 0,
					'id' => 'posts_draft',
					'title' => '<span class="ab-icon dashicons-before dashicons-media-text"></span><span class="count-'.$count_posts_by_type.'"><span>'.$count_posts_by_type.'</span></span>' ,
					'href' => admin_url('edit.php?post_status=draft&post_type=post')
				));
			}
			$payment_available = wpqa_payment_available();
			if ($payment_available == true) {
				$new_payments = (int)get_option("new_payments");
				$wp_admin_bar->add_menu( array(
					'parent' => 0,
					'id' => 'new_payments',
					'title' => '<span class="ab-icon dashicons-before dashicons-cart"></span><span class="count-'.$new_payments.'"><span>'.$new_payments.'</span></span>' ,
					'href' => admin_url('edit.php?post_type=statement')
				));
			}
			$active_message = wpqa_options('active_message');
			$count_messages_by_type = wpqa_count_posts_by_type( "message", "draft" );
			if ($active_message == "on" && $count_messages_by_type > 0) {
				$wp_admin_bar->add_menu( array(
					'parent' => 0,
					'id' => 'messages_draft',
					'title' => '<span class="ab-icon dashicons-before dashicons-email-alt"></span><span class="count-'.$count_messages_by_type.'"><span>'.$count_messages_by_type.'</span></span>' ,
					'href' => admin_url('edit.php?post_status=draft&post_type=message')
				));
			}
			$count_users = count_users();
			$count_user_under_review = (isset($count_users["avail_roles"]["wpqa_under_review"])?$count_users["avail_roles"]["wpqa_under_review"]:0);
			if ($count_user_under_review > 0) {
				$wp_admin_bar->add_menu( array(
					'parent' => 0,
					'id' => 'user_under_review',
					'title' => '<span class="ab-icon dashicons-before dashicons-admin-users"></span><span class="count-'.$count_user_under_review.'"><span>'.$count_user_under_review.'</span></span>' ,
					'href' => admin_url('users.php?role=wpqa_under_review')
				));
			}
		}
	}
endif;
add_action( 'wp_before_admin_bar_render', 'wpqa_admin_bar' );
/* Admin bar menu */
add_action('admin_bar_menu', 'wpqa_admin_bar_menu', 70 );
if (!function_exists('wpqa_admin_bar_menu')) :
	function wpqa_admin_bar_menu( $wp_admin_bar ) {
		if (is_super_admin()) {
			$answers_count = count(get_comments(array("post_type" => "question")));
			if ($answers_count > 0) {
				$wp_admin_bar->add_node( array(
					'parent' => 0,
					'id' => 'answers',
					'title' => '<span class="ab-icon dashicons-before dashicons-format-chat"></span><span class="count-'.$answers_count.'"><span>'.$answers_count.'</span></span>' ,
					'href' => admin_url('edit-comments.php?comment_status=all&answers=1')
				));
			}
		}
	}
endif;

/* Comments */
if (!function_exists('wpqa_comments')) :
	function wpqa_comments($args = array()) {
		$defaults = array(
			'post_or_question' => 'post',
			'comments_number'  => '5',
			'comment_excerpt'  => '20',
			'show_images'      => 'on',
			'display_date'     => 'on',
			'specific_date'    => '',
		);
		
		$args = wp_parse_args($args,$defaults);
		
		$post_or_question = $args['post_or_question'];
		$comments_number  = $args['comments_number'];
		$comment_excerpt  = $args['comment_excerpt'];
		$show_images      = $args['show_images'];
		$display_date     = $args['display_date'];
		$specific_date    = $args['specific_date'];

		if (isset($specific_date)) {
			if ($specific_date == "24" || $specific_date == "48" || $specific_date == "72" || $specific_date == "96" || $specific_date == "120" || $specific_date == "144") {
				$specific_date = $specific_date." hours";
			}else if ($specific_date == "week" || $specific_date == "month" || $specific_date == "year") {
				$specific_date = "1 ".$specific_date;
			}
		}
		$specific_date_array = (isset($specific_date) && $specific_date != "" && $specific_date != "all"?array('date_query' => array(array('after' => $specific_date.' ago'))):array());

		$comments = get_comments(array_merge($specific_date_array,array("post_type" => $post_or_question,"status" => "approve","number" => $comments_number,"meta_query" => array(array("key" => "answer_question_user","compare" => "NOT EXISTS")))));?>
		<div class="user-notifications user-profile-area">
			<div>
				<ul>
					<?php foreach ($comments as $comment) {
						$comment_user_id = $comment->user_id;
						$user = get_user_by('id',$comment_user_id);
						$anonymously_user = get_comment_meta($comment->comment_ID,'anonymously_user',true);
						$deleted_user = ($comment_user_id > 0 && isset($user->display_name)?$user->display_name:($comment_user_id == 0?$comment->comment_author:"delete"));
						$comment_author_name = ($deleted_user == "delete"?esc_html__("[Deleted User]","wpqa"):($anonymously_user > 0 || $anonymously_user == "anonymously"?esc_html__('Anonymous','wpqa'):$deleted_user));
						$user_profile_page = wpqa_profile_url($comment_user_id);?>
						<li>
							<?php if ($show_images == "on") {?>
								<span class="span-icon">
									<?php if ($comment_user_id > 0) {?>
										<a href="<?php echo esc_url($user_profile_page)?>">
									<?php }
										echo wpqa_get_user_avatar(array("user_id" => ($comment_user_id > 0?$comment_user_id:$comment->comment_author_email),"size" => 25,"user_name" => $comment->comment_author));
									if ($comment_user_id > 0) {?>
										</a>
									<?php }?>
								</span>
							<?php }?>
							<div>
								<?php echo ($comment_user_id > 0?"<a href='".esc_url($user_profile_page)."'>":"").$comment_author_name.($comment_user_id > 0?"</a>":"") ." ". ($post_or_question == "question"?esc_html__("added an answer","wpqa"):esc_html__("added a comment","wpqa")) ?> <span class="question-title"><a href="<?php echo get_permalink($comment->comment_post_ID);?>#comment-<?php echo (int)$comment->comment_ID;?>"><?php echo wp_trim_words($comment->comment_content,$comment_excerpt);?></a></span>
								<?php if ($display_date == "on") {
									$time_format = wpqa_options("time_format");
									$time_format = ($time_format?$time_format:get_option("time_format"));
									$date_format = wpqa_options("date_format");
									$date_format = ($date_format?$date_format:get_option("date_format"));?>
									<span class="notifications-date"><?php printf(esc_html__('%1$s at %2$s','wpqa'),get_comment_date($date_format,$comment->comment_ID),get_comment_date($time_format,$comment->comment_ID))?></span>
								<?php }?>
							</div>
						</li>
					    <?php
					}?>
				</ul>
			</div>
		</div>
	<?php }
endif;
/* Posts */
if (!function_exists('wpqa_posts')) :
	function wpqa_posts($args = array()) {
		$defaults = array(
			'posts_per_page'      => '5',
			'orderby'             => '',
			'excerpt_title'       => '5',
			'show_images'         => 'on',
			'post_or_question'    => 'post',
			'display_comment'     => 'on',
			'display'             => '',
			'category'            => '',
			'categories'          => array(),
			'e_categories'        => array(),
			'custom_posts'        => '',
			'display_question'    => '',
			'category_question'   => '',
			'categories_question' => array(),
			'e_cats_question'     => array(),
			'custom_questions'    => '',
			'custom_args'         => array(),
			'no_query'            => '',
			'display_image'       => 'on',
			'display_video'       => 'on',
			'display_date'        => 'on',
			'blog_h_button'       => '',
			'blog_h_button_text'  => esc_html__('Explore Our Blog','wpqa'),
			'blog_h_page'         => '',
			'blog_h_link'         => '',
			'post_style'          => 'style_1',
			'excerpt_post'        => '40',
			'specific_date'       => '',
		);
		
		$args = wp_parse_args($args,$defaults);
		
		$posts_per_page      = $args['posts_per_page'];
		$orderby             = $args['orderby'];
		$excerpt_title       = $args['excerpt_title'];
		$show_images         = $args['show_images'];
		$post_or_question    = $args['post_or_question'];
		$display_comment     = $args['display_comment'];
		$display             = $args['display'];
		$category            = $args['category'];
		$categories          = $args['categories'];
		$e_categories        = $args['e_categories'];
		$custom_posts        = $args['custom_posts'];
		$display_question    = $args['display_question'];
		$category_question   = $args['category_question'];
		$categories_question = $args['categories_question'];
		$e_cats_question     = $args['e_cats_question'];
		$custom_questions    = $args['custom_questions'];
		$custom_args         = $args['custom_args'];
		$no_query            = $args['no_query'];
		$display_image       = $args['display_image'];
		$display_video       = $args['display_video'];
		$display_date        = $args['display_date'];
		$blog_h_button       = $args['blog_h_button'];
		$blog_h_button_text  = $args['blog_h_button_text'];
		$blog_h_page         = $args['blog_h_page'];
		$blog_h_link         = $args['blog_h_link'];
		$post_style          = $args['post_style'];
		$excerpt_post        = $args['excerpt_post'];
		$specific_date       = $args['specific_date'];
		
		global $post;
		if (empty($custom_args)) {
			$get_current_user_id = get_current_user_id();
			$ask_question_to_users = wpqa_options("ask_question_to_users");
			$question_meta_query = ($ask_question_to_users == "on"?array("key" => "user_id","compare" => "NOT EXISTS"):array());
			$advanced_queries = wpqa_options("advanced_queries");
			if ($advanced_queries == "on" && !is_super_admin($get_current_user_id)) {
				$question_meta_query = array(
					$question_meta_query,array(
						'relation' => 'OR',
						array("key" => "private_question","compare" => "NOT EXISTS"),
						array("key" => "private_question","compare" => "=","value" => 0),
						array(
							'relation' => 'AND',
							array("key" => "private_question","compare" => "EXISTS"),
							array("key" => "private_question_author","compare" => "=","value" => $get_current_user_id),
						)
					)
				);
			}
			
			$user_array_question = array("meta_query" => array($question_meta_query));
			if ($orderby == "popular") {
				$orderby_array = array_merge($user_array_question,array('orderby' => 'comment_count'));
			}else if ($orderby == "random") {
				$orderby_array = array_merge($user_array_question,array('orderby' => 'rand'));
			}else if ($orderby == "most_visited") {
				$post_meta_stats = wpqa_options("post_meta_stats");
				$post_meta_stats = ($post_meta_stats != ""?$post_meta_stats:"post_stats");
				$orderby_array = array('orderby' => array('post_stats_order' => "DESC"),"meta_query" => array('relation' => 'AND','user_ques_slug_order' => $question_meta_query,'post_stats_order' => array('type' => 'numeric',"key" => $post_meta_stats,"value" => 0,"compare" => ">=")));
			}else if ($orderby == "most_voted") {
				$orderby_array = array('orderby' => array('question_vote_order' => "DESC"),"meta_query" => array('relation' => 'AND','user_ques_slug_order' => $question_meta_query,'question_vote_order' => array('type' => 'numeric',"key" => "question_vote","value" => 0,"compare" => ">=")));
			}else if ($orderby == "most_rated") {
				$orderby_array = array("orderby" => "meta_value_num","meta_key" => "final_review","meta_query" => array(array('type' => 'numeric',"key" => "final_review","value" => 0,"compare" => ">=")));
			}else {
				$orderby_array = $user_array_question;
			}
			
			if ($post_or_question == "post") {
				$display      = $display;
				$category     = $category;
				$categories   = $categories;
				$e_categories = $e_categories;
				$custom_posts = $custom_posts;
				$taxonomy     = "category";
			}else if ($post_or_question == "question") {
				$display      = $display_question;
				$category     = $category_question;
				$categories   = $categories_question;
				$e_categories = $e_cats_question;
				$custom_posts = $custom_questions;
				$taxonomy     = "question-category";
			}
			
			$categories_a = $exclude_categories_a = array();
			if (isset($categories) && is_array($categories)) {
				$categories_a = $categories;
			}
			
			if (isset($e_categories) && is_array($e_categories)) {
				$exclude_categories_a = $e_categories;
			}
			
			if ($display == "category") {
				$cat_query = array('tax_query' => array(array('taxonomy' => $taxonomy,'field' => 'id','terms' => $category,'operator' => 'IN')));
			}else if ($display == "categories") {
				$cat_query = array('tax_query' => array(array('taxonomy' => $taxonomy,'field' => 'id','terms' => $categories_a,'operator' => 'IN')));
			}else if ($display == "exclude_categories") {
				$cat_query = array('tax_query' => array(array('taxonomy' => $taxonomy,'field' => 'id','terms' => $exclude_categories_a,'operator' => 'NOT IN')));
			}else if ($display == "custom_posts") {
				$custom_posts = explode(",",$custom_posts);
				$cat_query = array('post__in' => $custom_posts);
			}else {
				$cat_query = array();
			}
		}

		if ($specific_date == "24" || $specific_date == "48" || $specific_date == "72" || $specific_date == "96" || $specific_date == "120" || $specific_date == "144") {
			$specific_date = $specific_date." hours";
		}else if ($specific_date == "week" || $specific_date == "month" || $specific_date == "year") {
			$specific_date = "1 ".$specific_date;
		}

		$specific_date_array = ($specific_date != "" && $specific_date != "all"?array('date_query' => array(array('after' => $specific_date.' ago'))):array());
		$comment_count = ($orderby == "no_response"?array("comment_count" => "0"):array());
		
		$args = (empty($custom_args)?array_merge($comment_count,$specific_date_array,$orderby_array,$cat_query,array('post_type' => $post_or_question,'ignore_sticky_posts' => 1,'cache_results' => false,'no_found_rows' => true,'posts_per_page' => $posts_per_page)):$custom_args);

		$related_query = new WP_Query( $args );
		$out = '';
		if ($related_query->have_posts()) :
			$out .= '<div class="user-notifications user-profile-area'.($post_style == "style_2"?" widget-post-style-2".($display_image === "on"?" post-style-2-image":""):"").'">
				<div>
					<ul>';
						while ( $related_query->have_posts() ) : $related_query->the_post();
							$what_post = get_post_meta($post->ID,'what_post',true);
							$video_type = get_post_meta($post->ID,'video_post_type',true);
							$out .= '<li class="widget-posts-';if (is_sticky()) {$out .= 'sticky';}else if ($what_post == "google") {$out .= 'google';}else if ($what_post == "audio") {$out .= 'volume-up';}else if ($what_post == "video") {if ($video_type == 'youtube') {$out .= 'youtube';}else if ($video_type == 'vimeo') {$out .= 'vimeo';}else if ($video_type == 'daily' || $video_type == 'embed' || $video_type == 'html5' || $video_type == 'facebook') {$out .= 'daily';}}else if ($what_post == "slideshow") {$out .= 'slideshow';}else if ($what_post == "quote") {$out .= 'quote';}else if ($what_post == "link") {$out .= 'link';}else if ($what_post == "soundcloud") {$out .= 'soundcloud';}else if ($what_post == "twitter") {$out .= 'twitter';}else if ($what_post == "facebook") {$out .= 'facebook';}else if ($what_post == "instagram") {$out .= 'instagram';}else {if (has_post_thumbnail()) {$out .= 'image';}else {$out .= 'text';}}$out .= (has_post_thumbnail()?'':' widget-no-img').($display_comment === "on" || ($post_style == "style_2" && $display_date === "on")?'':' widget-no-meta').'">';
								$video_description = get_post_meta($post->ID,"video_description",true);
								if ($post_style == "style_2" && $display_video === "on" && ($what_post == "video" || $video_description == "on")) {
									if ($post_or_question == "question") {
										$ask_question_items = wpqa_options("ask_question_items");
										$video_desc_active = (isset($ask_question_items["video_desc_active"]["value"]) && $ask_question_items["video_desc_active"]["value"] == "video_desc_active"?"on":"");
										if ($video_desc_active == "on" && $video_description == "on") {
											$video_desc = get_post_meta($post->ID,'video_desc',true);
											$video_id = get_post_meta($post->ID,"video_id",true);
											$video_type = get_post_meta($post->ID,"video_type",true);
											if ($video_id != "") {
												$type = wpqa_video_iframe($video_type,$video_id);
												$las_video = '<iframe frameborder="0" allowfullscreen height="155" src="'.$type.'"></iframe>';
												$out .= '<div class="question-video-widget">'.$las_video.'</div>';
											}
										}
									}else if ($what_post == "video") {
										$video_id = get_post_meta($post->ID,wpqa_meta.'video_post_id',true);
										if ($video_id != "") {
											$type = wpqa_video_iframe($video_type,$video_id);
										}
										$video_mp4 = get_post_meta($post->ID,wpqa_meta."video_mp4",true);
										$video_m4v = get_post_meta($post->ID,wpqa_meta."video_m4v",true);
										$video_webm = get_post_meta($post->ID,wpqa_meta."video_webm",true);
										$video_ogv = get_post_meta($post->ID,wpqa_meta."video_ogv",true);
										$video_wmv = get_post_meta($post->ID,wpqa_meta."video_wmv",true);
										$video_flv = get_post_meta($post->ID,wpqa_meta."video_flv",true);
										$video_image = get_post_meta($post->ID,wpqa_meta."video_image",true);
										$video_mp4 = (isset($video_mp4) && $video_mp4 != ""?" mp4='".$video_mp4."'":"");
										$video_m4v = (isset($video_m4v) && $video_m4v != ""?" m4v='".$video_m4v."'":"");
										$video_webm = (isset($video_webm) && $video_webm != ""?" webm='".$video_webm."'":"");
										$video_ogv = (isset($video_ogv) && $video_ogv != ""?" ogv='".$video_ogv."'":"");
										$video_wmv = (isset($video_wmv) && $video_wmv != ""?" wmv='".$video_wmv."'":"");
										$video_flv = (isset($video_flv) && $video_flv != ""?" flv='".$video_flv."'":"");
										$video_image = (isset($video_image) && $video_image != ""?" poster='".wpqa_image_url_id($video_image)."'":"");
										if ($video_type == "html5") {
											$out .= do_shortcode('[video'.$video_mp4.$video_m4v.$video_webm.$video_ogv.$video_wmv.$video_flv.$video_image.']');
										}else if ($video_type == "embed") {
											$out .= get_post_meta($post->ID,"custom_embed",true);
										}else if (isset($type) && $type != "") {
											$las_video = '<iframe frameborder="0" allowfullscreen height="155" src="'.$type.'"></iframe>';
											$out .= '<div class="question-video-widget">'.$las_video.'</div>';
										}
									}
								}else if ($post_style == "style_2" && $display_image === "on") {
									$out .= '<div class="widget-post-image"><a href="'.get_permalink().'" title="'.sprintf('%s', the_title_attribute('echo=0')).'" rel="bookmark">';
									$img_width = "229";
									$img_height = "155";
									if (has_post_thumbnail()) {
										$out .= apply_filters("wpqa_filter_image_widget",wpqa_get_aq_resize_img($img_width,$img_height),$post,$img_width,$img_height);
									}else {
										$wpqa_image = wpqa_image();
										if (!is_single() && !empty($wpqa_image)) {
											$out .= "<img alt='".get_the_title()."' src='".wpqa_get_aq_resize_url(wpqa_image(),$img_width,$img_height)."'>";
										}
									}
									$out .= '</a></div>';
								}
								if ($post_style != "style_2") {
									if ($post->post_author > 0) {
										$user_name = get_the_author_meta("display_name",$post->post_author);
										$user_id = $post->post_author;
									}else {
										$user_id = get_post_meta($post->ID,$post_or_question.'_email',true);
										$anonymously_user     = get_post_meta($post->ID,"anonymously_user",true);
										$anonymously_question = get_post_meta($post->ID,"anonymously_question",true);
										if (($anonymously_question == "on" || $anonymously_question == 1) && $anonymously_user != "") {
											$user_name = esc_html__('Anonymous','wpqa');
										}else {
											$user_name = get_post_meta($post->ID,$post_or_question."_username",true);
											$user_name = ($user_name != ""?$user_name:esc_html__('Anonymous','wpqa'));
										}
									}
									$user_profile_page = wpqa_profile_url($user_id);
									if ($show_images === "on") {
										$out .= '<span class="span-icon">';
											if ($user_id > 0) {
												$out .= '<a href="'.esc_url($user_profile_page).'">';
											}
											$out .= wpqa_get_user_avatar(array("user_id" => $user_id,"size" => 20,"user_name" => $user_name));
											if ($user_id > 0) {
												$out .= '</a>';
											}
										$out .= '</span>';
									}
								}
								$out .= '<div>';
									if ($post_style == "style_2") {
										$sort_title_meta = array("meta","title");
									}else {
										$sort_title_meta = array("title","meta");
									}
									foreach ($sort_title_meta as $key => $value) {
										if ($value == "title") {
											$out .= '<h3><a href="'.get_permalink().'" title="'.sprintf('%s', the_title_attribute('echo=0')).'" rel="bookmark">'.wpqa_excerpt_title($excerpt_title,wpqa_excerpt_type,"return").'</a></h3>';
											if ($post_style == "style_2" && $excerpt_post > 0) {
												$out .= '<p>'.wpqa_excerpt($excerpt_post,wpqa_excerpt_type,"return").'</p>';
											}
										}else if ($value == "meta") {
											if ($display_comment === "on" || ($post_style == "style_2" && $display_date === "on")) {
												$out .= '<ul class="widget-post-meta">';
												if ($post_style == "style_2" && $display_date === "on") {
													$out .= '<li><span class="post-meta-date">';
														$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
														$date_format = wpqa_options("date_format");
														$date_format = ($date_format?$date_format:get_option("date_format"));
														$time_string = sprintf($time_string,esc_attr(get_the_date('c')),esc_html(get_the_time($date_format)));
														$data_string = esc_html__("On","wpqa");
														$posted_on   = $data_string.': '.$time_string;
														$out .= $posted_on;
													$out .= '</span></li>';
												}
												if ($display_comment === "on") {
													$count_post_all = (int)wpqa_count_comments($post->ID);
													$out .= '<li><a class="post-meta-comment" href="'.get_comments_link().'">';
														if ($post_style == "style_2") {
															$comment_string = ($post_or_question == "question"?_n("Answer","Answers",$count_post_all,"wpqa"):_n("Comment","Comments",$count_post_all,"wpqa"));
															$comments = $comment_string.': '.$count_post_all;
														}else if ($post_style != "style_2") {
															$comment_string = ($post_or_question == "question"?sprintf(_n("%s Answer","%s Answers",$count_post_all,"wpqa"),$count_post_all):sprintf(_n("%s Comment","%s Comments",$count_post_all,"wpqa"),$count_post_all));
															$out .= '<i class="icon-comment"></i>';
															$comments = $comment_string;
														}
														$out .= $comments;
													$out .= '</a></li>';
												}
												$out .= '</ul>';
											}
										}
									}
								$out .= '</div>
							</li>';
						endwhile;
					$out .= '</ul>';
					if ($post_or_question == "post" && $post_style == "style_2" && $blog_h_button === "on") {
						$out .= '<div class="blog-post-button"><a href="'.esc_url(($blog_h_link != ""?$blog_h_link:($blog_h_page != "" && $blog_h_page > 0?get_page_link($blog_h_page):""))).'" class="button-default">'.($blog_h_button_text != ""?$blog_h_button_text:esc_html__("Explore Our Blog","wpqa")).'</a></div>';
					}
				$out .= '</div>
			</div>';
		else : $out .= (isset($no_query) && $no_query == "no_query"?"no_query":"");endif;
		wp_reset_postdata();
		return $out;
	}
endif;
/* Post tag callback */
if (!function_exists('wpqa_post_tag_callback')) :
	function wpqa_post_tag_callback($count) {
		return sprintf(_n(esc_html__('%s post','wpqa'),esc_html__('%s posts','wpqa'),$count),number_format_i18n($count));
	}
endif;
/* Question tag callback */
if (!function_exists('wpqa_question_tags_callback')) :
	function wpqa_question_tags_callback($count) {
		return sprintf(_n(esc_html__('%s question','wpqa'),esc_html__('%s questions','wpqa'),$count),number_format_i18n($count));
	}
endif;
/* Count posts by type */
if (!function_exists('wpqa_count_posts_by_type')) :
	function wpqa_count_posts_by_type($post_type = 'post',$post_status = "publish",$asked = "asked") {
		$ask_question_to_users = wpqa_options("ask_question_to_users");
		$question_meta_query = ($ask_question_to_users == "on"?array("key" => "user_id","compare" => "NOT EXISTS"):array());
		$question_meta_query = ($asked == "asked"?array():array("meta_query" => array($question_meta_query)));
		$args = array(
			'post_type'   => $post_type,
			'post_status' => $post_status
		);
		$the_query = new WP_Query(array_merge($question_meta_query,$args));
		return $the_query->found_posts;
		wp_reset_postdata();
	}
endif;
/* Count posts by user */
if (!function_exists('wpqa_count_posts_by_user')) :
	function wpqa_count_posts_by_user($user_id,$post_type = "post",$post_status = "publish",$category = 0,$date = 0) {
		$author = ($user_id > 0?array("author" => $user_id):array());
		$tax = (is_array($category) && !empty($category)?array("tax_query" => array(array("taxonomy" => ($post_type == "post"?"category":"question-category"),"field" => "id","terms" => $category,'operator' => 'IN'))):array());
		$meta_query = ($post_type == "question"?array("meta_query" => array("relation" => "OR",array("key" => "private_question","compare" => "NOT EXISTS"),array("key" => "private_question","compare" => "=","value" => 0))):array());
		$date_query = (is_array($date) && !empty($date)?array("date_query" => array($date)):array());
		$args = array(
			"post_type"   => $post_type,
			"post_status" => $post_status,
		);
		$args = array_merge($author,$tax,$meta_query,$date_query,$args);
		$the_query = new WP_Query($args);
		return $the_query->found_posts;
		wp_reset_postdata();
	}
endif;
/* Count new notifications */
if (!function_exists('wpqa_count_new_notifications')) :
	function wpqa_count_new_notifications( $user_id = "", $post_status = "publish" ) {
		$args = array(
			"post_type"   => "notification",
			"author"      => $user_id,
			"post_status" => $post_status,
			"meta_query"  => array(array("key" => "notification_new","compare" => "=","value" => 1))
		);
		$the_query = new WP_Query($args);
		return $the_query->found_posts;
		wp_reset_postdata();
	}
endif;
/* Count new messages */
if (!function_exists('wpqa_count_new_messages')) :
	function wpqa_count_new_messages( $user_id = "", $post_status = "publish" ) {
		$args = array(
			"post_type"   => "message",
			"post_status" => $post_status,
			"meta_query" => array(
				"relation" => "AND",
				array("key" => "delete_inbox_message","compare" => "NOT EXISTS"),
				array("key" => "message_user_id","compare" => "=","value" => $user_id),
				array(
					"relation" => "OR",
					array("key" => "message_new","compare" => "=","value" => 1),
					array("key" => "message_new","compare" => "=","value" => "on")
				)
			)
		);
		$the_query = new WP_Query($args);
		return $the_query->found_posts;
		wp_reset_postdata();
	}
endif;
/* User table */
if (!function_exists('wpqa_user_table')) :
	function wpqa_user_table( $column ) {
		$user_meta_admin = wpqa_options("user_meta_admin");
		if (isset ($user_meta_admin) && is_array($user_meta_admin)) {
			$column['question'] = esc_html__('Questions','wpqa');
			if (isset($user_meta_admin["points"]) && $user_meta_admin["points"] == "points") {
				$column['points'] = esc_html__('Points','wpqa');
			}
			if (isset($user_meta_admin["phone"]) && $user_meta_admin["phone"] == "phone") {
				$column['phone'] = esc_html__('Phone','wpqa');
			}
			if (isset($user_meta_admin["country"]) && $user_meta_admin["country"] == "country") {
				$column['country'] = esc_html__('Country','wpqa');
			}
			if (isset($user_meta_admin["age"]) && $user_meta_admin["age"] == "age") {
				$column['age'] = esc_html__('Age','wpqa');
			}
			if (isset($user_meta_admin["invitation"]) && $user_meta_admin["invitation"] == "invitation") {
				$column['invitation'] = esc_html__('Invitation','wpqa');
			}
		}
		return $column;
	}
endif;
add_filter( 'manage_users_columns', 'wpqa_user_table' );
if (!function_exists('wpqa_user_table_row')) :
	function wpqa_user_table_row( $val, $column_name, $user_id ) {
		$user = get_userdata( $user_id );
		switch ($column_name) {
			case 'question' :
				$count_user_ques_slugs = wpqa_count_posts_by_user($user_id,"question");
				return ($count_user_ques_slugs > 0?'<a href="'.admin_url('edit.php?post_type=question&author='.$user_id).'">':'').$count_user_ques_slugs.($count_user_ques_slugs > 0?'</a>':'');
				break;
			case 'points' :
				$points = (int)get_the_author_meta( 'points', $user_id );
				return $points;
				break;
			case 'phone' :
				$phone = get_the_author_meta( 'phone', $user_id );
				return ($phone != ""?apply_filters("wpqa_show_phone",esc_attr($phone),$user_id):" - ");
				break;
			case 'country' :
				$get_countries = apply_filters('wpqa_get_countries',false);
				$country = get_the_author_meta( 'country', $user_id );
				if ($country && isset($get_countries[$country])) {
					return $get_countries[$country];
				}else {
					return ' - ';
				}
				break;
			case 'age' :
				$age = get_the_author_meta( 'age', $user_id );
				return (date_create($age)?date_diff(date_create($age),date_create('today'))->y:"");
				break;
			case 'invitation' :
				$invitation = (int)get_the_author_meta( 'wpqa_invitations', $user_id );
				return ($invitation > 0?'<a href="'.get_author_posts_url($invitation).'" target="_blank">'.get_the_author_meta('display_name',$invitation).'</a>':' - ');
				break;
			default:
		}
	}
endif;
add_filter( 'manage_users_custom_column', 'wpqa_user_table_row', 10, 3 );
/* Media library */
add_action('pre_get_posts','wpqa_media_library');
if (!function_exists('wpqa_media_library')) :
	function wpqa_media_library($wp_query_obj) {
		global $current_user,$pagenow;
		if (!is_a($current_user,'WP_User') || is_super_admin($current_user->ID))
			return;
		if ('admin-ajax.php' != $pagenow || (isset($_REQUEST['action']) && $_REQUEST['action'] != 'query-attachments'))
			return;
		if (!current_user_can('manage_media_library'))
			$wp_query_obj->set('author',$current_user->ID);
		return;
	}
endif;
/* Remove item by value */
if (!function_exists('wpqa_remove_item_by_value')) :
	function wpqa_remove_item_by_value($array,$val = '',$preserve_keys = true) {
		if (empty($array) || !is_array($array)) {
			return false;
		}
		if (!in_array($val,$array)) {
			return $array;
		}
		foreach ($array as $key => $value) {
			if ($value == $val) unset($array[$key]);
		}
		return ($preserve_keys === true)?$array:array_values($array);
	}
endif;
/* Insert after key in array */
if (!function_exists('wpqa_array_insert_after')) :
function wpqa_array_insert_after( array $array, $key, array $new ) {
	$keys = array_keys( $array );
	$index = array_search( $key, $keys );
	$pos = false === $index ? count( $array ) : $index + 1;
	return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
}
endif;
/* Excerpt row */
if (!function_exists('wpqa_excerpt_row')) :
	function wpqa_excerpt_row($excerpt_length,$content) {
		$words = explode(' ',$content,$excerpt_length + 1);
		if (count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words,'...');
			$content = implode(' ',$words).'...';
		endif;
			$content = strip_tags($content);
		echo ($content);
	}
endif;
/* Excerpt title row */
if (!function_exists('wpqa_excerpt_title_row')) :
	function wpqa_excerpt_title_row($excerpt_length,$title) {
		$words = explode(' ',$title,$excerpt_length + 1);
		if (count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words,'');
			$title = implode(' ',$words).'...';
		endif;
			$title = strip_tags($title);
		echo ($title);
	}
endif;
/* Excerpts */
if (!defined("wpqa_excerpt_type")) {
	define("wpqa_excerpt_type",wpqa_options("excerpt_type"));
}
if (!function_exists('wpqa_excerpt_title')) :
	function wpqa_excerpt_title($excerpt_length,$excerpt_type = wpqa_excerpt_type,$return = "") {
		global $post;
		$title = "";
		$excerpt_length = ((isset($excerpt_length) && $excerpt_length != "") || $excerpt_length == 0?$excerpt_length:5);
		if ($excerpt_length > 0) {
			$title = $post->post_title;
		}
		if ($excerpt_type == "characters") {
			$title = mb_substr($title,0,$excerpt_length,"UTF-8");
		}else {
			$words = explode(' ',$title,$excerpt_length + 1);
			if (count($words) > $excerpt_length) :
				array_pop($words);
				array_push($words,'');
				$title = implode(' ',$words).'...';
			endif;
		}
		$title = strip_tags($title);
		if ($return == "return") {
			return esc_attr($title);
		}else {
			echo esc_attr($title);
		}
	}
endif;
if (!function_exists('wpqa_excerpt')) :
	function wpqa_excerpt($excerpt_length,$excerpt_type = wpqa_excerpt_type,$return = "",$main_content = "",$content = "") {
		global $post;
		$content = "";
		$excerpt_length = ((isset($excerpt_length) && $excerpt_length != "") || $excerpt_length == 0?$excerpt_length:5);
		if ($excerpt_length > 0) {
			if ($main_content == "yes") {
				$content = strip_shortcodes($content);
			}else {
				$content = $post->post_content;
			}
		}
		if ($excerpt_type == "characters") {
			$content = mb_substr($content,0,$excerpt_length,"UTF-8");
		}else {
			$words = explode(' ',$content,$excerpt_length + 1);
			if (count($words) > $excerpt_length) :
				array_pop($words);
				array_push($words,'');
				$content = implode(' ',$words);
			endif;
		}
		$content = strip_tags($content);
		if ($return == "return") {
			return esc_attr($content);
		}else {
			echo esc_attr($content);
		}
	}
endif;
if (!function_exists('wpqa_excerpt_any')) :
	function wpqa_excerpt_any($excerpt_length,$content,$more = '...',$excerpt_type = wpqa_excerpt_type) {
		$excerpt_length = (isset($excerpt_length) && $excerpt_length != ""?$excerpt_length:5);
		$content = strip_tags($content);
		if ($excerpt_type == "characters") {
			$content = mb_substr($content,0,$excerpt_length,"UTF-8");
		}else {
			$words = explode(' ',$content,$excerpt_length + 1);
			if (count(explode(' ',$content)) > $excerpt_length) {
				array_pop($words);
				array_push($words,'');
				$content = implode(' ',$words);
				$content = $content.$more;
			}
		}
		return $content;
	}
endif;
/* Admin menus */
if (!function_exists('wpqa_add_admin_page_menu')) :
	function wpqa_add_admin_page_menu() {
		if (is_super_admin(get_current_user_id())) {
			$user_review = wpqa_options("user_review");
			$confirm_email = wpqa_options("confirm_email");
			$subscriptions_payment = wpqa_options("subscriptions_payment");
			if ($user_review == "on") {
				add_users_page(esc_html__('Under review','wpqa'),esc_html__('Under review','wpqa'),'read','users.php?role=wpqa_under_review');
			}
			if ($confirm_email == "on") {
				add_users_page(esc_html__('Activation users','wpqa'),esc_html__('Activation users','wpqa'),'read','users.php?role=activation');
			}
			if ($subscriptions_payment == "on") {
				add_users_page(esc_html__('Subscription users','wpqa'),esc_html__('Subscription users','wpqa'),'read','users.php?role='.wpqa_options("subscriptions_group"));
			}
		}
	}
endif;
add_action('admin_menu','wpqa_add_admin_page_menu');
/* Before delete user */
add_action('delete_user','wpqa_before_delete_user');
if (!function_exists('wpqa_before_delete_user')) :
	function wpqa_before_delete_user($user_id) {
		update_user_meta($user_id,"password_changed","changed");
		
		$active_points = wpqa_options("active_points");
		$point_following_me = wpqa_options("point_following_me");
		
		$following_me = get_user_meta($user_id,"following_me",true);
		if (isset($following_me) && is_array($following_me)) {
			foreach ($following_me as $key => $value) {
				$following_me = get_user_meta($value,"following_me",true);
				$remove_following_me = wpqa_remove_item_by_value($following_me,$user_id);
				update_user_meta($value,"following_me",$remove_following_me);
				if ($active_points == "on" && $point_following_me > 0) {
					wpqa_add_points($value,$point_following_me,"-","delete_follow_user");
				}
				
				$following_you = get_user_meta($value,"following_you",true);
				$remove_following_you = wpqa_remove_item_by_value($following_you,$user_id);
				update_user_meta($value,"following_you",$remove_following_you);
			}
		}
	}
endif;
/* Action delete post */
if (!function_exists('wpqa_delete_post')) :
	function wpqa_delete_post() {
		if (isset($_GET['wpqa_delete_nonce']) && wp_verify_nonce($_GET['wpqa_delete_nonce'],'wpqa_delete_nonce') && !is_admin() && isset($_GET["delete"]) && $_GET["delete"] != "") {
			$post_id   = (int)$_GET["delete"];
			$get_post  = get_post($post_id);
			$post_type = $get_post->post_type;
			$post_author  = $get_post->post_author;
			$post_status  = $get_post->post_status;
			$filter_post_type = apply_filters("wpqa_filter_delete_post_type",false,$post_type);
			$filter_delete_post = apply_filters("wpqa_filter_delete_post",true,$post_id);
			if ($filter_delete_post == true && $post_id > 0 && (($post_type != "posts" && $post_status == "publish") || $post_type == "posts") && ($post_type == "post" || $post_type == "question" || $post_type == "group" || $post_type == "posts" || $filter_post_type == true)) {
				$user_id      = get_current_user_id();
				$delete_post  = wpqa_options($post_type."_delete");
				$delete_trush = wpqa_options("delete_".$post_type);
				$moderators_permissions = wpqa_user_moderator($user_id);
				$is_super_admin = is_super_admin($user_id);
				if ($post_type == "posts") {
					$group_id = get_post_meta($post_id,"group_id",true);
					$group_moderators = get_post_meta($group_id,"group_moderators",true);
					if (($delete_post == "on" && $post_author == $user_id) || (isset($group_moderators) && is_array($group_moderators) && in_array($user_id,$group_moderators)) || $is_super_admin) {
						$yes_can_delete = true;
					}
				}
				if ($post_type == "question") {
					$anonymously_user = get_post_meta($post_id,"anonymously_user",true);
				}
				if (isset($yes_can_delete) || ($user_id > 0 && ($post_author == $user_id || (isset($anonymously_user) && $anonymously_user == $user_id)) && $delete_post == "on") || (isset($moderators_permissions['delete']) && $moderators_permissions['delete'] == "delete") || $is_super_admin) {
					if ($user_id > 0) {
						wpqa_notifications_activities($user_id,"","","","","delete_".$post_type,"activities","",$post_type);
					}
					if ($delete_trush == "trash" && !$is_super_admin) {
						wp_trash_post($post_id);
					}else {
						wp_delete_post($post_id,true);
					}
					wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Deleted successfully.","wpqa").'</p></div>','wpqa_session');
					$protocol    = is_ssl() ? 'https' : 'http';
					$redirect_to = wp_unslash($protocol.'://'.wpqa_server('HTTP_HOST').wpqa_server('REQUEST_URI'));
					$redirect_to = (isset($_GET["page"]) && esc_attr($_GET["page"]) != ""?esc_attr($_GET["page"]):$redirect_to);
					$redirect_to = ((isset($_GET["page"]) && esc_attr($_GET["page"]) != "") || is_page()?site_url("/").$redirect_to:esc_url(home_url('/')));
					if ($post_type == "posts") {
						if (isset($group_id) && $group_id != "") {
							$get_permalink = get_permalink($group_id);
							if ($get_permalink != "") {
								$redirect_to = $get_permalink;
							}
						}
					}
					wp_redirect($redirect_to);
					exit;
				}
			}
		}
	}
endif;
add_action('wpqa_init','wpqa_delete_post');
/* Before delete post */
add_action('before_delete_post','wpqa_before_delete_post');
if (!function_exists('wpqa_before_delete_post')) :
	function wpqa_before_delete_post($post_id) {
		$get_post = get_post($post_id);
		$post_type = $get_post->post_type;
		$post_author = $get_post->post_author;
		$active_points = wpqa_options("active_points");
		$active_points_category = wpqa_options("active_points_category");
		if ($active_points == "on" && $active_points_category == "on") {
			$categories = wp_get_post_terms($post_id,'question-category',array('fields' => 'ids'));
			update_post_meta($post_id,"question_category",$categories);
		}
		$remove_best_answer_stats = wpqa_options("remove_best_answer_stats");
		if ($remove_best_answer_stats == "on" && $active_points == "on") {
			$post_approved_before = get_post_meta($post_id,'post_approved_before',true);
			if ($post_approved_before == "yes") {
				if ($post_author > 0) {
					$point_add_post = (int)wpqa_options("point_add_".$post_type);
					if ($point_add_post > 0) {
						wpqa_add_points($post_author,$point_add_post,"-","delete_".$post_type,$post_id);
					}
				}
			}
		}

		if ($post_id != "" && $post_type == "request") {
			$request_new = get_post_meta($post_id,"request_new",true);
			if ($request_new == 1) {
				$new_requests = get_option("new_requests");
				$new_requests--;
				update_option('new_requests',($new_requests < 0?0:$new_requests));
			}
		}
		if ($post_id != "" && $post_type == "statement") {
			$statement_type = get_post_meta($post_id,"statement_type",true);
			if ($statement_type != "refund") {
				$item_price = floatval(get_post_meta($post_id,"payment_item_price",true));
				if ($item_price > 0) {
					$item_currency = get_post_meta($post_id,"payment_item_currency",true);
					wpqa_site_user_money($item_price,"-",$item_currency,($post_author > 0?$post_author:0));
				}
			}
		}
		if ($post_id > 0 && $post_type == "question") {
			$args = array(
				"nopaging"   => true,
				"post_type"  => "report",
				'meta_query' => array(
					array(
						'key'     => 'report_post_id',
						'value'   => $post_id,
						'compare' => '=',
					)
				)
			);
			$get_posts = get_posts($args);
			foreach ($get_posts as $report_post) {
				wp_delete_post($report_post->ID,true);
			}
		}
		if ($post_id > 0 && $post_type == "posts") {
			$group_id = (int)get_post_meta($post_id,"group_id",true);
			$group_posts = (int)get_post_meta($group_id,"group_posts",true);
			$group_posts = $group_posts-1;
			update_post_meta($group_id,"group_posts",($group_posts <= 0?0:$group_posts));
		}
		if ($post_id != "" && ($post_type == "post" || $post_type == "question")) {
			$favorites_questions = get_post_meta($post_id,"favorites_questions",true);
			if (isset($favorites_questions) && is_array($favorites_questions) && count($favorites_questions) > 0) {
				foreach ($favorites_questions as $user_id) {
					$favorites_questions_user = get_user_meta($user_id,$user_id."_favorites",true);
					$remove_favorites_questions = wpqa_remove_item_by_value($favorites_questions_user,$post_id);
					update_user_meta($user_id,$user_id."_favorites",$remove_favorites_questions);
				}
			}
			
			$following_questions = get_post_meta($post_id,"following_questions",true);
			$following_questions = (is_array($following_questions) && !empty($following_questions)?get_users(array('fields' => 'ID','include' => $following_questions,'orderby' => 'registered')):array());
			if (isset($following_questions) && is_array($following_questions) && count($following_questions) > 0) {
				foreach ($following_questions as $user_id) {
					$following_questions_user = get_user_meta($user_id,"following_questions",true);
					$remove_following_questions = wpqa_remove_item_by_value($following_questions_user,$post_id);
					update_user_meta($user_id,"following_questions",$remove_following_questions);
				}
			}
		}
	}
endif;
/* Transition the post status */
add_action('transition_post_status','wpqa_run_on_update_post',10,3);
if (!function_exists('wpqa_run_on_update_post')) :
	function wpqa_run_on_update_post($new_status,$old_status,$post) {
		if (is_admin()) {
			$post_id = $post->ID;
			$post_type = $post->post_type;
			if ($post_type == "question" || $post_type == "group" || $post_type == "posts" || $post_type == "post" || $post_type == "message") {
				$post_from_front = get_post_meta($post_id,'post_from_front',true);
				if ($post_type == "question") {
					$user_id = get_post_meta($post_id,"user_id",true);
					$anonymously_user = get_post_meta($post_id,"anonymously_user",true);
					$question_username = get_post_meta($post_id,'question_username',true);
					$question_email = get_post_meta($post_id,'question_email',true);
					if ($question_username == "") {
						$question_no_username = get_post_meta($post_id,'question_no_username',true);
					}
				}
				if ($post_type == "post") {
					$post_username = get_post_meta($post_id,'post_username',true);
					$post_email = get_post_meta($post_id,'post_email',true);
				}
				if ($post_type == "message") {
					$message_username = get_post_meta($post_id,'message_username',true);
					$message_email = get_post_meta($post_id,'message_email',true);
				}
				
				if ((isset($anonymously_user) && $anonymously_user > 0) || (isset($question_no_username) && $question_no_username == "no_user") || (isset($question_username) && $question_username != "" && isset($question_email) && $question_email != "") || (isset($post_username) && $post_username != "" && isset($post_email) && $post_email != "") || (isset($message_username) && $message_username != "" && isset($message_email) && $message_email != "")) {
					$not_user = 0;
				}else {
					$not_user = $post->post_author;
				}
				
				$post_approved_before = get_post_meta($post_id,'post_approved_before',true);
				if ($post_approved_before != "yes") {
					if ('publish' == $new_status && $post_type == "group") {
						do_action("wpqa_after_added_group",$post_id,$post->post_author);
					}else if ('publish' == $new_status && $post_type == "message") {
						$message_user_array = get_post_meta($post_id,'message_user_array',true);
						if (is_array($message_user_array) && !empty($message_user_array)) {
							update_post_meta($post_id,'post_approved_before',"yes");
							$get_message_user = get_post_meta($post_id,'message_user_id',true);
							$send_email_message = wpqa_options("send_email_message");
							if ($post->post_author != $get_message_user && $get_message_user > 0) {
								$header_messages = wpqa_options("header_messages");
								$header_style = wpqa_options("header_style");
								$show_message_area = ($header_messages == "on" && $header_style == "simple"?"on":0);
								wpqa_notifications_activities($get_message_user,$post->post_author,($post->post_author == 0?$get_message_user:""),"","","add_message_user","notifications","","message",($show_message_area === "on"?false:true));
							}
							if ($not_user > 0) {
								wpqa_notifications_activities($not_user,"","","","","approved_message","notifications");
								wpqa_notifications_activities($not_user,$get_message_user,"","","","add_message","activities","","message");
							}
							
							if ($send_email_message == "on" && $get_message_user > 0) {
								$user = get_userdata($get_message_user);
								$send_text = wpqa_send_mail(
									array(
										'content'          => wpqa_options("email_new_message"),
										'user_id'          => $get_message_user,
										'post_id'          => $post_id,
										'sender_user_id'   => $post->post_author,
										'received_user_id' => $user->ID,
									)
								);
								$email_title = wpqa_options("title_new_message");
								$email_title = ($email_title != ""?$email_title:esc_html__("New message","wpqa"));
								$email_title = wpqa_send_mail(
									array(
										'content'          => $email_title,
										'title'            => true,
										'break'            => '',
										'user_id'          => $get_message_user,
										'post_id'          => $post_id,
										'sender_user_id'   => $post->post_author,
										'received_user_id' => $user->ID,
									)
								);
								$unsubscribe_mails = get_the_author_meta('unsubscribe_mails',$user->ID);
								$send_message_mail = get_the_author_meta('send_message_mail',$user->ID);
								if ($unsubscribe_mails != "on" && $send_message_mail == "on") {
									wpqa_send_mails(
										array(
											'toEmail'     => esc_html($user->user_email),
											'toEmailName' => esc_html($user->display_name),
											'title'       => $email_title,
											'message'     => $send_text,
										)
									);
								}
							}
						}
					}
					if ('publish' == $new_status && isset($post_from_front) && $post_from_front == "from_front" && ($post_type == "question" || $post_type == "group" || $post_type == "posts" || $post_type == "post")) {
						if ($not_user > 0 || $anonymously_user > 0) {
							if ($post_type == "question") {
								wpqa_notifications_activities(($anonymously_user > 0?$anonymously_user:$not_user),"","",$post_id,"","approved_question","notifications","","question");
								if ($post->post_author != $user_id && $user_id > 0) {
									wpqa_notifications_activities($user_id,($anonymously_user > 0?0:$not_user),"",$post_id,"","add_question_user","notifications","","question");
								}
							}else if ($not_user > 0) {
								wpqa_notifications_activities($not_user,"","",$post_id,"","approved_".$post_type,"notifications");
							}
						}
						
						if ($post_type == "question") {
							wpqa_notifications_ask_question($post_id,$question_username,$user_id,$not_user,$anonymously_user,"admin");
						}
						
						if ($post_type == "question" || $post_type == "group" || $post_type == "posts" || $post_type == "post") {
							wpqa_post_publish($post,$not_user,"admin");
						}
						update_post_meta($post_id,'post_approved_before',"yes");
					}
					do_action("wpqa_update_post",$post);
				}
			}
		}
	}
endif;
/* Save post */
add_action('save_post','wpqa_save_post',10,3);
if (!function_exists('wpqa_save_post')) :
	function wpqa_save_post($post_id,$post_data,$update) {
		if (is_admin()) {
			if ($post_data->post_type == "question" || $post_data->post_type == "post" || $post_data->post_type == "message") {
				if ($post_data->post_type == "question") {
					$question_username = get_post_meta($post_id,'question_username',true);
					$question_email = get_post_meta($post_id,'question_email',true);
					$anonymously_user = get_post_meta($post_id,'anonymously_user',true);
					if ($question_username == "") {
						$question_no_username = get_post_meta($post_id,'question_no_username',true);
					}
				}
				if ($post_data->post_type == "post") {
					$post_username = get_post_meta($post_id,'post_username',true);
					$post_email = get_post_meta($post_id,'post_email',true);
				}
				if ($post_data->post_type == "message") {
					$message_username = get_post_meta($post_id,'message_username',true);
					$message_email = get_post_meta($post_id,'message_email',true);
				}
				
				if ((isset($anonymously_user) && $anonymously_user != "") || (isset($question_no_username) && $question_no_username == "no_user") || (isset($question_username) && $question_username != "" && isset($question_email) && $question_email != "") || (isset($post_username) && $post_username != "" && isset($post_email) && $post_email != "") || (isset($message_username) && $message_username != "" && isset($message_email) && $message_email != "")) {
					$data = array(
						'ID' => $post_id,
						'post_author' => 0,
					);
					remove_action('save_post','wpqa_save_post');
					$post_id = wp_update_post($data);
					add_action('save_post','wpqa_save_post',10,3);
				}
			}
		}else {
			if ($post_data->post_type == "question") {
				if ($post_data->post_status == "draft") {
					$data = array(
						'ID' => $post_id,
						'post_status' => $post_data->post_status,
					);
					remove_action('save_post','wpqa_save_post');
					$post_id = wp_update_post($data);
					add_action('save_post','wpqa_save_post',10,3);
				}
			}
		}
	}
endif;
/* Get comment */
if (!function_exists('wpqa_comment')) :
	function wpqa_comment($comment,$args,$depth,$answer = "",$owner = "",$k_ad = "",$best_answer = "",$answer_args = array()) {
		if ($answer != "answer") {
			if ($k_ad == "") {
				global $k_ad;
			}
			if ($comment->comment_parent == 0) {
				$k_ad++;
			}
		}
		$show_replies = wpqa_options("show_replies");
	    $user_get_current_user_id = get_current_user_id();
	    $is_super_admin = is_super_admin($user_get_current_user_id);
	    $comment_id = (int)$comment->comment_ID;
	    $comment_user_id = (int)$comment->user_id;
	    $post_id = (int)$comment->comment_post_ID;
	    $post_data = get_post($post_id);
    	$post_type = $post_data->post_type;
	    $user = get_user_by('id',$comment_user_id);
	    $deleted_user = ($comment_user_id > 0 && isset($user->display_name)?$user->display_name:($comment_user_id == 0?$comment->comment_author:"delete"));
		$like_answer = apply_filters("wpqa_comment_like_answer",false,(isset($post_data->post_type)?$post_data->post_type:""));
	    if ($like_answer == true || ($answer != "answer" && isset($post_data->post_type) && $post_data->post_type == 'question') || ($answer == "answer" && $post_type == "question")) {
	    	$its_question = (isset($post_data->post_type)?$post_data->post_type:$post_type);
	    	$the_best_answer = get_post_meta(($answer == "answer"?$post_id:(isset($post_data->ID)?$post_data->ID:0)),"the_best_answer",true);
	    	$best_answer_comment = get_comment_meta($comment_id,"best_answer_comment",true);
	    	$comment_class = ($best_answer_comment == "best_answer_comment" || $the_best_answer == $comment_id?"comment-best-answer":"");
	    	$_paid_answer = get_comment_meta($comment_id,'_paid_answer',true);
	    	$comment_class = ($comment_class != ""?$comment_class." ":$comment_class).($_paid_answer == 'paid'?'comment-paid-answer':'');
	    	$active_reports = wpqa_options("active_reports");
	    	$active_logged_reports = wpqa_options("active_logged_reports");
	    	$active_vote = wpqa_options("active_vote");
	    	$active_vote_unlogged = wpqa_options("active_vote_unlogged");
			$active_best_answer = wpqa_options("active_best_answer");
	    }
	    
	    if ($like_answer == true || (isset($its_question) && $its_question == "question")) {
	    	$comment_vote = get_comment_meta($comment_id,'comment_vote',true);
	    	if (isset($comment_vote) && is_array($comment_vote) && isset($comment_vote["vote"])) {
	    		update_comment_meta($comment_id,'comment_vote',$comment_vote["vote"]);
	    		$comment_vote = get_comment_meta($comment_id,'comment_vote',true);
	    	}else if ($comment_vote == "") {
	    		update_comment_meta($comment_id,'comment_vote',0);
	    		$comment_vote = get_comment_meta($comment_id,'comment_vote',true);
	    	}
	    }
	    $can_delete_comment = wpqa_options("can_delete_comment");
	    $can_edit_comment = wpqa_options("can_edit_comment");
	    $can_edit_comment_after = (int)wpqa_options("can_edit_comment_after");
	    $can_edit_comment_after = (isset($can_edit_comment_after) && $can_edit_comment_after > 0?$can_edit_comment_after:0);
	    if (version_compare(phpversion(), '5.3.0', '>')) {
	    	$time_now = strtotime(current_time('mysql'),date_create_from_format('Y-m-d H:i',current_time('mysql')));
	    }else {
	    	list($year, $month, $day, $hour, $minute, $second) = sscanf(current_time('mysql'),'%04d-%02d-%02d %02d:%02d:%02d');
	    	$datetime = new DateTime("$year-$month-$day $hour:$minute:$second");
	    	$time_now = strtotime($datetime->format('r'));
	    }
	    $time_edit_comment = strtotime('+'.$can_edit_comment_after.' hour',strtotime($comment->comment_date));
	    $time_end = ($time_now-$time_edit_comment)/60/60;
	    $edit_comment = get_comment_meta($comment_id,"edit_comment",true);
	    $between_comments_position = (int)wpqa_options("between_comments_position");
	    $adv_type_repeat = wpqa_options("between_comments_adv_type_repeat");
	    $count_adv = ($between_comments_position > 0 && isset($k_ad) && $k_ad > 0?$k_ad % $between_comments_position:0);
	    if (isset($k_ad) && (($k_ad == $between_comments_position) || ($adv_type_repeat == "on" && $k_ad != 0 && $count_adv == 0))) {
	    	echo wpqa_ads("between_comments_adv_type","between_comments_adv_code","between_comments_adv_href","between_comments_adv_img","li","","","on");
	    }
	    if ($answer == "answer") {
	    	$k_ad++;
	    }
	    $answer_question_style = wpqa_options("answer_question_style");
	    $profile_credential = ($comment_user_id > 0?get_the_author_meta('profile_credential',$comment_user_id):"");
	    $privacy_credential = ($comment_user_id > 0?wpqa_check_user_privacy($comment_user_id,"credential"):"");
	    if (isset($answer_args['custom_home_answer']) && $answer_args['custom_home_answer'] == "on") {
        	$answer_image         = get_post_meta($answer_args['answer_question_id'],prefix_meta.'answers_image_h',true);
        	$active_vote_answer   = get_post_meta($answer_args['answer_question_id'],prefix_meta.'active_vote_answer_h',true);
        	$show_dislike_answers = get_post_meta($answer_args['answer_question_id'],prefix_meta.'show_dislike_answers_h',true);
        }else if (isset($answer_args['custom_answers']) && $answer_args['custom_answers'] == "on") {
        	$answer_image         = get_post_meta($answer_args['answer_question_id'],prefix_meta.'answers_image_a',true);
        	$active_vote_answer   = get_post_meta($answer_args['answer_question_id'],prefix_meta.'active_vote_answer_a',true);
        	$show_dislike_answers = get_post_meta($answer_args['answer_question_id'],prefix_meta.'show_dislike_answers_a',true);
        }else {
        	$answer_image         = wpqa_options("answer_image");
        	$active_vote_answer   = wpqa_options("active_vote_answer");
        	$show_dislike_answers = wpqa_options("show_dislike_answers");
        }?>
	    <li <?php comment_class(($answer_image == "on"?"":"comment-without-image ").($show_replies == "on"?"comment-show-replies ":"").(isset($answer_args["comment_read_more"])?"comment-read-more ":"").(isset($answer_args["comment_with_title"])?"comment-with-title ".($answer_question_style != ""?"comment-with-title-".str_replace('style_','',$answer_question_style)." ":""):"").($privacy_credential == true && $profile_credential != ""?"comment-credential ":"").(isset($its_question) && $its_question == "question"?$comment_class." ":"").($comment->comment_type == "pingback"?"comment":""),$comment_id,$post_id);echo (isset($its_question) && $its_question == 'question' && is_single()?' itemscope itemtype="https://schema.org/'.($comment->comment_parent > 0?"Comment":"Answer").'"'.($comment->comment_parent == 0?' itemprop="'.($best_answer_comment == 'best_answer_comment' || $the_best_answer == $comment_id?'acceptedAnswer':'suggestedAnswer').'"':''):'');?> id="li-comment-<?php echo esc_attr($comment_id);?>">
	    	<div id="comment-<?php echo esc_attr($comment_id);?>" class="comment-body clearfix">
	            <?php if (isset($answer_args["comment_with_title"])) {
		            echo '<div class="comment-question-title"><header class="article-header"><div class="question-header"><div class="post-meta">';
		            	wpqa_theme_meta("on","on","","","","",$post_id,$post_data);
		        	echo '</div></div></header>
		        	<div class="clearfix"></div>
		            <h2 class="post-title"><a class="post-title" href="' . esc_url( get_permalink($post_id) ) . '" rel="bookmark">'.get_the_title($post_id).'</a></h2></div>';
		        }?>
	            <div class="comment-text">
	            	<?php if ($answer_image == "on") {
		            	do_action("wpqa_action_avatar_link",array("user_id" => ($comment_user_id > 0 && $deleted_user != "delete"?$comment_user_id:0),"size" => 42,"span" => "span","pop" => "pop","comment" => $comment,"email" => ($comment_user_id > 0?"":$comment->comment_author_email)));
		            }?>
	                <div class="author clearfix">
	                	<?php if (isset($its_question) && $its_question == "question") {
	                		if ($best_answer == "" && ($best_answer_comment == "best_answer_comment" || $the_best_answer == $comment_id)) {?>
		                		<div class="best-answer"><?php esc_html_e("Best Answer","wpqa")?></div>
		                	<?php }
		                	if (isset($_paid_answer) && $_paid_answer == 'paid') {?>
		                		<div class="best-answer paid-answer"><?php esc_html_e("Paid Answer","wpqa")?></div>
		                	<?php }
		                }?>
	                	<div class="comment-meta">
	                    	<div class="comment-author">
	                    		<?php $wpqa_activate_comment_author = apply_filters('wpqa_activate_comment_author',true,$comment_id);
	                    		if ($wpqa_activate_comment_author == true) {
	                    			if ($comment_user_id > 0 && $deleted_user != "delete") {
		                    			$wpqa_profile_url = wpqa_profile_url($comment_user_id);
		                    		}else {
		                    			$wpqa_profile_url = ($comment->comment_author_url != "" && $deleted_user != "delete"?$comment->comment_author_url:"wpqa_No_site");
		                    		}
		                    		if ($wpqa_profile_url != "" && $wpqa_profile_url != "wpqa_No_site") {?>
		                    			<a href="<?php echo esc_url($wpqa_profile_url)?>">
		                    		<?php }
			                    		$anonymously_user = get_comment_meta($comment_id,"anonymously_user",true);
		                        		echo ($deleted_user == "delete"?esc_html__("[Deleted User]","wpqa"):($anonymously_user != ""?esc_html__("Anonymous","wpqa"):$deleted_user));
		                        	if ($wpqa_profile_url != "" && $wpqa_profile_url != "wpqa_No_site") {?>
		                        		</a>
		                        	<?php }
		                        }
	                        	if ($comment_user_id > 0 && $deleted_user != "delete") {
	                        		do_action("wpqa_verified_user",$comment_user_id);
	                        		$active_points_category = wpqa_options("active_points_category");
									if ($active_points_category == "on") {
										$get_terms = wp_get_post_terms($post_id,'question-category',array('fields' => 'ids'));
										if (!empty($get_terms) && is_array($get_terms) && isset($get_terms[0])) {
											$points_category_user = (int)get_user_meta($comment_user_id,"points_category".$get_terms[0],true);
											echo apply_filters("wpqa_comments_before_badge",false,$get_terms[0]);
										}
									}
	                        		do_action("wpqa_get_badge",$comment_user_id,"",(isset($points_category_user)?$points_category_user:""));
	                        		do_action("wpqa_action_comment_after_badge",$comment,$post_type);
	                        	}
	                        	if ($privacy_credential == true && $profile_credential != "") {?>
	                        		<span class="profile-credential"><?php echo esc_html($profile_credential)?></span>
	                        	<?php }
	                        	do_action("wpqa_action_after_credential",(isset($post_data->ID)?$post_data->ID:0),$comment_id,$comment_user_id);?>
	                    	</div>
							<?php $show_date = apply_filters("wpqa_filter_comment_show_date",true,$comment,$post_type);
							if ($show_date == true) {
								$date_format = wpqa_options("date_format");
								$date_format = ($date_format?$date_format:get_option("date_format"));?>
		                        <a href="<?php echo get_comment_link($comment_id); ?>" class="comment-date"<?php echo(isset($its_question) && $its_question == "question"?" itemprop='url'":"")?>>
		                        	<?php $get_comment_date = get_comment_date("c",$comment_id);
									echo (is_single()?'<span class="wpqa_hide" itemprop="dateCreated" datetime="'.$get_comment_date.'">'.$get_comment_date.'</span>':'');
		                        	if ((isset($its_question) && $its_question == "question") || ($answer == "answer")) {
		                        		echo ($comment->comment_parent > 0?esc_html__("Replied to","wpqa"):esc_html__("Added an","wpqa"))." ";
		                        		printf(esc_html__('answer on %1$s at %2$s','wpqa'),get_comment_date($date_format,$comment_id),wpqa_get_comment_time(false,false,false,$comment));
		                        	}else {
		                        		echo ($comment->comment_parent > 0?esc_html__("Replied to","wpqa"):esc_html__("Added a","wpqa"))." ";
		                        		printf(esc_html__('comment on %1$s at %2$s','wpqa'),get_comment_date($date_format,$comment_id),wpqa_get_comment_time(false,false,false,$comment));
		                        	}?>
		                        </a>
		                    <?php }else {
		                    	do_action("wpqa_action_comment_show_date",$comment,$post_type);
		                    }?>
	                    </div><!-- End comment-meta -->
	                </div><!-- End author -->
	                <div class="text">
	                	<?php if ($edit_comment == "edited") {?>
	                		<em class="comment-edited">
	                			<?php if (isset($its_question) && $its_question == "question") {
	                				esc_html_e('This answer was edited.','wpqa');
	                			}else {
	                				esc_html_e('This comment was edited.','wpqa');
	                			}?>
	                		</em>
	                	<?php }
	                	if ($comment->comment_approved == '0') : ?>
	                	    <em class="comment-awaiting">
		                	    <?php if (isset($its_question) && $its_question == "question") {
		                	    	esc_html_e('Your answer is awaiting moderation.','wpqa');
		                	    }else {
		                	    	esc_html_e('Your comment is awaiting moderation.','wpqa');
		                	    }?>
	                	    </em><br>
	                	<?php endif;
	                	
	                	if (is_singular("question")) {
	                		$featured_image_in_answers = wpqa_options("featured_image_question_answers");
	                	}else {
	                		$featured_image_in_answers = wpqa_options("featured_image_in_answers");
	                	}
	                	if ($featured_image_in_answers == "on") {
	                		$featured_image = get_comment_meta($comment_id,'featured_image',true);
	                		if ($featured_image != "") {
	                			$img_url = wp_get_attachment_url($featured_image,"full");
	                			if ($img_url != "") {
		                			$featured_image_answers_lightbox = wpqa_options("featured_image_answers_lightbox");
		                			$featured_image_answer_width = wpqa_options("featured_image_answer_width");
		                			$featured_image_answer_height = wpqa_options("featured_image_answer_height");
		                			$featured_image_answer_width = ($featured_image_answer_width != ""?$featured_image_answer_width:260);
		                			$featured_image_answer_height = ($featured_image_answer_height != ""?$featured_image_answer_height:185);
		                			$link_url = ($featured_image_answers_lightbox == "on"?$img_url:get_permalink($post_id)."#comment-".$comment_id);
		                			$last_image = wpqa_get_aq_resize_img($featured_image_answer_width,$featured_image_answer_height,"",$featured_image);
		                			$featured_answer_position = wpqa_options("featured_answer_position");
		                			if ($featured_answer_position != "after" && isset($last_image) && $last_image != "") {
		                				echo "<div class='featured_image_answer'><a href='".$link_url."'>".$last_image."</a></div>
		                				<div class='clearfix'></div>";
		                			}
		                		}
	                		}
	                	}

	                	$answer_video = wpqa_options("answer_video");
	                	$video_answer_position = wpqa_options("video_answer_position");
	                	$video_answer_width = wpqa_options("video_answer_width");
						$video_answer_100 = wpqa_options("video_answer_100");
						$video_answer_height = wpqa_options("video_answer_height");
	                	$video_answer_description = get_comment_meta($comment_id,"video_answer_description",true);
						if ($answer_video == "on" && $video_answer_description == "on") {
							$video_answer_type = get_comment_meta($comment_id,"video_answer_type",true);
							$video_answer_id = get_comment_meta($comment_id,"video_answer_id",true);
							if ($video_answer_id != "") {
								$type = wpqa_video_iframe($video_answer_type,$video_answer_id);
								$las_video = '<div class="question-video-loop answer-video'.($video_answer_100 == "on"?' question-video-loop-100':'').($video_answer_position == "after"?' question-video-loop-after':'').'"><iframe frameborder="0" allowfullscreen width="'.$video_answer_width.'" height="'.$video_answer_height.'" src="'.$type.'"></iframe></div>';
								
								if ($video_answer_position == "before" && $answer_video == "on" && isset($video_answer_id) && $video_answer_id != "" && $video_answer_description == "on") {
									echo ($las_video);
								}
							}
						}?>

	                	<div<?php echo (isset($its_question) && $its_question == "question" && is_single()?" itemprop='text'":"")?>>
	                		<?php if (isset($answer_args["comment_with_title"]) || isset($answer_args["comment_read_more"]) || isset($args["comment_read_more"])) {
	                			$comment_excerpt_count = apply_filters('wpqa_answer_number',300);
                				$strlen_comment = strlen(wp_html_excerpt($comment->comment_content,$comment_excerpt_count));
	                			echo '<p class="less_answer_text'.($strlen_comment < $comment_excerpt_count?" wpqa_hide":"").'">'.wp_html_excerpt($comment->comment_content,$comment_excerpt_count,'<a class="post-read-more comment-read-more read_more_answer" href="'.get_permalink($post_id).'#comment-'.esc_attr($comment_id).'" rel="bookmark" title="'.esc_attr__('Read more','wpqa').'">'.esc_html__('Read more','wpqa').'</a>').'</p>
	                			<div class="full_answer_text'.($strlen_comment < $comment_excerpt_count?"":" wpqa_hide").'">';
	                				comment_text($comment_id);
				            		do_action("wpqa_action_comment_after_comment_text",$comment,$post_type);
	                				echo '<a class="read_less_answer'.($strlen_comment < $comment_excerpt_count?" wpqa_hide":"").'" href="#">'.esc_html__("See less","wpqa").'</a>
	                			</div>';
	                		}else {
				            	comment_text($comment_id);
				            	do_action("wpqa_action_comment_after_comment_text",$comment,$post_type);
					        }?>
	                	</div>
	                	<div class="clearfix"></div>
	                	<?php if ($video_answer_position == "after" && $answer_video == "on" && isset($video_answer_id) && $video_answer_id != "" && $video_answer_description == "on") {
							echo ($las_video);
						}?>
						<div class="clearfix"></div>
	                	<?php if ($featured_image_in_answers == "on" && isset($featured_answer_position) && $featured_answer_position == "after" && isset($img_url) && $img_url != "" && isset($last_image) && $last_image != "") {
	                		echo "<div class='featured_image_answer featured_image_after'><a href='".$link_url."'>".$last_image."</a></div>
	                		<div class='clearfix'></div>";
	                	}
	                	
	                	if (isset($its_question) && $its_question == "question") {
	                		$added_file = get_comment_meta($comment_id,'added_file', true);
	                		if ($added_file != "") {
	                			echo "<a href='".wp_get_attachment_url($added_file)."'>".esc_html__("Attachment","wpqa")."</a><div class='clearfix'></div><br>";
	                		}
	                	}?>
	                	<div class="wpqa_error"></div>
	                	<?php if ($like_answer == true || (isset($its_question) && $its_question == "question")) {
	                		if ($active_vote == "on" && $active_vote_answer == "on") {
	                			if ($owner == false) {
	                				$comment_vote_type = apply_filters("wpqa_comment_vote_type","comment",(isset($post_data->post_type)?$post_data->post_type:""));
	                				$count_up = get_comment_meta($comment_id,'wpqa_comment_vote_up',true);
									$count_down = get_comment_meta($comment_id,'wpqa_comment_vote_down',true);
									$count_up = (isset($count_up) && is_array($count_up) && !empty($count_up)?$count_up:array());
									$count_down = (isset($count_down) && is_array($count_down) && !empty($count_down)?$count_down:array());?>
		                			<ul class="question-vote answer-vote<?php echo ($show_dislike_answers != "on"?" answer-vote-dislike":"")?>">
		                				<li><a href="#"<?php echo ((is_user_logged_in() && $comment_user_id != $user_get_current_user_id) || (!is_user_logged_in() && $active_vote_unlogged == "on")?' id="'.$comment_vote_type.'_vote_up-'.$comment_id.'"':'')?> data-type="<?php echo esc_html($comment_vote_type)?>" data-vote-type="up" class="wpqa_vote comment_vote_up<?php echo (is_user_logged_in() && $comment_user_id != $user_get_current_user_id?"":(is_user_logged_in() && $comment_user_id == $user_get_current_user_id?" vote_not_allow":($active_vote_unlogged == "on"?"":" vote_not_user"))).((is_user_logged_in() && $comment_user_id != $user_get_current_user_id) || (!is_user_logged_in() && $active_vote_unlogged == "on")?' vote_allow'.((is_user_logged_in() && is_array($count_up) && in_array($user_get_current_user_id,$count_up)) || (!is_user_logged_in() && isset($_COOKIE[wpqa_options("uniqid_cookie").'wpqa_comment_vote_up'.$comment_id]) && $_COOKIE[wpqa_options("uniqid_cookie").'wpqa_comment_vote_up'.$comment_id] == "wpqa_yes_comment")?" wpqa_voted_already":""):'')?>" title="<?php esc_attr_e("Like","wpqa");?>"><i class="<?php echo apply_filters('wpqa_vote_up_icon','icon-up-dir');?>"></i></a></li>
		                				<li class="vote_result"<?php echo (is_single()?' itemprop="upvoteCount"':'')?>><?php echo ($comment_vote != ""?wpqa_count_number($comment_vote):0)?></li>
		                				<li class="li_loader"><span class="loader_3 fa-spin"></span></li>
		                				<?php if ($show_dislike_answers != "on") {?>
		                					<li class="dislike_answers"><a href="#"<?php echo ((is_user_logged_in() && $comment_user_id != $user_get_current_user_id) || (!is_user_logged_in() && $active_vote_unlogged == "on")?' id="'.$comment_vote_type.'_vote_down-'.$comment_id.'"':'')?> data-type="<?php echo esc_html($comment_vote_type)?>" data-vote-type="down" class="wpqa_vote comment_vote_down<?php echo (is_user_logged_in() && $comment_user_id != $user_get_current_user_id?"":(is_user_logged_in() && $comment_user_id == $user_get_current_user_id?" vote_not_allow":($active_vote_unlogged == "on"?"":" vote_not_user"))).((is_user_logged_in() && $comment_user_id != $user_get_current_user_id) || (!is_user_logged_in() && $active_vote_unlogged == "on")?' vote_allow'.((is_user_logged_in() && is_array($count_down) && in_array($user_get_current_user_id,$count_down)) || (!is_user_logged_in() && isset($_COOKIE[wpqa_options("uniqid_cookie").'wpqa_comment_vote_down'.$comment_id]) && $_COOKIE[wpqa_options("uniqid_cookie").'wpqa_comment_vote_down'.$comment_id] == "wpqa_yes_comment")?" wpqa_voted_already":""):'')?>" title="<?php esc_attr_e("Dislike","wpqa");?>"><i class="<?php echo apply_filters('wpqa_vote_down_icon','icon-down-dir');?>"></i></a></li>
		                				<?php }?>
		                			</ul>
	                			<?php }
	                		}
	                	}?>
	                	<ul class="comment-reply comment-reply-main">
	                	    <?php if ($answer != "answer" && $answer != "comment") {
	                	    	$custom_permission = wpqa_options("custom_permission");
	                	    	$add_answer = wpqa_options("add_answer");
	                	    	if (is_user_logged_in()) {
									$user_is_login = get_userdata($user_get_current_user_id);
									$roles = $user_is_login->allcaps;
								}
								if ((isset($args["comment_type"]) && $args["comment_type"] == "comment_group") || (!isset($its_question) || ((isset($its_question) && $its_question == "question") && $is_super_admin || $custom_permission != "on" || (is_user_logged_in() && $custom_permission == "on" && isset($roles["add_answer"]) && $roles["add_answer"] == 1) || (!is_user_logged_in() && $add_answer == "on")))) {
	                	    		comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="icon-reply"></i>'.esc_html__( 'Reply', 'wpqa' ),'login_text' => '<i class="icon-lock"></i>'.esc_html__( 'Login to Reply', 'wpqa' ), 'before' => '<li>', 'after' => '</li>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
	                	    	}
	                	    }
	                	    do_action("wpqa_action_after_reply_comment",$comment,($like_answer == true || (isset($its_question) && $its_question == "question")?"answer":"comment"));
	                	    $group_id = get_post_meta($comment->comment_post_ID,"group_id",true);
	                	    $comment_share  = wpqa_options("comment_share");
	                	    $share_facebook = (isset($comment_share["share_facebook"]["value"])?$comment_share["share_facebook"]["value"]:"");
	                	    $share_twitter  = (isset($comment_share["share_twitter"]["value"])?$comment_share["share_twitter"]["value"]:"");
	                	    $share_linkedin = (isset($comment_share["share_linkedin"]["value"])?$comment_share["share_linkedin"]["value"]:"");
	                	    $share_whatsapp = (isset($comment_share["share_whatsapp"]["value"])?$comment_share["share_whatsapp"]["value"]:"");
	                	    if ($group_id == "" && $share_facebook == "share_facebook" || $share_twitter == "share_twitter" || $share_linkedin == "share_linkedin" || $share_whatsapp == "share_whatsapp") {?>
	                	    	<li class="comment-share question-share question-share-2">
	                	    		<i class="icon-share"></i>
	                	    		<?php esc_html_e("Share","wpqa");
	                	    		wpqa_share($comment_share,$share_facebook,$share_twitter,$share_linkedin,$share_whatsapp,"style_2",$comment_id,"","","",$post_id);?>
	                	    	</li>
	                	    <?php }
	                	    do_action("wpqa_action_after_share_comment",$comment,(isset($its_question) && $its_question == "question"?"answer":"comment"));
	                	    if (isset($its_question) && $its_question == "question" && $answer != "answer") {
	                	    	$user_best_answer_filter = apply_filters("wpqa_user_best_answer_filter",true);
	                	    	$best_answer_userself = wpqa_options("best_answer_userself");
		                	    $user_best_answer = esc_attr(get_the_author_meta('user_best_answer',$user_get_current_user_id));
		                	    if ($user_best_answer_filter == true && ((is_user_logged_in() && $active_best_answer == "on" && $user_get_current_user_id > 0 && (($comment_user_id != $user_get_current_user_id && $user_get_current_user_id == $post_data->post_author) || ($best_answer_userself == "on" && $comment_user_id == $user_get_current_user_id && $user_get_current_user_id == $post_data->post_author))) || $user_best_answer == "on" || $is_super_admin)) {
		                	    	if ($the_best_answer != 0 && ($best_answer_comment == "best_answer_comment" || $the_best_answer == $comment_id)) {
		                	        	echo '<li><a class="best_answer_re" data-nonce="'.wp_create_nonce("wpqa_best_answer_nonce").'" title="'.esc_attr__("Cancel the best answer","wpqa").'" href="#"><i class="icon-cancel"></i>'.esc_html__("Cancel the best answer","wpqa").'</a></li>';
		                	    	}
			                	    if ($the_best_answer == 0 || $the_best_answer == "") {?>
			                	    	<li><a class="best_answer_a" data-nonce="<?php echo wp_create_nonce("wpqa_best_answer_nonce")?>" title="<?php esc_attr_e("Select as best answer","wpqa");?>" href="#"><i class="icon-check"></i><?php esc_html_e("Select as best answer","wpqa");?></a></li>
			                	    <?php }
		                	    }
	                	    }?>
	                	    <li class="clearfix last-item-answers"></li>
	                	</ul>
	                	<?php do_action("wpqa_after_answer_links",$comment,$can_edit_comment,$can_edit_comment_after,$time_end,$can_delete_comment,$like_answer,(isset($its_question)?$its_question:false),(isset($active_reports)?$active_reports:false),(isset($active_logged_reports)?$active_logged_reports:false),$owner);
	                	if ((($can_edit_comment == "on" && $comment_user_id == $user_get_current_user_id && $comment_user_id != 0 && $user_get_current_user_id != 0 && ($can_edit_comment_after == 0 || $time_end <= $can_edit_comment_after))) || (($can_delete_comment == "on" && $comment_user_id == $user_get_current_user_id && $comment_user_id > 0 && $user_get_current_user_id > 0) || $is_super_admin) || (($like_answer == true || (isset($its_question) && $its_question == "question")) && $active_reports == "on" && ((is_user_logged_in() && $comment_user_id != $user_get_current_user_id && $user_get_current_user_id != 0) || (!is_user_logged_in() && $active_logged_reports != "on")))) {?>
		                	<ul class="comment-reply comment-list-links">
		                	    <li class="question-list-details comment-list-details">
									<i class="icon-dot-3"></i>
									<ul>
				                	    <?php if ($is_super_admin || ($can_edit_comment == "on" && $comment_user_id == $user_get_current_user_id && $comment_user_id != 0 && $user_get_current_user_id != 0 && ($can_edit_comment_after == 0 || $time_end <= $can_edit_comment_after))) {
				                	    	echo "<li><a class='comment-edit-link edit-comment' href='".wpqa_edit_permalink($comment_id,"comment")."'><i class='icon-pencil'></i>".esc_html__("Edit","wpqa")."</a></li>";
				                	    }
				                	    if (($can_delete_comment == "on" && $comment_user_id == $user_get_current_user_id && $comment_user_id > 0 && $user_get_current_user_id > 0) || $is_super_admin) {
				                	    	echo "<li><a class='delete-comment".(isset($its_question) && $its_question == "question"?' delete-answer':'')."' href='".esc_url_raw(add_query_arg(array('delete_comment' => $comment_id,"wpqa_delete_nonce" => wp_create_nonce("wpqa_delete_nonce")),get_permalink($post_id)))."'><i class='icon-trash'></i>".esc_html__("Delete","wpqa")."</a></li>";
				                	    }
			                	    	if (($like_answer == true || (isset($its_question) && $its_question == "question")) && $active_reports == "on" && ((is_user_logged_in() && $comment_user_id != $user_get_current_user_id && $user_get_current_user_id != 0) || (!is_user_logged_in() && $active_logged_reports != "on"))) {
			                	    		if ($owner == false) {?>
				                	    		<li class="report_activated"><a class="report_c" href="<?php echo esc_attr($comment_id)?>"><i class="icon-attention"></i><?php esc_html_e("Report","wpqa")?></a></li>
				                	    	<?php }
				                	    }?>
				               		</ul>
				               	</li>
		                	    <li class="clearfix last-item-answers"></li>
		                	</ul>
		                <?php }
		                do_action("wpqa_action_after_list_comment",$comment,(isset($its_question) && $its_question == "question"?"answer":"comment"));?>
	                </div><!-- End text -->
	                <div class="clearfix"></div>
	            </div><!-- End comment-text -->
	        </div><!-- End comment-body -->
	<?php }
endif;
/* Breadcrumbs */
if (!function_exists('wpqa_breadcrumbs')) :
	function wpqa_breadcrumbs($text = "",$breadcrumb_right = true,$breadcrumbs_style = "style_1") {
		global $post,$wp_query;
		$active_points         = wpqa_options("active_points");
		$breadcrumbs_separator = wpqa_options("breadcrumbs_separator");
		$breadcrumbs_separator = ($breadcrumbs_separator != ""?$breadcrumbs_separator:"/");
		$breadcrumbs_skin      = wpqa_options("breadcrumbs_skin");
		$active_cover_category = wpqa_options("active_cover_category");
		$post_type             = get_post_type();
	    $home                  = '<i class="icon-home"></i>'.esc_html__('Home','wpqa');
	    $before_schema         = '<span class="crumbs-span">'.$breadcrumbs_separator.'</span>';
	    $user_id               = get_current_user_id();
	    $wpqa_get_the_title    = wpqa_get_the_title();
	    if ($breadcrumbs_skin == "dark") {
	    	$breadcrumbs_class = "breadcrumbs-dark";
	    }else if ($breadcrumbs_skin == "colored") {
	    	$breadcrumbs_class = "breadcrumbs-colored background-color";
	    }else {
	    	$breadcrumbs_class = "breadcrumbs-light";
	    }
		echo '<div class="breadcrumbs '.($breadcrumbs_style == "style_2"?"breadcrumbs_2 ".$breadcrumbs_class:"breadcrumbs_1").'">';
			$before = '<h1>';
			$after = '</h1>';
			if ($breadcrumbs_style == "style_2") {
				echo '<div class="the-main-container">';
			}
				echo '<div class="breadcrumbs-wrap">
					<div class="breadcrumb-left">';
						if ($breadcrumbs_style == "style_2") {
							$text_filter = apply_filters("wpqa_breadcrumbs_text",false);
						    if (isset($text) && $text != "") {
						    	echo ($before . $text . $after);
						    }else if ($text_filter != "") {
						    	echo ($before . $text_filter . $after);
						    }else if (wpqa_is_add_questions()) {
						    	$wpqa_add_question_user = wpqa_add_question_user();
						    	if ($wpqa_add_question_user > 0) {
						    		$display_name = get_the_author_meta('display_name',$wpqa_add_question_user);
						    	}
						    	echo ($before . esc_html__('Ask question', 'wpqa') . ($wpqa_add_question_user > 0?" ".esc_html__("to","wpqa")." ".$display_name:"") . $after);
						    }else if (wpqa_is_user_profile() && wpqa_is_home_profile()) {
						    	$wpqa_user_id = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
					    		$user_name = get_the_author_meta("display_name",$wpqa_user_id);
					    		echo ($before.$user_name.$after);
						    }else if ($wpqa_get_the_title != "") {
								echo ($before.$wpqa_get_the_title.$after);
							}else if (is_category() || is_tag() || is_tax()) {
						        echo ($before.single_cat_title('',false).$after);
						    }else if (is_day()) {
						        echo ($before . get_the_time('d') . $after);
						    }else if (is_month()) {
						        echo ($before . get_the_time('F') . $after);
						    }else if (is_year()) {
						        echo ($before . get_the_time('Y') . $after);
						    }else if (!is_single() && !is_page() && $post_type != 'post') {
						        $post_type_object = get_post_type_object($post_type);
						    	echo ($before . (isset($post_type_object->labels->singular_name) && !is_404()?$post_type_object->labels->singular_name:esc_html__("Error 404","wpqa")) . $after);
						    }else if (is_attachment() || (is_single() && !is_attachment()) || (is_page() && !$post->post_parent) || (is_page() && $post->post_parent)) {
						        echo ($before . get_the_title() . $after);
						    }else if (is_tag()) {
						        echo ($before . esc_html__('Posts tagged ', 'wpqa') . '"' . single_tag_title('', false) . '"' . $after);
						    }else if (is_404()) {
						        echo ($before . esc_html__('Error 404 ', 'wpqa') . $after);
						    }
						}
						$before = $before_schema.'<span class="current">';
				    	$after  = '</span>';
						echo '<span class="crumbs">
							<span itemscope itemtype="https://schema.org/BreadcrumbList">
								'.wpqa_breadcrumbs_schema(esc_url(home_url('/')),$home,1);
							    $text_filter = apply_filters("wpqa_breadcrumbs_text",false);
							    if (isset($text) && $text != "") {
							    	echo ($before . $text . $after);
							    }else if ($text_filter != "") {
							    	echo ($before . $text_filter . $after);
							    }else if (wpqa_is_add_questions()) {
							    	$wpqa_add_question_user = wpqa_add_question_user();
							    	if ($wpqa_add_question_user > 0) {
							    		$display_name = get_the_author_meta('display_name',$wpqa_add_question_user);
							    	}
							    	echo ($before . esc_html__('Ask question', 'wpqa') . ($wpqa_add_question_user > 0?" ".esc_html__("to","wpqa")." ".$display_name:"") . $after);
							    }else if (wpqa_is_user_profile()) {
							    	$wpqa_user_id = (int)get_query_var(apply_filters('wpqa_user_id','wpqa_user_id'));
						    		$user_name = get_the_author_meta("display_name",$wpqa_user_id);
						    		echo ($before_schema.(wpqa_is_home_profile()?$user_name:wpqa_breadcrumbs_schema(wpqa_profile_url($wpqa_user_id),$user_name,2,"yes")));
						    		if (wpqa_user_title()) {
						    			echo ($before.wpqa_profile_title().$after);
						    		}
							    }else if ($wpqa_get_the_title != "") {
							    	if (wpqa_is_view_posts_group() || wpqa_is_edit_posts_group()) {
							    		if (wpqa_is_view_posts_group()) {
									    	$post_id = (int)get_query_var(apply_filters('wpqa_view_posts_group','view_post_group'));
									    }else {
									    	$post_id = (int)get_query_var(apply_filters('wpqa_edit_posts_group','edit_post_group'));
									    }
								    	$group_id = (int)get_post_meta($post_id,"group_id",true);
								    	echo ($group_id > 0?$before . wpqa_breadcrumbs_schema(esc_url(get_permalink($group_id)),get_the_title($group_id),2,'yes') . $after:"");
								    }
									echo ($before.$wpqa_get_the_title.$after);
								}else if (is_category() || is_tag() || is_tax()) {
							        $term = $wp_query->get_queried_object();
							    	$taxonomy = get_taxonomy( $term->taxonomy );
							    	if ( isset($item) && is_array($item) && ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent ) && $parents = wpqa_breadcrumbs_get_term_parents( $term->parent, $term->taxonomy ) )
							    		$item = array_merge( $item, $parents );
							    	$item['last'] = $term->name;
							    	if (isset($term->term_id)) {
							    		echo wpqa_get_taxonomy_parents($term->term_id,$taxonomy->name,true,$term->term_id,array(),$before_schema);
							    	}
							        echo ($before.single_cat_title('',false).$after);
							    }else if (is_day()) {
							        echo ($before_schema.wpqa_breadcrumbs_schema(esc_url(get_year_link(get_the_time('Y'))),get_the_time('Y'),2,"yes")).
							        ($before_schema.wpqa_breadcrumbs_schema(esc_url(get_month_link(get_the_time('Y'),get_the_time('m'))),get_the_time('F'),3,"yes")).
							        ($before . get_the_time('d') . $after);
							    }else if (is_month()) {
							        echo ($before_schema.wpqa_breadcrumbs_schema(esc_url(get_year_link(get_the_time('Y'))),get_the_time('Y'),2,"yes")).
							        ($before . get_the_time('F') . $after);
							    }else if (is_year()) {
							        echo ($before . get_the_time('Y') . $after);
							    }else if (is_single() && !is_attachment()) {
							        if ($post_type != 'post') {
							        	if ($post_type == 'question') {
							    			echo ($before_schema.wpqa_breadcrumbs_schema(get_post_type_archive_link("question"),esc_html__("Questions","wpqa"),2,'yes')).
							    			($before . esc_html__("Q","wpqa")." ". $post->ID . $after);
							        	}else {
							        		$post_type_object = get_post_type_object($post_type);
								        	$slug = $post_type_object->rewrite;
								        	echo ($before_schema.wpqa_breadcrumbs_schema(esc_url(home_url('/').(isset($post_type_object->has_archive)?$post_type_object->has_archive:$slug['slug'])).'/',$post_type_object->labels->singular_name,2,'yes')).
							        		($before.get_the_title().$after);
							        	}
							        }else {
							            $cat = get_the_category();
							            if (isset($cat) && is_array($cat) && isset($cat[0])) {
											$term_id = $cat[0];
											$taxonomy = 'category';
											$list = '';
											$term = get_term( $term_id, $taxonomy );
											if ( is_wp_error( $term ) ) {
												return $term;
											}
											if ( ! $term ) {
												return $list;
											}
											$term_id = $term->term_id;
											$parents = get_ancestors( $term_id, $taxonomy, 'taxonomy' );
											array_unshift( $parents, $term_id );
											$counter = 1;
											foreach ( array_reverse( $parents ) as $term_id ) {
												$counter++;
												$parent = get_term( $term_id, $taxonomy );
												$name   = $parent->name;
												$list  .= $before_schema.wpqa_breadcrumbs_schema(esc_url(get_term_link($parent->term_id,$taxonomy)),$name,$counter,"yes");
											}
							            	echo ($list);
							            }
							            echo ($before.get_the_title().$after);
							        }
							    }else if (!is_single() && !is_page() && $post_type != 'post') {
							        $post_type_object = get_post_type_object($post_type);
							    	echo ($before . (isset($post_type_object->labels->singular_name) && !is_404()?$post_type_object->labels->singular_name:esc_html__("Error 404","wpqa")) . $after);
							    }else if (is_attachment()) {
							        $parent = get_post($post->post_parent);
							        $cat = get_the_category($parent->ID);
							        echo ($before . get_the_title() . $after);
							    }else if (is_page() && !$post->post_parent) {
							        echo ($before . get_the_title() . $after);
							    }else if (is_page() && $post->post_parent) {
							        $parent_id  = $post->post_parent;
							        $breadcrumbs = array();
							        $counter = 1;
							        while ($parent_id) {
							        	$counter++;
							            $page = get_page($parent_id);
							            $breadcrumbs[] = wpqa_breadcrumbs_schema(esc_url(get_permalink($page->ID)),get_the_title($page->ID),$counter,'yes');
							            $parent_id  = $page->post_parent;
							        }
							        $breadcrumbs = array_reverse($breadcrumbs);
							        foreach ($breadcrumbs as $crumb) echo ($before_schema.$crumb);
							        echo ($before . get_the_title() . $after);
							    }else if (is_tag()) {
							        echo ($before . esc_html__('Posts tagged ', 'wpqa') . '"' . single_tag_title('', false) . '"' . $after);
							    }else if (is_404()) {
							        echo ($before . esc_html__('Error 404 ', 'wpqa') . $after);
							    }
							    do_action("wpqa_filter_breadcrumb",$before,$after);
							    if (get_query_var('paged')) {
							        echo ($before . esc_html__('Page', 'wpqa') . ' ' . esc_attr(get_query_var('paged')) . $after);
							    }
							echo '</span>
						</span>';
					echo '</div><!-- End breadcrumb-left -->';
					if ($breadcrumb_right == true) {
						$live_search = wpqa_options('live_search');
						$category_filter = wpqa_options('category_filter');
						echo '<div class="breadcrumb-right">';
							$tax_archive = apply_filters('wpqa_tax_archive',false);
							$tax_filter = apply_filters("wpqa_before_question_category",false);
							$tax_question = apply_filters("wpqa_question_category","question-category");
							if (wpqa_is_user_profile()) {
								if (wpqa_is_user_owner()) {
									if (!wpqa_is_user_edit_home()) {?>
										<div class="question-navigation edit-profile"><a href="<?php echo esc_url(wpqa_get_profile_permalink($user_id,"edit"))?>"><i class="icon-pencil"></i><?php esc_html_e("Edit profile","wpqa")?></a></div>
									<?php }
								}else {
									$ask_question_to_users = wpqa_options("ask_question_to_users");
									if ($ask_question_to_users == "on") {
										$display_name = get_the_author_meta("display_name",$wpqa_user_id);?>
										<div class="ask-question"><a href="<?php echo esc_url(wpqa_add_question_permalink("user"))?>" class="button-default ask-question-user"><?php echo esc_html__("Ask","wpqa")." ".$display_name?></a></div>
									<?php }
								}
							}else if (!is_tag() && !is_tax("question_tags") && ((is_category() || (is_archive() && !is_post_type_archive() && !is_post_type_archive("group")) || is_tax("question-category") || $tax_filter == true || $tax_archive == true || is_page_template("template-categories.php") || is_post_type_archive("question")) && $category_filter == "on")) {
								if (is_page_template("template-categories.php")) {
									$cat_search = get_post_meta($post->ID,prefix_meta.'cat_search',true);
									$cat_filter = get_post_meta($post->ID,prefix_meta.'cat_filter',true);
								}else {
									$cat_search = wpqa_options("cat_search");
									$cat_filter = "";
								}
								if ($tax_archive != true || $tax_filter == true) {
									$cats_search = (is_tax("question-category") || $tax_filter == true || is_post_type_archive("question")?$tax_question:"category");
									$exclude = apply_filters('wpqa_exclude_question_category',array());
									$args = array_merge($exclude,array(
									'child_of'     => 0,
									'parent'       => '',
									'orderby'      => 'name',
									'order'        => 'ASC',
									'hide_empty'   => 1,
									'hierarchical' => 1,
									'exclude'      => '',
									'include'      => '',
									'number'       => '',
									'taxonomy'     => $cats_search,
									'pad_counts'   => false ));
									$options_categories = get_categories($args);
									if ((!is_page_template("template-categories.php") && isset($options_categories) && is_array($options_categories)) || $cat_search == "on" || ($cat_filter == "on" && is_page_template("template-categories.php"))) {?>
										<div class="search-form">
											<?php do_action("wpqa_before_select_filter");
											if ($cat_filter == "on" || is_page_template("template-categories.php")) {
												$cat_sort = get_post_meta($post->ID,prefix_meta.'cat_sort',true);
												$cat_sort = ($cat_sort != ""?$cat_sort:"name");
												$g_cat_filter = (isset($_GET["cat_filter"]) && $_GET["cat_filter"] != ""?esc_html($_GET["cat_filter"]):$cat_sort);
												echo '<form method="get" class="search-filter-form">
													<span class="styled-select cat-filter">
														<select name="cat_filter" onchange="this.form.submit()">
															<option value="count" '.selected($g_cat_filter,"count",false).'>'.esc_html__('Popular','wpqa').'</option>
															<option value="followers" '.selected($g_cat_filter,"followers",false).'>'.esc_html__('Followers','wpqa').'</option>
															<option value="name" '.selected($g_cat_filter,"name",false).'>'.esc_html__('Name','wpqa').'</option>
														</select>
													</span>
												</form>';
											}
											if (!is_page_template("template-categories.php") && isset($options_categories) && is_array($options_categories)) {?>
												<div class="search-filter-form">
													<span class="styled-select cat-filter">
														<select class="home_categories">
															<option<?php echo (is_post_type_archive("question")?' selected="selected"':'')?> value="<?php echo (is_tax("question-category") || $tax_filter == true || is_post_type_archive("question")?get_post_type_archive_link("question"):"")?>"><?php esc_html_e('All Categories','wpqa')?></option>
															<?php foreach ($options_categories as $category) {
																echo apply_filters("wpqa_select_filter_categories",'<option '.(is_category() || is_tax("question-category") || $tax_filter == true?selected(esc_attr(get_query_var((is_category()?'cat':'term'))),(is_category()?$category->term_id:$category->slug),false):"").' value="'.get_term_link($category->slug,is_tax($tax_question) || $tax_filter == true || is_post_type_archive("question")?$tax_question:"category").'">'.esc_html($category->name).'</option>',$tax_filter,$category,$tax_question);
															}?>
														</select>
													</span>
												</div>
											<?php }
											if (is_tax("question-category") && $active_cover_category != "on") {
												$tax_id = (int)get_query_var('wpqa_term_id');
												echo wpqa_follow_cat_button($tax_id,$user_id);
											}
											if ($cat_search == "on") {
												if (is_page_template("template-categories.php")) {
													$cats_tax = get_post_meta($post->ID,prefix_meta.'cats_tax',true);
													$cats_tax = ($cats_tax != ""?$cats_tax:"question");
												}else {
													$cats_tax = (is_tax("question-category") || $tax_filter == true || is_post_type_archive("question")?"question":"post");
												}
												echo '<form method="get" action="'.esc_url(wpqa_get_search_permalink()).'" class="search-input-form main-search-form">
													<input class="search-input'.($live_search == "on"?" live-search live-search-icon":"").'"'.($live_search == "on"?" autocomplete='off'":"").' type="search" name="search" placeholder="'.esc_attr__('Type to find...','wpqa').'">';
													if ($live_search == "on") {
														echo '<div class="loader_2 search_loader"></div>
														<div class="search-results results-empty"></div>';
													}
													echo '<button class="button-search"><i class="icon-search"></i></button>
													<input type="hidden" name="search_type" class="search_type" value="'.apply_filters("wpqa_breadcrumb_search_type",($cats_tax == "post"?"category":$tax_question)).'">
												</form>';
											}
											do_action("wpqa_after_select_filter");?>
										</div><!-- End search-form -->
									<?php }
								}
							}else if (is_page_template("template-tags.php") || is_tag() || is_tax("question_tags")) {
								if (is_page_template("template-tags.php")) {
									$tag_search = get_post_meta($post->ID,prefix_meta.'tag_search',true);
									$tag_filter = get_post_meta($post->ID,prefix_meta.'tag_filter',true);
								}else {
									$tag_search = wpqa_options("tag_search");
									$tag_filter = "";
								}
								if ($tag_search == "on" || $tag_filter == "on") {
									echo '<div class="search-form">';
										if (is_page_template("template-tags.php") && $tag_filter == "on") {
											$tag_sort = get_post_meta($post->ID,prefix_meta.'tag_sort',true);
											$tag_sort = ($tag_sort != ""?$tag_sort:"name");
											$g_tag_filter = (isset($_GET["tag_filter"]) && $_GET["tag_filter"] != ""?esc_html($_GET["tag_filter"]):$tag_sort);
											echo '<form method="get" class="search-filter-form">
												<span class="styled-select tag-filter">
													<select name="tag_filter" onchange="this.form.submit()">
														<option value="count" '.selected($g_tag_filter,"count",false).'>'.esc_html__('Popular','wpqa').'</option>
														<option value="followers" '.selected($g_tag_filter,"followers",false).'>'.esc_html__('Followers','wpqa').'</option>
														<option value="name" '.selected($g_tag_filter,"name",false).'>'.esc_html__('Name','wpqa').'</option>
													</select>
												</span>
											</form>';
										}
										if (is_tax("question_tags")) {
											$tax_id = (int)get_query_var('wpqa_term_id');
											echo wpqa_follow_cat_button($tax_id,$user_id,'tag');
										}
										if ($tag_search == "on") {
											if (is_page_template("template-tags.php")) {
												$tags_tax = get_post_meta($post->ID,prefix_meta.'tags_tax',true);
												$tags_tax = ($tags_tax != ""?$tags_tax:"question");
											}else {
												$tags_tax = (is_tax("question_tags")?"question":"post");
											}
											echo '<form method="get" action="'.esc_url(wpqa_get_search_permalink()).'" class="search-input-form main-search-form">
												<input class="search-input'.($live_search == "on"?" live-search live-search-icon":"").'"'.($live_search == "on"?" autocomplete='off'":"").' type="search" name="search" placeholder="'.esc_attr__('Type to find...','wpqa').'">';
												if ($live_search == "on") {
													echo '<div class="loader_2 search_loader"></div>
													<div class="search-results results-empty"></div>';
												}
												echo '<button class="button-search"><i class="icon-search"></i></button>
												<input type="hidden" name="search_type" class="search_type" value="'.($tags_tax == "post"?"post_tag":"question_tags").'">
											</form>';
										}
									echo '</div>';
								}
							}else if (is_page_template("template-users.php")) {
								$user_search = get_post_meta($post->ID,prefix_meta.'user_search',true);
								$user_filter = get_post_meta($post->ID,prefix_meta.'user_filter',true);
								if ($user_search == "on" || $user_filter == "on") {
									echo '<div class="search-form">';
										if ($user_filter == "on") {
											$user_sort = get_post_meta($post->ID,prefix_meta.'user_sort',true);
											$user_sort = ($user_sort != ""?$user_sort:"user_registered");
											$g_user_filter = (isset($_GET["user_filter"]) && $_GET["user_filter"] != ""?esc_html($_GET["user_filter"]):$user_sort);
											echo '<form method="get" class="search-filter-form">
												<span class="styled-select user-filter">
													<select name="user_filter" onchange="this.form.submit()">
														<option value="user_registered" '.selected($g_user_filter,"user_registered",false).'>'.esc_html__('Date Registered','wpqa').'</option>
														<option value="display_name" '.selected($g_user_filter,"display_name",false).'>'.esc_html__('Name','wpqa').'</option>
														<option value="ID" '.selected($g_user_filter,"ID",false).'>'.esc_html__('ID','wpqa').'</option>
														<option value="question_count" '.selected($g_user_filter,"question_count",false).'>'.esc_html__('Questions','wpqa').'</option>
														<option value="answers" '.selected($g_user_filter,"answers",false).'>'.esc_html__('Answers','wpqa').'</option>
														<option value="the_best_answer" '.selected($g_user_filter,"the_best_answer",false).'>'.esc_html__('Best Answers','wpqa').'</option>';
														if ($active_points == "on") {
															echo '<option value="points" '.selected($g_user_filter,"points",false).'>'.esc_html__('Points','wpqa').'</option>';
														}
														echo '<option value="followers" '.selected($g_user_filter,"followers",false).'>'.esc_html__('Followers','wpqa').'</option>
														<option value="post_count" '.selected($g_user_filter,"post_count",false).'>'.esc_html__('Posts','wpqa').'</option>
														<option value="comments" '.selected($g_user_filter,"comments",false).'>'.esc_html__('Comments','wpqa').'</option>
													</select>
												</span>
											</form>';
										}
										if ($user_search == "on") {
											echo '<form method="get" action="'.esc_url(wpqa_get_search_permalink()).'" class="search-input-form main-search-form">
												<input class="search-input'.($live_search == "on"?" live-search live-search-icon":"").'"'.($live_search == "on"?" autocomplete='off'":"").' type="search" name="search" placeholder="'.esc_attr__('Type to find...','wpqa').'">';
												if ($live_search == "on") {
													echo '<div class="loader_2 search_loader"></div>
													<div class="search-results results-empty"></div>';
												}
												echo '<button class="button-search"><i class="icon-search"></i></button>
												<input type="hidden" name="search_type" class="search_type" value="users">
											</form>';
										}
									echo '</div>';
								}
							}else if (is_singular("question")) {
								$question_navigation = wpqa_options("question_navigation");
								$question_nav_category = wpqa_options("question_nav_category");
								$custom_page_setting = get_post_meta($post->ID,prefix_meta.'custom_page_setting',true);
								if ($custom_page_setting == "on") {
									$question_navigation = get_post_meta($post->ID,prefix_meta.'post_navigation',true);
									$question_nav_category = get_post_meta($post->ID,prefix_meta.'question_nav_category',true);
								}
								if ($question_navigation == "on") {
									if ($question_nav_category == "on") {
										$previous_post = get_previous_post(true,'','question-category');
										$next_post = get_next_post(true,'','question-category');
									}else {
										$previous_post = get_previous_post();
										$next_post = get_next_post();
									}?>
									<div class="question-navigation">
										<?php if (isset($next_post) && is_object($next_post)) {?>
											<a class="nav-next" href="<?php echo get_permalink($next_post->ID)?>"><?php esc_html_e("Next","wpqa")?><i class="icon-right-open"></i></a>
										<?php }
										if (isset($previous_post) && is_object($previous_post)) {?>
											<a class="nav-previous" href="<?php echo get_permalink($previous_post->ID)?>"><i class="icon-left-open"></i></a>
										<?php }?>
									</div><!-- End page-navigation -->
								<?php }
								$question_stats = apply_filters('wpqa_question_stats',true);
								$the_best_answer = get_post_meta($post->ID,"the_best_answer",true);
								$comments = get_comments('post_id='.$post->ID);
								$closed_question = get_post_meta($post->ID,"closed_question",true);
								if ($question_stats == true && ($closed_question == 1 || (isset($the_best_answer) && $the_best_answer != "" && $comments) || ($the_best_answer == "" && $comments))) {?>
									<div class="question-stats">
										<?php if ($closed_question == 1) {?>
											<span class="question-stats-closed question-closed"><i class="icon-cancel"></i><?php esc_html_e("Closed","wpqa")?></span>
										<?php }else if (isset($the_best_answer) && $the_best_answer != "" && $comments) {?>
											<span class="question-stats-answered question-answered-done"><i class="icon-check"></i><?php esc_html_e("Answered","wpqa")?></span>
										<?php }else if ($the_best_answer == "" && $comments) {?>
											<span class="question-stats-process"><i class="icon-flash"></i><?php esc_html_e("In Process","wpqa")?></span>
										<?php }?>
									</div><!-- End question-stats -->
								<?php }
							}
							do_action("wpqa_right_breadcrumb");
							echo '<div class="clearfix"></div>
						</div><!-- End breadcrumb-right -->';
					}
				echo '</div><!-- End breadcrumbs-wrap -->';
			if ($breadcrumbs_style == "style_2") {
				echo '</div><!-- End the-main-container -->';
			}
		echo '</div><!-- End breadcrumbs -->';
	}
endif;
/* Get taxonomy parents */
if (!function_exists('wpqa_get_taxonomy_parents')) :
	function wpqa_get_taxonomy_parents( $id, $taxonomy = 'category', $link = false,$main_id = '', $visited = array(), $before = "" ) {
		$out = '';
		$parent = get_term( $id, $taxonomy );
		if ( is_wp_error( $parent ) ) {
			return $parent;
		}
		$name = $parent->name;
		if ( $parent->parent && ( $parent->parent != $parent->term_id ) && is_array($visited) && !in_array( $parent->parent, $visited ) ) {
			$visited[] = $parent->parent;
			$out .= $before.wpqa_get_taxonomy_parents( $parent->parent, $taxonomy, $link, $visited, $before );
		}
		if ( $link ) {
			if ($parent->term_id != $main_id) {
				$out .= '<a href="' . esc_url( get_term_link( $parent,$taxonomy ) ) . '" title="' . esc_attr( $parent->name ) . '">'.$name.'</a>';
			}
		}else {
			$out .= $name;
		}
		return $out;
	}
endif;
/* Get term parents */
if (!function_exists('wpqa_breadcrumbs_get_term_parents')) :
	function wpqa_breadcrumbs_get_term_parents( $parent_id = '', $taxonomy = '' ) {
		$html = array();
		$parents = array();
		if ( empty( $parent_id ) || empty( $taxonomy ) )
			return $parents;
		$counter = 1;
		while ( $parent_id ) {
			$counter++;
			$parent = get_term( $parent_id, $taxonomy );
			$parents[] = wpqa_breadcrumbs_schema(esc_url(get_term_link($parent,$taxonomy)),$parent->name,$counter,"yes");
			$parent_id = $parent->parent;
		}
		if ( $parents )
			$parents = array_reverse( $parents );
		return $parents;
	}
endif;
/* Breadcrumbs schema */
if (!function_exists('wpqa_breadcrumbs_schema')) :
	function wpqa_breadcrumbs_schema($link = '',$name,$position,$current = '') {
		$return = '<span'.($current != ''?' class="current"':'').' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
			<meta itemprop="position" content="'.$position.'">';
			if ($link != '') {
				$return .= '<a itemprop="item" href="'.$link.'" title="'.esc_attr(str_replace('<i class="icon-home"></i>','',$name)).'">';
			}
				$return .= '<span itemprop="name">'.$name.'</span>';
			if ($link != '') {
				$return .= '</a>';
			}
		$return .= '</span>';
		return $return;
	}
endif;
/* Set post & question stats */
add_action("wpqa_action_after_post_content","wpqa_set_post_stats",1,2);
function wpqa_set_post_stats($post_id,$post_author) {
	$active_post_stats = wpqa_options("active_post_stats");
	if ($active_post_stats == "on" && is_single($post_id)) {
    	$active_stats = true;
		$user_id     = get_current_user_id();
		$yes_private = (is_singular("question")?wpqa_private($post_id,$post_author,$user_id):1);
		if (!is_super_admin($user_id) && $yes_private != 1) {
			$active_stats = false;
		}
    	if ($active_stats == true) {
    		$ajax_file = wpqa_options("ajax_file");
			$ajax_file = ($ajax_file == "theme"?plugins_url('includes/ajax.php',__FILE__):admin_url("admin-ajax.php"));?>
    		<script type="text/javascript">
    			jQuery(document).ready(function($) {
    				jQuery.post("<?php echo ($ajax_file)?>",{action:"wpqa_update_post_stats",post_id:"<?php echo (int)$post_id?>"});
    			});
    		</script>
        <?php }
    }
}
/* Update post stats */
add_action('wp_ajax_wpqa_update_post_stats','wpqa_update_post_stats');
add_action('wp_ajax_nopriv_wpqa_update_post_stats','wpqa_update_post_stats');
function wpqa_update_post_stats($post_id = 0) {
	$post_id = (int)($post_id > 0?$post_id:$_POST["post_id"]);
	$post_meta_stats = wpqa_options("post_meta_stats");
	$cache_post_stats = wpqa_options("cache_post_stats");
	$post_meta_stats = ($post_meta_stats != ""?$post_meta_stats:"post_stats");
    $current_stats = get_post_meta($post_id,$post_meta_stats,true);
    $visit_cookie = wpqa_options("visit_cookie");
    if ($visit_cookie != "on" || ($visit_cookie == "on" && !isset($_COOKIE[wpqa_options("uniqid_cookie").'wpqa_post_stats'.$post_id]))) {
        if (!isset($current_stats)) {
            add_post_meta($post_id,$post_meta_stats,1);
            if ($cache_post_stats == "on") {
	            set_transient($post_meta_stats.$post_id,(int)$current_stats+1,60*60*24);
	        }
        }else {
            update_post_meta($post_id,$post_meta_stats,(int)$current_stats+1);
            if ($cache_post_stats == "on") {
            	$post_stats = get_transient($post_meta_stats.$post_id);
            	if ($post_stats == false) {
		            set_transient($post_meta_stats.$post_id,(int)$current_stats+1,60*60*24);
		        }
	        }
        }
    }
    if ($visit_cookie == "on") {
    	setcookie(wpqa_options("uniqid_cookie").'wpqa_post_stats'.$post_id,"wpqa_post_stats",time()+YEAR_IN_SECONDS,COOKIEPATH,COOKIE_DOMAIN);
    }
}
/* Update profile */
if (!function_exists('wpqa_update_profile')) :
	function wpqa_update_profile($user_id) {
		$update_profile = "";
		if (is_user_logged_in()) {
			$update_profile = get_user_meta($user_id,"update_profile",true);
			if (is_page()) {
				global $post;
				$login_only = get_post_meta($post->ID,prefix_meta."login_only",true);
				$update_profile = ($update_profile == "yes" && $login_only != "on"?"yes":"no");
			}
		}
		return $update_profile;
	}
endif;
/* Get edit profile page */
if (!function_exists('wpqa_update_edit_profile')) :
	function wpqa_update_edit_profile($user_id,$update_profile) {
		if ($update_profile == "yes") {
			echo '<div class="alert-message"><i class="icon-lamp"></i><p>'.esc_html__("Kindly fill the required fields, You need to fill all the required fields.","wpqa").'</p></div>';
			$nicename = wpqa_get_user_nicename($user_id);
			$templates = array(
				'edit-'.$nicename.'.php',
				'edit-'.$user_id.'.php',
				'edit.php',
				'profile.php',
			);
			if (isset($templates) && is_array($templates) && !empty($templates)) {
				$wpqa_get_template = wpqa_get_template($templates,"profile/");
				if ($wpqa_get_template) {
					include $wpqa_get_template;
				}
			}
			get_footer();
			die();
		}
	}
endif;
/* Check if site for the users only */
if (!function_exists('wpqa_site_users_only')) :
	function wpqa_site_users_only() {
		$site_users_only = $active_confirm_email = $login_only = "no";
		$site_users_option = wpqa_options("site_users_only");
		
		if (is_user_logged_in()) {
			$if_user_id = get_user_by("id",get_current_user_id());
			if (isset($if_user_id->caps["activation"]) && $if_user_id->caps["activation"] == 1) {
				$active_confirm_email = "yes";
			}
		}
		
		if ((!is_user_logged_in() && $site_users_option == "on") || (is_user_logged_in() && $active_confirm_email == "yes" && $site_users_option == "on")) {
			if (is_page()) {
				global $post;
				$login_only = get_post_meta($post->ID,prefix_meta.'login_only',true);
			}
			if ($login_only != "on") {
				$site_users_only = "yes";
			}else {
				$site_users_only = "no";
			}
		}
		
		return $site_users_only;
	}
endif;
/* Check if the site under construction */
if (!function_exists('wpqa_under_construction')) :
	function wpqa_under_construction() {
		$under_construction = wpqa_options("under_construction");
		if (!is_super_admin() && $under_construction == "on") {
			return $under_construction;
		}
	}
endif;
/* Check if confirm mail active */
if (!function_exists('wpqa_users_confirm_mail')) :
	function wpqa_users_confirm_mail($make_pages_work = true) {
		$site_users_only = $login_only = "no";
		if (is_user_logged_in()) {
			$if_user_id = get_user_by("id",get_current_user_id());
			if (isset($if_user_id->caps["ban_group"]) && $if_user_id->caps["ban_group"] == 1) {
				$site_users_only = "yes";
				if (is_page()) {
					global $post;
					$banned_only = get_post_meta($post->ID,prefix_meta.'banned_only',true);
					if ($banned_only == "on") {
						$site_users_only = "no";
					}
				}
			}else {
				if ($make_pages_work == true && $site_users_only == "yes") {
					if (is_page()) {
						global $post;
						$login_only = get_post_meta($post->ID,prefix_meta.'login_only',true);
					}
					
					if ($login_only != "on") {
						$site_users_only = "yes";
					}else {
						$site_users_only = "no";
					}
				}
			}
		}
		return $site_users_only;
	}
endif;
/* Header content */
add_action('wpqa_header_content','wpqa_header_content');
if (!function_exists('wpqa_header_content')) :
	function wpqa_header_content($args = array()) {
		/* Session */
		do_action("wpqa_show_session");
		/* Update */
		if (is_user_logged_in()) {
			wpqa_update_edit_profile($args["user_id"],$args["update_profile"]);
		}
		do_action("wpqa_do_payments");
	}
endif;
/* Top bar wordpress */
add_filter('show_admin_bar','wpqa_disable_admin_bar',20,1);
if (!function_exists('wpqa_disable_admin_bar')) :
	function wpqa_disable_admin_bar( $show_admin_bar ) {
		$top_bar_wordpress = wpqa_options("top_bar_wordpress");
		$user_info = get_userdata(get_current_user_id());
		$user_group = (isset($user_info->roles) && isset($user_info->roles[0])?$user_info->roles[0]:"");
		if ($user_group == "ban_group" || ($top_bar_wordpress == "on" && !current_user_can('administrator'))) {
			$top_bar_groups = wpqa_options("top_bar_groups");
			if ($user_group == "ban_group" || (is_array($top_bar_groups) && in_array($user_group,$top_bar_groups))) {
				$show_admin_bar = false;
			}
		}
		return $show_admin_bar;
	}
endif;
/* Pagination */
if (!function_exists('wpqa_pagination')) :
	function wpqa_pagination($args = array(),$max_num_pages = '',$query = '') {
		global $wp_rewrite,$wp_query;
		do_action('wpqa_pagination_start');
		if (isset($query)) {
			$o_wp_query = $wp_query;
			$wp_query = $query;
		}
		if ($max_num_pages == "") {
			$max_num_pages = $wp_query->max_num_pages;
		}
		/* If there's not more than one page,return nothing. */
		if (1 >= $max_num_pages) {
			if (isset($o_wp_query)) {
				$wp_query = $o_wp_query;
			}
			return;
		}
		/* Get the current page. */
		$paged = $current = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
		$page_paged = (get_query_var("paged") != ""?"paged":(get_query_var("page") != ""?"page":"paged"));
		$current = $paged;
		/* Get the max number of pages. */
		$max_num_pages = ($max_num_pages != ""?$max_num_pages:intval($wp_query->max_num_pages));
		/* Set up some default arguments for the paginate_links() function. */
		$defaults = array(
			'base'         => esc_url_raw(add_query_arg($page_paged,'%#%')),
			'format'       => '',
			'total'        => $max_num_pages,
			'current'      => esc_attr($current),
			'prev_next'    => true,
			'prev_text'    => '<i class="icon-left-open"></i>',
			'next_text'    => '<i class="icon-right-open"></i>',
			'show_all'     => false,
			'end_size'     => 1,
			'mid_size'     => 1,
			'add_fragment' => '',
			'type'         => 'plain',
			'before'       => '<div class="main-pagination"><div class="pagination">',
			'after'        => '</div></div>',
			'echo'         => true,
		);
		/* Add the $base argument to the array if the user is using permalinks. */
		if (!wpqa_is_search() && $wp_rewrite->using_permalinks()) {
			$defaults['base'] = user_trailingslashit(trailingslashit(get_pagenum_link()) . 'page/%#%');
		}
		/* If we're on a search results page,we need to change this up a bit. */
		if (!wpqa_is_search() && is_search()) {
		/* If we're in BuddyPress,use the default "unpretty" URL structure. */
			if (class_exists('BP_Core_User')) {
				$search_query = esc_attr(get_query_var('s'));
				$base = user_trailingslashit(esc_url(home_url('/'))) . '?s=' . $search_query . '&paged=%#%';
				$defaults['base'] = $base;
			}else {
				$search_permastruct = $wp_rewrite->get_search_permastruct();
				if (!empty($search_permastruct))
					$defaults['base'] = esc_url_raw(add_query_arg('page','%#%'));
			}
		}
		/* Merge the arguments input with the defaults. */
		$args = wp_parse_args($args,$defaults);
		/* Allow developers to overwrite the arguments with a filter. */
		$args = apply_filters('wpqa_pagination_args',$args);
		/* Don't allow the user to set this to an array. */
		if ('array' == $args['type'])
			$args['type'] = 'plain';
		/* Make sure raw querystrings are displayed at the end of the URL,if using pretty permalinks. */
		$pattern = '/\?(.*?)\//i';
		preg_match($pattern,$args['base'],$raw_querystring);
		if ($wp_rewrite->using_permalinks() && $raw_querystring)
			$raw_querystring[0] = str_replace('','',$raw_querystring[0]);
			if (!empty($raw_querystring)) {
				$args['base'] = str_replace($raw_querystring[0],'',$args['base']);
				$args['base'] .= substr($raw_querystring[0],0,-1);
			}
		/* Get the paginated links. */
		$page_links = paginate_links($args);
		/* Remove 'page/1' from the entire output since it's not needed. */
		if (!wpqa_is_search()) {
			$page_links = str_replace(array('&#038;paged=1\'','/page/1\''),'\'',$page_links);
		}
		/* Wrap the paginated links with the $before and $after elements. */
		$page_links = $args['before'] . $page_links . $args['after'];
		/* Allow devs to completely overwrite the output. */
		$page_links = apply_filters('wpqa_pagination',$page_links);
		do_action('wpqa_pagination_end');
		/* Return the paginated links for use in themes. */
		if (isset($o_wp_query)) {
			$wp_query = $o_wp_query;
		}
		if ($args['echo']) {
			echo ($page_links);
		}else {
			return $page_links;
		}
	}
endif;
/* Search load more */
add_filter("get_pagenum_link","wpqa_get_pagenum_link");
function wpqa_get_pagenum_link($result) {
	if (wpqa_is_search() || wpqa_is_user_questions() || wpqa_is_user_posts()) {
		global $wp_rewrite;
		$paged = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
		$pagenum = $paged + 1;
		$request = remove_query_arg('page');
		$home_root = parse_url(home_url());
		$home_root = (isset($home_root['path']))?$home_root['path']:'';
		$home_root = preg_quote($home_root,'|');
		$request = preg_replace('|^'.$home_root.'|i','',$request);
		$request = preg_replace('|^/+|','',$request);
		if (wpqa_is_user_questions()) {
			$request = str_ireplace('page/'.$pagenum.'/','',$request);
		}
		$base = trailingslashit(get_bloginfo('url'));
		if ($pagenum > 1) {
			$result = add_query_arg('page',$pagenum,$base.$request);
		}else {
			$result = $base.$request;
		}
	}
	return $result;
}
/* Pagination load */
if (!function_exists('wpqa_pagination_load')) :
	function wpqa_pagination_load($post_pagination = "pagination",$max_num_pages = "",$it_answer_pagination = false,$its_post_type = false,$wpqa_query = null,$it_comment_pagination = false,$args = array()) {
		if ($post_pagination != "none") {
			$get_post_type = get_post_type();
			if (isset($it_answer_pagination) && $it_answer_pagination == true) {
				if (isset($it_comment_pagination) && $it_comment_pagination == true) {
					$nomore_text = esc_html__("No more comments","wpqa");
					$load_text = esc_html__("Load More Comments","wpqa");
					$old_posts = esc_html__('Old Comments','wpqa');
					$new_posts = esc_html__('New Comments','wpqa');
				}else {
					$nomore_text = esc_html__("No more answers","wpqa");
					$load_text = esc_html__("Load More Answers","wpqa");
					$old_posts = esc_html__('Old Answers','wpqa');
					$new_posts = esc_html__('New Answers','wpqa');
				}
				$wrap_main_content = 'commentlist';
				$pagination_class = 'pagination-answer';
			}else if (isset($its_post_type) && $its_post_type == "question") {
				$nomore_text = esc_html__("No more questions","wpqa");
				$load_text = esc_html__("Load More Questions","wpqa");
				$wrap_main_content = 'post-articles.question-articles';
				$pagination_class = 'pagination-question';
				$old_posts = esc_html__('Old Questions','wpqa');
				$new_posts = esc_html__('New Questions','wpqa');
			}else if (isset($its_post_type) && $its_post_type == "group") {
				$nomore_text = esc_html__("No more groups","wpqa");
				$load_text = esc_html__("Load More Groups","wpqa");
				$wrap_main_content = 'post-articles.group-articles';
				$pagination_class = 'pagination-group';
				$old_posts = esc_html__('Old Groups','wpqa');
				$new_posts = esc_html__('New Groups','wpqa');
			}else {
				$nomore_text = esc_html__("No more posts","wpqa");
				$load_text = esc_html__("Load More Posts","wpqa");
				$wrap_main_content = 'post-articles';
				$pagination_class = 'pagination-post';
				$old_posts = esc_html__('Old Entries','wpqa');
				$new_posts = esc_html__('New Entries','wpqa');
			}
			if ($post_pagination == "infinite_scroll" || $post_pagination == "load_more") {
				$more_link = get_next_posts_link(' ',$max_num_pages);
				if (!empty($more_link)) :?>
					<script type="text/javascript">
						(function($) {
							jQuery(document).ready(function() {
								/* Load more */
								function wpqa_load_more(load_type,j_this,ajax_new_count) {
									var main_content = ".the-main-inner";
									if (load_type == "infinite-scroll") {
										var $link = jQuery('.posts-infinite-scroll a');
									}else {
										var $link = j_this;
									}
									var page_url = $link.attr("href");
									if (page_url != undefined) {
										if (load_type == "infinite-scroll") {
											$link.parent().parent().animate({ opacity: 1}, 300).css('padding', '10px');
										}else {
											$link.closest(main_content).find(".posts-"+load_type+" a").hide();
										}
										$link.closest(main_content).find(".posts-"+load_type+" .load_span").show();
										jQuery("<div>").load(page_url, function() {
											var n = ajax_new_count.toString();
											var $wrap = $link.closest(main_content).find('.<?php echo esc_js($wrap_main_content)?>');
											<?php if ('post' === $get_post_type) {?>
												var $share = $link.closest(main_content).find('.post-articles article.post .post-share > ul').attr("style");
											<?php }?>
											var $new = jQuery(this).find('.<?php echo (isset($it_answer_pagination) && $it_answer_pagination == true?"commentlist > li.comment":"post-articles article.".esc_js($get_post_type))?>').addClass('<?php echo (isset($it_answer_pagination) && $it_answer_pagination == true?"comment":"post-section")?>-new-'+n);
											<?php if ('post' === $get_post_type) {?>
												$new.find('.post-share > ul').attr("style",$share);
											<?php }?>
											var $this_div = jQuery(this);
											$new.imagesLoaded( function() {
												$new.hide().appendTo($wrap).fadeIn(400);
												/* Lightbox */
												var lightboxArgs = {
													animation_speed: "fast",
													overlay_gallery: true,
													autoplay_slideshow: false,
													slideshow: 5000,
													theme: "pp_default",
													opacity: 0.8,
													show_title: false,
													social_tools: "",
													deeplinking: false,
													allow_resize: true,
													counter_separator_label: "/",
													default_width: 940,
													default_height: 529
												};
												jQuery("a[href$=jpg],a[href$=JPG],a[href$=jpeg],a[href$=JPEG],a[href$=png],a[href$=gif],a[href$=bmp]:has(img)").prettyPhoto(lightboxArgs);
												jQuery("a[class^='prettyPhoto'],a[rel^='prettyPhoto']").prettyPhoto(lightboxArgs);
												/* Facebook */
												jQuery(".facebook-remove").remove();
												/* Owl */
												jQuery(".post-section-new-"+n+" .slider-owl").each(function () {
													var $slider = jQuery(this);
													var $slider_item = $slider.find('.slider-item').length;
													$slider.find('.slider-item').css({"height":"auto"});
													if ($slider.find('img').length) {
														var $slider = jQuery(this).imagesLoaded(function() {
															$slider.owlCarousel({
																autoPlay: 5000,
																margin: 10,
																responsive: {
																	0: {
																		items: 1
																	}
																},
																stopOnHover: true,
																navText : ["", ""],
																nav: ($slider_item > 1)?true:false,
																rtl: jQuery('body.rtl').length?true:false,
																loop: ($slider_item > 1)?true:false,
															});
														});
													}else {
														$slider.owlCarousel({
															autoPlay: 5000,
															margin: 10,
															responsive: {
																0: {
																	items: 1
																}
															},
															stopOnHover: true,
															navText : ["", ""],
															nav: ($slider_item > 1)?true:false,
															rtl: jQuery('body.rtl').length?true:false,
															loop: ($slider_item > 1)?true:false,
														});
													}
												});
												/* Question masonry */
												if (jQuery(".post-section-new-"+n+".post-with-columns").length) {
													if ($new.eq(0).is('.question-masonry')) {
														var newItems = jQuery('.post-section-new-'+n);
														jQuery('.question-articles').isotope( 'insert', newItems );
														jQuery('.question-articles').isotope({
															filter: "*",
															animationOptions: {
																duration: 750,
																itemSelector: '.question-masonry',
																easing: "linear",
																queue: false,
															}
														});
														setTimeout(function() {
															if ($new.eq(0).is('.post-masonry')) {
																jQuery('.question-articles').isotope({
																	filter: "*",
																	animationOptions: {
																		duration: 750,
																		itemSelector: '.question-masonry',
																		easing: "linear",
																		queue: false,
																	}
																});
															}
														}, 1000);
													}else {
														jQuery(".post-section-new-"+n+".post-with-columns").matchHeight();
														jQuery(".post-section-new-"+n+".post-with-columns > .article-question").matchHeight();
													}
												}
												/* Poll */
												if (jQuery(".post-section-new-"+n+" .progressbar-percent").length) {
													jQuery(".post-section-new-"+n+" .progressbar-percent").each(function(){
														var $this = jQuery(this);
														var percent = $this.attr("attr-percent");
														$this.bind("inview", function(event, isInView, visiblePartX, visiblePartY) {
															if (isInView) {
																$this.animate({ "width" : percent + "%"}, 700);
															}
														});
													});
												}
												/* Audio */
												if ($new.eq(0).find('.wp-audio-shortcode')) {
													<?php wp_enqueue_style('wp-mediaelement');
													wp_enqueue_script('wp-playlist');?>
													mejs.plugins.silverlight[0].types.push('video/x-ms-wmv');
													mejs.plugins.silverlight[0].types.push('audio/x-ms-wma');
													jQuery(function () {
														var settings = {};
														if ( typeof _wpmejsSettings !== 'undefined' ) {
															settings = _wpmejsSettings;
														}
														settings.success = settings.success || function (mejs) {
															var autoplay, loop;
															if ( 'flash' === mejs.pluginType ) {
																autoplay = mejs.attributes.autoplay && 'false' !== mejs.attributes.autoplay;
																loop = mejs.attributes.loop && 'false' !== mejs.attributes.loop;
																autoplay && mejs.addEventListener( 'canplay', function () {
																	mejs.play();
																}, false );
																loop && mejs.addEventListener( 'ended', function () {
																	mejs.play();
																}, false );
															}
														};
														jQuery('.post-section-new-'+n+' .wp-audio-shortcode').mediaelementplayer( settings );
													});
												}
												$link.closest(main_content).find(".posts-"+load_type+" .load_span").hide();
												if (load_type == "load-more") {
													$link.closest(main_content).find(".posts-"+load_type+" a").show();
												}
												/* Content */
												jQuery(".all-main-wrap,.fixed-sidebar,.fixed_nav_menu").css({"height":"auto"});
												/* load more */
												if ($this_div.find(".posts-"+load_type).length) {
													if (load_type == "infinite-scroll") {
														$link.closest(main_content).find(".posts-infinite-scroll").html($this_div.find(".posts-infinite-scroll").html()).animate({opacity: 0}, 300).css("padding","0");
													}else {
														$link.closest(main_content).find(".posts-"+load_type).html($this_div.find(".posts-"+load_type).html());
													}
												}else {
													$link.closest(main_content).find(".pagination-wrap").html('<p class="no-comments"><?php echo esc_js($nomore_text)?></p>');
													$link.closest(main_content).find(".posts-"+load_type).fadeOut("fast").remove();
												}
												jQuery("<?php echo (isset($it_answer_pagination) && $it_answer_pagination == true?"comment":"post-section")?>-new-"+n).removeClass("<?php echo (isset($it_answer_pagination) && $it_answer_pagination == true?"comment":"post-section")?>-new-"+n);
												return false;
											});
										});
									}
								}
								var ajax_new_count = 0;
								/* infinite scroll */
								jQuery(".posts-infinite-scroll").each (function () {
									jQuery(this).bind("inview",function(event,isInView,visiblePartX,visiblePartY) {
										if  (jQuery(".posts-infinite-scroll").length && isInView) {
											/* wpqa_load_more */
											ajax_new_count++;
											wpqa_load_more("infinite-scroll","",ajax_new_count);
										}
									});
								});
								/* load more */
								jQuery("body").on("click",".posts-load-more a",function(e) {
									e.preventDefault();
									/* wpqa_load_more */
									ajax_new_count++;
									wpqa_load_more("load-more",jQuery(this),ajax_new_count);
								});
							});
						})(jQuery);
					</script>
				<?php endif;
			}?>
			<div class="clearfix"></div>
			<div class="pagination-wrap <?php echo esc_attr($pagination_class).(empty($more_link)?" no-pagination-wrap":"")?>">
				<?php if ($post_pagination == "load_more" || $post_pagination == "infinite_scroll") {
					$more_link = ($post_pagination == "load_more"?get_next_posts_link($load_text,$max_num_pages):$more_link);
					if (!empty($more_link)) {?>
						<div class="pagination-nav <?php echo ($post_pagination == "infinite_scroll"?"posts-infinite-scroll":"posts-load-more")?>"<?php (is_array($args) && !empty($args)?" data-query='".json_encode($args)."'":"")?>>
							<span class="load_span"><span class="loader_2"></span></span>
							<div class="load-more"><?php echo ($more_link)?></div>
						</div><!-- End pagination-nav -->
					<?php }
				}else if ($post_pagination == "pagination") {
					wpqa_pagination(array(),$max_num_pages,(isset($wpqa_query)?$wpqa_query:null));
				}else {?>
					<div class="page-navigation page-navigation-before clearfix">
						<div class="row">
							<div class="col col6">
								<div class="nav-next"><?php next_posts_link('<i class="icon-left-thin"></i><span>'.$old_posts.'</span>',$max_num_pages)?></div>
							</div>
							<div class="col col6">
								<div class="nav-previous"><?php previous_posts_link('<span>'.$new_posts.'</span><i class="icon-right-thin"></i>',$max_num_pages)?></div>
							</div>
						</div>
					</div>
				<?php }?>
			</div>
		<?php }
	}
endif;
/* Share links */
if (!function_exists('wpqa_share')) :
	function wpqa_share($post_share,$share_facebook,$share_twitter,$share_linkedin,$share_whatsapp,$share_style = "style_1",$comment_id = "",$tax_id = "",$url = "",$title = "",$post_id = 0,$send_email = "") {
		if ($post_id == 0) {
			global $post;
			$post_id = (int)$post->ID;
		}
		if ($share_facebook == "share_facebook" || $share_twitter == "share_twitter" || $share_linkedin == "share_linkedin" || $share_whatsapp == "share_whatsapp") {
			if ($url == "" && $title == "") {
				if ($tax_id > 0 && is_tax("question-category")) {
					$url = get_term_link($tax_id,"question-category");
					$title = esc_html(get_query_var('wpqa_term_name'));
				}else {
					$url = urlencode(get_permalink($post_id).($comment_id > 0?"#comment-".$comment_id:""));
					$title = urlencode($comment_id > 0?wp_html_excerpt(strip_shortcodes(get_comment_text($comment_id)),160):get_the_title($post_id));
				}
			}
			if (isset($post_id) && "post" == get_post_type($post_id)) {
				$window_title = esc_html__("Share This Article","wpqa");
			}else {
				$window_title = esc_html__("Share","wpqa");
			}?>
			<div class="post-share">
				<span><i class="icon-share"></i><span><?php echo ($window_title)?></span></span>
				<ul>
					<?php foreach ($post_share as $key => $value) {
						if ($share_facebook == "share_facebook" && isset($value["value"]) && $value["value"] == "share_facebook") {?>
							<li class="share-facebook"><a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php echo ($url)?>&amp;t=<?php echo ($title);?>"><i class="icon-facebook"></i><?php echo ($share_style == "style_2"?esc_html__("Share on","wpqa")." ":"")."<span>".esc_html__("Facebook","wpqa")."</span>"?></a></li>
						<?php }else if ($share_twitter == "share_twitter" && isset($value["value"]) && $value["value"] == "share_twitter") {?>
							<li class="share-twitter"><a target="_blank" href="http://twitter.com/share?text=<?php echo ($title);?>&amp;url=<?php echo ($url);?>"><i class="icon-twitter"></i><?php echo ($share_style == "style_2"?esc_html__("Share on","wpqa")." ".esc_html__("Twitter","wpqa"):"")?></a></li>
						<?php }else if ($share_linkedin == "share_linkedin" && isset($value["value"]) && $value["value"] == "share_linkedin") {?>
							<li class="share-linkedin"><a target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo ($url);?>&amp;title=<?php echo ($title);?>"><i class="icon-linkedin"></i><?php echo ($share_style == "style_2"?esc_html__("Share on","wpqa")." ".esc_html__("LinkedIn","wpqa"):"")?></a></li>
						<?php }else if ($share_whatsapp == "share_whatsapp" && isset($value["value"]) && $value["value"] == "share_whatsapp") {?>
							<li class="share-whatsapp"><a target="_blank" href="https://api.whatsapp.com/send?text=<?php echo ($title)?> - <?php echo ($url);?>"><i class="fab fa-whatsapp"></i><?php echo ($share_style == "style_2"?esc_html__("Share on","wpqa")." ".esc_html__("WhatsApp","wpqa"):"")?></a></li>
						<?php }
					}
					if ($send_email == "send_email") {?>
						<li class="share-email"><a target="_blank" title="<?php esc_html_e("Send an email","wpqa")?>" href="mailto:?subject=<?php echo ($title)?>&amp;body=<?php echo ($url);?>"><i class="icon-mail"></i></a></li>
					<?php }?>
				</ul>
			</div><!-- End post-share -->
		<?php }
	}
endif;
/* Return video iframe */
function wpqa_video_iframe($video_type,$video_id) {
	if ($video_type == 'youtube') {
		preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/",$video_id,$matches);
		if (isset($matches[1])) {
			$video_id = $matches[1];
		}
		$type = "https://www.youtube.com/embed/".$video_id;
	}else if ($video_type == 'vimeo') {
		preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/",$video_id,$matches);
		if (isset($matches[3])) {
			$video_id = $matches[3];
		}
		$type = "https://player.vimeo.com/video/".$video_id;
	}else if ($video_type == 'daily') {
		preg_match("!^.+dailymotion\.com/(video|hub)/([^_]+)[^#]*(#video=([^_&]+))?|(dai\.ly/([^_]+))!",$video_id,$matches);
		if (isset($matches[2])) {
			$video_id = $matches[2];
		}
		$type = "https://www.dailymotion.com/embed/video/".$video_id;
	}else if ($video_type == 'facebook') {
		$type = "https://www.facebook.com/video/embed?video_id=".$video_id;
	}
	return (isset($type)?$type:"");
}
/* Get image for share */
if (!function_exists('wpqa_image_for_share')) :
	function wpqa_image_for_share() {
		global $post;
		$post_thumb = wpqa_get_the_image($post->ID,apply_filters("wpqa_image_share_size","large"));
		if (!empty($post_thumb)) {
			$post_thumb = $post_thumb;
		}else {
			$fb_share_image = wpqa_image_url_id(wpqa_options("fb_share_image"));
			if ($fb_share_image != "") {
				$post_thumb = $fb_share_image;
			}
		}
		return $post_thumb;
	}
endif;
/* Get the image */
if (!function_exists('wpqa_get_the_image')) :
	function wpqa_get_the_image($post_id,$imge_size = "large",$aq_resize = "") {
		$what_post = get_post_meta($post_id,'what_post',true);
		$post_thumb = "";
		if ($what_post == "video") {
			$protocol = is_ssl() ? 'https' : 'http';
			$video_id = get_post_meta($post_id,prefix_meta.'video_post_id',true);
			$video_type = get_post_meta($post_id,prefix_meta.'video_post_type',true);
			if (!empty($video_id)) {
				if ($video_type == 'youtube') {
					$post_thumb = $protocol.'://img.youtube.com/vi/'.$video_id.'/0.jpg';
				}else if ($video_type == 'vimeo') {
					$url = $protocol.'://vimeo.com/api/v2/video/'.$video_id.'.php';
					$data = wp_remote_get($url);
					if (!is_wp_error($data)) {
						$thumb = @unserialize(trim($data['body']));
						$post_thumb = $thumb[0]['thumbnail_large'];
					}
				}else if ($video_type == 'daily') {
					$post_thumb = $protocol.'://www.dailymotion.com/thumbnail/video/'.$video_id;
				}else if ($video_type == 'facebook') {
					$post_thumb = $protocol.'://graph.facebook.com/'.$video_id.'/picture';
				}
			}else if (has_post_thumbnail($post_id)) {
				$post_thumb = wpqa_get_aq_resize_img_url(600,315,"on",get_post_thumbnail_id($post_id));
			}else {
				$wpqa_image = wpqa_image();
				if ($wpqa_image != "") {
					$post_thumb = wpqa_get_aq_resize_url($wpqa_image,600,315);
				}
			}
		}else if ($what_post == "slideshow") {
			$slideshow_type = get_post_meta($post_id,prefix_meta.'slideshow_type',true);
			if ($slideshow_type == "custom_slide") {
				$slideshow_post = get_post_meta($post_id,prefix_meta.'slideshow_post',true);
				if (isset($slideshow_post[1]['image_url']['id'])) {
					$post_thumb = wpqa_get_aq_resize_img_url(600,315,"on",$slideshow_post[1]['image_url']['id']);
				}
			}else if ($slideshow_type == "upload_images") {
				$upload_images = get_post_meta($post_id,prefix_meta.'upload_images',true);
				if (isset($upload_images[1])) {
					$post_thumb = wpqa_get_aq_resize_img_url(600,315,"on",$upload_images[1]);
				}
			}
		}else {
			if (has_post_thumbnail($post_id)) {
				$post_thumb = wpqa_get_aq_resize_img_url(600,315,"on",get_post_thumbnail_id($post_id));
			}else {
				$wpqa_image = wpqa_image();
				if ($wpqa_image != "") {
					$post_thumb = wpqa_get_aq_resize_url($wpqa_image,600,315);
				}
			}
		}
		return (isset($post_thumb)?$post_thumb:"");
	}
endif;
/* hex2rgb */
if (!function_exists('wpqa_hex2rgb')) :
	function wpqa_hex2rgb ($hex) {
	   $hex = str_replace("#","",$hex);
	   if (strlen($hex) == 3) {
	      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
	      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
	      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   }else {
	      $r = hexdec(substr($hex,0,2));
	      $g = hexdec(substr($hex,2,2));
	      $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   return $rgb;
	}
endif;
/* Mention */
add_filter("the_content","wpqa_mention");
add_filter("comment_text","wpqa_mention");
if (!function_exists('wpqa_mention')) :
	function wpqa_mention($content) {
		$active_mention = wpqa_options("active_mention");
		if ($active_mention == "on") {
			if (preg_match_all('/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i',$content, $matches_email)) {
				if (isset($matches[0])) {}
			}else if (preg_match_all('/@[\w\s+]+/',$content,$matches)) {
				if (isset($matches[0])) {
					foreach ($matches[0] as $key => $match) {
						$first_match = str_ireplace("@","",$match);
						$middle_match = preg_split("/[\s,]+/",$first_match);
						$match = trim((isset($middle_match[0]) && $middle_match[0] !== ""?$middle_match[0]:"")." ".(isset($middle_match[1]) && $middle_match[1] !== ""?$middle_match[1]:""));
						$last_match = trim(preg_replace('/\s+/', ' ', $match));
						if ($last_match != "") {
							$first_name = (isset($middle_match[0]) && $middle_match[0] !== ""?$middle_match[0]:$last_match);
							add_action('pre_user_query','wpqa_custom_search_mention');
							$args = array(
								'meta_query' => array('relation' => 'OR',array("key" => "first_name","value" => $first_name,"compare" => "RLIKE")),
								'orderby'    => "user_registered",
								'order'      => "ASC",
								'search'     => '*'.$last_match.'*',
								'number'     => 1,
								'fields'     => 'ID',
							);
							$query = new WP_User_Query($args);
							$query = $query->get_results();
							if (isset($query[0])) {
								$user_id = $query[0];
								$content = str_ireplace('@'.$last_match,'<a target="_blank" href="'.wpqa_profile_url($user_id).'">'.get_the_author_meta('display_name',$user_id).'</a>',$content);
							}else {
								$args = array(
									'meta_query' => array('relation' => 'OR',array("key" => "first_name","value" => $first_name,"compare" => "RLIKE")),
									'orderby'    => "user_registered",
									'order'      => "ASC",
									'search'     => '*'.$first_name.'*',
									'number'     => 1,
									'fields'     => 'ID',
								);
								$query = new WP_User_Query($args);
								$query = $query->get_results();
								if (isset($query[0])) {
									$user_id = $query[0];
									$content = str_ireplace('@'.$first_name,'<a target="_blank" href="'.wpqa_profile_url($user_id).'">'.get_the_author_meta('display_name',$user_id).'</a>',$content);
								}
							}
							remove_action('pre_user_query','wpqa_custom_search_mention');
						}
					}
				}
			}
		}
		return $content;
	}
endif;
/* Custom search for mention */
if (!function_exists('wpqa_custom_search_mention')) :
	function wpqa_custom_search_mention($user_query) {
		global $wpdb;
		$search_value = $user_query->query_vars;
		if (is_array($search_value) && isset($search_value['search'])) {
			$search_value = str_replace("*","",$search_value['search']);
		}
		$search_value = apply_filters("wpqa_search_value_filter",$search_value);
		$user_query->query_where .= " 
		OR ($wpdb->users.ID LIKE '".$search_value."' OR $wpdb->users.ID RLIKE '".$search_value."') 
		OR ($wpdb->users.display_name LIKE '".$search_value."' OR $wpdb->users.display_name RLIKE '".$search_value."') 
		OR ($wpdb->users.user_login LIKE '".$search_value."' OR $wpdb->users.user_login RLIKE '".$search_value."') 
		OR ($wpdb->users.user_nicename LIKE '".$search_value."' OR $wpdb->users.user_nicename RLIKE '".$search_value."') 
		OR ($wpdb->usermeta.meta_key = 'nickname' AND ($wpdb->usermeta.meta_value LIKE '".$search_value."' OR $wpdb->usermeta.meta_value RLIKE '".$search_value."')) 
		OR ($wpdb->usermeta.meta_key = 'first_name' AND ($wpdb->usermeta.meta_value LIKE '".$search_value."' OR $wpdb->usermeta.meta_value RLIKE '".$search_value."'))
		OR ($wpdb->usermeta.meta_key = 'last_name' AND ($wpdb->usermeta.meta_value LIKE '".$search_value."' OR $wpdb->usermeta.meta_value RLIKE '".$search_value."')) ";
	}
endif;
/* Random token */
function wpqa_token($length){
	$token = "";
	$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
	$codeAlphabet.= "0123456789";
	$max = strlen($codeAlphabet);
	for ($i=0; $i < $length; $i++) {
		$token .= $codeAlphabet[random_int(0, $max-1)];
	}
	return $token;
}
/* Remove buttons from the editor */
add_filter("mce_buttons","wpqa_mce_buttons");
function wpqa_mce_buttons($return) {
	if (!is_admin()) {
		$return = array('formatselect','bold','italic','bullist','numlist','blockquote','alignleft','aligncenter','alignright','link','unlink','spellchecker','wp_adv');
	}
	return $return;
}
/* Update the options */
add_action(wpqa_theme_name."_update_options","wpqa_update_options");
function wpqa_update_options($posted) {
	$options = $posted[wpqa_options];
	/* Roles */
	global $wp_roles;
	if (isset($options["roles"])) {$k = 0;
		foreach ($options["roles"] as $value_roles) {$k++;
			$is_group = get_role($value_roles["id"]);
			if (isset($value_roles["new"]) && $value_roles["new"] == "new") {
				if (!isset($is_group)) {
					$is_group = add_role($value_roles["id"],ucfirst($value_roles["group"]),array('read' => false));
					$is_group->add_cap('new');
				}
			}
			if (isset($is_group)) {
				$roles_array = array("ask_question","ask_question_payment","show_question","add_answer","add_answer_payment","show_answer","add_group","add_post","add_post_payment","add_category","send_message","upload_files","approve_question","approve_group","approve_answer","approve_post","approve_comment","approve_question_media","approve_answer_media","without_ads");
				if (isset($roles_array) && !empty($roles_array)) {
					foreach ($roles_array as $roles_key) {
						if (isset($value_roles[$roles_key]) && $value_roles[$roles_key] == "on") {
							$is_group->add_cap($roles_key);
						}else {
							$is_group->remove_cap($roles_key);
						}
					}
				}
			}
		}
	}
	if (isset($options["schedules_time_hour"])) {
		$schedules_time_hour = get_option("schedules_time_hour");
		if ($options["schedules_time_hour"] != $schedules_time_hour) {
			update_option("schedules_time_hour",$options["schedules_time_hour"]);
			delete_option(wpqa_theme_name."_schedules_time");
		}
	}
	if (isset($options["schedules_time_day"])) {
		$schedules_time_day = get_option("schedules_time_day");
		if ($options["schedules_time_day"] != $schedules_time_day) {
			update_option("schedules_time_day",$options["schedules_time_day"]);
			delete_option(wpqa_theme_name."_schedules_time");
		}
	}
	if (isset($options["activate_currencies"])) {
		$activate_currencies = get_option("activate_currencies");
		if ($options["activate_currencies"] != $activate_currencies) {
			update_option("activate_currencies",$options["activate_currencies"]);
			echo 2;
		}
	}
	$pay_ask               = (isset($options['pay_ask'])?$options['pay_ask']:0);
	$payment_type_ask      = (isset($options['payment_type_ask'])?$options['payment_type_ask']:0);
	$pay_to_sticky         = (isset($options['pay_to_sticky'])?$options['pay_to_sticky']:0);
	$payment_type_sticky   = (isset($options['payment_type_sticky'])?$options['payment_type_sticky']:0);
	$subscriptions_payment = (isset($options['subscriptions_payment'])?$options['subscriptions_payment']:0);
	$buy_points_payment    = (isset($options['buy_points_payment'])?$options['buy_points_payment']:0);
	$pay_answer            = (isset($options['pay_answer'])?$options['pay_answer']:0);
	$payment_type_answer   = (isset($options['payment_type_answer'])?$options['payment_type_answer']:0);
	$currency_code         = (isset($options['currency_code'])?$options['currency_code']:"USD");
	$pay_to_anything       = apply_filters("wpqa_filter_pay_to_anything",false);
	if (($pay_ask == "on" && $payment_type_ask != "points") || ($pay_to_sticky == "on" && $payment_type_sticky != "points") || $subscriptions_payment == "on" || $buy_points_payment == "on" || ($pay_answer == "on" && $payment_type_answer != "points") || $pay_to_anything == true) {
		$payment_methods = (isset($options['payment_methodes'])?$options['payment_methodes']:array());
		if (isset($payment_methods["stripe"]["value"]) && $payment_methods["stripe"]["value"] == "stripe" && $options["secret_key"] != "") {
			$array = array(
				"monthly"  => array("key" => "monthly","name" => esc_html__("Monthly membership","wpqa")),
				"3months"  => array("key" => "3months","name" => esc_html__("Three months membership","wpqa")),
				"6months"  => array("key" => "6months","name" => esc_html__("Six Months membership","wpqa")),
				"yearly"   => array("key" => "yearly","name" => esc_html__("Yearly membership","wpqa")),
				"2years"   => array("key" => "2years","name" => esc_html__("Two Years membership","wpqa")),
				"lifetime" => array("key" => "lifetime","name" => esc_html__("Lifetime membership","wpqa")),
			);
			require_once plugin_dir_path(dirname(__FILE__)).'payments/stripe/init.php';
			\Stripe\Stripe::setApiKey($options["secret_key"]);
			if (isset($options["coupons"]) && is_array($options["coupons"]) && !empty($options["coupons"])) {
				foreach ($options["coupons"] as $key => $value) {
					$coupon_name = preg_replace('/[^a-zA-Z0-9._\-]/','',strtolower($value['coupon_name']));
					$coupon_amount = (int)$value['coupon_amount'];
					$coupon_type = $value['coupon_type'];
					$coupon_id = $coupon_amount.'_'.$coupon_name;
					try {
						$get_coupon = \Stripe\Coupon::retrieve($coupon_id);
					}catch ( \Stripe\Exception\CardException $e ) {
						$result_error_coupon = $e->getError()->message;
					}catch ( Exception $e ) {
						$result_error_coupon = $e->getMessage();
					}
					if (!isset($result_error_coupon) || (isset($result_error_coupon) && $result_error_coupon != "")) {
						if ($coupon_type == "percent") {
							$coupon_type = array('percent_off' => $coupon_amount);
						}else {
							$coupon_type = array('amount_off' => $coupon_amount);
						}
						$coupon_array = array(
							'duration' => 'once',//repeating
							'id'       => $coupon_id
						);
						try {
							$coupon = \Stripe\Coupon::create(array_merge($coupon_type,$coupon_array));
						}catch ( \Stripe\Exception\CardException $e ) {
							$result_error_coupon = $e->getError()->message;
						}catch ( Exception $e ) {
							$result_error_coupon = $e->getMessage();
						}
					}
				}
			}
		}
	}
}?>