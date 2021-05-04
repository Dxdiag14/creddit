<?php

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;

/**
 * Auxin_Welcome class
 */
class Auxin_Welcome extends Auxin_Welcome_Base {

    /**
     * Current step
     *
     * @var string
     */
    protected $step     = '';

    /** @var array Steps for the setup wizard */
    protected $steps    = array();

	/**
	 * TGMPA instance storage
	 *
	 * @var object
	 */
	protected $tgmpa_instance;

	/**
	 * TGMPA Menu slug
	 *
	 * @var string
	 */
	protected $tgmpa_menu_slug 	= 'tgmpa-install-plugins';

	/**
	 * TGMPA Menu url
	 *
	 * @var string
	 */
	protected $tgmpa_url 		= 'themes.php?page=tgmpa-install-plugins';

    /**
     * Plugin filters
     *
     * @var array
     */
    protected $plugin_filters   = array();


	/**
	 * Holds the current instance of the theme manager
	 *
	 */
	protected static $instance 	= null;

	/**
	 * Retrieves class instance
	 *
	 * @return Auxin_Welcome
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance 	= new self;
		}

		return self::$instance;
	}


	/**
	 * Constructor
	 */
	public function __construct() {
        parent::__construct();

		$this->init_globals();
		$this->init_actions();
	}

	/**
	 * Setup the class globals.
	 *
	 */
	public function init_globals() {
		$this->page_slug       	= 'auxin-welcome';
        $this->parent_slug      = 'auxin-welcome';
	}

