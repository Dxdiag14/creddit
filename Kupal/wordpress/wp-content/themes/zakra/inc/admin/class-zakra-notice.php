<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

class Zakra_Notice {
	public $name;
	public $type;
	public $dismiss_url;
	public $temporary_dismiss_url;
	public $pricing_url;
	public $current_user_id;

	/**
	 * The constructor.
	 *
	 * @param string $name Notice Name.
	 * @param string $type Notice type.
	 * @param string $dismiss_url Notice permanent dismiss URL.
	 * @param string $temporary_dismiss_url Notice temporary dismiss URL.
	 *
	 * @since 1.4.2
	 */
	public function __construct( $name, $type, $dismiss_url, $temporary_dismiss_url ) {
		$this->name                  = $name;
		$this->type                  = $type;
		$this->dismiss_url           = $dismiss_url;
		$this->temporary_dismiss_url = $temporary_dismiss_url;
		$this->pricing_url           = 'https://zakratheme.com/pricing/?utm_source=zakra-dashboard-message&utm_medium=view-pricing-link&utm_campaign=upgrade';
		$this->current_user_id       = get_current_user_id();

		// Notice markup.
		add_action( 'admin_notices', array( $this, 'notice' ) );

		$this->dismiss_notice();
		$this->dismiss_notice_temporary();
	}

	public function notice() {
		if ( ! $this->is_dismiss_notice() ) {
			$this->notice_markup();
		}
	}

	private function is_dismiss_notice() {
		return apply_filters( 'zakra_' . $this->name . '_notice_dismiss', true );
	}

	public function notice_markup() {
		echo '';
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */
	public function dismiss_notice() {
		if ( isset( $_GET['zakra_notice_dismiss'] ) && isset( $_GET['_zakra_upgrade_notice_dismiss_nonce'] ) ) { // WPCS: input var ok.
			if ( ! wp_verify_nonce( wp_unslash( $_GET['_zakra_upgrade_notice_dismiss_nonce'] ), 'zakra_upgrade_notice_dismiss_nonce' ) ) { // phpcs:ignore WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'zakra' ) ); // WPCS: xss ok.
			}

			if ( ! current_user_can( 'publish_posts' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', 'zakra' ) ); // WPCS: xss ok.
			}

			$dismiss_notice = sanitize_text_field( wp_unslash( $_GET['zakra_notice_dismiss'] ) );

			// Hide.
			if ( $dismiss_notice === $_GET['zakra_notice_dismiss'] ) {
				add_user_meta( get_current_user_id(), 'zakra_' . $dismiss_notice . '_notice_dismiss', 'yes', true );
			}
		}
	}

	public function dismiss_notice_temporary() {
		if ( isset( $_GET['zakra_notice_dismiss_temporary'] ) && isset( $_GET['_zakra_upgrade_notice_dismiss_temporary_nonce'] ) ) { // WPCS: input var ok.
			if ( ! wp_verify_nonce( wp_unslash( $_GET['_zakra_upgrade_notice_dismiss_temporary_nonce'] ), 'zakra_upgrade_notice_dismiss_temporary_nonce' ) ) { // phpcs:ignore WordPress.VIP.ValidatedSanitizedInput.InputNotSanitized
				wp_die( __( 'Action failed. Please refresh the page and retry.', 'zakra' ) ); // WPCS: xss ok.
			}

			if ( ! current_user_can( 'publish_posts' ) ) {
				wp_die( __( 'Cheatin&#8217; huh?', 'zakra' ) ); // WPCS: xss ok.
			}

			$dismiss_notice = sanitize_text_field( wp_unslash( $_GET['zakra_notice_dismiss_temporary'] ) );

			// Hide.
			if ( $dismiss_notice === $_GET['zakra_notice_dismiss_temporary'] ) {
				add_user_meta( get_current_user_id(), 'zakra_' . $dismiss_notice . '_notice_dismiss_temporary', 'yes', true );
			}
		}
	}
}
