<div class="no-results">
	<h3><?php esc_html_e( 'Sorry, No Results Found.', 'discy' ); ?></h3>
	<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'discy' ); ?></p>
	<?php $back_to_home = apply_filters('wpqa_back_to_home',true);
	if ($back_to_home == true) {?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button-default"><?php esc_html_e( 'Back To Homepage', 'discy' ); ?></a>
	<?php }?>
</div><!-- no-results -->