<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       codeable.io/developers/stacey-blaschke
 * @since      1.0.0
 *
 * @package    Mb_Agreement
 * @subpackage Mb_Agreement/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mb_Agreement
 * @subpackage Mb_Agreement/includes
 * @author     Stacey <codeable@sunlitstud.io>
 */
class Mb_Agreement {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Mb_Agreement_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'MB_AGREEMENT_VERSION' ) ) {
			$this->version = MB_AGREEMENT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'mb-agreement';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Mb_Agreement_Loader. Orchestrates the hooks of the plugin.
	 * - Mb_Agreement_i18n. Defines internationalization functionality.
	 * - Mb_Agreement_Admin. Defines all hooks for the admin area.
	 * - Mb_Agreement_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mb-agreement-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mb-agreement-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mb-agreement-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-mb-agreement-public.php';

		$this->loader = new Mb_Agreement_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Mb_Agreement_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Mb_Agreement_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Mb_Agreement_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Mb_Agreement_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		/**
		 * Allow HTML field contents to use merge tags to insert field values
		 * from previous pages. This makes it easy to create a final page with a
		 * "Please Review Your Entry" HTML field that shows the user critical
		 * values they entered on previous pages.
		 * 
		 * All of below is to add functionality to the membership agreement form which is hardcoded as being
		 * form 1 as shown by the _1 at the end of  each filter name below
		 */
		$this->loader->add_filter( 'gform_field_content_1', $plugin_public, 'enable_merge_tags_in_html_fields' , 10, 5 );
		$this->loader->add_filter( 'gform_field_content_1_30', $plugin_public, 'get_agreement' , 10, 5 );
		$this->loader->add_filter( 'gform_submit_button_1', $plugin_public, 'add_logout_link' , 10, 2 );
		$this->loader->add_filter( 'login_redirect', $plugin_public, 'check_if_agreementsigned', 10, 3 );
		$this->loader->add_filter( 'gform_after_submission_1', $plugin_public, 'add_user_signed', 10, 2 );
		$this->loader->add_action( 'template_redirect', $plugin_public, 'stay_on_agreement_until_signed' );
		$this->loader->add_filter( 'gform_validation_message_1', $plugin_public, 'change_error_msg', 10, 2 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Mb_Agreement_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
