<?php
defined( 'ABSPATH' ) || exit;

register_activation_hook( defined( 'WPCSS_LITE' ) ? WPCSS_LITE : WPCSS_FILE, 'wpcss_activate' );
register_deactivation_hook( defined( 'WPCSS_LITE' ) ? WPCSS_LITE : WPCSS_FILE, 'wpcss_deactivate' );
add_action( 'admin_init', 'wpcss_check_version' );

function wpcss_check_version() {
	if ( ! empty( get_option( 'wpcss_version' ) ) && ( get_option( 'wpcss_version' ) < WPCSS_VERSION ) ) {
		wpc_log( 'wpcss', 'upgraded' );
		update_option( 'wpcss_version', WPCSS_VERSION, false );
	}
}

function wpcss_activate() {
	wpc_log( 'wpcss', 'installed' );
	update_option( 'wpcss_version', WPCSS_VERSION, false );
}

function wpcss_deactivate() {
	wpc_log( 'wpcss', 'deactivated' );
}

if ( ! function_exists( 'wpc_log' ) ) {
	function wpc_log( $prefix, $action ) {
		$logs = get_option( 'wpc_logs', [] );
		$user = wp_get_current_user();

		if ( ! isset( $logs[ $prefix ] ) ) {
			$logs[ $prefix ] = [];
		}

		$logs[ $prefix ][] = [
			'time'   => current_time( 'mysql' ),
			'user'   => $user->display_name . ' (ID: ' . $user->ID . ')',
			'action' => $action
		];

		update_option( 'wpc_logs', $logs, false );
	}
}