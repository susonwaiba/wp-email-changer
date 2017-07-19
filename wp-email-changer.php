<?php
/*
Plugin Name: WP Email Changer
Description: Just a plugin which will change default wordpress@domain.com to anything you wish.
Version: 1.0.0
Author: Suson Waiba
Author URI: http://susonwaiba.com
License: GPL2

== Copyright ==
Copyright 2017 Suson Waiba (susonwaiba.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/



add_action('admin_menu', 'wp_email_changer_setup');

function wp_email_changer_setup()
{
    add_submenu_page('options-general.php', 'WP Email Changer', 'WP Email Changer', 'administrator', 'wp-email-changer', 'wp_email_changer_bootstrap');
    add_action('admin_init', 'register_wp_email_changer_settings');
}

function register_wp_email_changer_settings()
{
    register_setting('wp-email-changer', 'wp_email_changer_name');
    register_setting('wp-email-changer', 'wp_email_changer_address');
}

function wp_email_changer_bootstrap()
{

    if(isset($_GET['test-email'])) {
        $test_email = htmlspecialchars($_GET['test-email']);
        $headers = array('Content-Type: text/html; charset=UTF-8');
        // wp_mail( $test_email, 'Just a test email from Wordpress', "Hi there,\nThis is just a test email send from Su WP Email Plugin.", $headers );
        ?>
        <div class="wrap">
            <h1>WP Email Changer</h1>
            <div class="card" style="max-width: 100%;">
                <h4>Test Email has been send to "<strong><?php echo $test_email; ?></strong>"</h4>
                <a class="button button-primary" href="<?php echo admin_url('admin.php?page=wp-email-changer'); ?>">Back to settings</a>
            </div>
        </div>
        <?php
        die();
    };
    ?>
    <div class="wrap">
        <h1>WP Email Changer</h1>

        <div class="card" style="max-width: 100%;">
            <h2>Enter Name & Email that you want to change to:</h2>
            <?php
            if ( isset( $_GET['settings-updated'] ) ) {
                add_settings_error( 'wp-email-changer-settings-messages', 'wp-email-changer-settings-message', __( 'Settings Saved', 'wp-email-changer-settings' ), 'updated' );
            }
            settings_errors( 'order-settings-messages' );
            ?>
            <form method="post" action="options.php">
                <?php settings_fields( 'wp-email-changer' ); ?>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="wp-email-changer-name">Name</label>
                            </th>
                            <td>
                                <input name="wp_email_changer_name" type="text" id="wp-email-changer-name" class="regular-text" value="<?php echo esc_attr( get_option('wp_email_changer_name') ); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="wp-email-changer-address">Email</label>
                            </th>
                            <td>
                                <input name="wp_email_changer_address" type="email" id="wp-email-changer-address" class="regular-text" value="<?php echo esc_attr( get_option('wp_email_changer_address') ); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td><?php submit_button(); ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php settings_fields( 'wp-email-changer' ); ?>

            </form>
        </div>

        <div class="card" style="max-width: 100%;">
            <form method="GET" action="<?php echo admin_url('options-general.php'); ?>">
                <input type="hidden" name="page" value="wp-email-changer">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="test-email">Test Email</label>
                            </th>
                            <td>
                                <input name="test-email" type="email" id="test-email" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td><input class="button button-primary" type="submit" name="submit" value="Send Test Email"></td>
                        </tr>
                    </tbody>
                </table>

            </form>
        </div>

    </div>
    <p>- Just a plugin which will change default wordpress@domain.com to anything you wish.<br>- by: <a href="http://susonwaiba.com" target="_blank">Suson Waiba</p>
    <?php
}


if (!empty(esc_attr( get_option('wp_email_changer_address'))) && !empty(esc_attr( get_option('wp_email_changer_name'))))
{
    add_filter('wp_mail_from', 'new_mail_from');
    add_filter('wp_mail_from_name', 'new_mail_from_name');
    
    function new_mail_from($old) {
        return esc_attr( get_option('wp_email_changer_address'));
    }
    function new_mail_from_name($old) {
        return esc_attr( get_option('wp_email_changer_name'));
    }
}