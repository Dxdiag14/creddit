<?php
/*-----------------------------------------------------------------------------------*/
/* Add meta boxes */
/*-----------------------------------------------------------------------------------*/
add_action ('add_meta_boxes','discy_meta_boxes');
function discy_meta_boxes($post_type) {
	global $post;
	$allow_post_type = apply_filters("discy_allow_post_type",array('post','page','question','group'));
	if (in_array($post_type,$allow_post_type)) {
		add_meta_box('discy_meta_tabs',esc_html__('Page settings',"discy"),'discy_meta_tabs',$post_type,'normal','high');
	}
}
/*-----------------------------------------------------------------------------------*/
/* Page settings */
/*-----------------------------------------------------------------------------------*/
function discy_meta_tabs() {
	global $post;
	wp_nonce_field ('discy_builder_save_meta','discy_save_meta_nonce');
	$discy_admin_meta = discy_admin_meta();
	if (is_array($discy_admin_meta) && !empty($discy_admin_meta)) {?>
		<div id="discy-admin-wrap" class="discy-admin">
			<div class="discy-admin-header">
				<a href="<?php echo discy_theme_url_tf?>" target="_blank"><i class="dashicons-before dashicons-admin-tools"></i><?php echo discy_theme_name?></a>
				<div class="discy_social">
					<ul>
						<li class="discy_social_facebook"><a class="discy_social_f" href="https://www.facebook.com/2code.info" target="_blank"><i class="dashicons dashicons-facebook"></i></a></li>
						<li class="discy_social_twitter"><a class="discy_social_t" href="https://www.twitter.com/2codeThemes" target="_blank"><i class="dashicons dashicons-twitter"></i></a></li>
						<li class="discy_social_site"><a class="discy_social_e" href="https://2code.info/" target="_blank"><i class="dashicons dashicons-email-alt"></i></a></li>
						<li class="discy_social_docs"><a class="discy_social_s" href="https://2code.info/docs/discy/" target="_blank"><i class="dashicons dashicons-sos"></i></a></li>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
			<div class="discy-admin-content">
			    <h2 class="nav-tab-wrapper">
			        <?php echo discy_admin_fields_class::discy_admin_tabs("meta",$discy_admin_meta,$post->ID);?>
			    </h2>
			    <?php settings_errors( 'options-framework' ); ?>
			    <div id="discy-admin-metabox" class="metabox-holder">
				    <div id="discy-admin" class="discy_framework postbox">
				    	<?php discy_admin_fields_class::discy_admin_fields("meta",discy_meta,"meta",$post->ID,$discy_admin_meta);?>
					</div><!-- End container -->
				</div>
			</div>
			<div class="clear"></div>
		</div><!-- End wrap -->
	<?php }
}
/*-----------------------------------------------------------------------------------*/
/* Process save meta box */
/*-----------------------------------------------------------------------------------*/
add_action ('save_post','discy_meta_save',1,2);
function discy_meta_save ($post_id,$post) {
	if (!isset($_POST)) return $post_id;
	$allow_post_type = apply_filters("discy_allow_post_type",array('post','page','question','group'));
	if (!in_array($post->post_type,$allow_post_type)) return $post_id;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
	if (!isset($_POST['discy_save_meta_nonce']) || !wp_verify_nonce ($_POST['discy_save_meta_nonce'],'discy_builder_save_meta')) return $post_id;
	if (!current_user_can ('edit_post',$post_id)) return $post_id;
	
	do_action("wpqa_action_meta_save",$_POST,$post);
	
	$options = discy_admin_meta();
	foreach ($options as $value) {
		if (!isset($value['unset']) && $value['type'] != 'heading' && $value['type'] != "heading-2" && $value['type'] != "heading-3" && $value['type'] != 'info' && $value['type'] != 'content') {
			$val = "";
			
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
				if (isset($value['save']) && $value['save'] == "option") {
					delete_option($field_name);
				}else {
					delete_post_meta($post->ID,$field_name);
				}
			}else if (isset($_POST[$field_name]) || $value['type'] == "checkbox") {
				if ($value['id'] == "question_poll" && $val != "on") {
					update_post_meta($post->ID,'question_poll',2);
				}else {
					if (isset($_POST["private_question"]) && ($_POST["private_question"] == "on" || $_POST["private_question"] == 1)) {
						$anonymously_user = discy_post_meta("anonymously_user","",false);
						update_post_meta($post->ID,'private_question_author',($anonymously_user > 0?$anonymously_user:$post->post_author));
					}
					if (isset($value['save']) && $value['save'] == "option") {
						update_option($field_name,$val);
						if (isset($_POST["tabs_menu"])) {
							$wp_page_template = discy_post_meta("_wp_page_template",$post->ID,false);
							if ($wp_page_template == "template-home.php") {
								update_option("home_page_id",$post->ID);
							}
						}
					}else {
						update_post_meta($post->ID,$field_name,$val);
					}
				}
			}
		}
	}
	do_action("discy_action_after_meta_save",$_POST,$post);
}?>