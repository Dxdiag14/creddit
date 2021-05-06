<form action="<?php echo esc_url(site_url( '/wp-comments-post.php' ))?>" method="post" enctype="multipart/form-data">
	<div>
		<div class="form-input form-textarea form-comment-normal">
			<textarea name="comment" aria-required="true" placeholder="<?php echo apply_filters("discy_filter_textarea_comment_group",esc_html__("Comment","discy"))?>"></textarea>
		</div>
	</div>
	<div class="cancel-comment-reply"><?php cancel_comment_reply_link(esc_html__("Click here to cancel reply.","discy"));?></div>
	<p class="form-submit">
		<span class="load_span"><span class="loader_2"></span></span>
		<input name="submit" type="submit" value="<?php esc_html_e('Comment','discy')?>" class="button-default button-hide-click">
	</p>
	<?php comment_id_fields($post_data->ID);
	do_action('comment_form', $post_data->ID);?>
</form>