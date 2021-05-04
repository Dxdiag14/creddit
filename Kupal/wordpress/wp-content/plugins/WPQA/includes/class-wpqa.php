<?php

/* @author    2codeThemes
*  @package   WPQA/includes
*  @version   1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (!class_exists('WPQA')) :
	class WPQA {
		/* Name */
		protected $plugin_name;
		public function plugin_name() {
			return $this->plugin_name;
		}
		/* Name capital */
		public function super_plugin_name() {
			return strtoupper($this->plugin_name);
		}
		/* Plugin URL */
		protected $plugin_url;
		public function plugin_url() {
			return $this->plugin_url;
		}
		/* URL */
		protected $site_url;
		public function site_url() {
			return $this->site_url;
		}
		/* The php main path */
		protected $wpqa_main_path;
		public function wpqa_main_path() {
			return $this->wpqa_main_path;
		}
		
		/* Define the core functionality of the plugin. */
		public function __construct() {
			$text_domain = get_file_data(plugin_dir_path(dirname(__DIR__))."WPQA/wpqa.php",array('Text Domain'),'plugin');
			$this->plugin_name = $text_domain[0];
			$this->plugin_url = "https://2code.info/WPQA/";
			$this->site_url = "https://2code.info/";
			$this->wpqa_main_path = plugin_dir_path(dirname(__FILE__));
		}
		/* The code that runs during plugin activation */
		public static function activate() {
			global $wp_version,$wpdb;
			$wpdb->query($wpdb->prepare("ALTER TABLE ".$wpdb->users." CHANGE `user_nicename` `user_nicename` VARCHAR(255) NOT NULL DEFAULT %s;",''));
			$wp_compatible_version = '4.0';
			if (version_compare($wp_version,$wp_compatible_version,'<')) {
				deactivate_plugins(basename(__FILE__));
				wp_die('<p>'.sprintf(esc_html__('This plugin can not be activated because it requires a WordPress version at least %1$s (or later). Please go to Dashboard &#9656; Updates to get the latest version of WordPress.','wpqa'),$wp_compatible_version).'</p><a href="'.admin_url('plugins.php').'">'.esc_html__('go back','wpqa').'</a>');
			}
			update_option("FlushRewriteRules",true);
		}
		/* The code that runs during plugin deactivation */
		public static function deactivate() {
			flush_rewrite_rules(true);
		}
	}
endif;
$wpqa = new WPQA;
?>