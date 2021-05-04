<?php
/**
 * Create HTML list of custom nav menu input items.
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
class Auxin_Walker_Nav_Menu_Back extends Walker_Nav_Menu {

    /**
     * List of custom meta fields for menu items
     *
     * @var array
     */
    protected $menu_item_fields;


    /**
     * Starts the list before the elements are added.
     *
     * @see Walker_Nav_Menu::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   Not used.
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {}


    /**
     * Ends the list of after the elements are added.
     *
     * @see Walker_Nav_Menu::end_lvl()
     *
     * @since 3.0.0
     *
     * @param string $output Passed by reference.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   Not used.
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {}


    /**
     * Start the element output.
     *
     * @see Walker_Nav_Menu::start_el()
     * @since 3.0.0
     *
     * @global int $_wp_nav_menu_max_depth
     *
     * @param string $output Passed by reference. Used to append additional content.
     * @param object $item   Menu item data object.
     * @param int    $depth  Depth of menu item. Used for padding.
     * @param array  $args   Not used.
     * @param int    $id     Not used.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

        $this->menu_item_fields = Auxin_Master_Nav_Menu::get_instance()->menu_item_fields; //@Edit

        global $_wp_nav_menu_max_depth;
        $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

        ob_start();
        $item_id = esc_attr( $item->ID );
        $removed_args = array(
            'action',
            'customlink-tab',
            'edit-menu-item',
            'menu-item',
            'page-tab',
            '_wpnonce',
        );

        $original_title = '';
        if ( 'taxonomy' == $item->type ) {
            $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
            if ( is_wp_error( $original_title ) )
                $original_title = false;
        } elseif ( 'post_type' == $item->type ) {
            $original_object = get_post( $item->object_id );
            $original_title = get_the_title( $original_object->ID );
        }

        $classes = array(
            'menu-item menu-item-depth-' . $depth,
            'menu-item-' . esc_attr( $item->object ),
            'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
        );

        $title = $item->title;

        if ( ! empty( $item->_invalid ) ) {
            $classes[] = 'menu-item-invalid';
            /* translators: %s: title of menu item which is invalid */
            $title = sprintf( __( '%s (Invalid)', 'auxin-elements' ), $item->title );
        } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
            $classes[] = 'pending';
            /* translators: %s: title of menu item in draft status */
            $title = sprintf( __('%s (Pending)', 'auxin-elements'), $item->title );
        }

        $title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;

        $submenu_text = '';
        if ( 0 == $depth )
            $submenu_text = 'style="display: none;"';

        ?>
        <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo esc_attr( implode(' ', $classes ) ); ?>">
            <div class="menu-item-bar">
                <div class="menu-item-handle">
                    <span class="item-title">
                        <span class="menu-item-title"><?php echo esc_html( $title ); ?></span>
                        <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e( 'sub item', 'auxin-elements' ); ?></span>
                    </span>
                    <span class="item-controls">
                        <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>

                        <span class="item-type aux-mm-mega-badge"><?php _e( 'Mega'  , 'auxin-elements' ); //@Edit ?></span>
                        <span class="item-type aux-mm-col-badge" ><?php _e( 'Column', 'auxin-elements' ); //@Edit ?></span>

                        <span class="item-order hide-if-js">
                            <a href="<?php
                                echo wp_nonce_url(
                                    add_query_arg(
                                        array(
                                            'action' => 'move-up-menu-item',
                                            'menu-item' => $item_id,
                                        ),
                                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                                    ),
                                    'move-menu_item'
                                );
                            ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'auxin-elements'); ?>">&#8593;</abbr></a>
                            |
                            <a href="<?php
                                echo wp_nonce_url(
                                    add_query_arg(
                                        array(
                                            'action' => 'move-down-menu-item',
                                            'menu-item' => $item_id,
                                        ),
                                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
                                    ),
                                    'move-menu_item'
                                );
                            ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'auxin-elements'); ?>">&#8595;</abbr></a>
                        </span>
                        <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item', 'auxin-elements'); ?>" href="<?php
                            echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
                        ?>"><?php _e( 'Edit Menu Item', 'auxin-elements' ); ?></a>
                    </span>
                </div>
            </div>

            <div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo $item_id; ?>">
                <?php if ( 'custom' == $item->type ) : ?>
                    <p class="field-url     `ption description-wide">
                        <label for="edit-menu-item-url-<?php echo $item_id; ?>">
                            <?php _e( 'URL', 'auxin-elements' ); ?><br />
                            <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
                        </label>
                    </p>
                <?php endif; ?>
                <p class="description description-wide">
                    <label for="edit-menu-item-title-<?php echo $item_id; ?>">
                        <?php _e( 'Navigation Label', 'auxin-elements' ); ?><br />
                        <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
                    </label>
                </p>
                <p class="field-title-attribute description description-wide">
                    <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
                        <?php _e( 'Title Attribute', 'auxin-elements' ); ?><br />
                        <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
                    </label>
                </p>
                <p class="field-link-target description">
                    <label for="edit-menu-item-target-<?php echo $item_id; ?>">
                        <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
                        <?php _e( 'Open link in a new window/tab', 'auxin-elements' ); ?>
                    </label>
                </p>
                <p class="field-css-classes description description-thin">
                    <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
                        <?php _e( 'CSS Classes (optional)', 'auxin-elements' ); ?><br />
                        <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
                    </label>
                </p>
                <p class="field-xfn description description-thin">
                    <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
                        <?php _e( 'Link Relationship (XFN)', 'auxin-elements' ); ?><br />
                        <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
                    </label>
                </p>
                <p class="field-description description description-wide">
                    <label for="edit-menu-item-description-<?php echo $item_id; ?>">
                        <?php _e( 'Description', 'auxin-elements' ); ?><br />
                        <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
                        <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'auxin-elements'); ?></span>
                    </label>
                </p>



                <div class="aux-master-menu-setting-wrapper">