	/**
	 * Setup the hooks, actions and filters.
	 *
	 */
	public function init_actions() {
        // Call the parent method
        parent::init_actions();

		if ( current_user_can( 'manage_options' ) ) {

			// Disable redirect for "related posts for WordPress" plugin
            update_option('rp4wp_do_install', 0, false );
            // Disable redirect for the "WooCommerce" plugin
            delete_transient( '_wc_activation_redirect' );
            // Disable redirect for Phlox Pro plugin
            remove_action( 'init', 'auxpro_redirect_to_welcome_page_on_first_activation' );

			if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
				add_action( 'init'					, array( $this, 'get_tgmpa_instanse' ), 30 );
				add_action( 'init'					, array( $this, 'set_tgmpa_url' ), 40 );
			}

            if( ! class_exists( 'Auxin_Demo_Importer' ) ){
                require_once( 'class-auxin-demo-importer.php' );
            }

			// Get instance of Auxin_Demo_Importer Class
			Auxin_Demo_Importer::get_instance();

            // add_action( 'admin_enqueue_scripts'		, array( $this, 'enqueue_scripts' ) );
            add_filter( 'tgmpa_load'				, array( $this, 'tgmpa_load' ), 10, 1 );
            add_action( 'wp_ajax_aux_setup_plugins'	, array( $this, 'ajax_plugins' ) );

            add_action( 'wp_ajax_aux_ajax_uninstall', array( $this, 'ajax_uninstall') );

            add_action( 'wp_ajax_aux_ajax_lightbox' , array( $this, 'ajax_lightbox') );
            add_action( 'wp_ajax_aux_step_manager'  , array( $this, 'step_manager' ) );

			if( isset( $_POST['action'] ) && $_POST['action'] === "aux_setup_plugins" && wp_doing_ajax() ) {
				add_filter( 'wp_redirect', '__return_false', 999 );
			}

            Auxin_Welcome_Sections::get_instance()->page_slug = $this->page_slug;
            Auxin_Welcome_Sections::get_instance()->welcome   = $this;
		}
	}

    /**
     * Adds a constant class names to body on wizard page
     */
    public function add_body_class( $classes ){
        $classes = parent::add_body_class( $classes );

        if( $this->current_tab( 'importer', 'plugins' ) ){
            $classes .= ' auxin-wizard-panel';

            // Add PRO selector, for some probable custom styles
            if( defined('THEME_PRO' ) && THEME_PRO ) {
            	$classes .= ' auxin-wizard-pro';
            }
        }

        return $classes;
    }

	/**
	 * Enqueue admin scripts
	 *
	 */
	public function enqueue_scripts() {}

    /**
     * Check for TGMPA load
     *
     */
	public function tgmpa_load( $status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}

	/**
	 * Get configured TGMPA instance
	 *
	 */
	public function get_tgmpa_instanse() {
		$this->tgmpa_instance 	= call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
	}

	/**
	 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
	 *
	 */
	public function set_tgmpa_url() {
		$this->tgmpa_menu_slug 	= ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
		$this->tgmpa_menu_slug 	= apply_filters( $this->theme_id . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );

		$tgmpa_parent_slug 		= ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';

		$this->tgmpa_url 		= apply_filters( $this->theme_id . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );
	}

    /**
     * Register the admin menu
     *
     * @return void
     */
    public function register_admin_menu() {

        $menu_args = $this->get_admin_menu_args();

        /*  Register root setting menu
        /*-----------------------------*/
        add_menu_page(
            $menu_args['title'],         // [Title]    The title to be displayed on the corresponding page for this menu
            $menu_args['name'],          // [Text]     The text to be displayed for this actual menu item
            $menu_args['compatibility'],
            $this->page_slug,            // [ID/slug]  The unique ID - that is, the slug - for this menu item
            array( $this, 'render'),      // [Callback] The name of the function to call when rendering the menu for this page
            '',                          // icon_url
            3                            // [Position] The position in the menu order this menu should appear 3 means after dashboard
        );

        /*  Add a menu separator
        /*-----------------------------*/
        add_menu_page(
            '',
            '',
            'read',
            'wp-menu-separator',
            '',
            '',
            4
        );

        $this->add_submenus();
    }


    /**
     * Add submenu for admin menu
     *
     * @return void
     */
    protected function add_submenus(){

        global $submenu;

        $menu_args = $this->get_admin_menu_args();

        $sections  = $this->get_sections();
        if( empty( $sections ) ){
            return;
        }

        foreach ( $sections as $section_id => $section ) {
            if( ! empty( $section['add_admin_menu'] ) && $section['add_admin_menu'] ){

                if( ! empty( $section['url'] ) ){

                    $submenu[ $this->page_slug ][] = array(
                        $section['label'],
                        $menu_args['compatibility'],
                        esc_url( $section['url'] )
                    );

                } else {
                    add_submenu_page(
                        $this->page_slug,
                        $section['label'],
                        $section['label'],
                        $menu_args['compatibility'],
                        $this->get_page_rel_tab( $section_id )
                    );
                }

            }
        }

        if( isset( $submenu[ $this->page_slug ]['0'] ) ){
            $submenu[ $this->page_slug ]['0']['0'] = __( 'Dashboard', 'auxin-elements' );
        }
        unset( $submenu[ $this->page_slug ]['1'] );
    }

	/*-----------------------------------------------------------------------------------*/
	/*  Start Setup Wizard
	/*-----------------------------------------------------------------------------------*/

    /**
     * Retrieves the welcome page relative path
     *
     * @return string     Page relative path
     */
    public function get_page_rel_path(){
        return 'admin.php?page=' . $this->page_slug;
    }

	/**
	 * Display Alert Message
	 */
	public function display_alerts( $message_body = '', $class_name = '' ){
	?>
		<div class="aux-alert <?php echo esc_attr( $class_name ); ?>">
			<p>
				<?php
					if( empty($message_body ) ) {
						echo sprintf("<strong>%s</strong> %s", esc_html__( 'Note:', 'auxin-elements' ), __( 'You are recommended to install Phlox exclusive plugins in order to enable all features.', 'auxin-elements' ) );
					} else {
						echo esc_html( $message_body );
					}
				?>
			</p>
		</div>
	<?php
	}


    /**
     * Collect the plugin filters
     *
     * @return array    plugin filters
     */
    private function get_plugins_categories_localized(){
        if( empty( $this->plugin_filters ) ){
            $this->plugin_filters = apply_filters( 'auxin_admin_welcome_plugins_categories_localized', array() );
        }

        return $this->plugin_filters;
    }


    /**
     * Collect all plugin categories from bundled plugins
     *
     * @return array    plugin categories
     */
    private function get_plugins_categories( $all_plugins ){
        $plugin_categories = array();

        foreach ( $all_plugins as $slug => $plugin ) {
            $filter_terms = '';
            if( ! empty( $plugin['categories'] ) ){
                if( is_array( $plugin['categories'] ) ){
                    $plugin_categories = array_merge( $plugin_categories, $plugin['categories'] );
                }
            }
        }

        return array_unique( $plugin_categories );
    }


	/*-----------------------------------------------------------------------------------*/
	/*  Third step (Plugin installation)
	/*-----------------------------------------------------------------------------------*/
	public function setup_plugins() {

		tgmpa_load_bulk_installer();
		// install plugins with TGM.
		if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
			die( 'Failed to find TGM' );
		}
        $url     = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'aux-setup' );

        $custom_list = isset( $_GET['items'] ) && ! empty( $_GET['items'] ) ? explode( ',',  $_GET['items'] ) : array();
        $plugins = $this->get_plugins( $custom_list );

		// copied from TGM

		$method = ''; // Leave blank so WP_Filesystem can populate it as necessary.
		$fields = array_keys( $_POST ); // Extra fields to pass to WP_Filesystem.

		if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields ) ) ) {
			return true; // Stop the normal page form from displaying, credential request form will be shown.
		}

		// Now we have some credentials, setup WP_Filesystem.
		if ( ! WP_Filesystem( $creds ) ) {
			// Our credentials were no good, ask the user for them again.
			request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );

			return true;
		}

		$embeds_plugins_desc = array(
			'js_composer'        => 'Drag and drop page builder for WordPress. Take full control over your WordPress site, build any layout you can imagine – no programming knowledge required.',
			'Ultimate_VC_Addons' => 'Includes Visual Composer premium addon elements like Icon, Info Box, Interactive Banner, Flip Box, Info List & Counter. Best of all - provides A Font Icon Manager allowing users to upload / delete custom icon fonts.',
			'masterslider'       => 'Master Slider is the most advanced responsive HTML5 WordPress slider plugin with touch swipe navigation that works smoothly on devices too.',
			'go_pricing'         => 'The New Generation Pricing Tables. If you like traditional Pricing Tables, but you would like get much more out of it, then this rodded product is a useful tool for you.',
            'waspthemes-yellow-pencil'      => 'The most advanced visual CSS editor. Customize any page in real-time without coding.',
			'auxin-the-news'     => 'Publish news easily and beautifully with Phlox theme.',
			'auxin-pro-tools'    => 'Premium features for Phlox theme.',
			'auxin-shop'         => 'Make a shop in easiest way using phlox theme.',
			'envato-market'      => 'WP Theme Updater based on the Envato WordPress Toolkit Library and Pixelentity class from ThemeForest forums.'
		);

		/* If we arrive here, we have the filesystem */

		?>
        <div class="aux-setup-content">
            <div class="aux-section-content-box">
            <?php if( ! isset( $_GET['view'] ) || $_GET['view'] !== 'abstract'  ) : ?>
                <h3 class="aux-content-title"><?php _e('Recommended Plugins', 'auxin-elements' ); ?></h3>
                <p style="margin-bottom:0;"><?php echo wp_sprintf( esc_html__( 'The following is a list of best integrated plugins for %s theme, you can install them from here and add or remove them later on WordPress plugins page.', 'auxin-elements' ), THEME_NAME_I18N );?></p>
        		<p><?php esc_html_e( 'We recommend you to install only the plugins under "Essential" tab, and avoid installing all of plugins.', 'auxin-elements' ); ?></p>
            <?php endif; ?>

                <div class="aux-plugins-step aux-has-required-plugins aux-fadein-animation">
                    <?php
                    if ( count( $plugins['all'] ) ) {

                        $plugin_categories           = $this->get_plugins_categories( $plugins['all'] );
                        $plugin_categories_localized = $this->get_plugins_categories_localized();

                        // -----------------------------------------------------
                        ?>

        				<div class="aux-table">
        					<section class="auxin-list-table">

                            <?php if( ! isset( $_GET['view'] ) || $_GET['view'] !== 'abstract'  ) : ?>
                                <div class="aux-isotope-filters aux-filters aux-underline aux-clearfix aux-togglable aux-clearfix aux-center">
                                    <div class="aux-select-overlay"></div>
                                    <ul>
                                        <li data-filter="all"><a href="#" class="aux-selected"><span data-select="<?php _e('Recent', 'auxin-elements'); ?>"><?php _e('Recent', 'auxin-elements'); ?></span></a></li>
                                    <?php
                                        foreach ( $plugin_categories_localized as $filter_slug => $filter_label ) {
                                            if( in_array( $filter_slug, $plugin_categories ) ){
                                                echo '<li data-filter="'. esc_attr( $filter_slug . '-plugins' ) .'"><a href="#"><span data-select="'. $filter_label .'">'. $filter_label .'</span></a></li>';
                                            }
                                        }
                                    ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                                <header class="aux-table-heading aux-table-row aux-clearfix">
                                    <div id="cb" class="manage-column aux-column-cell column-cb check-column">
                                        <label class="screen-reader-text" for="cb-select-all"><?php esc_html_e( 'Select All', 'auxin-elements' ); ?></label>
                                        <input id="cb-select-all" type="checkbox" style="display:none;">
                                    </div>
                                    <div class="manage-column aux-column-cell column-thumbnail"></div>
                                    <div scope="col" id="name" class="manage-column aux-column-cell column-name"><?php esc_html_e( 'Name', 'auxin-elements' ); ?></div>
                                    <div scope="col" id="description" class="manage-column aux-column-cell column-description"><?php esc_html_e( 'Description', 'auxin-elements' ); ?></div>
                                    <div scope="col" id="status" class="manage-column aux-column-cell column-status"><?php esc_html_e( 'Status', 'auxin-elements' ); ?></div>
                                    <div scope="col" id="version" class="manage-column aux-column-cell column-version"><?php esc_html_e( 'Version', 'auxin-elements' ); ?></div>
                                </header>

        					    <div class="aux-wizard-plugins aux-table-body aux-isotope-plugins-list aux-clearfix">
        							<?php
        							foreach ( $plugins['all'] as $slug => $plugin ) {

                                        // Collect plugin filters for current item
                                        $filter_terms = '';
                                        if( ! empty( $plugin['categories'] ) ){
                                            if( is_array( $plugin['categories'] ) ){
                                                foreach ( $plugin['categories'] as $category ) {
                                                    $filter_terms .=  $category . '-plugins ';
                                                }
                                            }
                                        }

        								if( $this->tgmpa_instance->is_plugin_installed( $slug ) ) {
        									$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin['file_path'] );
        								} else {
        									$plugin_data = $this->get_plugin_data_by_slug( $slug );
        								}
        							?>
        								<div class="aux-plugin aux-table-row aux-iso-item <?php echo esc_attr( $filter_terms ); ?>" data-slug="<?php echo esc_attr( $slug ); ?>">
        						            <div scope="row" class="check-column aux-column-cell">
        						                <input class="aux-check-column" name="plugin[]" value="<?php echo esc_attr( $slug ); ?>" type="checkbox">
        						                <div class="spinner"></div>
        						            </div>
        						            <div class="thumbnail column-thumbnail aux-column-cell" data-colname="Thumbnail">
        						            	<?php
                                                    $thumbnail = "https://ps.w.org/{$plugin['slug']}/assets/icon-128x128.png";

        								            if( isset( $plugin['thumbnail'] ) ){
                                                        if( 'custom' == $plugin['thumbnail'] ){
                                                            $thumbnail = AUXELS_ADMIN_URL . '/assets/images/welcome/' . $plugin['slug'] . '-plugin.png';
                                                        } elseif( 'default' === $plugin['thumbnail'] ){
                                                            $thumbnail = AUXELS_ADMIN_URL . '/assets/images/welcome/def-plugin.png';
                                                        } elseif( ! empty( $plugin['thumbnail'] ) ){
                                                            $thumbnail = $plugin['thumbnail'];
                                                        }
                                                    }
        								            ?>
        	        							<img src="<?php echo esc_url( $thumbnail ); ?>" width="64" height="64" />
        						            </div>
        						            <div class="name column-name aux-column-cell" data-colname="Plugin"><?php echo esc_html( $plugin['name'] ); ?></div>
        						            <div class="description column-description aux-column-cell" data-colname="Description">
        						            <?php
        						            	$description = '';
                                                if( isset( $plugin_data['Description'] ) ) {
                                                    $description = $plugin_data['Description'];
                                                } else if ( isset( $embeds_plugins_desc[ $plugin['slug'] ] ) ){
                                                    $description = $embeds_plugins_desc[ $plugin['slug'] ];
                                                }
                                                if( $description ){
                                                    echo '<p>'. $description .'</p>';
                                                }
        										if ( ! empty( $plugin['badge'] ) ) {
        										    echo '<span class="aux-label aux-exclusive-label">' . esc_html( $plugin['badge'] ) . '</span>';
        										}
        						            ?>
        						            </div>
        						            <div class="status column-status aux-column-cell" data-colname="Status">
        										<span>
    		    								<?php
    											    if ( isset( $plugins['install'][ $slug ] ) ) {
    												    echo esc_html__( 'Not Installed', 'auxin-elements' );
    											    } elseif ( isset( $plugins['activate'][ $slug ] ) ) {
    												    echo esc_html__( 'Not Activated', 'auxin-elements' );
    											    }
    										    ?>
        		    							</span>
        						            </div>
        					                <div class="version column-version aux-column-cell" data-colname="Version">
        					                	<?php if( isset( $plugin_data['Version'] ) ) { ?>
        					                    <span><?php echo esc_html( $plugin_data['Version'] ); ?></span>
        					                    <?php } ?>
        					                </div>
        								</div>
        							<?php } ?>
        					    </div>
        					</section>
        				</div>

        				<div class="clear"></div>

        				<div class="aux-sticky">
        					<div class="aux-setup-actions step">
        						<a href="#"
        						   class="aux-button aux-primary install-plugins disabled"
        						   data-callback="install_plugins"><?php esc_html_e( 'Install Plugins', 'auxin-elements' ); ?></a>
        						<?php wp_nonce_field( 'aux-setup' ); ?>
        					</div>
        				</div>

        			<?php
        			} else { ?>

        	 			<?php $this->display_alerts( esc_html__( 'Good news! All plugins are already installed and up to date. Please continue.', 'auxin-elements'  ) , 'success' ); ?>

        			<?php
        			} ?>
        		</div>
            </div>
        </div>
		<?php
	}

	/**
	 * Output the tgmpa plugins list
	 */
	private function get_plugins( $custom_list = array() ) {

		$plugins  = array(
			'all'      => array(), // Meaning: all plugins which still have open actions.
			'install'  => array(),
			'update'   => array(),
			'activate' => array(),
        );

		foreach ( $this->tgmpa_instance->plugins as $slug => $plugin ) {

			if( ! empty( $custom_list ) && ! in_array( $slug, $custom_list ) ){
				// This condition is for custom requests lists
				continue;
			} elseif( $this->tgmpa_instance->is_plugin_active( $slug ) && false === $this->tgmpa_instance->does_plugin_have_update( $slug ) ) {
				// No need to display plugins if they are installed, up-to-date and active.
				continue;
			} else {
				$plugins['all'][ $slug ] = $plugin;

				if ( ! $this->tgmpa_instance->is_plugin_installed( $slug ) ) {
					$plugins['install'][ $slug ] = $plugin;
				} else {

					if ( false !== $this->tgmpa_instance->does_plugin_have_update( $slug ) ) {
						$plugins['update'][ $slug ] = $plugin;
					}
					if ( $this->tgmpa_instance->can_plugin_activate( $slug ) ) {
						$plugins['activate'][ $slug ] = $plugin;
					}

				}
			}
		}

		return $plugins;
	}

	/**
	 * Returns the plugin data from WP.org API
	 */
	private function get_plugin_data_by_slug( $slug = '' ) {

		if ( empty( $slug ) ) {
			return false;
		}

	    $key = sanitize_key( 'auxin_plugin_data_'.$slug );

	    if ( false === ( $plugins = auxin_get_transient( $key ) ) ) {
			$args = array(
				'slug' => $slug,
				'fields' => array(
			 		'short_description' => true
				)
			);
			$response = wp_remote_post(
				'http://api.wordpress.org/plugins/info/1.0/',
				array(
					'body' => array(
						'action' => 'plugin_information',
						'request' => serialize( (object) $args )
					)
				)
			);
			$data    = unserialize( wp_remote_retrieve_body( $response ) );

			$plugins = is_object( $data ) ? array( 'Description' => $data->short_description , 'Version' => $data->version ) : false;

			// Set transient for next time... keep it for 24 hours
			auxin_set_transient( $key, $plugins, 24 * HOUR_IN_SECONDS );

	    }

	    return $plugins;
	}

	/**
	 * Plugins AJAX Process
	 */
	public function ajax_plugins() {
        // Inputs validations
		if ( ! check_ajax_referer( 'aux_setup_nonce', 'wpnonce' ) || ! isset( $_POST['slug'] ) || empty( $_POST['slug'] ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'No Slug Found', 'auxin-elements' ) ) );
		}
        $request = array();
        // send back some json we use to hit up TGM
        $plugins = $this->get_plugins();
		// what are we doing with this plugin?
		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $slug === 'related-posts-for-wp' ) {
				update_option( 'rp4wp_do_install', false );
			}
			if ( $_POST['slug'] == $slug ) {
				$request = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating', 'auxin-elements' ),
				);
				break;
			}
		}
		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$request = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( 'Updating', 'auxin-elements' ),
				);
				break;
			}
		}
		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$request = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing', 'auxin-elements' ),
				);
				break;
			}
		}

		if ( ! empty( $request ) ) {
			$request['hash'] = md5( serialize( $request ) ); // used for checking if duplicates happen, move to next plugin
			wp_send_json_success( $request );
		}

        wp_send_json_success( array( 'message' => esc_html__( 'Activated', 'auxin-elements' ) ) );

	}


    /*-----------------------------------------------------------------------------------*/
    /*  Online Demo Importer
    /*-----------------------------------------------------------------------------------*/

    public function setup_templates(){
        $template_list = $this->get_demo_list( 'templates' );

        // Create subjects group by type
        $subjects_group = array();

        if( $template_list ){
            foreach ( $template_list['templates'] as $key => $args ) {
                // Convert subject JSON to Array
                $categoryStack = json_decode( $args['subject'], true );
                // Set group type
                if( ! isset( $subjects_group[ $args['type'] ] ) ) {
                    $subjects_group[ $args['type'] ] = array();
                }
                // Pass items to group type
                $subjects_group[ $args['type'] ] = array_unique( array_merge( $subjects_group[ $args['type'] ], $categoryStack ), SORT_REGULAR );
            }
        }

        $activeIsoGroup = get_option( 'aux_isotope_group_templates_kit', 'page' );
    ?>

        <div class="aux-setup-template">

        <div class="aux-fadein-animation">

            <div class="aux-isotope-filters aux-filters aux-underline aux-clearfix aux-togglable aux-clearfix aux-center">
                <div class="aux-isotope-group-wrapper">
                    <span>Pages</span>
                    <input type="checkbox" class="aux-isotope-group aux_switch" data-nonce="<?php echo wp_create_nonce( 'aux-iso-group' ); ?>" value="1" <?php checked( 1, $activeIsoGroup === 'page' ? 0 : 1, true ); ?> />
                    <span>Sections</span>
                </div>
                <div class="aux-isotope-filters-wrapper">
            <?php
            foreach ( $subjects_group as $type => $subjects ) {
            ?>
                <ul class="aux-group-filter aux-grouping-<?php echo esc_attr( $type ); ?> <?php echo $activeIsoGroup !== $type ? 'aux-iso-hidden' : ''; ?>">
                    <li data-filter="all"><a href="#" class="aux-selected"><span data-select="<?php _e('Recent', 'auxin-elements'); ?>"><?php _e('Recent', 'auxin-elements'); ?></span></a></li>
                <?php
                    foreach ( $subjects as $filter_label ) {
                        $filter_data = preg_replace( '/[^A-Za-z0-9\-]/', '', wp_specialchars_decode( $filter_label ) );
                        echo '<li data-filter="'. strtolower( str_replace( ' ', '-', $filter_data ) . '-subject' )  .'"><a href="#"><span data-select="'. $filter_label .'">'. $filter_label .'</span></a></li>';
                    }
                ?>
                </ul>
            <?php
            }
            ?>
                </div>
                <div class="aux-isotope-search-wrapper">
                    <input type="text" placeholder="<?php echo esc_html__( 'Search Templates', 'auxin-elements' ); ?>" class="aux-isotope-search">
                </div>
            </div>

            <div class="aux-templates-list aux-grid-list aux-isotope-templates" data-search-filter="true" data-grouping="<?php echo esc_attr( $activeIsoGroup ); ?> ">
            <?php
                if( ! is_array( $template_list['templates'] ) ){
                    echo '<p class="aux-grid-item grid_12">'. __( 'An error occurred while downloading the list of templates. Please try again later.' ) .'</p>';
                } else {
                    foreach ( $template_list['templates'] as $key => $args ) {

                        // Collect plugin filters for current item
                        $filter_categories = '';
                        if( ! empty( $args['subject'] ) ){
                            $categories = json_decode( $args['subject'], true );
                            foreach ( $categories as $num => $category ) {
                                $category_decode   = preg_replace( '/[^A-Za-z0-9\-]/', '', wp_specialchars_decode( $category ) );
                                $filter_categories .=  strtolower(  str_replace( ' ', '-', $category_decode ) . '-subject ' );
                            }
                        }

                        $filter_tags = '';
                        if( ! empty( $args['tags'] ) ){
                            $categories = json_decode( $args['tags'], true );
                            foreach ( $categories as $num => $tags ) {
                                $filter_tags .=  strtolower( str_replace( ' ', '-', $tags ) . '-tag ' );
                            }
                        }

                        // Check demo license
                        $is_demo_allowed = auxin_is_activated() || ! $args['is_pro'];

                        echo '<div data-template-type="'.$args['type'].'" class="aux-grid-item aux-iso-item grid_3 aux-grouping-'.esc_attr( $args['type'] ).' '.esc_attr( $filter_categories ).' '.esc_attr( $filter_tags ).'">';
                        echo '<div class="aux-grid-item-inner">';
                            echo '<div class="aux-grid-template-media">';
                                echo '<img class="template_thumbnail aux-preload aux-blank" data-src='.$args['thumbnail'].' src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">';
                        if( ! $is_demo_allowed ) {
                            echo '<img class="premium_badge" alt="This is a premium demo" src="'. esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/pro-badge.png' ) .'">';
                        }
                            echo '</div>';
                        ?>
                            <div class="aux-grid-item-footer">
                                <h3 class="aux-item-title"><?php echo $args['title']; ?></h3>
                                <div class="aux-grid-item-buttons aux-clearfix">
                                <?php

                                    $template_status = 'import';
                                    $more_btn_class  = 'hide';

                                    if( $is_demo_allowed ) {
                                        $color_class    = " aux-open-modal aux-import-template aux-has-next-action aux-iconic-action aux-green2";
                                        $btn_label      = esc_html__( 'Download', 'auxin-elements' );
                                        $import_btn_url = add_query_arg( array( 'action' => 'aux_ajax_lightbox', 'type' => 'progress' , 'nonce' => wp_create_nonce( 'aux-open-lightbox' ) ), admin_url( 'admin-ajax.php' ) );

                                        if( ! empty( $args['plugins'] ) ) {
                                            $plugins             = $this->get_plugins( json_decode( $args['plugins'], true ) );
                                            if( ! empty( $plugins['all'] ) ) {
                                                $color_class     = " aux-open-modal aux-import-template aux-iconic-action aux-green2";
                                                $import_btn_url  = add_query_arg( array( 'action' => 'aux_ajax_lightbox', 'key' => $key, 'type' => 'plugins' , 'nonce' => wp_create_nonce( 'aux-open-lightbox' ) ), admin_url( 'admin-ajax.php' ) );
                                            }
                                        }

                                        if( false !==  auxin_get_transient( sanitize_key( "auxin_template_kit_{$args['type']}_data_for_origin_id_{$args['id']}" ) ) ) {
                                            $template_status = 'copy';
                                            $btn_label       = esc_html__( 'Copy to clipboard', 'auxin-elements' );
                                            $color_class     = " aux-copy-template aux-iconic-action aux-orange";
                                            $import_btn_url  = '#';
                                            $more_btn_class  = '';
                                        }

                                    } else {
                                        $color_class    = " aux-blue aux-pro-demo aux-locked-demo aux-iconic-action";
                                        $btn_label      = esc_html__( 'Unlock', 'auxin-elements' );
                                        $import_btn_url = esc_url( 'http://phlox.pro/go-pro/?utm_source=phlox-welcome&utm_medium=phlox-free&utm_campaign=phlox-go-pro&utm_content=template-unlock&utm_term='. $args['id'] );

                                        if( defined('THEME_PRO' ) && THEME_PRO ){
                                            $color_class   .= " aux-ajax-open-modal";
                                            $import_btn_url = add_query_arg( array( 'action' => 'auxin_display_actvation_form',  'nonce' => wp_create_nonce( 'aux-activation-form' ) ), admin_url( 'admin-ajax.php' ) );
                                        }
                                    }


                                ?>
                                    <a target="_blank" href="<?php echo esc_url( add_query_arg( array( 'action' => 'aux_ajax_lightbox', 'type' => 'preview', 'preview' => $args['preview'], 'nonce' => wp_create_nonce( 'aux-open-lightbox' ) ), admin_url( 'admin-ajax.php' ) ) ) ?>"
                                       class="aux-wl-button aux-open-modal aux-outline aux-round aux-transparent aux-large aux-preview"><?php esc_html_e( 'Preview', 'auxin-elements' ); ?></a>
                                    <a  href="<?php echo esc_url( $import_btn_url ); ?>"
                                        class="aux-wl-button aux-outline aux-round aux-large <?php echo esc_attr( $color_class ); ?>"
                                        data-template-id="<?php echo esc_attr( $args['id'] ); ?>"
                                        data-template-type="<?php echo esc_attr( $args['type'] ); ?>"
                                        data-template-page-tmpl="<?php echo esc_attr( $args['page_tmpl'] ); ?>"
                                        data-template-title="<?php echo esc_attr( $args['title'] ); ?>"
                                        data-status-type="<?php echo esc_attr( $template_status ); ?>"
                                        data-nonce="<?php echo wp_create_nonce( 'aux-template-manager' ); ?>"
                                    ><span><?php echo $btn_label; ?></span></a>
                                    <a href="#" class="aux-more-button <?php echo esc_attr( $more_btn_class ) ;?>">
                                        <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/more.svg' ); ?>" width="4" height="18" />
                                    </a>
                                    <ul class="aux-more-items">
                                        <li>
                                            <a  href="<?php echo esc_url( add_query_arg( array( 'action' => 'aux_ajax_lightbox', 'type' => 'progress' , 'nonce' => wp_create_nonce( 'aux-open-lightbox' ) ), admin_url( 'admin-ajax.php' ) ) ); ?>"
                                                class="aux-open-modal aux-has-next-action"
                                                data-template-id="<?php echo esc_attr( $args['id'] ); ?>"
                                                data-template-type="<?php echo esc_attr( $args['type'] ); ?>"
                                                data-template-page-tmpl="<?php echo esc_attr( $args['page_tmpl'] ); ?>"
                                                data-template-title="<?php echo esc_attr( $args['title'] ); ?>"
                                                data-status-type="create_my_template"
                                                data-nonce="<?php echo wp_create_nonce( 'aux-template-manager' ); ?>"
                                            ><span><?php echo esc_html__( 'Save to my templates', 'auxin-elements' ); ?></span></a>
                                        </li>
                                        <li>
                                            <a  href="<?php echo esc_url( add_query_arg( array( 'action' => 'aux_ajax_lightbox', 'type' => 'progress' , 'nonce' => wp_create_nonce( 'aux-open-lightbox' ) ), admin_url( 'admin-ajax.php' ) ) ); ?>"
                                                class="aux-open-modal aux-has-next-action"
                                                data-template-id="<?php echo esc_attr( $args['id'] ); ?>"
                                                data-template-type="<?php echo esc_attr( $args['type'] ); ?>"
                                                data-template-page-tmpl="<?php echo esc_attr( $args['page_tmpl'] ); ?>"
                                                data-status-type="create_new_page"
                                                data-template-title="<?php echo esc_attr( $args['title'] ); ?>"
                                                data-nonce="<?php echo wp_create_nonce( 'aux-template-manager' ); ?>"
                                            ><span><?php echo esc_html__( 'Create new page', 'auxin-elements' ); ?></span></a>
                                        </li>
                                    </ul>
                               </div>
                            </div>
                        </div>
                        <?php
                        echo '</div>';
                    }
                }
            ?>
            </div>

            <div class="clear"></div>

        </div>

        </div>

    <?php
    }

	/*-----------------------------------------------------------------------------------*/
	/*  Online Demo Importer
	/*-----------------------------------------------------------------------------------*/

	public function setup_importer() {
		// Get the available demos list from Averta API
        $demo_list          = $this->get_demo_list();
        // Get last imported demo data
        $last_demo_imported = get_option( 'auxin_last_imported_demo' );

	?>
        <div class="aux-setup-content">

		<div class="aux-demo-importer-step aux-fadein-animation">

            <div class="aux-demo-list aux-grid-list aux-isotope-list">
			<?php
                if( ! is_array( $demo_list ) ){
                    echo '<p class="aux-grid-item grid_12">'. __( 'An error occurred while downloading the list of demo sites. Please try again later.' ) .'</p>';
                } else {
    				foreach ( $demo_list['items'] as $key => $args ) {
                        // Checking the last imported demo...
                        $is_active_demo  = ! empty( $last_demo_imported ) && $last_demo_imported['id'] == $args['id'] ? 'aux-last-imported-demo' : '';

                        // Check demo license
                        $is_demo_allowed = auxin_is_activated() || !$args['is_pro'];

    					echo '<div data-demo-id="demo-'.$args['id'].'" class="aux-grid-item aux-iso-item grid_4 '.$is_active_demo.'">';
                        echo '<div class="aux-grid-item-inner">';
                            echo '<div class="aux-grid-item-media">';
                                echo '<img class="demo_thumbnail aux-preload aux-blank" data-src='.$args['thumbnail'].' src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7">';
    					if( ! $is_demo_allowed ) {
                            echo '<img class="premium_badge" alt="This is a premium demo" src="'. esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/pro-badge.png' ) .'">';
    					}
                            echo '</div>';
                        ?>
                            <div class="aux-grid-item-footer">
                                <h3><?php echo $args['title']; ?></h3>
                                <div class="aux-grid-item-buttons aux-clearfix">
                                <?php
                                    if( $is_demo_allowed ) {
                                        $color_class    = $is_active_demo ? " aux-open-modal aux-import-demo aux-iconic-action aux-uninstall aux-orange" : " aux-open-modal aux-import-demo aux-iconic-action aux-green2";
                                        $btn_label      = $is_active_demo ? esc_html__( 'Uninstall', 'auxin-elements' ) : esc_html__( 'Import', 'auxin-elements' );
                                        $import_type    = $is_active_demo ? 'uninstall' : 'install';
                                        $import_btn_url = add_query_arg( array( 'action' => 'aux_ajax_lightbox', 'key' => $key, 'type' => $import_type , 'nonce' => wp_create_nonce( 'aux-open-lightbox' ) ), admin_url( 'admin-ajax.php' ) );
                                    } else {
                                        $color_class    = " aux-blue aux-pro-demo aux-locked-demo aux-iconic-action";
                                        $btn_label      = esc_html__( 'Unlock', 'auxin-elements' );
                                        $import_btn_url = esc_url( 'http://phlox.pro/go-pro/?utm_source=phlox-welcome&utm_medium=phlox-free&utm_campaign=phlox-go-pro&utm_content=demo-unlock&utm_term='. $args['id'] );
                                        if( defined('THEME_PRO' ) && THEME_PRO ){
                                            $color_class   .= " aux-ajax-open-modal";
                                            $import_btn_url = add_query_arg( array( 'action' => 'auxin_display_actvation_form',  'nonce' => wp_create_nonce( 'aux-activation-form' ) ), admin_url( 'admin-ajax.php' ) );
                                        }
                                    }
                                ?>
                                    <a target="_blank" href="<?php echo $import_btn_url; ?>"
                                        class="aux-wl-button aux-outline aux-round aux-large <?php echo esc_attr( $color_class ); ?>" data-demo-key="<?php echo esc_html( $key );?>"><?php echo $btn_label; ?></a>
                                    <a target="_blank" href="<?php echo ! empty( $args['url'] ) ? esc_url( $args['url'] .'&utm_term='.$args['id'] ) : '#'; ?>"
                                       class="aux-wl-button aux-outline aux-round aux-transparent aux-large aux-preview"><?php esc_html_e( 'Preview', 'auxin-elements' ); ?></a>
                               </div>
                            </div>
                        </div>
    					<?php
    					echo '</div>';
    				}
                }
			?>
			</div>

			<div class="clear"></div>

		</div>

        </div>

	<?php
	}

	/**
	 * Parse the demos list API
	 */
    public function get_demo_list( $type = 'demos', $url = 'http://demo.phlox.pro/api/v2/info/', $sanitize_key = 'auxin_cache_demo_library_items' ) {

        if( $type === 'templates' ){
            $url          = 'http://library.phlox.pro/info-api/';
            $sanitize_key = 'auxin_cache_template_library_items';
        }

    	$key = sanitize_key( $sanitize_key );

        if ( ( false === $data = auxin_get_transient( $key ) ) || isset( $_GET['flush_transient'] ) ) {

            $response = wp_remote_post( $url );

            if ( ! is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) === 200 ) {
                $response = wp_remote_retrieve_body( $response );
            } else {
                $error_message = "Something went wrong while connecting ($url): " . $response->get_error_message();
                echo '<div class="aux-admin-info-box aux-admin-info-warn aux-admin-welcome-info">' . $error_message . '</div>';
                // if cUrl timeout error was thrown, increase the timeout to 15. Default is 5.
                if( false !== strpos( $error_message, 'cURL error 28') ){
                    set_theme_mod('increasing_curl_timeout_is_required', 15);
                }

                if ( wp_doing_ajax() ){
                    die();
                } else {
                    return;
                }
            }

            // translate the JSON into Array
            $data = json_decode( $response, true );

            if( ! is_array( $data ) ){
                if ( wp_doing_ajax() ){
                    die();
                } else {
                    return;
                }
            }

            // Add transient
            auxin_set_transient( $key, $data, 24 * HOUR_IN_SECONDS );
        }

        return $data;
    }


	/*-----------------------------------------------------------------------------------*/
	/*  Step setup_updates
    /*-----------------------------------------------------------------------------------*/

    public function setup_updates(){
        $last_update  = auxin_get_update_list();

        $has_response = isset( $last_update->total_updates ) ? $last_update->total_updates : false;
    ?>
        <div class="aux-setup-content">
            <div class="aux-updates-step aux-section-content-box">

            <?php
                if( ! $has_response ){
            ?>
                <h3 class="aux-content-title"><?php _e('You have already the latest version.', 'auxin-elements' ); ?></h3>
            <?php
                } else {
            ?>
                <h3 class="aux-content-title"><?php _e('New updates are available.', 'auxin-elements' ); ?></h3>
                <p><?php esc_html_e( 'The following items require update, click update button to update them to the latest version.', 'auxin-elements' ); ?></p>
            <?php
                }
            ?>
                <div class="aux-fadein-animation">
                    <?php
                        if( ! $has_response ){
                            $this->display_alerts( sprintf(
                                '%s %s %s',
                                esc_html__( 'Last checked ', 'auxin-elements'  ),
                                $last_update->last_checked,
                                esc_html__( 'ago', 'auxin-elements' )
                            ) , 'success' );
                        } else {
                            echo '<div class="aux-update-items">';

                            if( isset( $last_update->themes ) && is_array( $last_update->themes ) ) {
                            ?>
                                <ul class="aux-update-list aux-update-themes">
                            <?php
                                foreach ( $last_update->themes as $stylesheet => $args ) {
                                    $theme = wp_get_theme( $stylesheet );
                                ?>
                                    <li class="aux-item" data-key="<?php echo esc_attr( $stylesheet ); ?>" data-type="themes">
                                        <label class="aux-control">
                                            <?php echo esc_html( $theme->get( 'Name' ) ); ?>
                                            <div class="aux-indicator"></div>
                                            <input name="theme[]" value="<?php echo esc_attr( $stylesheet ); ?>" type="hidden">
                                        </label>
                                        <div class="aux-status column-status">
                                            <span class="update">
                                                <?php echo esc_html__( 'Ready to update', 'auxin-elements' ); ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php
                                }
                            ?>
                                </ul>
                            <?php
                            }

                            if( isset( $last_update->plugins ) && is_array( $last_update->plugins ) ) {
                            ?>
                                <div class="aux-headers">
                                    <span>Plugin Name</span>
                                    <span>Status</span>
                                </div>
                                <ul class="aux-update-list aux-update-plugins">
                            <?php
                                foreach ( $last_update->plugins as $path => $args ) {
                                    $plugin = get_plugin_data( WP_PLUGIN_DIR . '/' . $path );
                                ?>
                                    <li class="aux-item" data-key="<?php echo esc_attr( $path ); ?>" data-type="plugins">
                                        <label class="aux-control">
                                            <?php echo esc_html( $plugin['Name'] ); ?>
                                            <div class="aux-indicator"></div>
                                            <input name="plugin[]" value="<?php echo esc_attr( $path ); ?>" type="hidden">
                                        </label>
                                        <div class="aux-status column-status">
                                            <span class="update">
                                                <?php echo esc_html__( 'Ready to update', 'auxin-elements' ); ?>
                                            </span>
                                        </div>
                                    </li>
                                <?php
                                }
                            ?>
                                </ul>
                            <?php
                            }

                            echo '</div>';
                        }

                    ?>
                </div>
                <div class="aux-sticky">
                    <div class="aux-setup-actions step">
                    <?php if( $has_response ){ ?>
                        <a href="#"
                            class="aux-button aux-primary aux-install-updates" data-nonce="<?php echo esc_attr( wp_create_nonce( 'auxin-start-upgrading' ) ); ?>"><?php esc_html_e( 'Update Now', 'auxin-elements' ); ?></a>
                    <?php } ?>
                        <a href="<?php echo self_admin_url( 'admin.php?page=auxin-welcome&tab=updates&force-check=1' ); ?>"
                            class="aux-button aux-outline"><?php esc_html_e( 'Check Again', 'auxin-elements' ); ?></a>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    <?php
    }

	/*-----------------------------------------------------------------------------------*/
	/*  Step manager in modal
	/*-----------------------------------------------------------------------------------*/

    /**
     * This function will removing the last imported demo
     */
    public function ajax_uninstall(){
        // Check Security Token
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aux-demo-uninstall' ) ) {
            // This nonce is not valid.
            wp_send_json_error( esc_html__( 'Security Token Error!', 'auxin-elements' ) );
        }

        // Checking the Active Demo ID with requested one
        $last_demo  = get_option( 'auxin_last_imported_demo' );
        $request_id = isset( $_POST['id'] ) ? $_POST['id'] : false;
        if( ! $request_id || ! $last_demo || $last_demo['id'] != $request_id  ) {
            wp_send_json_error( esc_html__( 'You can\'t remove this demo.', 'auxin-elements' ) );
        }

        // call WPDB class instance
        global $wpdb;

        // Remove Attachments
        $attachments = get_posts( array(
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => 'inherit',
            'meta_key'       => 'auxin_import_id'
        ) );
        if ( $attachments ) {
            foreach ( $attachments as $attachment ) {
                wp_delete_attachment( $attachment->ID, true );
            }
        }

        // Remove Posts
        $posts = get_posts( array(
            'post_type'      => 'any',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'meta_key'       => 'auxin_import_post'
        ) );
        if ( $posts ) {
            foreach ( $posts as $post ) {
                wp_delete_post( $post->ID, true );
            }
        }

        // Remove Menus
        $menus = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE '%auxin_demo_importer_menu_origin_id_%'", OBJECT );
        foreach ($menus as $key => $menu) {
            if( is_numeric( $menu->option_value ) ) {
                wp_delete_nav_menu( $menu->option_value );
            }
        }

        // Remove Options
        delete_option( THEME_ID.'_theme_options' );
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', '0' );
        remove_theme_mod('custom_css_string');
        remove_theme_mod('custom_css_array');
        remove_theme_mod('custom_logo');
        remove_theme_mod('font_subsets');

        // Remove all active widgets
        update_option( 'sidebars_widgets', array( 'array_version' => 3 ) );

        // Disable all demo plugins other than auxin plugins...
        $demo_plugins   = isset( $_POST['plugins'] ) ? $_POST['plugins'] : array();
        if( ! empty( $demo_plugins ) ) {
            $active_plugins = get_option( 'active_plugins' );
            foreach ( $active_plugins as $key => $name ) {
                if( auxin_strposa( $name, $demo_plugins ) && strpos( $name, 'auxin' ) === false ){
                    unset( $active_plugins[$key] );
                }
            }
            update_option( 'active_plugins', $active_plugins );
        }

        // Remove Additional CSS data
        wp_update_custom_css_post( NULL );

        // Stores css content in custom css file
        auxin_save_custom_css();
        // Stores JavaScript content in custom js file
        auxin_save_custom_js();

        // And finally remove the last imported demo info
        delete_option( 'auxin_last_imported_demo' );

        ob_start();
        ?>
            <div class="aux-setup-demo-content aux-content-col aux-step-import-completed">
                <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/completed.svg' ); ?>" />
                <div><h2 class="aux-step-import-title"><?php esc_html_e( 'Done!', 'auxin-elements' ); ?></h2></div>
                <p class="aux-step-description"><?php esc_html_e( "Demo has been successfully uninstalled.", 'auxin-elements' ); ?></p>
            </div>
            <div class="aux-setup-demo-actions">
                <div class="aux-return-back">
                    <a href="<?php echo home_url(); ?>" class="aux-button aux-round aux-green aux-medium" target="_blank">
                        <?php _e( 'Preview', 'auxin-elements' ); ?>
                    </a>
                    <a href="#" class="aux-button aux-outline aux-round aux-transparent aux-medium aux-pp-close">
                        <?php _e( 'Close', 'auxin-elements' ); ?>
                    </a>
                </div>
            </div>
        <?php

        // Return Success Notification
        wp_send_json_success( array(
            'button' => __( 'Import', 'auxin-elements' ),
            'url'    => add_query_arg( array( 'action' => 'aux_ajax_lightbox', 'key' => $_POST['key'], 'type' => 'install' , 'nonce' => wp_create_nonce( 'aux-open-lightbox' ) ), admin_url( 'admin-ajax.php' ) ),
            'markup' => ob_get_clean(),
        ) );
    }

    /**
     * Ajax modal box
     */
	public function ajax_lightbox() {

        if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], 'aux-open-lightbox' ) ) {
            // This nonce is not valid.
            wp_die( esc_html__( 'Security Token Error!', 'auxin-elements' ) );
        }

        $type = isset( $_GET['type'] ) ? $_GET['type'] : 'progress';

        ob_start();

        if( $type == 'progress' ) {
            echo sprintf( '<div class="aux-template-lightbox"><div class="aux-modal-item aux-default-modal clearfix aux-steps-col">%s</div></div>', $this->progress_step( array(), '' ) );
            wp_die( ob_get_clean() );
        }

        if( $type == 'preview' ) {
            if( isset( $_GET['preview'] ) ){
                echo sprintf( '<div class="aux-template-lightbox aux-preview-lightbox"><div class="clearfix"><img class="aux-preview-image" src="%s" /><div class="aux-template-actions"><a href="#" class="aux-button aux-medium aux-outline aux-transparent aux-pp-close">%s</a></div></div></div>', $_GET['preview'], __( 'Close', 'auxin-elements' ) );
                wp_die( ob_get_clean() );
            }
        }

        if( $type == 'plugins' ) {
            $data    = $this->get_demo_list( 'templates' );
            $args    = $data['templates'][ $_GET['key'] ];
            $args    = array(
                'plugins'     => $args['plugins'],
                'next_action' => 'template_manager'
            );
            echo sprintf( '<div class="aux-template-lightbox"><div class="aux-modal-item aux-default-modal clearfix aux-has-required-plugins aux-steps-col">%s</div></div>', $this->second_step( $args, '5' ) );
            wp_die( ob_get_clean() );
        }

		$data = $this->get_demo_list();

		if( ! isset( $_GET['key'] ) || empty( $data['items'] ) || ! array_key_exists( $_GET['key'] , $data['items'] ) ) {
			wp_die( esc_html__( 'An Error Occurred!', 'auxin-elements' ) );
		}

		$args = $data['items'][ $_GET['key'] ];

        if(  $type == 'install' ) :
	?>
		<div id="demo-<?php echo esc_attr( $args['id'] ); ?>" class="aux-demo-lightbox">
			<div class="aux-modal-item clearfix aux-has-required-plugins">
				<div class="grid_5 no-gutter aux-media-col" style="background-image: url(<?php echo esc_url( $args['screen'] ); ?>);" >
				</div>
				<div class="grid_7 no-gutter aux-steps-col">
					<div class="aux-setup-demo-content aux-content-col aux-step-import-notice">
                        <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/import-notice.svg' ); ?>" />
                        <div><h2 class="aux-step-import-title aux-iconic-title"><?php esc_html_e( 'Notice', 'auxin-elements' ); ?></h2></div>
                        <p class="aux-step-description">
                        <?php esc_html_e( "For better and faster result, it's recommended to install the demo on a clean WordPress website.", 'auxin-elements' ); ?>
                        </p>
					</div>
					<div class="aux-setup-demo-actions">
						<div class="aux-return-back">
                            <a href="#" data-next-step="2" class="aux-button aux-next-step aux-primary aux-medium" data-args="<?php echo htmlspecialchars( wp_json_encode( $args ), ENT_QUOTES, 'UTF-8' ); ?>" data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>">
                            	<?php _e( 'Continue', 'auxin-elements' ); ?>
                       		</a>
                            <a href="#" class="aux-button aux-outline aux-round aux-transparent aux-medium aux-pp-close">
                            	<?php _e( 'Cancel', 'auxin-elements' ); ?>
                       		</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
        else :
    ?>
        <div id="demo-<?php echo esc_attr( $args['id'] ); ?>" class="aux-demo-lightbox">
            <div class="aux-modal-item clearfix aux-has-required-plugins">
                <div class="grid_5 no-gutter aux-media-col" style="background-image: url(<?php echo esc_url( $args['screen'] ); ?>);" >
                </div>
                <div class="grid_7 no-gutter aux-steps-col">
                    <div class="aux-setup-demo-content aux-content-col aux-step-import-notice">
                        <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/warning.png' ); ?>" />
                        <div><h2 class="aux-step-import-title aux-iconic-title"><?php esc_html_e( 'Warning!', 'auxin-elements' ); ?></h2></div>
                        <p class="aux-step-description">
                        <?php esc_html_e( "This process will erase all images, posts and settings of this demo...", 'auxin-elements' ); ?>
                        </p>
                    </div>
                    <div class="aux-setup-demo-actions">
                        <div class="aux-return-back">
                            <a href="#" class="aux-button aux-uninstall-demo aux-red aux-medium" data-demo-plugins="<?php echo htmlspecialchars( wp_json_encode( $args['plugins'] ), ENT_QUOTES, 'UTF-8' ); ?>" data-demo-id="<?php echo $args['id']; ?>" data-demo-nonce="<?php echo wp_create_nonce( 'aux-demo-uninstall' ); ?>" data-demo-confirm="<?php esc_html_e( 'Are you sure you want to uninstall this demo?', 'auxin-elements' ); ?>">
                                <?php _e( 'Uninstall', 'auxin-elements' ); ?>
                            </a>
                            <a href="#" class="aux-button aux-outline aux-round aux-transparent aux-medium aux-pp-close">
                                <?php _e( 'Cancel', 'auxin-elements' ); ?>
                            </a>
                        </div>
                        <div class="aux-progress hide">
                            <div class="aux-big">
                                <div class="aux-progress-bar aux-progress-info aux-progress-active" data-percent="100" style="transition: none; width: 100%;">
                                    <span class="aux-progress-label"><?php esc_html_e( 'Please wait, this may take several minutes ..', 'auxin-elements' ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        endif;

		wp_die( ob_get_clean() );
	}


	public function step_manager() {
		$next_step = $_POST['next_step'];
		$nonce     = $_POST['nonce'];
		$args      = $_POST['args'];

		$steps     = array(
        	'1' => array(
				'method' => 'first_step',
				'next'   => '2'
        	),
        	'2' => array(
				'method' => 'second_step',
				'next'   => '6'
        	),
        	'3' => array(
				'method' => 'third_step',
				'next'   => '4'
        	),
            '4' => array(
                'method' => 'fourth_step',
                'next'   => ''
            ),
            '5' => array(
                'method' => 'progress_step',
                'next'   => ''
            ),
            '6' => array(
                'method' => 'remove_watermark_step',
                'next'   => '7'
            ),
            '7' => array(
                'method' => 'envato_elements_step',
                'next'   => '3'
            ),
            '8' => array(
                'method' => 'get_user_email_step',
                'next'   => '9'
            ),
            '9' => array(
                'method' => 'verify_envato_elements_step',
                'next'   => '10'
            ),
            '10' => array(
                'method' => 'envato_elements_success_step',
                'next'   => '3'
            )

        );

        if ( ! wp_verify_nonce( $nonce, 'aux-step-manager' ) ) {
            // This nonce is not valid.
            wp_send_json_error( esc_html__( 'An error occurred!', 'auxin-elements' ) );
        } elseif( ! $next_step || $steps[$next_step]['method'] == '' ){
        	wp_send_json_error( esc_html__( 'Method not exist!', 'auxin-elements' ) );
        }

		wp_send_json_success(
			array(
				'markup' => call_user_func( array( $this, $steps[$next_step]['method'] ), $args, $steps[$next_step]['next'] )
			)
		);
	}

	public function first_step( array $args, $next_step ) {
		ob_start();
		?>
			<div class="aux-setup-demo-content aux-content-col">
			    <h2><?php esc_html_e( 'Required Plugins for this demo.', 'auxin-elements' ); ?></h2>
                <p class="aux-step-description">
                <?php esc_html_e( "For better and faster install process it's recommended to install demo on a clean WordPress website.", 'auxin-elements' ); ?>
                </p>
			</div>
			<div class="aux-setup-demo-actions">
				<div class="aux-return-back">
                    <a href="#" data-next-step="<?php echo esc_attr( $next_step ); ?>" class="aux-button aux-next-step aux-primary aux-medium" data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>" data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>">
                    	<?php _e( 'Continue', 'auxin-elements' ); ?>
               		</a>
                    <a href="#" class="aux-button aux-outline aux-round aux-transparent aux-medium aux-pp-close">
                    	<?php _e( 'Cancel', 'auxin-elements' ); ?>
               		</a>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}

	public function second_step( array $args, $next_step ) {

        // Goto next step, if no required plugins found
        if( ! isset( $args['plugins'] ) ) {
            return call_user_func( array( $this, 'remove_watermark_step' ), $args, '7' );
        }

        $plugins_list = json_decode( stripslashes( $args['plugins'] ),  true );

		$plugins = $this->get_plugins( $plugins_list );
		$has_plugin_required = ! empty( $plugins_list ) && ! empty( $plugins['all'] );

        if( $has_plugin_required ) :
            $next_step = Auxels_Envato_Elements::get_instance()->is_envato_element_enabled() ? '4' : $next_step;
			ob_start();
		?>
				<div class="aux-setup-demo-content aux-content-col aux-install-plugins">
			        <h2><?php esc_html_e( 'Required Plugins for this demo.', 'auxin-elements' ); ?></h2>
					<p class="aux-step-description"><?php esc_html_e( 'The following plugins are required to be installed for this demo.', 'auxin-elements' ); ?></p>
					<ul class="aux-wizard-plugins">
					<?php
					foreach ( $plugins['all'] as $slug => $plugin ) { ?>
						<li class="aux-plugin" data-slug="<?php echo esc_attr( $slug ); ?>">
							<label class="aux-control aux-checkbox">
								<?php echo esc_html( $plugin['name'] ); ?>
								<input name="plugin[]" value="<?php echo esc_attr($slug); ?>" type="checkbox" checked>
								<div class="aux-indicator"></div>
							</label>
				            <div class="status column-status">
							<?php
							    $keys = $class = '';
							    if ( isset( $plugins['install'][ $slug ] ) ) {
								    $keys 	= esc_html__( 'Ready to install', 'auxin-elements' );
								    $class  = 'install';
							    }
							    if ( isset( $plugins['activate'][ $slug ] ) ) {
								    $keys 	= esc_html__( 'Not activated', 'auxin-elements' );
								    $class  = 'activate';
							    }
							    if ( isset( $plugins['update'][ $slug ] ) ) {
								    $keys 	= esc_html__( 'Ready to update', 'auxin-elements' );
								    $class  = 'update';
							    }
						    ?>
								<span class="<?php echo $class ?>">
									<?php echo $keys; ?>
								</span>
								<div class="spinner"></div>
				            </div>
						</li>
					<?php
					}
					?>
					</ul>
				</div>
				<div class="aux-setup-demo-actions">
					<div class="aux-return-back">
						<a 	href="#"
							class="aux-button aux-medium install-plugins aux-primary"
							data-callback="install_plugins"
							data-next-step="<?php echo esc_attr( $next_step ); ?>"
							data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>"
                            data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>"
							data-next-action="<?php echo isset( $args['next_action'] ) ? $args['next_action'] : false; ?>"
						><?php esc_html_e( 'Install Plugins', 'auxin-elements' ); ?></a>
	                    <a href="#" class="aux-button aux-outline aux-round aux-transparent aux-medium aux-pp-close">
	                    	<?php _e( 'Cancel', 'auxin-elements' ); ?>
	               		</a>
					</div>
				</div>
		<?php
			return ob_get_clean();
        else :
            return Auxels_Envato_Elements::get_instance()->is_envato_element_enabled() ? call_user_func( array( $this, 'third_step' ), $args, '4' ) : call_user_func( array( $this, 'remove_watermark_step' ), $args, '7' ) ;
			return ;
		endif;
	}

	public function third_step( array $args, $next_step ) {
		ob_start();
		?>
			<div class="aux-setup-demo-content aux-content-col aux-install-demos">
				<h2><?php esc_html_e( 'Import Demo Content of Phlox Theme.' ); ?></h2>

				<form id="aux-import-data-<?php echo esc_attr( $args['id'] ); ?>" class="aux-import-parts">
					<div class="complete aux-border is-checked">
					    <label class="aux-control aux-radio">
					    	<?php esc_html_e( 'Complete pre-build Website', 'auxin-elements' ); ?>
					      	<input type="radio" name="import" value="complete" checked="checked" />
					      	<div class="aux-indicator"></div>
					    </label>
					    <label class="aux-control aux-checkbox">
					    	<?php esc_html_e( 'Import media (images, videos, etc.)', 'auxin-elements' ); ?>
					      	<input type="checkbox" name="import-media" checked="checked" />
					      	<div class="aux-indicator"></div>
					    </label>
					</div>
					<div class="custom aux-border">
					    <label class="aux-control aux-radio">
					    	<?php esc_html_e( 'Selected Data Only', 'auxin-elements' ); ?>
					      	<input type="radio" name="import" value="custom" />
					      	<div class="aux-indicator"></div>
					    </label>
						<div class="one_half no-gutter">
						    <label class="aux-control aux-checkbox">
						    	<?php esc_html_e( 'Posts/Pages', 'auxin-elements' ); ?>
						      	<input type="checkbox" name="posts" />
						      	<div class="aux-indicator"></div>
						    </label>
					    	<label class="aux-control aux-checkbox">
						    	<?php esc_html_e( 'Media', 'auxin-elements' ); ?>
						      	<input type="checkbox" name="media" />
						      	<div class="aux-indicator"></div>
						    </label>
					    	<label class="aux-control aux-checkbox">
						    	<?php esc_html_e( 'Widgets', 'auxin-elements' ); ?>
						      	<input type="checkbox" name="widgets" />
						      	<div class="aux-indicator"></div>
						    </label>
			    		</div>
			    		<div class="one_half no-gutter right-half">
					    	<label class="aux-control aux-checkbox">
						    	<?php esc_html_e( 'Menus', 'auxin-elements' ); ?>
						      	<input type="checkbox" name="menus" />
						      	<div class="aux-indicator"></div>
						    </label>
					    	<label class="aux-control aux-checkbox">
						    	<?php esc_html_e( 'Theme Options', 'auxin-elements' ); ?>
						      	<input type="checkbox" name="options" />
						      	<div class="aux-indicator"></div>
						    </label>
					    	<label class="aux-control aux-checkbox">
						    	<?php esc_html_e( 'MasterSlider (If Available)', 'auxin-elements' ); ?>
						      	<input type="checkbox" name="masterslider" />
						      	<div class="aux-indicator"></div>
						    </label>
			    		</div>
					</div>
				</form>
			</div>
            <div class="aux-setup-demo-content aux-content-col aux-install-demos-waiting hide">
                <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/importing-cloud.svg' ); ?>" />
                <h2><?php esc_html_e( 'Importing Demo Content is in Progress...' ); ?></h2>
                <p class="aux-step-description"><?php esc_html_e( 'This process may take 5 to 10 minutes to complete, please do not close or refresh this page.', 'auxin-elements' ); ?></p>
            </div>
			<div class="aux-setup-demo-actions">
				<div class="aux-return-back">
					<a 	href="#"
						class="aux-button aux-install-demo aux-medium aux-primary button-next"
						data-nonce="<?php echo wp_create_nonce( 'aux-import-demo-' . $args['id'] ); ?>"
						data-import-id="<?php echo esc_attr( $args['id'] ); ?>"
						data-callback="install_demos"
						data-next-step="<?php echo esc_attr( $next_step ); ?>"
						data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>"
						data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>"
					><?php esc_html_e( 'Import Content', 'auxin-elements' ); ?></a>
                    <a href="#" class="aux-button aux-outline aux-round aux-transparent aux-medium aux-pp-close">
                    	<?php _e( 'Cancel', 'auxin-elements' ); ?>
               		</a>
				</div>
				<div class="aux-progress hide">
					<div class="aux-big">
						<div class="aux-progress-bar aux-progress-info aux-progress-active" data-percent="100" style="transition: none; width: 100%;">
							<span class="aux-progress-label"><?php esc_html_e( 'Please wait, this may take several minutes ..', 'auxin-elements' ); ?></span>
						</div>
					</div>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}

    public function fourth_step( array $args, $next_step ) {
        ob_start();
        ?>
            <div class="aux-setup-demo-content aux-content-col aux-step-import-completed">
                <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/completed.svg' ); ?>" />
                <div><h2 class="aux-step-import-title"><?php esc_html_e( 'Congratulations!' ); ?></h2></div>
                <p class="aux-step-description"><?php esc_html_e( "Demo has been successfully imported.", 'auxin-elements' ); ?></p>
            </div>
            <div class="aux-setup-demo-actions">
                <div class="aux-return-back">
                    <a href="<?php echo self_admin_url('customize.php'); ?>" class="aux-button aux-primary aux-medium" target="_blank">
                        <?php _e( 'Customize', 'auxin-elements' ); ?>
                    </a>
                    <a href="<?php echo home_url(); ?>" class="aux-button aux-round aux-green aux-medium" target="_blank">
                        <?php _e( 'Preview', 'auxin-elements' ); ?>
                    </a>
                    <a href="#" class="aux-button aux-outline aux-round aux-transparent aux-medium aux-pp-close">
                        <?php _e( 'Close', 'auxin-elements' ); ?>
                    </a>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }

	public function progress_step( array $args, $next_step ) {
		ob_start();
		?>
            <h3 class="aux-loading-title"><?php esc_html_e( 'Importing page content ...', 'auxin-elements' ); ?></h3>
            <div class="aux-progress">
                <div class="aux-big">
                    <div class="aux-progress-bar aux-progress-info aux-progress-active" data-percent="100" style="transition: none; width: 100%;"></div>
                </div>
            </div>
		<?php
		return ob_get_clean();
    }

    public function remove_watermark_step( array $args, $next_step ) {
        ob_start();
        ?>
            <div class="aux-setup-demo-content aux-content-col aux-step-remove-watermark">
                <div class="aux-watermark">
                    <div class="locked">
                        <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/watermark-2.png' ); ?>" />
                        <img class="lock-icon" src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/lock-icon.svg' ); ?>" />
                    </div>
                    <div class="unlocked">
                        <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/watermark.png' ); ?>" />
                        <img class="check-icon" src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/check.svg' ); ?>" />
                    </div>
                </div>
                <h2 class="aux-step-import-title"><?php esc_html_e( 'Remove Watermarks?', 'auxin-elements' ); ?></h2>
                <p class="aux-step-description"><?php esc_html_e( "Some images in this demo are copyrighted and watermarked, you can remove watermarks by authorizing your Envato Elements subscription.", 'auxin-elements' ); ?></p>
                <?php
                $skip_watermark_step = '3';
                ?>
            </div>
            <div class="aux-setup-demo-actions">
                <div class="aux-return-back">
                    <a 	href="#"
                        class="aux-button aux-medium aux-primary aux-next-step"
                        data-callback="envato_elements_step"
                        data-next-step="<?php echo esc_attr( $next_step ); ?>"
                        data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>"
                        data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>"
                        data-next-action="<?php echo isset( $args['next_action'] ) ? $args['next_action'] : false; ?>"
                    ><?php esc_html_e( 'Remove Watermarks', 'auxin-elements' ); ?></a>
                    <a href="#" data-next-step="<?php echo esc_attr( $skip_watermark_step ); ?>" class="aux-button aux-next-step aux-outline aux-round aux-transparent aux-medium aux-skip" data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>" data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>">
                    	<?php _e( 'Skip', 'auxin-elements' ); ?>
               		</a>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }

    public function envato_elements_step( array $args, $next_step ) {
        ob_start();
        ?>
            <div class="aux-setup-demo-content aux-content-col aux-step-envato-elements">
                <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/envato_elements.svg' ); ?>" />
                <h2 class="aux-step-import-title"><?php esc_html_e( 'Remove watermarks by authorizing your Envato Elements subscription', 'auxin-elements' ); ?></h2>
                <p class="aux-step-description"><?php esc_html_e( "By subscribing to Envato Elements you will have access to unlimited premium stock images, icons, graphical assets, videos and more.", 'auxin-elements' ); ?></p>
                <a href="http://avt.li/elements" class="aux-button aux-primary aux-medium aux-explore-envato" target="_blank">
                    <?php _e( 'Explore and Subscribe', 'auxin-elements' ); ?>
                </a>
                <?php
                $activate_step = '8';
                if ( ! empty( get_option( 'phlox_envato_elements_license_code', '' ) ) ) {
                    $activate_step = '9';
                }
                ?>
                <p><?php esc_html_e( 'Already an Envato Elements member?', 'auxin-elements' ); ?> <a href="#" class="aux-next-step" data-next-step="<?php echo esc_attr( $activate_step ); ?>" data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>" data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>"><?php esc_html_e( 'Activate here', 'auxin-elements' );?></a> </p>
            </div>
            <div class="aux-setup-demo-actions">
                <div class="aux-return-back">
                    <a href="#" data-next-step="<?php echo esc_attr( $next_step ); ?>" class="aux-button aux-next-step aux-outline aux-round aux-transparent aux-medium" data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>" data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>">
                    	<?php _e( 'Skip', 'auxin-elements' ); ?>
               		</a>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }

    public function get_user_email_step( array $args, $next_step) {
        ob_start();
        ?>
            <div class="aux-setup-demo-content aux-content-col aux-step-envato-elements aux-step-verify-envato-elements">
                <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/activation.svg' ); ?>" />
                <h2 class="aux-step-import-title"><?php esc_html_e( 'Verify Your Envato Elements Subscription', 'auxin-elements' ); ?></h2>
                <p class="aux-step-description"><?php esc_html_e( "Enter your email below and click continue button", 'auxin-elements' ); ?></p>
                <div class="token-wrapper">
                    <input type="text" class="email-field" placeholder="<?php esc_attr_e( 'Enter your email', 'auxin-elements' ); ?>">
                    <p class="result"></p>
                </div>
            </div>
            <div class="aux-setup-demo-actions">
                <div class="aux-return-back">
                <a 	href="#"
						class="aux-button aux-medium aux-primary aux-verify-elements-email"
                        data-callback="get_license_code"
                        data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>"
						data-next-step="<?php echo esc_attr( $next_step ); ?>"
						data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>"
					><?php esc_html_e( 'Continue', 'auxin-elements' ); ?></a>
                    <a href="#" class="aux-button aux-outline aux-round aux-transparent aux-medium aux-pp-close">
                    	<?php _e( 'Cancel', 'auxin-elements' ); ?>
               		</a>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }

    public function verify_envato_elements_step( array $args, $next_step) {
        ob_start();
        ?>
            <div class="aux-setup-demo-content aux-content-col aux-step-envato-elements aux-step-verify-envato-elements">
                <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/activation.svg' ); ?>" />
                <h2 class="aux-step-import-title"><?php esc_html_e( 'Verify Your Envato Elements Subscription', 'auxin-elements' ); ?></h2>
                <p class="aux-step-description"><?php esc_html_e( "Enter your token below to verify your Subscription", 'auxin-elements' ); ?></p>
                <div class="token-wrapper">
                    <input type="text" class="token-field" placeholder="<?php esc_attr_e( 'Enter token here', 'auxin-elements' ); ?>">
                    <p class="result"></p>
                </div>
                <div class="aux-info-links">
                    <?php
                        $token_link = '#';
                        $extension_id = get_option( 'phlox_envato_elements_license_code', '' );
                        if ( ! empty( $extension_id ) ) {
                            $extension_description = get_bloginfo( 'name' ) . ' (' . get_home_url() . ')';
                            $token_link = 'https://api.extensions.envato.com/extensions/begin_activation?extension_id=' . $extension_id . '&extension_type=envato-wordpress&extension_description=' . $this->encode_url_parameter( $extension_description ) . '&utm_content=settings&irgwc=1&clickid=1bPRxZzJ-xyOR7DwUx0Mo3EUUkiwuR1BCVsiXk0&iradid=298927&utm_campaign=elements_af_1264782&iradtype=ONLINE_TRACKING_LINK&irmptype=mediapartner&utm_medium=affiliate&utm_source=impact_radius&mp=averta';
                        }
                    ?>
                    <a href="<?php echo esc_url( $token_link ); ?>" target="_blank" class="aux-generate-token" ><?php esc_html_e( 'How to generate a token ?', 'auxin-elements' );?></a>
                    <a href="http://avt.li/elements" target="_blank" class="aux-subscription" ><?php esc_html_e( 'Don\'t have subscription?', 'auxin-elements' );?></a>
                </div>
            </div>
            <div class="aux-setup-demo-actions">
                <div class="aux-return-back">
                <a 	href="#"
						class="aux-button aux-medium aux-primary button-next aux-verify-elements-token"
						data-next-step="<?php echo esc_attr( $next_step ); ?>"
						data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>"
						data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>"
					><?php esc_html_e( 'Verify Token', 'auxin-elements' ); ?></a>
                    <a href="#"
                        class="aux-button aux-outline aux-round aux-transparent aux-medium aux-next-step aux-skip"
                        data-next-step="3"
						data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>"
						data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' );?>">
                    	<?php _e( 'Skip', 'auxin-elements' ); ?>
               		</a>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }

    public function envato_elements_success_step( array $args, $next_step) {
        ob_start();
        ?>
            <div class="aux-setup-demo-content aux-content-col aux-step-envato-elements-success aux-step-import-notice">
                <img src="<?php echo esc_url( AUXELS_ADMIN_URL . '/assets/images/welcome/completed.svg' ); ?>" />
                <h2 class="aux-step-import-title"><?php esc_html_e( 'Succeed', 'auxin-elements' ); ?></h2>
                <p class="aux-step-description"><?php esc_html_e( "Congratulations! you have successfully authorized your Envato Elements subscription.", 'auxin-elements' ); ?></p>
            </div>
            <div class="aux-setup-demo-actions">
                <div class="aux-return-back">
                <a 	href="#"
						class="aux-button aux-medium aux-primary button-next aux-next-step"
						data-next-step="<?php echo esc_attr( $next_step ); ?>"
						data-args="<?php echo htmlspecialchars( wp_json_encode($args), ENT_QUOTES, 'UTF-8' ); ?>"
						data-step-nonce="<?php echo wp_create_nonce( 'aux-step-manager' ); ?>"
					><?php esc_html_e( 'Continue', 'auxin-elements' ); ?></a>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }

    private function encode_url_parameter( $parameter ) {
		$parameter = html_entity_decode( $parameter, ENT_QUOTES | ENT_XML1, 'UTF-8' );
		$parameter = str_replace( '#', '', $parameter );

		return urlencode( $parameter );
	}
}
