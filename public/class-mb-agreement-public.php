<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       codeable.io/developers/stacey-blaschke
 * @since      1.0.0
 *
 * @package    Mb_Agreement
 * @subpackage Mb_Agreement/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Mb_Agreement
 * @subpackage Mb_Agreement/public
 * @author     Stacey <codeable@sunlitstud.io>
 */
class Mb_Agreement_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mb_Agreement_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mb_Agreement_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mb-agreement-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Mb_Agreement_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Mb_Agreement_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mb-agreement-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Redirect the login ot the  membership agreement form
	 *
	 * @since    1.0.0
	 */
	public function check_if_agreementsigned( $redirect_to, $request, $user ) {


		if ( ! empty( $user->roles ) && !in_array( 'administrator', (array) $user->roles ) && !in_array( 'author', (array) $user->roles ) && !in_array( 'editor', (array) $user->roles )  && !in_array( 'contributor', (array) $user->roles )) {
			$signed = get_user_meta( $user->ID, 'signed_agreement', true);
			if ( ! isset( $signed ) || $signed != true ) {
			  $redirect_to =  '/license-agreement-prompt/';
			}
		}

		return $redirect_to;
	}

	/**
	 * Change the validation error message at the top of the page
	 *
	 * @since    1.0.0
	 */
	public function change_error_msg( $message, $form) {


		$message = '<div class="validation_error"><span class="warning-stop">!</span>' . esc_html__( 'Please read and accept the license agreement to access this site.', 'gravityforms' )  . '</div>';

		return $message;
	}

	/**
	 * Store user signed and full name after successful validation and submission of agreement form
	 *
	 * @since    1.0.0
	 */
	public function add_user_signed( $entry, $form ) {

        $current_user_id = get_current_user_id();
		if ( isset( $current_user_id ) )  {
			$full_name = rgar($entry, 20);
			update_user_meta($current_user_id, 'signed_agreement', true);
            update_user_meta($current_user_id, 'fullname', $full_name);
		}
	}

	/**
	 * If user has not signed agreement do not let them leave the agreement page
	 *
	 * @since    1.0.0
	 */
	public function stay_on_agreement_until_signed() {
		$user = wp_get_current_user();
		$signed = get_user_meta(  get_current_user_id(), 'signed_agreement', true);
		$allowed_pages = array( 450, 451 );

		if ( is_user_logged_in()
			&& ! empty( $user->roles )
			&& !in_array( 'administrator', (array) $user->roles )
			&& !in_array( 'author', (array) $user->roles )
			&& !in_array( 'editor', (array) $user->roles )
			&& !in_array( 'contributor', (array) $user->roles )
			&& ( !isset( $signed) || empty( $signed) )
			&& !is_page( $allowed_pages ) ) {
				wp_redirect( get_permalink( 450) ); /* the page id of the member agreement prompt page */
				exit();
		}
	}

	/**
	 * Add a Logout Link to the member agreement form
	 *
	 * @since    1.0.0
	 */
	public function add_logout_link( $button, $form ) {

		$logout = '<a class="agreement-logout" href="' . wp_logout_url() . '">Log out</a>';

	    $button = $button . $logout;

		return $button;
	}

	/**
	 * Get Agreement text and place in form
	 *
	 * @since    1.0.0
	 */
	public function get_agreement( $field_content, $field, $value, $entry_id, $form_id ) {

		$agreement_id = 451; /* the page id of the member agreement */
		$agreement_text = get_post($agreement_id);
		$content = apply_filters('the_content', $agreement_text->post_content);
		$content = wp_specialchars_decode( $content );


		return '<div class="membership-agreement-text-area" style="display:none" >
					<a href="' . get_the_permalink( $agreement_id ) . '" target="_blank">Open in new window.</a>' . $content . '</div>';
	}

	/**
	 * Allow Gravity Form merge tags to be used in HTML fields
	 *
	 * @since    1.0.0
	 */
	public function enable_merge_tags_in_html_fields( $field_content, $field, $value, $entry_id, $form_id ) {

		if( 'html' != $field->type || false === strpos( $field_content, '{' ) ) {
			return $field_content;
		}

		//Borrowing this regex from GF_Common
		// Replacing field variables: {FIELD_LABEL:FIELD_ID} {My Field:2}.
		preg_match_all( '/{[^{]*?:(\d+(\.\d+)?)(:(.*?))?}/mi', $field_content, $matches, PREG_SET_ORDER );
		if ( ! is_array( $matches ) ) {
			return $field_content;
		}

		foreach ( $matches as $match ) {
			$input_id = $match[1];

			//Easy, fields with one input element
			if( ! empty( rgpost( 'input_' . $input_id ) ) ) {
				$field_content = str_replace( $match[0], rgpost( 'input_' . $input_id ), $field_content );
				continue;
			}

			/**
			 * Perhaps the field has multiple inputs, like the name field or
			 * a list of strings
			 */
			$tag_field = RGFormsModel::get_field( $form_id, $input_id );
			if( ! is_array( $tag_field->inputs ) ) {
				//nope
				continue;
			}
			$merge_tag_values = array();
			foreach( $tag_field->inputs as $key => $input ) {
				if( isset( $input['isHidden'] ) && $input['isHidden'] ) {
					continue;
				}

				//this ID is one of the ones you want
				$input_id = str_replace( '.', '_', $input['id'] );
				if( empty( rgpost( 'input_' . $input_id ) ) ) {
					continue;
				}
				$merge_tag_values[] = rgpost( 'input_' . $input_id );
			}

			/**
			 * The glue character in this implode() should be a space for
			 * the Name type field, but probably a line break or comma and
			 * space for the checkbox and list fields.
			 */
			$glue_characters = ' ';
			switch( $tag_field->type ) {
				case 'checkbox':
				case 'list':
					$glue_characters = ', ';
					break;
			}

			$field_content = str_replace( $match[0], implode( $glue_characters, $merge_tag_values ), $field_content );
		}
		return $field_content;
	}

}
