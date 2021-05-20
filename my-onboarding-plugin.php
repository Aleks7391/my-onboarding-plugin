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

add_action( 'admin_menu', 'ag_create_my_onboarding_options_page', 5 );
add_action( 'admin_init', 'ag_initialize_my_onboarding_options', 6 );

add_action( 'init', 'ag_apply_filter' );

function ag_apply_filter() {
	$option = get_option( 'toggle_filter' );
	if ( $option === '1' ) {
		add_filter( 'the_content', 'ag_change_content', 7 );
		add_filter( 'the_content', 'ag_add_hidden_div', 8 );
		add_filter( 'the_content', 'ag_add_paragraph', 9 );
		add_filter( 'the_content', 'ag_add_hidden_div', 10 );
		add_filter( 'wp_nav_menu_items', 'add_profile_settings_link', 11 );
		add_action( 'profile_update', 'send_email_on_profile_edit' );
	}
}

function ag_create_my_onboarding_options_page() {
    add_menu_page(
        'My Onboarding Options',
        'My Onboarding',
        'manage_options',
        'onboarding',
        'ag_render_onboard_settings_page'
    );
}

function ag_render_onboard_settings_page() {
	?>
	<div class="wrap">
		<form method="post" action="options.php">
		<?php
		settings_fields( 'onboarding' );
		do_settings_sections( 'onboarding' );
		submit_button();
		?>
		</form>
	</div>
	<?php
}

function ag_initialize_my_onboarding_options() {
	register_setting(
		'onboarding',
		'toggle_filter'
	);

    add_settings_section(
        'ag-my-onboarding-section',          // ID
        'My Onboarding',                     // Title
        'ag_my_onboarding_options_callback', // Callback
        'onboarding'                         // Page
    );
     
    add_settings_field( 
        'toggle_filter',                     // ID
        'Filter',                            // Label
        'ag_toggle_filter_callback',         // Callback
        'onboarding',                        // Page
        'ag-my-onboarding-section'           // Section
    );
}

function ag_my_onboarding_options_callback() {
    //
}

function ag_toggle_filter_callback() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
    $html = '<input type="checkbox" id="toggle_filter" name="toggle_filter" value="1" ' . checked(1, get_option('toggle_filter'), false) . '/>'; 
    $html .= '<label for="toggle_filter"> Activate this setting to toggle the filter.</label>'; 
     
    echo $html;
}