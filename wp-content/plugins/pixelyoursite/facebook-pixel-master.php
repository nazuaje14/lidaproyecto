<?php

/**
 * Plugin Name: PixelYourSite
 * Plugin URI: http://www.pixelyoursite.com/
 * Description: No codding <strong>Facebook Pixel</strong> and <strong>Google Analytics</strong> install. Track key actions with our Global Events, or configure your own events. WooCommerce and EDD fully supported, with Facebook Dynamic Ads Pixel set-up and Google Analytics Enhanced Ecommerce. Insert any custom script with our Head & Footer option. Add the <strong>Pinterest Tag</strong> with our free add-on. The PRO version adds support for the Google Ads tag plus a lot of extra stuff.
 * Version: 7.1.14
 * Author: PixelYourSite
 * Author URI: http://www.pixelyoursite.com
 * License: GPLv3
 *
 * Requires at least: 4.4
 * Tested up to: 5.4
 *
 * WC requires at least: 2.6.0
 * WC tested up to: 4.5
 *
 * Text Domain: pys
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

function isPysProActive() {

    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    return is_plugin_active( 'pixelyoursite-pro/pixelyoursite-pro.php' );

}

register_activation_hook( __FILE__, 'pysFreeActivation' );
function pysFreeActivation() {

    if ( isPysProActive() ) {
        deactivate_plugins('pixelyoursite-pro/pixelyoursite-pro.php');
    }

    \PixelYourSite\manageAdminPermissions();
}
/**
 * facebook-pixel-master.php used for backward compatibility.
 */
require_once 'pixelyoursite.php';
