<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              codeable.io/developers/stacey-blaschke
 * @since             1.0.0
 * @package           Mb_Agreement
 *
 * @wordpress-plugin
 * Plugin Name:       Membership Agreement
 * Plugin URI:        tbd
 * Description:       This adds the form and the rules to display the MemberShip Agreement if the user has not signed the Agreement yet
 * Version:           1.0.0
 * Author:            Stacey
 * Author URI:        codeable.io/developers/stacey-blaschke
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mb-agreement
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MB_AGREEMENT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mb-agreement-activator.php
 */
function activate_mb_agreement() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mb-agreement-activator.php';
	Mb_Agreement_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mb-agreement-deactivator.php
 */
function deactivate_mb_agreement() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mb-agreement-deactivator.php';
	Mb_Agreement_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mb_agreement' );
register_deactivation_hook( __FILE__, 'deactivate_mb_agreement' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mb-agreement.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_mb_agreement() {

	$plugin = new Mb_Agreement();
	$plugin->run();

}
run_mb_agreement();
