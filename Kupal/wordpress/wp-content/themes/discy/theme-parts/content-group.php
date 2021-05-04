<?php if (isset($post_data)) {
	$post_data = $post_data;
}else if (isset($GLOBALS['post'])) {
	$post_data = $post = $GLOBALS['post'];
}else {
	$post_data = $post;
}
$post_author = $post_data->post_author;
$post_id = $post_data->ID;
$is_sticky = is_sticky($post_id);
$edit_delete_posts_comments = discy_options("edit_delete_posts_comments");?>
<div class="content_group_item">
	<?php if ($is_sticky) {?>
		<div class="question-sticky-ribbon posts-sticky-ribbon"><div><?php esc_html_e("Pinned","discy")?></div></div>
	<?php }?>
	<div class="content_group_item_header">
		<div class="group_avatar"><?php do_action("wpqa_action_avatar_link",array("user_id" => $post_author,"size" => 50,"span" => "span","pop" => "pop"));?></div>
		<div class="col12">
			<div class="header-info">
				<div class="title">
					<h3>
						<a href="<?php echo wpqa_profile_url($post_author);?>" title=""><?php echo get_the_author_meta("display_name",$post_author);?></a>
						<?php do_action("wpqa_verified_user",$post_author);
						do_action("wpqa_get_badge",$post_author,"","","category_points");?>
					</h3>
					<div class="posts-action">
						<a href="<?php echo wpqa_custom_permalink($post_id,"view_posts_group","view_group_post")?>"><i class="icon-lifebuoy"></i><?php echo human_time_diff(get_the_time('U',$post_id),current_time('timestamp'))." ".esc_html__("ago","discy");?></a>
						<?php $posts_delete = discy_options("posts_delete");
						if (($posts_delete == "on" && $post_author == $user_id) || (isset($edit_delete_posts_comments["delete"]) && $edit_delete_posts_comments["delete"] == "delete" && isset($group_moderators) && is_array($group_moderators) && in_array($user_id,$group_moderators)) || $is_super_admin) {?>
							<a class="posts-delete" href="<?php echo esc_url_raw(add_query_arg(array("delete" => $post_id,"wpqa_delete_nonce" => wp_create_nonce("wpqa_delete_nonce")),wpqa_custom_permalink($post_id,"view_posts_group","view_group_post")))?>"><i class="icon-trash"></i><?php esc_html_e("Delete","discy")?></a>
						<?php }?>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<?php the_content();
	$posts_like = get_post_meta($post_id,"posts_like",true);
	$post_like_all = (is_array($posts_like)?count($posts_like):0);?>
	<footer class="question-footer posts-footer">
		<?php if ($post_data->post_status == 'publish') {?>
			<ul class="footer-meta">
				<li class="posts-likes">
					<div class="small_loader loader_2"></div>
					<?php if (is_user_logged_in()) {
						if (is_array($posts_like) && in_array($user_id,$posts_like)) {
							$class = "unlike-posts";
							$title = esc_html__("Unlike","discy");
						}else {
							$class = "like-posts";
							$title = esc_html__("Like","discy");
						}?>
						<a href="#" class="<?php echo esc_attr($class)?> tooltip-n" data-id="<?php echo (int)$post_id?>" original-title="<?php echo esc_attr($title)?>">
					<?php }?>
					<i class="icon-heart"></i>
					<span><?php echo discy_count_number($post_like_all)?></span>
					<?php echo sprintf(_n("Like","Likes",$post_like_all,"discy"),$post_like_all);
					if (is_user_logged_in()) {?>
						</a>
					<?php }?>
				</li>
				<?php $count_post_all = (int)wpqa_count_comments($post_id);?>
				<li class="posts-comments"><a href="<?php echo wpqa_custom_permalink($post_id,"view_posts_group","view_group_post")?>#group-comments"><i class="icon-comment"></i><span class="question-span"><?php echo sprintf(_n("%s Comment","%s Comments",$count_post_all,"discy"),$count_post_all)?></span></a></li>
			</ul>
			<?php }
		$group_id = get_post_meta($post_id,"group_id",true);
		$group_comments = get_post_meta($group_id,"group_comments",true);
		$group_moderators = get_post_meta($group_id,"group_moderators",true);
		if (is_user_logged_in() && ($is_super_admin || $post_author == $user_id || (isset($edit_delete_posts_comments["edit"]) && $edit_delete_posts_comments["edit"] == "edit" && is_array($group_moderators) && in_array($user_id,$group_moderators)))) {?>
			<a class="button-default edit-group-posts" href="<?php echo wpqa_custom_permalink($post_id,"edit_posts_group","edit_group_post")?>"><i class="icon-pencil"></i></a>
		<?php }
		if ($post_data->post_status == 'publish') {
			if ($post_data->post_status == 'publish' && (is_user_logged_in() && $group_comments == "on" || (isset($group_moderators) && is_array($group_moderators) && in_array($user_id,$group_moderators)) || $is_super_admin)) {?>
				<a class="meta-answer meta-comment-a meta-group-comments" href="<?php echo wpqa_custom_permalink($post_id,"view_posts_group","view_group_post")?>#respond"><?php esc_html_e("Comment","discy")?></a>
			<?php }
		}else {
			$group_users_array = get_post_meta($group_id,"group_users_array",true);
			echo '<div class="group_review_button">
				<div class="cover_loader discy_hide"><div class="small_loader loader_2"></div></div>';
				echo '<a href="#" class="button-default agree_posts_group" data-group="'.$post_id.'" data-user="'.$post_author.'">'.esc_html__("Agree","discy").'</a>';
				if (isset($group_users_array) && is_array($group_users_array) && in_array($post_author,$group_users_array)) {
					$blocked_users = get_post_meta($group_id,"blocked_users_array",true);
					if (isset($blocked_users) && is_array($blocked_users) && in_array($post_author,$blocked_users)) {
						echo '<a href="#" class="button-default unblock_user_group" data-group="'.$group_id.'" data-user="'.$post_author.'">'.esc_html__("Unblock","discy").'</a>';
					}else {
						echo '<a href="#" class="button-default remove_user_group" data-group="'.$group_id.'" data-user="'.$post_author.'">'.esc_html__("Remove","discy").'</a>
						<a href="#" class="button-default block_user_group" data-group="'.$group_id.'" data-user="'.$post_author.'">'.esc_html__("Block","discy").'</a>';
					}
				}
			echo '</div>';
		}?>
	</footer>
	<div class="embed_comments">
		<div class="clearfix"></div>
		<?php if (is_user_logged_in() && $group_comments == "on" || (isset($group_moderators) && is_array($group_moderators) && in_array($user_id,$group_moderators)) || $is_super_admin) {?>
			<!-- Write-comment -->
			<div class="write_comment discy_hide">
				<?php include locate_template("theme-parts/comment-group-form.php");?>
			</div>
		<?php }
		$show_number = 2;
		$number = (!wpqa_is_view_posts_group()?array('number' => $show_number):array());
		$paged = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
		$comments_per_page = get_option("comments_per_page");
		$offset = ($paged -1) * $comments_per_page;
		$offset = (wpqa_is_view_posts_group()?array('offset' => $offset,'number' => $comments_per_page):array());
		$args = array('post_id' => $post_id,'status' => 'approve','orderby' => 'comment_date','order' => 'DESC');
		$comments_args = get_comments(array_merge($number,$offset,$args));
		if (is_array($comments_args) && !empty($comments_args)) {
			if (!wpqa_is_view_posts_group() && $count_post_all > $show_number) {
				echo '<a class="button-default load-more-comments" href="'.wpqa_custom_permalink($post_id,"view_posts_group","view_group_post").'#group-comments">'.sprintf(_n("View %s more comment","View %s more comments",($count_post_all-$show_number),"discy"),($count_post_all-$show_number)).'</a>';
			}?>
			<ol<?php echo (wpqa_is_view_posts_group()?' id="group-comments"':"")?> class="commentlist clearfix">
				<?php wp_list_comments(array("callback" => "discy_comment","comment_type" => "comment_group"),$comments_args);?>
				</li>
			</ol>
			<?php $max_page = ceil($count_post_all/$comments_per_page);
			if (wpqa_is_view_posts_group() && $max_page > 1 && get_option('page_comments')) {?>
				<div class="clearfix"></div>
				<div class="pagination comments-pagination">
					<?php global $wp_rewrite;
					$args = array(
						'base'         => add_query_arg('page','%#%'),
						'format'       => '',
						'total'        => $max_page,
						'current'      => $paged,
						'echo'         => true,
						'type'         => 'plain',
						'add_fragment' => '#group-comments',
						'prev_text'    => '<i class="icon-left-open"></i>',
						'next_text'    => '<i class="icon-right-open"></i>'
					);
					if ($wp_rewrite->using_permalinks()) {
						$args['base'] = user_trailingslashit(trailingslashit(wpqa_custom_permalink($post_id,"view_posts_group","view_group_post")).'page/%#%/','page');
					}
					echo paginate_links($args);?>
				</div><!-- End comments-pagination -->
				<div class="clearfix"></div>
		    <?php }
		}?>
	</div>
</div>