<?php
                foreach ( $this->menu_item_fields as $field_id => $field_info ) {

                    if( isset( $field_info['visible'] ) && ! in_array( $item->object, $field_info['visible'] ) ){
                        continue;
                    }

                    if( ! isset( $field_info['min_depth'] ) ){
                        $field_info['min_depth'] = 0;
                    }
                    if( ! isset( $field_info['max_depth'] ) ){
                        $field_info['max_depth'] = 100;
                    }

                    // skip if it does not meet minimum or maximum depth requirements
                    if( (int)$field_info['min_depth'] > $depth || (int)$field_info['max_depth'] < $depth ){
                        //continue;
                    }

                    if( ! isset( $field_info['type'] ) ){
                        $field_info['type'] = 'text';
                    }

                    if( ! isset( $field_info['default'] ) ){
                        $field_info['default'] = '';
                    }

                    $data_depth  = 'data-min-depth="' . $field_info['min_depth'] . '" ';
                    $data_depth .= 'data-max-depth="' . $field_info['max_depth'] . '" ';

                    switch ( $field_info['type'] ) {

                        case 'switch':

                            $field_info['type'] = 'checkbox';
                            ?>
                                <p class="field-<?php echo $field_id; ?> description aux-mm-setting-field-<?php echo $field_info['type']; ?> aux-mm-setting-<?php echo $field_id; ?>" <?php echo $data_depth; ?> >
                                    <label for="edit-menu-item-<?php echo $field_id; ?>-<?php echo $item_id; ?>">
                                        <input type="checkbox" id="edit-menu-item-<?php echo $field_id; ?>-<?php echo $item_id; ?>" name="menu-item-<?php echo $field_id; ?>[<?php echo $item_id; ?>]" <?php checked( $item->{$field_id}, '1' ); ?> />
                                        <?php echo $field_info['label']; ?>
                                    </label>
                                </p>

                            <?php
                            break;

                        case 'select':

                            if( ! isset( $field_info['choices'] ) ){
                                $field_info['choices'] = array();
                            }
                            ?>
                                <p class="field-<?php echo $field_id; ?> description aux-mm-setting-field-<?php echo $field_info['type']; ?> aux-mm-setting-<?php echo $field_id; ?>" <?php echo $data_depth; ?> >
                                    <label for="edit-menu-item-<?php echo $field_id; ?>-<?php echo $item_id; ?>">
                                        <?php echo $field_info['label']; ?>
                                    </label>
                                    <select id="edit-menu-item-<?php echo $field_id; ?>-<?php echo $item_id; ?>" name="menu-item-<?php echo $field_id; ?>[<?php echo $item_id; ?>]" >
                                    <?php
                                    foreach ( $field_info['choices'] as $choice_id => $choice_value ) {
                                        echo '<option value="'. $choice_id .'" ' .selected( $item->{$field_id}, $choice_id, false ) .' >'. $choice_value . '</option>';
                                    }
                                    ?>
                                    </select>
                                </p>

                            <?php
                            break;

                        case 'icon':
                            $font_icons = Auxin()->Font_Icons->get_icons_list('fontastic');

                            if( ! isset( $field_info['choices'] ) ){
                                $field_info['choices'] = array();
                            }
                            ?>
                                <p class="field-<?php echo $field_id; ?> description aux-mm-setting-field-<?php echo $field_info['type']; ?> aux-mm-setting-<?php echo $field_id; ?>" <?php echo $data_depth; ?> >
                                    <label for="edit-menu-item-<?php echo $field_id; ?>-<?php echo $item_id; ?>">
                                        <select id="edit-menu-item-<?php echo $field_id; ?>-<?php echo $item_id; ?>" name="menu-item-<?php echo $field_id; ?>[<?php echo $item_id; ?>]" class="aux-fonticonpicker" >
                                        <?php
                                        echo '<option value="">' . __( 'Choose', 'auxin-elements' ) . '</option>';

                                        if( is_array( $font_icons ) ){
                                            foreach ( $font_icons as $icon ) {
                                                $icon_id = trim( $icon->classname, '.' );
                                                echo '<option value="'. $icon_id .'" '. selected( $item->{$field_id}, $icon_id, false ) .' >'. $icon->name . '</option>';
                                            }
                                        }
                                        ?>
                                        </select>
                                        <?php echo $field_info['label']; ?>
                                    </label>
                                </p>

                            <?php
                            break;

                        case 'textarea':
                            ?>
                            <p class="field-<?php echo $field_id; ?> description description-wide aux-mm-setting-field-<?php echo $field_info['type']; ?> aux-mm-setting-<?php echo $field_id; ?>" <?php echo $data_depth; ?> >
                                <label for="edit-menu-item-<?php echo $field_id; ?>-<?php echo $item_id; ?>">
                                    <?php echo $field_info['label']; ?><br />
                                    <textarea id="edit-menu-item-<?php echo $field_id; ?>-<?php echo $item_id; ?>" class="widefat edit-menu-item-<?php echo $field_id; ?>" rows="3" cols="20" name="menu-item-<?php echo $field_id; ?>[<?php echo $item_id; ?>]" ><?php echo esc_html( $item->{$field_id} ); // textarea_escaped ?></textarea>
                                </label>
                            </p>

                            <?php
                            break;

                        case 'text':
                        default:
                            ?>

                            <p class="description description-wide aux-mm-setting-field-<?php echo $field_info['type']; ?> aux-mm-setting-<?php echo $field_id; ?>" <?php echo $data_depth; ?>>
                                <label for="edit-menu-item-<?php echo $field_id; ?>-<?php echo $item_id; ?>">
                                    <?php echo $field_info['label']; ?><br />
                                    <input type="text" id="edit-menu-item-<?php echo $field_id; ?>-<?php echo $item_id; ?>" class="widefat edit-menu-item-<?php echo $field_id; ?>" name="menu-item-<?php echo $field_id; ?>[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->{$field_id} ); ?>" />
                                </label>
                            </p>

                            <?php
                            break;
                    }

                }
