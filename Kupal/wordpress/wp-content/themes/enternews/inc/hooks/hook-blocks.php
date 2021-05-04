<?php
if ( ! function_exists( 'enternews_archive_layout_selection' ) ) :
	/**
	 *
	 * @param null
	 *
	 * @return null
	 *
	 * @since EnterNews 1.0.0
	 *
	 */
	function enternews_archive_layout_selection( $archive_layout = 'full' ) {

		//$archive_layout = enternews_get_option( 'archive_layout' );

		//print_pre($archive_layout);

		switch ( $archive_layout ) {
			case "archive-layout-grid":
				enternews_get_block( 'grid', 'archive' );
				break;
			case "archive-layout-list":
				enternews_get_block( 'list', 'archive' );
				break;
			default:
				enternews_get_block( 'list', 'archive' );
		}
	}
endif;


if ( ! function_exists( 'enternews_archive_layout' ) ) :
	/**
	 *
	 * @param null
	 *
	 * @return null
	 *
	 * @since EnterNews 1.0.0
	 *
	 */
	function enternews_archive_layout( $cat_slug = '' ) {

		//$archive_class = enternews_get_option('archive_layout');

		$archive_args = enternews_archive_layout_class( $cat_slug );

		?>

		<?php if ( ! empty( $archive_args['data_mh'] ) ): ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( $archive_args['add_archive_class'] ); ?>
                     data-mh="<?php echo esc_attr( $archive_args['data_mh'] ); ?>">
				<?php enternews_archive_layout_selection( $archive_args['archive_layout'] ); ?>
            </article>
		<?php else: ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( $archive_args['add_archive_class'] ); ?> >
				<?php enternews_archive_layout_selection( $archive_args['archive_layout'] ); ?>
            </article>
		<?php endif; ?>

		<?php

	}

	add_action( 'enternews_action_archive_layout', 'enternews_archive_layout', 10, 1 );
endif;

function enternews_archive_layout_class( $cat_slug ) {



		$archive_class       = enternews_get_option( 'archive_layout' );
		$archive_layout_list = enternews_get_option( 'archive_image_alignment_list' );
		$archive_layout_grid = enternews_get_option( 'archive_image_alignment_grid' );



	if ( $archive_class == 'archive-layout-grid' ) {
		$archive_args['archive_layout']    = 'archive-layout-grid';
		$archive_args['add_archive_class'] = 'af-sec-post latest-posts-grid col-3 float-l pad ';
		$archive_args['data_mh'] = 'archive-layout-grid';

		//$image_align_class = enternews_get_option('archive_image_alignment_grid');
		$image_align_class                 = $archive_layout_grid;
		$archive_args['add_archive_class'] .= ' ' . $archive_class . ' ' . $image_align_class;

	}  else {
		$archive_args['archive_layout']    = 'archive-layout-list';
		$archive_args['add_archive_class'] = 'latest-posts-list col-1 float-l pad';
		$archive_args['data_mh']           = '';

		$image_align_class                 = $archive_layout_list;
		$archive_args['add_archive_class'] .= ' ' . $archive_class . ' ' . $image_align_class;
	}

	return $archive_args;

}


//Archive div wrap before loop

if ( ! function_exists( 'enternews_archive_layout_before_loop' ) ) :

	/**
	 *
	 * @param null
	 *
	 * @return null
	 *
	 * @since EnterNews 1.0.0
	 *
	 */

	function enternews_archive_layout_before_loop() {


			$archive_mode = enternews_get_option( 'archive_layout' );


		?>
        <div class="af-container-row aft-archive-wrapper clearfix <?php echo esc_attr( $archive_mode ); ?>">
		<?php

	}

	add_action( 'enternews_archive_layout_before_loop', 'enternews_archive_layout_before_loop' );
endif;

if ( ! function_exists( 'enternews_archive_layout_after_loop' ) ):

	function enternews_archive_layout_after_loop() {
		?>
        </div>
	<?php }

	add_action( 'enternews_archive_layout_after_loop', 'enternews_archive_layout_after_loop' );

endif;
