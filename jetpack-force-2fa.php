<?php
/*
Plugin Name: Force Jetpack 2FA
Plugin URI: http://automattic.com
Description: Force admins to use two factor authentiation.
Author: Brandon Kraft, Josh Betz, Automattic
Version: 0.1
Author URI: http://automattic.com
*/

class Jetpack_Force_2FA {

	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ) );
	}

	function plugins_loaded() {		
		// Bail if Jetpack SSO is not active
		if ( ! class_exists( 'Jetpack' ) || ! Jetpack::is_active() || ! Jetpack::is_module_active( 'sso' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			return;
		}

		$this->force_2fa();
	}

	function admin_notice() {
		if ( apply_filters( 'jetpack_force_2fa_dependency_notice', true ) ) {
			printf( '<div class="%1$s"><p>%2$s</p></div>', 'notice notice-warning', 'Jetpack Force 2FA requires Jetpack and the Jetpack SSO module.' );
		}
	}

	function force_2fa() {
		// Allows WP.com login to a local account if it matches the local account.
		add_filter( 'jetpack_sso_match_by_email', '__return_true', 9999 );
		
		// Hide the login form
		add_filter( 'jetpack_remove_login_form', '__return_true', 9999 );
		add_filter( 'jetpack_sso_bypass_login_forward_wpcom', '__return_true', 9999 );
		add_filter( 'jetpack_sso_display_disclaimer', '__return_false', 9999 );

		add_filter( 'wp_authenticate_user', function() {
			return new WP_Error( 'wpcom-required', $this->get_login_error_message() );
		}, 9999 );

		add_filter( 'jetpack_sso_require_two_step', '__return_true' );

		add_filter( 'allow_password_reset', '__return_false' );
	}

	private function get_login_error_message() {
		return apply_filters(
			'jetpack_force_2fa_login_error_message',
			sprintf( 'For added security, please log in using your WordPress.com account.<br /><br />Note: Your account must have <a href="%1$s" target="_blank">Two Step Authentication</a> enabled, which can be configured from <a href="%2$s" target="_blank">Security Settings</a>.', 'https://support.wordpress.com/security/two-step-authentication/', 'https://wordpress.com/me/security/two-step' )
		);
	}
}

new Jetpack_Force_2FA;
