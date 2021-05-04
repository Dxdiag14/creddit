<div class="no-results not-found">
	<?php $whats_post_type = (isset($its_question) && 'question' == $its_question?esc_html__('Questions','discy'):esc_html__('Posts','discy'));
	if (has_wpqa()) {
		$templates = array("search.php");
		$wpqa_get_template = wpqa_get_template($templates,(isset($folder) && $folder != ""?$folder."/":""));
	}
	if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
		<h3><?php printf(esc_html__('Sorry, No %1$s Found.','discy'),$whats_post_type)?></h3>
		<p><?php printf( esc_html__( 'Ready to publish your first post? %1$s Get started here %2$s.', 'discy' ), '<a href="'.esc_url( admin_url( 'post-new.php' ) ).'">', '</a>' ); ?></p>
	<?php elseif ( ( has_wpqa() && wpqa_is_search() ) || is_search() ) : ?>
		<h3><?php esc_html_e( 'Sorry, No Results Found.', 'discy' ); ?></h3>
		<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'discy' ); ?></p>
		<?php isset($wpqa_get_template) ? include $wpqa_get_template:"";?>
	<?php elseif ( is_tax() || is_post_type_archive() || is_archive() || isset($not_fount_error) ) :?>
		<h3><?php printf(esc_html__('Sorry, No %1$s Found.','discy'),$whats_post_type)?></h3>
		<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'discy' ); ?></p>
		<?php isset($wpqa_get_template) ? include $wpqa_get_template:"";
	elseif (is_404()) : ?>
		<h2><?php esc_html_e( '404', 'discy' ); ?></h2>
		<h3><?php esc_html_e( 'Oops! Page Not Found.', 'discy' ); ?></h3>
		<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'discy' ); ?></p>
		<?php isset($wpqa_get_template) ? include $wpqa_get_template:"";?>
	<?php else : ?>
		<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'discy' ); ?></p>
		<?php isset($wpqa_get_template) ? include $wpqa_get_template:"";?>
	<?php endif; ?>
</div><!-- no-results -->