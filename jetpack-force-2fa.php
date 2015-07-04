<?php
/*
Plugin Name: Force Jetpack 2FA
Plugin URI: http://automattic.com
Description: Force admins to use two factor authentiation.
Author: Brandon Kraft, Josh Betz, Automattic
Version: 0.1
Author URI: http://automattic.com
*/

// Hide the login form
/* Disabling via a comment so it is trivial to revert if not ready for primetime by deadline.
add_filter('jetpack_remove_login_form', '__return_true', 9999);
add_filter('jetpack_sso_bypass_login_forward_wpcom', '__return_true', 9999);
add_filter('jetpack_sso_display_disclaimer', '__return_false', 9999);
*/

// Allows WP.com login to a local account if it matches the local account.
add_filter('jetpack_sso_match_by_email', '__return_true', 9999);

// Completely disable the standard login form for admins.
add_filter('wp_authenticate_user', function( $user ) {
	if ( $user->has_cap('manage_options') ) {
		return new WP_Error('wpcom-required', "Local login disabled for this account.", $user->user_login );
	}
	return $user;
}, 9999);

// Force 2FA for admins.
add_filter('jetpack_sso_require_two_step', function() {
	return current_user_can('manage_options');
}, 9999);
