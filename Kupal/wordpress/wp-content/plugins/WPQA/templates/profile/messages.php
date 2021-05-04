<?php

/* @author    2codeThemes
*  @package   WPQA/templates/profile
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit; // Exit if accessed directly
}

$rows_per_page = get_option("posts_per_page");
$user_id = get_current_user_id();
$count_new_message = wpqa_count_new_messages($user_id);?>
<div id='section-<?php echo wpqa_user_title()?>' class="section-page-div">
	<div class="answers-tabs">
		<h3 class="section-title"><?php esc_html_e("Messages","wpqa")?></h3>
		<div class="answers-tabs-inner">
			<ul>
				<li<?php echo (empty($_GET["show"]) || (isset($_GET["show"]) && $_GET["show"] != "send")?' class="active-tab"':'')?>><a href="<?php echo esc_url_raw(wpqa_get_profile_permalink($user_id,"messages"))?>"><?php esc_html_e("inbox","wpqa")?> <?php echo($count_new_message > 0?"<span>( ".$count_new_message." )</span>":"")?></a></li>
				<li<?php echo (isset($_GET["show"]) && $_GET["show"] == "send"?' class="active-tab"':'')?>><a href="<?php echo esc_url_raw(add_query_arg("show","send"),wpqa_get_profile_permalink($user_id,"messages"))?>"><?php esc_html_e("Sent","wpqa")?></a></li>
			</ul>
		</div><!-- End answers-tabs-inner -->
		<div class="clearfix"></div>
	</div>
	
	<?php $time_format = wpqa_options("time_format");
	$time_format = ($time_format?$time_format:get_option("time_format"));
	$date_format = wpqa_options("date_format");
	$date_format = ($date_format?$date_format:get_option("date_format"));
	$paged = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
	if (isset($_GET["show"]) && $_GET["show"] == "send") {
		$attrs = array("author" => $user_id,"meta_query" => array(array("key" => "delete_send_message","compare" => "NOT EXISTS"),array("key" => "message_user_array","compare" => "NOT EXISTS")));
	}else {
		$attrs = array("meta_query" => array('relation' => 'AND',array("key" => "delete_inbox_message","compare" => "NOT EXISTS"),array('relation' => 'OR',array("key" => "message_user_id","compare" => "=","value" => $user_id),array("key" => "message_user_".$user_id,"compare" => "EXISTS"))));
	}
	$args = array_merge(array('post_type' => 'message','posts_per_page' => $rows_per_page,'paged' => $paged),$attrs);
	$messages_query = new WP_Query( $args );
	if ($messages_query->have_posts()) {
		echo "<ol class='commentlist clearfix".($messages_query->max_num_pages > 1?" section-message-paged":"")."'>";
			while ( $messages_query->have_posts() ) { $messages_query->the_post();
				$message_post = $messages_query->post;
				$message_user_id = get_post_meta($message_post->ID,'message_user_id',true);
				$message_user_array = get_post_meta($message_post->ID,'message_user_array',true);
				$message_delete = wpqa_options("message_delete");
				if (isset($_GET['wpqa_delete_nonce']) && wp_verify_nonce($_GET['wpqa_delete_nonce'],'wpqa_delete_nonce') && ($message_delete == 1 || $message_delete == "on" || is_super_admin($user_id)) && isset($_GET) && isset($_GET["delete_message"]) && $_GET["delete_message"] == $message_post->ID) {
					if (($message_post->post_author > 0 && $message_post->post_author == $user_id) || $message_user_id == $user_id || (is_array($message_user_array) && !empty($message_user_array) && in_array($user_id,$message_user_array))) {
						wpqa_notifications_activities($user_id,"","","","",($message_user_id == $user_id || (is_array($message_user_array) && !empty($message_user_array) && in_array($user_id,$message_user_array))?"delete_inbox_message":"delete_send_message"),"activities","","message");
						if (is_array($message_user_array) && !empty($message_user_array) && in_array($user_id,$message_user_array)) {
							$remove_message_user_array = wpqa_remove_item_by_value($message_user_array,$user_id);
							update_post_meta($message_post->ID,"message_user_array",$remove_message_user_array);
							delete_post_meta($message_post->ID,"message_user_".$user_id);
						}
						if ($message_post->post_author == $user_id || ($message_user_id == $user_id || (is_array($message_user_array) && !empty($message_user_array) && in_array($user_id,$message_user_array)))) {
							if ($message_post->post_author == $user_id) {
								update_post_meta($message_post->ID,"delete_send_message",1);
							}else {
								update_post_meta($message_post->ID,"delete_inbox_message",1);
							}
						}
						wpqa_session('<div class="alert-message success"><i class="icon-check"></i><p>'.esc_html__("Deleted successfully.","wpqa").'</p></div>','wpqa_session');
						$protocol = is_ssl() ? 'https' : 'http';
						$redirect_to = wp_unslash($protocol.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
						$redirect_to = (isset($_GET["page"]) && esc_attr($_GET["page"]) != ""?esc_attr($_GET["page"]):$redirect_to);
						wp_redirect((isset($_GET["show"]) && $_GET["show"] == "send"?esc_url_raw(add_query_arg("show","send"),wpqa_get_profile_permalink($user_id,"messages")):esc_url_raw(wpqa_get_profile_permalink($user_id,"messages"))));
						exit;
					}
				}?>
				<li class="comment">
					<div class="comment-body clearfix">
						<div class="comment-text">
							<?php do_action("wpqa_action_avatar_link",array("user_id" => (isset($_GET["show"]) && $_GET["show"] == "send"?$message_user_id:$message_post->post_author),"size" => 42,"span" => "span","pop" => "pop"));?>
							<div class="author clearfix">
								<div class="comment-meta">
									<div class="comment-author">
										<?php if (isset($_GET["show"]) && $_GET["show"] == "send") {
											$display_name = get_the_author_meta('display_name',$message_user_id);
											echo '<a href="'.get_author_posts_url($message_user_id).'">'.$display_name.'</a>';
										}else {
											$display_name = get_the_author_meta('display_name',$message_post->post_author);
											if ($message_post->post_author > 0) {
												echo '<a href="'.get_author_posts_url($message_post->post_author).'">'.$display_name.'</a>';
											}else {
												echo get_post_meta($message_post->ID,'message_username',true);
											}
										}?>
									</div>
									<span class="comment-date"><?php esc_html_e("Added a message on","wpqa")?> <?php echo sprintf(esc_html__('%1$s at %2$s','wpqa'),get_the_time($date_format),get_the_time($time_format));?></span>
								</div>
							</div>
							<div class="text">
								<h2 class="post-title message-title">
									<?php if (empty($_GET["show"]) || (isset($_GET["show"]) && $_GET["show"] != "send")) {
										if (is_array($message_user_array) && !empty($message_user_array) && in_array($user_id,$message_user_array)) {
											$message_not_new = get_post_meta($message_post->ID,'message_not_new_'.$user_id,true);
										}
										$message_not_new = (isset($message_not_new) && $message_not_new != "" && $message_not_new != "no"?$message_not_new:"no");
										$message_new = get_post_meta($message_post->ID,'message_new',true);?>
										<i class="message_new<?php echo ($message_new == 1 || $message_new == "on" || (is_array($message_user_array) && !empty($message_user_array) && in_array($user_id,$message_user_array) && $message_not_new == "no")?" message-new":"")?> icon-mail"></i>
									<?php }
									echo "<a href='#' class='view-message tooltip-n' title='".esc_html__("View the message","wpqa")."' data-id='".$message_post->ID."'><i class='message-open-close icon-plus'></i>";the_title();echo "</a>"?>
									<div class="small_loader_message small_loader loader_2"></div>
								</h2>
								<?php do_action("wpqa_action_message_after_title",$message_post)?>
								<div class="message-content"></div>
								<?php if (($message_post->post_author > 0 && (empty($_GET["show"]) || (isset($_GET["show"]) && $_GET["show"] != "send"))) || ((($message_post->post_author > 0 && $message_post->post_author == $user_id) || $message_user_id == $user_id || (is_array($message_user_array) && !empty($message_user_array) && in_array($user_id,$message_user_array))) && ($message_delete == 1 || $message_delete == "on" || is_super_admin($user_id)))) {?>
									<ul class="comment-reply">
										<?php if ($message_post->post_author > 0 && (empty($_GET["show"]) || (isset($_GET["show"]) && $_GET["show"] != "send"))) {
											$user_block_message = get_user_meta($message_post->post_author,"user_block_message",true);
											if (isset($user_block_message) && is_array($user_block_message) && in_array($user_id,$user_block_message)) {}else {?>
												<li class="message-reply"><a href="#" data-width="690" data-id="<?php echo esc_attr($message_post->ID)?>" data-user-id="<?php echo esc_attr($message_post->post_author)?>"><i class="icon-reply"></i><?php esc_html_e("Reply","wpqa");?></a></li>
											<?php }
										}
										if ((($message_post->post_author > 0 && $message_post->post_author == $user_id) || $message_user_id == $user_id || (is_array($message_user_array) && !empty($message_user_array) && in_array($user_id,$message_user_array))) && ($message_delete == 1 || $message_delete == "on" || is_super_admin($user_id))) {?>
											<li class="message-delete"><a href="<?php echo (empty($_GET["show"]) || (isset($_GET["show"]) && $_GET["show"] != "send")?esc_url_raw(add_query_arg(array("delete_message" => $message_post->ID,"wpqa_delete_nonce" => wp_create_nonce("wpqa_delete_nonce")),wpqa_get_profile_permalink($user_id,"messages"))):esc_url_raw(add_query_arg(array("delete_message" => $message_post->ID,"wpqa_delete_nonce" => wp_create_nonce("wpqa_delete_nonce")),add_query_arg("show","send"),wpqa_get_profile_permalink($user_id,"messages"))))?>" data-id="<?php echo esc_attr($message_post->ID)?>"><i class="icon-trash"></i><?php esc_html_e("Delete","wpqa");?></a></li>
										<?php }
										if ($message_post->post_author > 0 && (empty($_GET["show"]) || (isset($_GET["show"]) && $_GET["show"] != "send"))) {
											$user_block_message = get_user_meta($user_id,"user_block_message",true);?>
											<li class="message-block"><i class="icon-cancel"></i>
												<?php echo '<a href="#" class="block_message block_message_'.$message_post->post_author.($message_user_id != $message_post->post_author && $message_post->post_author > 0 && isset($user_block_message) && is_array($user_block_message) && in_array($message_post->post_author,$user_block_message)?' unblock_message':'').'" data-id="'.(int)$message_post->post_author.'" data-nonce="'.wp_create_nonce("block_message_nonce").'">'.($message_user_id != $message_post->post_author && $message_post->post_author > 0 && isset($user_block_message) && is_array($user_block_message) && in_array($message_post->post_author,$user_block_message)?esc_html__("Unblock Message","wpqa"):esc_html__("Block Message","wpqa")).'</a>';?>
											</li>
										<?php }?>
										<li class="clearfix"></li>
									</ul>
								<?php }?>
							</div>
						</div>
					</div>
				</li>
			<?php }
		echo "</ol>";
	}else {
		echo "<p class='no-item'>".esc_html__("There are no messages yet.","wpqa")."</p>";
	}

	if ($messages_query->max_num_pages > 1) :
		$current = max(1,$paged);
		$pagination_args = array(
			'total'     => $messages_query->max_num_pages,
			'current'   => $current,
			'show_all'  => false,
			'prev_text' => '<i class="icon-left-open"></i>',
			'next_text' => '<i class="icon-right-open"></i>',
		);
		if (!get_option('permalink_structure')) {
			$pagination_args['base'] = esc_url_raw(add_query_arg('paged','%#%'));
		}
		$paginate_links = paginate_links($pagination_args);?>
		<div class="main-pagination"><div class='comment-pagination pagination'><?php echo ($paginate_links != ""?$paginate_links:"")?></div></div>
	<?php endif;
	wp_reset_postdata();?>
</div>