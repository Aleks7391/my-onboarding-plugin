<?php
/**
 * Plugin Name: My Onboarding Plugin
 * Description: This is my first plugin.
 * Author:      Aleks Ganev
 * Version:     1.0
 */

function ag_change_content( $content ) {
	$prepend = 'Onboarding Filter: ';
	$append = ' by Aleks Ganev';
	return $prepend . $content . $append;
}

function ag_add_hidden_div( $content ) {
	return preg_replace( '</p>', '/p><div style="display:none">Hello</div', $content, 1 );
}

function ag_add_paragraph( $content ) {
	return preg_replace( '<p>', 'p>This Paragraph was added programmatically.</p><p', $content, 1 );
	return $paragraph . $content;
}

function add_profile_settings_link( $items ) {
	if ( is_user_logged_in() ) {
		return $items . '<li><a href="'. site_url('wp-admin/profile.php') .'">Profile Settings</a></li>';
	}
}

function send_email_on_profile_edit() {
	$admin_email = get_option( 'admin_email' );
	wp_mail( $admin_email, 'Profile Update', 'Your profile has just been updated' );
}

add_filter( 'the_content', 'ag_change_content', 7);
add_filter( 'the_content', 'ag_add_hidden_div', 8);
add_filter( 'the_content', 'ag_add_paragraph', 9);
add_filter( 'the_content', 'ag_add_hidden_div', 10);
add_filter( 'wp_nav_menu_items', 'add_profile_settings_link', 11);
add_action( 'profile_update', 'send_email_on_profile_edit');

