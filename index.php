<?php

/*
 * Plugin Name: Change Sender Name
 * Description: Easy way to change mail sender information
 * Version: 1.0
 * Author: Mohammad Javad Arshiyan
 * Author URI: http://arshiyan.ir
 */

// Don't call the file directly
if (!defined('ABSPATH')) exit;


/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
function csn_load_textdomain()
{
    load_plugin_textdomain('mailsender', false, basename(dirname(__FILE__)) . '/languages');
}

add_action('init', 'csn_load_textdomain');

function csn_register()
{
    add_settings_section('csn_section', __('Mail Sender Options', 'mailsender'), 'csn_text', 'mailsender');

    add_settings_field('csn_id', __('Mail Sender Name', 'mailsender'), 'csn_function', 'mailsender', 'csn_section');

    register_setting('csn_section', 'csn_id');

    add_settings_field('csn_email_id', __('Mail Sender Email', 'mailsender'), 'csn_email', 'mailsender', 'csn_section');

    register_setting('csn_section', 'csn_email_id');

}

add_action('admin_init', 'csn_register');


function csn_function()
{

    printf('<input name="csn_id" type="text" class="regular-text" value="%s" placeholder="Mail Name"/>', get_option('csn_id'));

}

function csn_email()
{
    printf('<input name="csn_email_id" type="email" class="regular-text" value="%s" placeholder="no_reply@yourdomain.com"/>', get_option('csn_email_id'));


}

function csn_text()
{

    __('<p> You may change your WordPress Default mail sender name and email </p>', 'mailsender');

}


function csn_menu()
{
    add_menu_page(__('Mail Sender Options', 'mailsender'), __('Mail Sender', 'mailsender'), 'manage_options', 'mailsender', 'csn_output_html', 'dashicons-email');

    add_submenu_page('mailsender', __('Test Mail', 'mailsender'), __('Test Mail', 'mailsender'), 'manage_options', 'csn_test_mail', 'csn_test_mail');

}

add_action('admin_menu', 'csn_menu');


//form of email info
function csn_output_html()
{
    ?>
    <?php settings_errors(); ?>
    <form action="options.php" method="POST">
        <?php do_settings_sections('mailsender'); ?>
        <?php settings_fields('csn_section'); ?>
        <?php submit_button(); ?>
    </form>
<?php }


//test mail template and function
function csn_test_mail()
{
    ?>
    <div class="wrap">
        <h1><?php _e('Test Mail', 'mailsender'); ?></h1>
        <form method="post">
            <?php
            if (isset($_POST['mail_to'])) {

                if (wp_verify_nonce($_POST['wp_test_email_nonce_field'], 'wp_test_email_nonce_action')) {
                    if (!empty($_POST['mail_to'])) {
                        $to = sanitize_email($_POST['mail_to']);
                        $subject = sanitize_text_field($_POST['mail_subject']);
                        $body = "hi,  This is a test email from " . get_bloginfo('name') . " that sent you";
                        $headers = array('Content-Type: text/html; charset=UTF-8');
                        $test_email = wp_mail($to, $subject, $body);
                        if ($test_email) {
                            ?>
                            <div class="notice notice-success is-dismissible">
                                <p><?php _e('Email has been sent!', 'mailsender'); ?></p>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="notice notice-error is-dismissible">
                                <p><?php _e('Email not sent!', 'mailsender'); ?></p>
                            </div>
                            <?php
                        }
                    }
                }
            }
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('To', 'mailsender'); ?></th>
                    <td>
                        <input type="email" name="mail_to" value=""/>
                        <p class="description"><i><?php _e('Enter "To address" here.', 'mailsender'); ?></i></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><?php _e('Subject', 'mailsender'); ?></th>
                    <td>
                        <input type="text" name="mail_subject" value="Test Mail"/>
                        <p class="description"><i><?php _e('Enter mail subject here', 'mailsender'); ?></i></p>
                    </td>
                </tr>
            </table>
            <?php wp_nonce_field('wp_test_email_nonce_action', 'wp_test_email_nonce_field'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Change the default wordpress email address
add_filter('wp_mail_from', '_new_mail_from');
add_filter('wp_mail_from_name', '_new_mail_from_name');

function _new_mail_from($old)
{
    return get_option('csn_email_id');
}

function _new_mail_from_name($old)
{
    return get_option('csn_id');
}