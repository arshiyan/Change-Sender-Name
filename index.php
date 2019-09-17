<?php 

/*
 * Plugin Name: Change Sender Name
 * Description: Easy way to change mail sender information
 * Version: 1.0
 * Author: Mohammad Javad Arshiyan
 * Author URI: http://arshiyan.ir
 */

// Don't call the file directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function _mail_load_textdomain() {
  load_plugin_textdomain( 'mail-sender', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

add_action( 'init', '_mail_load_textdomain' );

function _mail_sender_register() {
	add_settings_section('_mail_sender_section', __('Mail Sender Options', 'mail-sender'), '_mail_sender_text', '_mail_sender');

	add_settings_field('_mail_sender_id', __('Mail Sender Name','mail-sender'), '_mail_sender_function', '_mail_sender',  '_mail_sender_section');

	register_setting('_mail_sender_section', '_mail_sender_id');

	add_settings_field('_mail_sender_email_id', __('Mail Sender Email', 'mail-sender'), '_mail_sender_email', '_mail_sender',  '_mail_sender_section');

	register_setting('_mail_sender_section', '_mail_sender_email_id');

}
add_action('admin_init', '_mail_sender_register');



function _mail_sender_function(){

	printf('<input name="_mail_sender_id" type="text" class="regular-text" value="%s" placeholder="Mail Name"/>', get_option('_mail_sender_id'));

}
function _mail_sender_email() {
	printf('<input name="_mail_sender_email_id" type="email" class="regular-text" value="%s" placeholder="no_reply@yourdomain.com"/>', get_option('_mail_sender_email_id'));


}

function _mail_sender_text() {

	printf('%s You may change your WordPress Default mail sender name and email %s', '<p>', '</p>');

}



function _mail_sender_menu() {
	add_menu_page(__('Mail Sender Options', 'mail-sender'), __('Mail Sender', 'mail-sender'), 'manage_options', '_mail_sender', '_mail_sender_output', 'dashicons-email');


}
add_action('admin_menu', '_mail_sender_menu');



function _mail_sender_output(){
?>	
	<?php settings_errors();?>
	<form action="options.php" method="POST">
		<?php do_settings_sections('_mail_sender');?>
		<?php settings_fields('_mail_sender_section');?>
		<?php submit_button();?>
	</form>
<?php }

// Change the default wordpress@ email address
add_filter('wp_mail_from', '_new_mail_from');
add_filter('wp_mail_from_name', '_new_mail_from_name');
 
function _new_mail_from($old) {
	return get_option('_mail_sender_email_id');
}
function _new_mail_from_name($old) {
	return get_option('_mail_sender_id');
}
