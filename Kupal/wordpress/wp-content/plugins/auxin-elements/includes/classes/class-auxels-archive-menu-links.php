<?php
/**
 * Adds archive links to edit menus page
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */
class Auxels_Archive_Menu_Links{

    public function __construct(){
        add_action( 'admin_init', array( $this, 'add_archive_metabox' ) );
    }

    /**
     * Register menu metabox for archive links
     */
    public function add_archive_metabox(){
        add_meta_box(
            'aux_archive_menubox',
            __('Archive Pages', 'auxin-elements'),
            array( $this, 'display_metabox' ),
            'nav-menus',
            'side',
            'low'
        );
    }

    /**
     * Display the links for archives
     */
    public function display_metabox(){

        ?>
        <div id="posttype-archive-pages" class="posttypediv">
            <div id="tabs-panel-archive-pages" class="tabs-panel tabs-panel-active">

                <ul id="archive-pages" class="categorychecklist form-no-clear">

                    <?php
                    //loop through all registered content types that have 'has-archive' enabled
                    $post_types = get_post_types( array('has_archive' => true ) );

                    if( $post_types ){
                        $counter = -1;
                        foreach( $post_types as $post_type ){
                            $post_type_obj         = get_post_type_object( $post_type );
                            $post_type_archive_url = get_post_type_archive_link( $post_type );
                            $post_type_label       = $post_type_obj->labels->singular_name;
                            ?>
                            <li>
                                <label class="menu-item-title">
                                    <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo $counter; ?>][menu-item-object-id]" value="-1"/>
                                    <?php echo __( 'Archive', 'auxin-elements' ) . ' <strong>'.$post_type_label .'</strong>'; ?>
                                </label>
                                <input type="hidden" class="menu-item-type" name="menu-item[<?php echo $counter; ?>][menu-item-type]" value="custom"/>
                                <input type="hidden" class="menu-item-title" name="menu-item[<?php echo $counter; ?>][menu-item-title]" value="<?php echo esc_attr( $post_type_label ); ?>"/>
                                <input type="hidden" class="menu-item-url" name="menu-item[<?php echo $counter; ?>][menu-item-url]" value="<?php echo esc_attr( $post_type_archive_url ); ?>"/>
                                <input type="hidden" class="menu-item-classes" name="menu-item[<?php echo $counter; ?>][menu-item-classes]"/>
                            </li>
                            <?php
                            $counter--;
                        }
                    }?>
                </ul>
            </div>
            <p class="button-controls">
                <span class="list-controls">
                    <a href="<?php echo admin_url('nav-menus.php?page-tab=all&selectall=1#posttype-archive-pages'); ?>" class="select-all"> <?php _e('Select All', 'auxin-elements' ); ?></a>
                </span>
                <span class="add-to-menu">
                    <input type="submit" class="button-secondary submit-add-to-menu right" value="<?php _e('Add to Menu', 'auxin-elements') ?>" name="add-post-type-menu-item" id="submit-posttype-archive-pages">
                    <span class="spinner"></span>
                </span>
            </p>
        </div>
        <?php
    }

}
