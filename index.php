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
//load language 
function load_textdomain() {
  load_plugin_textdomain( 'mail', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}

add_action( 'init', 'load_textdomain' );

function sender_register() {
	add_settings_section('sender_section', __('Mail Sender Options', 'cb-mail'), 'sender_text', 'sender');

	add_settings_field('sender_id', __('Mail Sender Name','cb-mail'), 'sender_function', 'sender',  'sender_section');

	register_setting('sender_section', 'sender_id');

	add_settings_field('sender_email_id', __('Mail Sender Email', 'mail'), 'sender_email', 'sender',  'sender_section');

	register_setting('sender_section', 'sender_email_id');

}
add_action('admin_init', 'sender_register');



function sender_function(){

	printf('<input name="sender_id" type="text" class="regular-text" value="%s" placeholder="Mail Name"/>', get_option('sender_id'));

}
function sender_email() {
	printf('<input name="sender_email_id" type="email" class="regular-text" value="%s" placeholder="no_reply@yourdomain.com"/>', get_option('sender_email_id'));


}

function sender_text() {

	printf('%s You may change your WordPress Default mail sender name and email %s', '<p>', '</p>');

}



function sender_menu() {
	add_menu_page(__('Mail Sender Options', 'mail'), __('Mail Sender', 'mail'), 'manage_options', 'sender', 'sender_output', 'dashicons-email');


}
add_action('admin_menu', 'sender_menu');



function sender_output(){
?>	
	<?php settings_errors();?>
	<form action="options.php" method="POST">
		<?php do_settings_sections('sender');?>
		<?php settings_fields('sender_section');?>
		<?php submit_button();?>
	</form>
<?php }

// Change the default wordpress@ email address
add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');
 
function cb_new_mail_from($old) {
	return get_option('sender_email_id');
}
function cb_new_mail_from_name($old) {
	return get_option('sender_id');
}
