<?php register_nav_menus(array(
	'discy_explore'       => 'Menu left - not logged',
	'discy_explore_login' => 'Menu left - logged',
	'header_menu'         => 'Header menu - not logged',
	'header_menu_login'   => 'Header menu - logged',
));
function discy_nav_fallback() {
	echo '<div class="menu-alert">'.esc_html__('You can use WP menu builder to build menus',"discy").'</div>';
}
function discy_empty_fallback() {
	echo '';
}
/* Custom menu fields */
function discy_custom_fields($item_id,$item) {
	wp_nonce_field('menu_meta_icon_nonce','_menu_meta_icon_nonce_name');
	$menu_meta_icon = get_post_meta($item_id,'_menu_meta_icon',true)?>
	<input type="hidden" name="menu-meta-icon-nonce" value="<?php echo wp_create_nonce('menu-meta-icon-name')?>">
	<div class="field-menu_meta_icon description-wide" style="margin: 5px 0;">
		<p class="field-menu-meta-icon description description-thin">
			<label for="menu-meta-icon-<?php echo esc_attr($item_id)?>">
				<?php esc_html_e("Icon class",'discy')?><br>
				<input type="text" id="menu-meta-icon-<?php echo esc_attr($item_id)?>" class="widefat code menu-meta-icon" name="menu_meta_icon[<?php echo esc_attr($item_id)?>]" value="<?php echo esc_attr($menu_meta_icon)?>">
			</label>
		</p>
	</div>
<?php }
add_action('wp_nav_menu_item_custom_fields','discy_custom_fields',10,2);
/* Save menu fields */
function discy_nav_update($menu_id,$menu_item_id) {
	if (!isset($_POST['_menu_meta_icon_nonce_name']) || !wp_verify_nonce($_POST['_menu_meta_icon_nonce_name'],'menu_meta_icon_nonce')) {
		return $menu_id;
	}
	if (isset($_POST['menu_meta_icon'][$menu_item_id])) {
		$menu_meta_icon = sanitize_text_field($_POST['menu_meta_icon'][$menu_item_id]);
		update_post_meta($menu_item_id,'_menu_meta_icon',$menu_meta_icon);
	}else {
		delete_post_meta($menu_item_id,'_menu_meta_icon');
	}
}
add_action('wp_update_nav_menu_item','discy_nav_update',10,2);
/* Show menu title */
function discy_custom_menu_title($title,$item_id) {
	if (isset($item_id)) {
		$menu_meta_icon = get_post_meta($item_id,'_menu_meta_icon',true);
		if (!empty($menu_meta_icon)) {
			$title = '<i class="'.$menu_meta_icon.'"></i>'.$title;
		}
	}
	return $title;
}
add_filter('the_title','discy_custom_menu_title',10,2);?>