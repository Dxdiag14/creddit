<?php

if( ! class_exists( 'Auxin_Plugin_Requirements' ) ){

    /**
     * Checks the requirements for a plugin
     *
     */
    class Auxin_Plugin_Requirements {

        /**
         * An array containing the list of requirements
         *
         * @var array
         */
        public  $requirements = array();

        /**
         * Whether the requirements are available or not
         *
         * @var boolean
         */
        private $requirements_passed = true;

        /**
         * Collects error notices
         *
         * @var array
         */
        public $admin_notices = array();



        function __construct(){
            global $auxin_plugins_dependency_map;

            if( empty( $auxin_plugins_dependency_map ) ){
                $auxin_plugins_dependency_map = array();
            }

            if( is_admin() ){
                add_action( 'admin_notices'     , array( $this, 'admin_notices' ) );
                add_action( 'activated_plugin'  , array( $this, 'update_plugins_dependencies' ) );
            } else {
                add_action( 'auxin_after_inner_body_open', array( $this, 'front_notices' ) );
            }

        }

        /**
         * Make sure the client has the requirements, otherwise, throw a notice in admin
         *
         * @return void
         */
        public function admin_notices( $pop_notice =  '' ){
            if( $this->admin_notices = array_filter( $this->admin_notices ) ) {
                echo '<div class="error aux-admin-error">';
                echo $this->get_notices( $pop_notice );
                echo '</div>';
            }
        }

        /**
         * Make sure the client has the requirements, otherwise, throw a notice
         *
         * @return void
         */
        public function get_notices( $pop_notice =  '' ){
            $the_notice = '';

            if( $this->admin_notices && $notices = implode( '</li><li>', $this->admin_notices ) ) {
                $the_notice .= '<p>' . $pop_notice;
                $the_notice .= sprintf(
                    __( '%s plugin has been disabled automatically due to following reason:', 'auxin-elements' ),
                    '<strong>'. $this->requirements['config']['plugin_name'] . '</strong>'
                );
                $the_notice .= '<ul><li>'. $notices . '</li></ul></p>';
            }

            if( $this->requirements['config']['debug'] || ( defined( 'AUXIN_DEBUG' ) && AUXIN_DEBUG ) ){
                $active_plugins = get_option( 'active_plugins' );
                $the_notice .= "<pre>"; $the_notice .= print_r( $active_plugins, true ); $the_notice .= "</pre>";
            }

            return $the_notice;
        }

        /**
         * Make sure the client has the requirements, otherwise, throw a notice in frontend for administrator
         *
         * @return void
         */
        public function front_notices(){
            $this->admin_notices = array_filter( $this->admin_notices );

            if( $this->admin_notices && current_user_can( 'edit_theme_options' ) ) {
                $pop_notice = '<strong>' . __( 'Note for admin', 'auxin-elements' ) . '</strong>:  ';
                echo '<div class="aux-front-error aux-front-notice aux-fold">';
                echo $this->get_notices( $pop_notice );
                echo '</div>';
            }
        }

        /**
         * Wrapper around the core WP get_plugins function, making sure it's actually available.
         *
         * @return array Array of installed plugins with plugin information.
         */
        public function get_plugins() {
            if ( ! function_exists( 'get_plugins' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }

            return get_plugins();
        }

        /**
         * Check whether a plugin is active.
         *
         * @param string $plugin Base plugin path from plugins directory.
         *
         * @return bool True, if in the active plugins list. False, not in the list.
         */
        function is_plugin_active( $plugin ) {

            if ( ! function_exists( 'is_plugin_active' ) ) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            return is_plugin_active( $plugin );

        }

        /**
         * Load the dependency plugins before the current plugin
         *
         * @return void
         */
        function update_plugins_dependencies(){

            // Flush the rewrite rules on plugin activation
            flush_rewrite_rules();

            if( empty( $this->requirements['plugins'] ) ){
                return;
            }

            if( $plugin_requirements = $this->requirements['plugins'] ){

                global $auxin_plugins_dependency_map;

                if( ! class_exists( 'Auxin_Dependency_Sorting' ) ){
                    require_once( 'class-auxin-dependency-sorting.php' );
                }

                // Whether new dependency to dependency graph added or not
                $has_new_dependency = false;

                // Walk through the plugins and collect the dependencies
                foreach ( $plugin_requirements as $plugin_requirement ) {

                    // Make sure if the plugin is expected to be loaded prior to our main plugin
                    if( ! empty( $plugin_requirement['dependency'] ) && true == $plugin_requirement['dependency'] && $this->is_plugin_active( $plugin_requirement['basename'] ) ){
                        // Add current plugin dependencies to main plugins dependency graph
                        $auxin_plugins_dependency_map[ $this->requirements['config']['plugin_basename'] ][] = $plugin_requirement['basename'];
                        // If at least one plugin with required dependency detected
                        $has_new_dependency = true;
                    }

                }


                if( $has_new_dependency ){

                    // Sort the plugins based on the dependencies
                    $dependency_resolver = new Auxin_Dependency_Sorting();
                    $resolved_plugins_load_order = $dependency_resolver->resolve( $auxin_plugins_dependency_map );

                    // Get all activated plugins
                    $active_plugins = get_option( 'active_plugins' );

                    // Change the plugins load order
                    foreach ( $resolved_plugins_load_order as $plugin_basename ) {

                        if( ( $key = array_search( $plugin_basename, $active_plugins ) ) !== false ) {
                            unset( $active_plugins[ $key ] );
                        }
                        $active_plugins[] = $plugin_basename;
                    }

                    update_option( 'active_plugins', $active_plugins );
                }

            }

        }


        /**
         * Check plugin requirements
         *
         * @return void
         */
        function check_plugins_requirement(){

            if( empty( $this->requirements['plugins'] ) ){
                return;
            }

            if( $plugin_requirements = $this->requirements['plugins'] ){
                if ( ! wp_installing() || 'wp-activate.php' === $pagenow ) {

                    // Walk through the plugins
                    foreach ( $plugin_requirements as $plugin_requirement ) {

                        // check if the plugin is active
                        $is_plugin_active = $this->is_plugin_active( $plugin_requirement['basename'] );

                        // if activating the plugin is required
                        if(
                            ( ! empty( $plugin_requirement['required']    ) && true == $plugin_requirement['required'] && ! $is_plugin_active ) ||
                            ( ! empty( $plugin_requirement['is_callable'] ) && ! function_exists( $plugin_requirement['is_callable'] ) )
                        ){

                            $this->admin_notices[] = sprintf(
                                __( '%s plugin is required in order to use this plugin. Please install and activate the plugin.', 'auxin-elements' ).
                                ' <a href="https://docs.phlox.pro/article/210-update-phlox-pro/" target="_blank">'. __('How to upgrade', 'auxin-elements') . '</a>',
                                '<strong>'. $plugin_requirement['name'] . '</strong>'
                            );

                            $this->requirements_passed = false;

                        // if minimum plugin version was specified
                        } elseif( ! empty( $plugin_requirement['version'] ) && $is_plugin_active ){

                            $all_plugins = $this->get_plugins();

                            if( empty( $all_plugins[ $plugin_requirement['basename'] ]['Version'] ) ){
                                continue;
                            }

                            $current_plugin_version = $all_plugins[ $plugin_requirement['basename'] ]['Version'];

                            if ( version_compare( $current_plugin_version, $plugin_requirement['version'], '<' ) ) {

                                $this->admin_notices[] = sprintf(
                                    __( 'The plugin requires %s plugin version %s or higher (current version is %s). Please update it to the latest version.', 'auxin-elements' ),
                                    '<strong>'. $plugin_requirement['name'] . '</strong>',
                                    '<strong>'. $plugin_requirement['version'] . '</strong>',
                                    '<strong>'. $current_plugin_version . '</strong>'
                                );

                                $this->requirements_passed = false;
                            }

                        }

                    }

                }

            }

        }

        /**
         * Check them requirements
         *
         * @return void
         */
        function check_theme_requirement(){

            if( empty( $this->requirements['themes'] ) ){
                return;
            }

            $this->admin_notices['theme'] = '';

            if( $theme_requirements = $this->requirements['themes'] ){

                // Walk through the themes
                foreach ( $theme_requirements as $theme_requirement ) {

                    if( ! isset( $theme_requirement['id'] ) ){
                        $theme_requirement['id'] = str_replace( ' ', '-',  strtolower( $theme_requirement['name'] ) );
                    }
                    $theme_requirement = apply_filters( 'auxin_plugin_requirements_theme_dependency', $theme_requirement, $theme_requirement['id'] );

                    if ( ! empty( $theme_requirement['file_required'] ) ){
                        if( is_array( $theme_requirement['file_required'] ) ){

                            foreach ( $theme_requirement['file_required'] as $the_required_file_path ) {

                                if( file_exists( $the_required_file_path ) ){
                                    require_once( $the_required_file_path );
                                } else {
                                    $this->requirements_passed = false;
                                    continue 2;
                                }
                            }

                        } elseif( file_exists( $theme_requirement['file_required'] ) ){
                            require_once( $theme_requirement['file_required'] );
                        } else {
                            $this->requirements_passed = false;
                            continue;
                        }
                    } else {
                        $this->requirements_passed = false;
                        continue;
                    }

                    if( THEME_ID !== $theme_requirement['id'] ){
                        $this->requirements_passed = false;
                        continue;
                    }

                    if( ! empty( $theme_requirement['theme_requires_const'] ) && defined( $theme_requirement['theme_requires_const'] ) ){

                        $all_plugins = $this->get_plugins();

                        if( ! empty( $all_plugins[ $this->requirements['config']['plugin_basename'] ]['Version'] ) ){
                            $plugin_info = $all_plugins[ $this->requirements['config']['plugin_basename'] ];

                            if( version_compare( $plugin_info['Version'], constant( $theme_requirement['theme_requires_const'] ), '<' ) ){
                                $this->admin_notices['theme'] .= sprintf(
                                    __( '%s theme requires %s plugin version %s or higher in order to function property. Your current plugin version is %s, please update it to latest version.', 'auxin-elements' ).
                                    ' <a href="https://docs.phlox.pro/article/210-update-phlox-pro/" target="_blank">'. __('How to upgrade', 'auxin-elements') . '</a>',
                                    THEME_NAME_I18N,
                                    '<strong>'. $plugin_info['Name'] . '</strong>',
                                    '<strong>'. constant( $theme_requirement['theme_requires_const'] ) . '</strong>',
                                    '<strong>'. $plugin_info['Version'] . '</strong>'
                                );

                                $this->requirements_passed = false;
                                continue;
                            }
                        }
                    }

                    if ( $theme_requirement['version'] ){

                        $theme_data = wp_get_theme();
                        $theme_data = $theme_data->parent() ? $theme_data->parent() : $theme_data;

                        if ( version_compare( $theme_data->Version, $theme_requirement['version'], '<' ) ) {

                            $theme_requirement['update_anchor_start'] = ! empty( $theme_requirement['update_link'] ) ? '<a target="_blank" href="'. admin_url( $theme_requirement['update_link'] ).'">' : '';
                            $theme_requirement['update_anchor_end']   = ! empty( $theme_requirement['update_link'] ) ? '</a>' : '';

                            $this->admin_notices['theme'] .= sprintf(
                                __( 'The plugin requires %s theme version %s or higher in order to function property. Your current theme version is %s, please %s update to latest version %s.', 'auxin-elements' ).
                                ' <a href="https://docs.phlox.pro/article/210-update-phlox-pro/" target="_blank">'. __('How to upgrade', 'auxin-elements') . '</a>',
                                '<strong>'. $theme_requirement['name'] . '</strong>',
                                '<strong>'. $theme_requirement['version'] . '</strong>',
                                '<strong>'. $theme_data->Version . '</strong>',
                                $theme_requirement['update_anchor_start'],
                                $theme_requirement['update_anchor_end']
                            );

                            if( defined( 'AUXIN_DEBUG' ) && AUXIN_DEBUG ){
                                if( ! empty( $theme_requirement['file_exists'] ) ){
                                    $this->admin_notices['theme'] .= sprintf(
                                        __( '%s path while checking the availability of theme not found.', 'auxin-elements' ),
                                        '<code>'. $theme_requirement['file_exists'] . '</code>'
                                    );
                                } elseif( ! empty( $theme_requirement['is_callable'] ) ){
                                    $this->admin_notices['theme'] .= sprintf(
                                        __( '%s function callback while checking the availability of theme not found.', 'auxin-elements' ),
                                        '<code>'. $theme_requirement['file_exists'] . '</code>'
                                    );
                                }
                            }

                            $this->requirements_passed    = false;
                            continue;
                        }

                    }

                    unset( $this->admin_notices['theme'] );
                    $this->requirements_passed    = true;
                    return;
                }

                $this->requirements_passed = false;
                return;
            }

        }

        /**
         * Check PHP requirements
         *
         * @return void
         */
        function check_php_requirement(){

            if( empty( $this->requirements['php']['version'] ) ){
                return;
            }

            if ( version_compare( PHP_VERSION, $this->requirements['php']['version'], '<' ) ) {

                $this->admin_notices[] =  sprintf(
                    __( 'PHP version %s or above is required for this plugin while your the current PHP version is %s.', 'auxin-elements' ),
                    '<strong>'. $this->requirements['php']['version'] . '</strong>',
                    '<strong>'. PHP_VERSION . '</strong>'
                );

                $this->requirements_passed = false;
                return;
            }

        }

        /**
         * Checks all requirements
         *
         * @return string|boolean    True if all requirements are passed, false or error message on failure
         */
        public function validate(){

            $this->check_php_requirement();

            if( true !== $this->requirements_passed ){ return $this->requirements_passed; }

            $this->check_theme_requirement();

            if( true !== $this->requirements_passed ){ return $this->requirements_passed; }

            $this->check_plugins_requirement();

            return $this->requirements_passed;
        }

    }

}
