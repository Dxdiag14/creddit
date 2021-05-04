<?php // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Don't load if discy_admin_init is already defined
if (is_admin() && ! function_exists( 'discy_admin_init' ) ) :
	function discy_admin_init() {
		// Loads the required Options Framework classes.
		require_once locate_template("admin/includes/admin_options.php");
		require_once locate_template("admin/includes/options_sanitization.php");
		require_once locate_template("admin/option.php");
	
		// Instantiate the main class.
		$discy_admin = new discy_admin;
		// Instantiate the options page.
		$discy_admin_options = new discy_admin_options;
		$discy_admin_options->init("options");
	
	}
	add_action( 'init', 'discy_admin_init', 20 );
	if (strpos(apply_filters('wpqa_server','REQUEST_URI'),'page=options') === false) {
		add_action( 'current_screen', 'discy_admin_init' );
	}
endif;