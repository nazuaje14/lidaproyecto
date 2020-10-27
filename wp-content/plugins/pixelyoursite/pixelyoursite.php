<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'PYS_FREE_VERSION', '7.1.14' );
define( 'PYS_FREE_PINTEREST_MIN_VERSION', '2.0.6' );
define( 'PYS_FREE_BING_MIN_VERSION', '1.0.0' );
define( 'PYS_FREE_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'PYS_FREE_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );





if ( isPysProActive()) {
    return; // exit early when PYS PRO is active
}
require_once PYS_FREE_PATH.'/vendor/autoload.php';
require_once PYS_FREE_PATH.'/includes/functions-common.php';
require_once PYS_FREE_PATH.'/includes/functions-admin.php';
require_once PYS_FREE_PATH.'/includes/functions-custom-event.php';
require_once PYS_FREE_PATH.'/includes/functions-woo.php';
require_once PYS_FREE_PATH.'/includes/functions-edd.php';
require_once PYS_FREE_PATH.'/includes/functions-system-report.php';
require_once PYS_FREE_PATH.'/includes/functions-license.php';
require_once PYS_FREE_PATH.'/includes/functions-update-plugin.php';
require_once PYS_FREE_PATH.'/includes/functions-gdpr.php';
require_once PYS_FREE_PATH.'/includes/functions-migrate.php';
require_once PYS_FREE_PATH.'/includes/functions-optin.php';
require_once PYS_FREE_PATH.'/includes/functions-promo-notices.php';
require_once PYS_FREE_PATH.'/includes/class-pixel.php';
require_once PYS_FREE_PATH.'/includes/class-settings.php';
require_once PYS_FREE_PATH.'/includes/class-plugin.php';

require_once PYS_FREE_PATH.'/includes/class-pys.php';
require_once PYS_FREE_PATH.'/includes/class-events-manager.php';
require_once PYS_FREE_PATH.'/includes/class-custom-event.php';
require_once PYS_FREE_PATH.'/includes/class-custom-event-factory.php';
require_once PYS_FREE_PATH.'/modules/facebook/facebook.php';
require_once PYS_FREE_PATH.'/modules/google_analytics/ga.php';
require_once PYS_FREE_PATH.'/modules/head_footer/head_footer.php';

// here we go...
PixelYourSite\PYS();
