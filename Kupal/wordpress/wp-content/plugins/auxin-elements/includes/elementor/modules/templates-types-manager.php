<?php
namespace Auxin\Plugin\CoreElements\Elementor\Modules;

use Elementor\Plugin;

class Templates_Types_Manager {
	private $docs_types = [];

	public function __construct() {
		if( ! defined( 'ELEMENTOR_PRO_VERSION' ) ){
			define( 'AUXIN_ELEMENTOR_TEMPLATE', true );
			add_action( 'elementor/documents/register', [ $this, 'register_documents' ] );
		}
		add_action( 'elementor/dynamic_tags/register_tags', [ $this, 'register_tag' ] );
	}

	public function register_documents() {
		$this->docs_types = [
			'header' => Documents\Header::get_class_full_name(),
			'footer' => Documents\Footer::get_class_full_name()
		];

		foreach ( $this->docs_types as $type => $class_name ) {
			Plugin::instance()->documents->register_document_type( $type, $class_name );
		}
	}

	/**
	 * @param \Elementor\Core\DynamicTags\Manager $dynamic_tags
	 */
	public function register_tag( $dynamic_tags ) {

        $tags = array(
            // 'aux-archive-description' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/archive-description.php',
			// 	'class' => 'DynamicTags\Archive_Description',
			// 	'group' => 'archive',
			// 	'title' => 'Archive',
			// ),
            // 'aux-archive-meta' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/archive-meta.php',
			// 	'class' => 'DynamicTags\Archive_Meta',
			// 	'group' => 'archive',
			// 	'title' => 'Archive',
			// ),
            // 'aux-archive-title' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/archive-title.php',
			// 	'class' => 'DynamicTags\Archive_Title',
			// 	'group' => 'archive',
			// 	'title' => 'Archive',
			// ),
			// ),
            // 'aux-author-info' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/author-info.php',
			// 	'class' => 'DynamicTags\Author_Info',
			// 	'group' => 'author',
			// 	'title' => 'Author',
			// ),
            // 'aux-author-meta' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/author-meta.php',
			// 	'class' => 'DynamicTags\Author_Meta',
			// 	'group' => 'author',
			// 	'title' => 'Author',
			// ),
            // 'aux-author-name' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/author-name.php',
			// 	'class' => 'DynamicTags\Author_Name',
			// 	'group' => 'author',
			// 	'title' => 'Author',
			// ),
            // 'aux-author-profile-picture' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/author-profile-picture.php',
			// 	'class' => 'DynamicTags\Author_Profile_Picture',
			// 	'group' => 'author',
			// 	'title' => 'Author',
			// ),
            // 'aux-author-url' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/author-url.php',
			// 	'class' => 'DynamicTags\Author_URL',
			// 	'group' => 'author',
			// 	'title' => 'Author',
			// ),
            // 'aux-comments-number' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/comments-number.php',
			// 	'class' => 'DynamicTags\Comments_Number',
			// 	'group' => 'comments',
			// 	'title' => 'Comments',
			// ),
            // 'aux-comments-url' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/comments-url.php',
			// 	'class' => 'DynamicTags\Comments_URL',
			// 	'group' => 'comments',
			// 	'title' => 'Comments',
			// ),
            // 'aux-contact-url' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/contact-url.php',
			// 	'class' => 'DynamicTags\Contact_URL',
			// 	'group' => 'action',
			// 	'title' => 'Action',
			// ),
            // 'aux-current-date-time' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/current-date-time.php',
			// 	'class' => 'DynamicTags\Current_Date_Time',
			// 	'group' => 'site',
			// 	'title' => 'Site',
			// ),
            // 'aux-featured-image-data' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/featured-image-data.php',
			// 	'class' => 'DynamicTags\Featured_Image_Data',
			// 	'group' => 'media',
			// 	'title' => 'Media',
			// ),
            // 'aux-page-title' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/page-title.php',
			// 	'class' => 'DynamicTags\Page_Title',
			// 	'group' => 'site',
			// 	'title' => 'Site',
			// ),
            'aux-post-custom-field' => array(
                'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/post-custom-field.php',
				'class' => 'DynamicTags\Post_Custom_Field',
				'group' => 'post',
				'title' => 'Post',
			),
			'aux-featured-colors' => array(
                'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/featured-colors.php',
				'class' => 'DynamicTags\Auxin_Featured_Colors',
				'group' => 'colors',
				'title' => 'Colors',
			),
			'aux-pages-url' => array(
                'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/pages-url.php',
				'class' => 'DynamicTags\Auxin_Pages_Url',
				'group' => 'URL',
				'title' => 'URL',
			),
			'aux-cats-url' => array(
                'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/taxonomies-url.php',
				'class' => 'DynamicTags\Auxin_Taxonomies_Url',
				'group' => 'URL',
				'title' => 'URL',
			),
			'aux-archive-url' => array(
                'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/archive-url.php',
				'class' => 'DynamicTags\Archive_URL',
				'group' => 'URL',
				'title' => 'URL',
			),
            // 'aux-post-date' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/post-date.php',
			// 	'class' => 'DynamicTags\Post_Date',
			// 	'group' => 'post',
			// 	'title' => 'Post',
			// ),
            // 'aux-post-excerpt' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/post-excerpt.php',
			// 	'class' => 'DynamicTags\Post_Excerpt',
			// 	'group' => 'post',
			// 	'title' => 'Post',
			// ),
            // 'aux-post-featured-image' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/post-featured-image.php',
			// 	'class' => 'DynamicTags\Post_Featured_Image',
			// 	'group' => 'post',
			// 	'title' => 'Post',
			// ),
            // 'aux-post-gallery' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/post-gallery.php',
			// 	'class' => 'DynamicTags\Post_Gallery',
			// 	'group' => 'post',
			// 	'title' => 'Post',
			// ),
            // 'aux-post-id' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/post-id.php',
			// 	'class' => 'DynamicTags\Post_ID',
			// 	'group' => 'post',
			// 	'title' => 'Post',
			// ),
            // 'aux-post-terms' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/post-terms.php',
			// 	'class' => 'DynamicTags\Post_Terms',
			// 	'group' => 'post',
			// 	'title' => 'Post',
			// ),
            // 'aux-post-time' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/post-time.php',
			// 	'class' => 'DynamicTags\Post_Time',
			// 	'group' => 'post',
			// 	'title' => 'Post',
			// ),
            // 'aux-post-title' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/post-title.php',
			// 	'class' => 'DynamicTags\Post_Title',
			// 	'group' => 'post',
			// 	'title' => 'Post',
			// ),
            // 'aux-post-url' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/post-url.php',
			// 	'class' => 'DynamicTags\Post_URL',
			// 	'group' => 'post',
			// 	'title' => 'Post',
			// ),
            // 'aux-request-parameter' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/request-parameter.php',
			// 	'class' => 'DynamicTags\Request_Parameter',
			// 	'group' => 'site',
			// 	'title' => 'Site',
			// ),
            'aux-shortcode' => array(
                'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/shortcode.php',
				'class' => 'DynamicTags\Shortcode',
				'group' => 'site',
				'title' => 'Site',
			),
            // 'aux-site-logo' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/site-logo.php',
			// 	'class' => 'DynamicTags\Site_Logo',
			// 	'group' => 'site',
			// 	'title' => 'Site',
			// ),
            // 'aux-site-tagline' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/site-tagline.php',
			// 	'class' => 'DynamicTags\Site_Tagline',
			// 	'group' => 'site',
			// 	'title' => 'Site',
			// ),
            // 'aux-site-title' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/site-title.php',
			// 	'class' => 'DynamicTags\Site_Title',
			// 	'group' => 'site',
			// 	'title' => 'Site',
			// ),
            // 'aux-site-url' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/site-url.php',
			// 	'class' => 'DynamicTags\Site_URL',
			// 	'group' => 'site',
			// 	'title' => 'Site',
			// ),
            // 'aux-user-info' => array(
            //     'file'  => AUXELS_INC_DIR . '/elementor/modules/dynamic-tags/user-info.php',
			// 	'class' => 'DynamicTags\User_Info',
			// 	'group' => 'site',
			// 	'title' => 'Site',
			// )
        );

        foreach ( $tags as $tags_type => $tags_info ) {
            if( ! empty( $tags_info['file'] ) && ! empty( $tags_info['class'] ) ){
				// In our Dynamic Tag we use a group named request-variables so we need
				// To register that group as well before the tag
				\Elementor\Plugin::instance()->dynamic_tags->register_group( $tags_info['group'] , [
					'title' => $tags_info['title']
				] );

                include_once( $tags_info['file'] );
                if( class_exists( $tags_info['class'] ) ){
                    $class_name = $tags_info['class'];
                } elseif( class_exists( __NAMESPACE__ . '\\' . $tags_info['class'] ) ){
                    $class_name = __NAMESPACE__ . '\\' . $tags_info['class'];
                }
				$dynamic_tags->register_tag( $class_name );
            }
        }
	}

}