<?php
/**
 * Contact form element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://phlox.pro/
 * @copyright  (c) 2010-2021 averta
 */

function auxin_get_contact_form_master_array( $master_array ) {

    $master_array['aux_contact_form'] = array(
        'name'                    => __('Contact Form', 'auxin-elements' ),
        'auxin_output_callback'   => 'auxin_widget_contact_form_callback',
        'base'                    => 'aux_contact_form',
        'description'             => __('It adds a contact form element.', 'auxin-elements' ),
        'class'                   => 'aux-widget-contact-form',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => true,
        'is_shortcode'            => true,
        'is_so'                   => true,
        'is_vc'                   => true,
        'category'                => THEME_NAME,
        'group'                   => '',
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'aux-element aux-pb-icons-contact-form',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params' => array(
            array(
                'heading'           => __('Title','auxin-elements' ),
                'description'       => __('Contact form title, leave it empty if you don`t need title.', 'auxin-elements'),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Email','auxin-elements' ),
                'description'       => __('Email address of message\'s recipient', 'auxin-elements' ),
                'param_name'        => 'email',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'email',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Contact form type','auxin-elements' ),
                'description'       => __('Specifies contact form element\'s type. Whether to use built-in form or Contact Form 7.', 'auxin-elements' ),
                'param_name'        => 'type',
                'type'              => 'dropdown',
                'value'             => array(
                    'phlox'         => __('Phlox Contact Form', 'auxin-elements' ),
                    'cf7'           => __('Contact Form 7 plugin', 'auxin-elements' ),
                ),
                'def_value'         => 'phlox',
                'holder'            => '',
                'class'             => 'width',
                'admin_label'       => true,
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Contact form 7 shortcode','auxin-elements' ),
                'description'       => __('Put one of your Contact form 7 shortcodes that you created.','auxin-elements' ),
                'param_name'        => 'cf7_shortcode',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'cf7_shortcode',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'   => 'type',
                    'value'     => 'cf7'
                ),
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Extra class name','auxin-elements' ),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-elements' ),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => 'extra_classes',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            )
        ),

    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_contact_form_master_array', 10, 1 );

/**
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_contact_form_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'         => '', // header title
        'size'          => '33',  // section size
        'email'         => '',
        'type'          => 'phlox',
        'cf7_shortcode' => '',

        'extra_classes' => '',
        'custom_el_id'  => '',
        'base_class'    => 'aux-widget-contact-form'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );


    if( empty( $wcf7 ) && isset( $_POST['formSubmitted'] ) ) {

        if(trim($_POST['cName']) === '' ) {
            $nameError = __('Please enter your name.', 'auxin-elements' );
            $hasError = true;
        } else {
            $name = trim($_POST['cName']);
        }

        if( trim($_POST['cEmail']) === '' )  {
            $emailError = __('Please enter your email address.', 'auxin-elements' );
            $hasError = true;
        } elseif (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['cEmail']))) {
            $emailError = __('You entered an invalid email address.', 'auxin-elements' );
            $hasError = true;
        } else {
            $cEmail = trim($_POST['cEmail']);
        }


        $url = trim($_POST['cURL']);


        if(trim($_POST['cComment']) === '' ) {
            $commentError = __('Please enter a message.', 'auxin-elements' );
            $hasError = true;
        } else {
            if(function_exists('stripslashes')) {
                $comment = stripslashes(trim($_POST['cComment']));
            } else {
                $comment = trim($_POST['cComment']);
            }
        }

        if(!isset($hasError)) {
            $emailTo = $email;
            if (!isset($emailTo) || empty($emailTo) ){
                $emailTo = get_option('admin_email');
            }
            $subject = 'From '.$name.' ['.$cEmail.'] ';
            $body    = "Name: $name \n\nEmail: $cEmail \n\nWebsite: $url \n\nMessage: $comment";
            $headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

            wp_mail($emailTo, $subject, $body, $headers);
            $emailSent = true;
        }
    }

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];
    ?>
            <div class="aux-col-wrapper aux-no-gutter">
            <!-- @TODO - The output for element here -->

            <?php if( $type == "phlox" ){ ?>

            <?php if(isset($hasError) ) { ?>
                <p style="color:#B2950E" >
                    <span id="info"><?php _e("sorry, some problems occured with your form submission:", 'auxin-elements' ); ?>
                    <?php
                    if(isset($nameError ))   echo '<br/>- '.$nameError;
                    if(isset($emailError))   echo '<br/>- '.$emailError;
                    if(isset($commentError)) echo '<br/>- '.$commentError;
                    ?>
                    </span>
                </p>
            <?php } ?>

            <form action="<?php the_permalink(); ?>" id="contactForm" class="aux-contact-form" method="post" >
                <input type="text"  name="cName"    id="cName"    placeholder="Name" required >
                <input type="email" name="cEmail"   id="cEmail"   placeholder="E-Mail" required >
                <input type="text"  name="cURL"     id="cURL"     placeholder="Website" >
                <textarea           name="cComment" id="cComment" placeholder="Message" required></textarea>
                <input type="submit" id="submit" class="submit"  value="<?php esc_attr_e('Submit', 'auxin-elements' ); ?>" >

                <?php if(isset($emailSent) && $emailSent == true) { ?>
                <p style="color:#598527;"><i class="icon-ok" style="margin:5px;"></i><span id="info"><?php _e("Thanks for your Message. Your message sent successfully.", 'auxin-elements' ); ?></span></p>
                <?php } ?>

                <input type="hidden" name="formSubmitted" id="formSubmitted" value="true" />
            </form>

            <?php } else {
                echo do_shortcode($cf7_shortcode);
            } ?>


            </div><!-- aux-col-wrapper -->
    <?php
    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}

