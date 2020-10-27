<?php
/**
 * Theme init.
 *
 * @since 1.0.0
 */

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'THE7_MINIMUM_COMPATIBLE_WP_VERSION', '4.9.0' );

/**
 * Display notice about incompatible WP version.
 *
 * @since 7.5.0
 */
function the7_incompatible_wp_version_notice() {
	?>
    <div class="notice notice-error">
        <p>
            <strong><?php echo esc_html_x( 'The7 detected incompatible WordPress version!', 'admin', 'the7mk2' ) ?></strong>
        </p>
        <p>
            <?php echo esc_html( sprintf( _x( 'Minimum compatible version of WordPress is %s. Please, update your WordPress installation to be able to use The7 theme.', 'admin', 'the7mk2' ), THE7_MINIMUM_COMPATIBLE_WP_VERSION ) ) ?>
        </p>
        <p>
            <a href="<?php echo admin_url( '/update-core.php' ) ?>"><?php
		        echo esc_html( _x( 'Update WordPress.', 'admin', 'the7mk2' ) );
		        ?></a>
        </p>
    </div>
	<?php
}

/**
 * @var string $wp_version
 */

// include an unmodified $wp_version.
include ABSPATH . WPINC . '/version.php';

if ( version_compare( $wp_version, THE7_MINIMUM_COMPATIBLE_WP_VERSION, '<' ) ) {
	add_action( 'admin_notices', 'the7_incompatible_wp_version_notice' );

	return;
}

// load constants.
require_once trailingslashit( get_template_directory() ) . 'inc/constants.php';

require_once PRESSCORE_DIR . '/class-the7-autoloader.php';
new The7_Aoutoloader();

if ( ! class_exists( 'Color', false ) ) {
	require_once PRESSCORE_EXTENSIONS_DIR . '/color.php';
}

require_once PRESSCORE_DIR . '/deprecated-functions.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/aq_resizer.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/core-functions.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/stylesheet-functions.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/dt-pagination.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/presscore-web-fonts-compressor.php';

// less
require_once PRESSCORE_DIR . '/vendor/lessphp/the7_lessc.inc.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/class-less.php';
include_once PRESSCORE_EXTENSIONS_DIR . '/less-vars/less-functions.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/less-vars/class-composition.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/less-vars/class-factory.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/less-vars/class-builder.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/less-vars/class-color.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/less-vars/class-number.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/less-vars/class-image.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/less-vars/class-font.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/less-vars/interface-manager.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/less-vars/class-manager.php';
include_once PRESSCORE_CLASSES_DIR . '/less/interface-the7-less-gradient-color-stop.php';
include_once PRESSCORE_CLASSES_DIR . '/less/class-the7-less-gradient.php';
include_once PRESSCORE_CLASSES_DIR . '/less/class-the7-less-gradient-color-stop.php';
include_once PRESSCORE_CLASSES_DIR . '/class-the7-fancy-title-css.php';

// utils
require_once PRESSCORE_EXTENSIONS_DIR . '/class-presscore-simple-bag.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/class-presscore-template-manager.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/class-presscore-query.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/class-opengraph.php';
require_once PRESSCORE_EXTENSIONS_DIR . '/class-the7-remote-api.php';

if ( ! defined( 'OPTIONS_FRAMEWORK_VERSION' ) ) {
	require_once PRESSCORE_EXTENSIONS_DIR . '/options-framework/options-framework.php';
	require_once PRESSCORE_ADMIN_DIR . '/theme-options-parts.php';

	add_filter( 'options_framework_location', 'presscore_add_theme_options' );
}

/**
 * Include utility classes.
 */
require_once PRESSCORE_CLASSES_DIR . '/template-config/presscore-config.class.php';

require_once PRESSCORE_CLASSES_DIR . '/layout/columns-layout-parser.class.php';
require_once PRESSCORE_CLASSES_DIR . '/layout/sidebar-layout-parser.class.php';

require_once PRESSCORE_CLASSES_DIR . '/abstract-presscore-ajax-content-builder.php';

require_once PRESSCORE_CLASSES_DIR . '/tags.class.php';
require_once PRESSCORE_CLASSES_DIR . '/class-presscore-post-type-rewrite-rules-filter.php';
require_once PRESSCORE_CLASSES_DIR . '/class-the7-image-width-calculator-config.php';
require_once PRESSCORE_CLASSES_DIR . '/class-the7-image-bwb-width-calculator.php';
require_once PRESSCORE_CLASSES_DIR . '/image/class-the7-image-list-width-calculator.php';
require_once PRESSCORE_CLASSES_DIR . '/image/class-the7-image-list-width-calculator-config.php';
require_once PRESSCORE_CLASSES_DIR . '/class-the7-avatar.php';
require_once PRESSCORE_CLASSES_DIR . '/class-the7-slideshow-stub.php';

require_once PRESSCORE_DIR . '/helpers.php';
require_once PRESSCORE_DIR . '/template-hooks.php';

include_once locate_template( 'inc/widgets/load-widgets.php' );
include_once locate_template( 'inc/shortcodes/load-shortcodes.php' );

// Setup theme.
require_once PRESSCORE_DIR . '/theme-setup.php';

// Dynamic stylesheets.
require_once PRESSCORE_DIR . '/dynamic-stylesheets-functions.php';

// Frontend functions.
require_once PRESSCORE_DIR . '/static.php';

// Ajax functions.
require_once PRESSCORE_DIR . '/ajax-functions.php';

// Legacy.
The7_Admin_Dashboard_Settings::setup();

require_once PRESSCORE_MODS_DIR . '/legacy/legacy.php';
require_once PRESSCORE_ADMIN_DIR . '/admin-notices.php';

require_once PRESSCORE_MODS_DIR . '/dev-tools/main-module.class.php';
The7_DevToolMainModule::init();

if ( is_admin() ) {
	require_once PRESSCORE_ADMIN_DIR . '/class-the7-admin-dashboard.php';
	$the7_admin_dashboard = new The7_Admin_Dashboard();
	$the7_admin_dashboard->init();
	require_once PRESSCORE_ADMIN_DIR . '/admin-functions.php';
	include_once PRESSCORE_ADMIN_DIR . '/the7-site-health-tests.php';
	require_once PRESSCORE_ADMIN_DIR . '/admin-bulk-actions.php';

	include_once locate_template( 'inc/admin/load-meta-boxes.php' );

	require_once PRESSCORE_EXTENSIONS_DIR . '/class-the7-theme-auto-deactivation.php';
	The7_Theme_Auto_Deactivation::add_hooks();

	The7_Fancy_Title_CSS::bootstrap();
}
