<?php
/**
 * Woocommerce attributes nav menu Class.
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
*/

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}

/**
 *  Class to add woocommerce attributes filter as nav menu item
 */
class Auxels_WC_Attribute_Nav_Menu {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance  = null;

    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * __construct
     */
    public function __construct() {
        
        if ( ! class_exists( 'WooCommerce') ) {
            return;
        }

        add_filter( 'customize_nav_menu_available_item_types', [ $this, 'register_customize_nav_menu_item_types'] );
        add_filter( 'customize_nav_menu_available_items', [ $this, 'register_customize_nav_menu_items' ], 11, 4 );
        add_action( 'admin_head-nav-menus.php', [ $this, 'add_nav_menu_meta_boxes' ] );
    }

    /**
    * Register customize new nav menu item types.
    *
    * @param  array $item_types Menu item types.
    * @return array
    */
    public function register_customize_nav_menu_item_types( $item_types ) {
        $item_types[] = array(
            'title'      => __( 'WooCommerce Attributes', 'auxin-elements' ),
            'type_label' => __( 'WooCommerce Attributes', 'auxin-elements' ),
            'type'       => 'aux_woocommerce_attribute',
            'object'     => 'aux_woocommerce_attribute',
        );

        return $item_types;
    }


    /**
     * Register woocommerce attributes to customize nav menu items.
     *
     * @param  array   $items  List of nav menu items.
     * @param  string  $type   Nav menu type.
     * @param  string  $object Nav menu object.
     * @param  integer $page   Page number.
     * @return array
     */
    public function register_customize_nav_menu_items( $items = array(), $type = '', $object = '', $page = 0 ) {
        if ( 'aux_woocommerce_attribute' !== $object ) {
            return $items;
        }
        
        // Don't allow pagination since all items are loaded at once.
        if ( 0 < $page ) {
            return $items;
        }

        $taxonomies = [];
        $attr_tax = wc_get_attribute_taxonomies();

        foreach( $attr_tax as $tax ) {
            $taxonomies[ $tax->attribute_name ] = $tax->attribute_label;
        }

        foreach ( $taxonomies as $slug => $label ) {
            $terms = get_terms( 'pa_' . $slug, array(
                'hide_empty' => false,
            ) );

            foreach ( $terms as $term ) {
                $items[] = array(
                    'id'         => $term->slug,
                    'title'      => $term->name,
                    'type_label' => __( 'Attribute', 'auxin-elements' ),
                    'url'        => esc_url( get_permalink( woocommerce_get_page_id( 'shop' ) ) . '?filter_' . $slug . '=' . $term->slug ),
                );
            }
        }

        return $items;
    }

    public function add_nav_menu_meta_boxes() {
        add_meta_box( 'aux_woocommerce_attribute_nav_link', __( 'WooCommerce attributes', 'auxin-elements' ), [ $this, 'woocommerce_attr_nav_menu_links' ], 'nav-menus', 'side', 'low' );
    }
    
    public function woocommerce_attr_nav_menu_links() {
        
        $taxonomies = [];
        $attr_tax = wc_get_attribute_taxonomies();

        foreach( $attr_tax as $tax ) {
            $taxonomies[ $tax->attribute_name ] = $tax->attribute_label;
        }
        
        ?>
        <div id="posttype-woocommerce-attr" class="posttypediv">
            <div id="tabs-panel-woocommerce-attr" class="tabs-panel tabs-panel-active">
                <ul id="woocommerce-attr-checklist" class="categorychecklist form-no-clear">
                    <?php
                    $i = -1;
                    foreach ( $taxonomies as $slug => $label ) :
                        $terms = get_terms( 'pa_' . $slug, array(
                            'hide_empty' => false,
                        ) );
                        foreach ( $terms as $term ) {
                        ?>
                            <li>
                                <label class="menu-item-title">
                                    <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-object-id]" value="<?php echo esc_attr( $i ); ?>" /> <?php echo esc_html( $term->name ); ?>
                                </label>
                                <input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-type]" value="custom" />
                                <input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-title]" value="<?php echo esc_attr( $term->name ); ?>" />
                                <input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-url]" value="<?php echo esc_url( get_permalink( woocommerce_get_page_id( 'shop' ) ) . '?filter_' . $slug . '=' . $term->slug ); ?>" />
                                <input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-classes]" />
                            </li>
                        <?php
                        }
                        $i--;
                    endforeach;
                    ?>
                </ul>
            </div>
            <p class="button-controls">
                <span class="list-controls">
                    <a href="<?php echo esc_url( admin_url( 'nav-menus.php?page-tab=all&selectall=1#posttype-woocommerce-attr' ) ); ?>" class="select-all"><?php esc_html_e( 'Select all', 'auxin-elements' ); ?></a>
                </span>
                <span class="add-to-menu">
                    <button type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu', 'auxin-elements' ); ?>" name="add-post-type-menu-item" id="submit-posttype-woocommerce-attr"><?php esc_html_e( 'Add to menu', 'auxin-elements' ); ?></button>
                    <span class="spinner"></span>
                </span>
            </p>
        </div>
        <?php
    }
}
?>