?>
                </div>

                <?php
                /**
                 * Fires just before the move buttons of a nav menu item in the menu editor.
                 *
                 * @since 5.4.0
                 *
                 * @param int      $item_id Menu item ID.
                 * @param WP_Post  $item    Menu item data object.
                 * @param int      $depth   Depth of menu item. Used for padding.
                 * @param stdClass $args    An object of menu item arguments.
                 * @param int      $id      Nav menu ID.
                 */
                do_action( 'wp_nav_menu_item_custom_fields', $item_id, $item, $depth, $args, $id );
                ?>

                <p class="field-move hide-if-no-js description description-wide">
                    <label>
                        <span><?php _e( 'Move', 'auxin-elements' ); ?></span>
                        <a href="#" class="menus-move menus-move-up" data-dir="up"><?php _e( 'Up one', 'auxin-elements' ); ?></a>
                        <a href="#" class="menus-move menus-move-down" data-dir="down"><?php _e( 'Down one', 'auxin-elements' ); ?></a>
                        <a href="#" class="menus-move menus-move-left" data-dir="left"></a>
                        <a href="#" class="menus-move menus-move-right" data-dir="right"></a>
                        <a href="#" class="menus-move menus-move-top" data-dir="top"><?php _e( 'To the top', 'auxin-elements' ); ?></a>
                    </label>
                </p>

                <div class="menu-item-actions description-wide submitbox">
                    <?php if ( 'custom' != $item->type && $original_title !== false ) : ?>
                        <p class="link-to-original">
                            <?php printf( __('Original: %s', 'auxin-elements'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
                        </p>
                    <?php endif; ?>
                    <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
                    echo wp_nonce_url(
                        add_query_arg(
                            array(
                                'action' => 'delete-menu-item',
                                'menu-item' => $item_id,
                            ),
                            admin_url( 'nav-menus.php' )
                        ),
                        'delete-menu_item_' . $item_id
                    ); ?>"><?php _e( 'Remove', 'auxin-elements' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
                        ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel', 'auxin-elements'); ?></a>
                </div>

                <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_Attr( $item_id ); ?>" />
                <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
                <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
                <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
                <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
                <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />


            </div><!-- .menu-item-settings-->
            <ul class="menu-item-transport"></ul>
        <?php
        $output .= ob_get_clean();
    }

}
