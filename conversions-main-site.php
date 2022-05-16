<?php
/**
 * Plugin Name: Conversions Main Site
 * Description: Styles, scripts, and functions.
 * Version: 1.0.9
 * Author: js2484
 * Author URI: https://conversionswp.com
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Conversions_Main_Site class.
 *
 * @since 2020-03-14
 */
class Conversions_Main_Site {
	/**
	 * Class constructor.
	 *
	 * @since 2020-02-09
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'scripts' ], 777 );
		// old hook.
		add_action( 'conversions_homepage_after_features', [ $this, 'add_features_button' ], 777 );
		// new hook.
		add_action( 'conversions_after_icon_features', [ $this, 'add_features_button' ], 777 );
		add_action( 'init', [ $this, 'register_cpt_docs' ] );
		add_action( 'conversions_footer_info', [ $this, 'gpl_footer_note' ], 30 );
		add_action( 'wp_print_scripts', [ $this, 'conditionally_load_cf_js_css' ] );
		add_filter( 'theme_page_templates', [ $this, 'pe_custom_page_template_select' ], 10, 4 );
		add_filter( 'template_include', [ $this, 'pe_custom_page_template_load' ] );
		add_filter( 'wp_nav_menu_items', [ $this, 'wp_nav_menu_items' ], 777, 2 );
		add_shortcode( 'theme_stats', [ $this, 'theme_stats' ] );
		add_filter( 'wp_nav_menu', [ $this, 'conversions_menu_notitle' ] );
		add_filter( 'conversions_nav_open_wrapper', [ $this, 'conversions_nav_open_wrapper' ] );
		add_action( 'wp_head', [ $this, 'conversions_topbar_style' ], 9999 );
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @since 2020-03-14
	 */
	public function scripts() {
		// Styles.
		$my_css_ver = gmdate( 'ymd-Gis', filemtime( plugin_dir_path( __FILE__ ) . '/css/additional.min.css' ) );
		wp_enqueue_style(
			'conversions-additional-css',
			plugin_dir_url( __FILE__ ) . 'css/additional.min.css',
			array(),
			$my_css_ver
		);
	}

	/**
	 * Features section hook
	 *
	 * @since 2020-03-15
	 */
	public function add_features_button() {
		if ( is_page_template( 'page-templates/homepage.php' ) ) {
			echo sprintf(
				'<div class="%s"><a href="%s" class="%s">%s</a></div>',
				esc_attr( 'col-12 text-center mb-4' ),
				esc_url( site_url( '/features/' ) ),
				esc_attr( 'btn btn-lg btn-outline-primary py-1' ),
				esc_html__( 'See all features', 'conversions' )
			);
		}
	}

	/**
	 * Post Type: Docs.
	 *
	 * @since 2020-03-14
	 */
	public function register_cpt_docs() {

		$labels = [
			'name'                     => __( 'Docs', 'conversions' ),
			'singular_name'            => __( 'Doc', 'conversions' ),
			'menu_name'                => __( 'Docs', 'conversions' ),
			'all_items'                => __( 'All Docs', 'conversions' ),
			'add_new'                  => __( 'Add new', 'conversions' ),
			'add_new_item'             => __( 'Add new Doc', 'conversions' ),
			'edit_item'                => __( 'Edit Doc', 'conversions' ),
			'new_item'                 => __( 'New Doc', 'conversions' ),
			'view_item'                => __( 'View Doc', 'conversions' ),
			'view_items'               => __( 'View Docs', 'conversions' ),
			'search_items'             => __( 'Search Docs', 'conversions' ),
			'not_found'                => __( 'No Docs found', 'conversions' ),
			'not_found_in_trash'       => __( 'No Docs found in trash', 'conversions' ),
			'parent'                   => __( 'Parent Doc:', 'conversions' ),
			'featured_image'           => __( 'Featured image for this Doc', 'conversions' ),
			'set_featured_image'       => __( 'Set featured image for this Doc', 'conversions' ),
			'remove_featured_image'    => __( 'Remove featured image for this Doc', 'conversions' ),
			'use_featured_image'       => __( 'Use as featured image for this Doc', 'conversions' ),
			'archives'                 => __( 'Doc archives', 'conversions' ),
			'insert_into_item'         => __( 'Insert into Doc', 'conversions' ),
			'uploaded_to_this_item'    => __( 'Upload to this Doc', 'conversions' ),
			'filter_items_list'        => __( 'Filter Docs list', 'conversions' ),
			'items_list_navigation'    => __( 'Docs list navigation', 'conversions' ),
			'items_list'               => __( 'Docs list', 'conversions' ),
			'attributes'               => __( 'Docs attributes', 'conversions' ),
			'name_admin_bar'           => __( 'Doc', 'conversions' ),
			'item_published'           => __( 'Doc published', 'conversions' ),
			'item_published_privately' => __( 'Doc published privately.', 'conversions' ),
			'item_reverted_to_draft'   => __( 'Doc reverted to draft.', 'conversions' ),
			'item_scheduled'           => __( 'Doc scheduled', 'conversions' ),
			'item_updated'             => __( 'Doc updated.', 'conversions' ),
			'parent_item_colon'        => __( 'Parent Doc:', 'conversions' ),
		];

		$args = [
			'label'                 => __( 'Docs', 'conversions' ),
			'labels'                => $labels,
			'description'           => 'Documentation',
			'public'                => true,
			'publicly_queryable'    => true,
			'show_ui'               => true,
			'show_in_rest'          => true,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'has_archive'           => false,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'delete_with_user'      => false,
			'exclude_from_search'   => false,
			'capability_type'       => 'post',
			'map_meta_cap'          => true,
			'hierarchical'          => true,
			'rewrite'               => [ 'slug' => 'docs', 'with_front' => false ],
			'query_var'             => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-media-text',
			'supports'              => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'page-attributes' ],
		];

		register_post_type( 'docs', $args );
	}

	/**
	 * GPL footer note
	 *
	 * @since 2020-04-12
	 */
	public function gpl_footer_note() {
		?>
		<div class="gpl-footer-note col-12">
			<span class="text-white">
				All products are distributed/sold under the terms of the GNU GPLv2.
			</span>
		</div>
		<?php
	}

	/**
	 * Conditionally load Contact Form 7 JS and CSS
	 *
	 * @since 2020-08-19
	 */
	public function conditionally_load_cf_js_css() {
		if ( ! is_page( 2153 ) ) {
			wp_dequeue_script( 'google-recaptcha' );
			wp_dequeue_script( 'wpcf7-recaptcha' );
			wp_dequeue_script( 'google-invisible-recaptcha' );
		}
	}

	/**
	 * Add Premium Extensions sales page custom page template.
	 *
	 * @since 2020-08-28
	 */
	public function pe_custom_page_template_select( $post_templates, $wp_theme, $post, $post_type ) {

		// Add custom template named premium-extensions.php to select dropdown.
		$post_templates['premium-extensions.php'] = __( 'Premium extensions' );

		return $post_templates;
	}

	/**
	 * Check if current page has our Premium Extensions sales custom template.
	 *
	 * If so try to load from root plugin directory.
	 *
	 * @since 2020-08-28
	 */
	public function pe_custom_page_template_load( $template ) {

		if ( get_page_template_slug() === 'premium-extensions.php' ) {
			$template = plugin_dir_path( __FILE__ ) . '/page-templates/premium-extensions.php';
		}

		if ( $template == '' ) {
			throw new \Exception( 'No template found' );
		}

		return $template;
	}

	/**
	 * Add menu items from customizer options.
	 *
	 * @since 2019-08-30
	 *
	 * @param string $items Menu items.
	 * @param string $args Arguments.
	 */
	public function wp_nav_menu_items( $items, $args ) {
		if ( $args->theme_location === 'primary' ) {
			$nav_button_2 = sprintf(
				'<li class="nav-callout-button menu-item nav-item"><a title="%1$s" href="%2$s" class="btn %3$s">%1$s</a></li>',
				esc_html( 'Premium' ),
				esc_url( '/premium-extensions/' ),
				esc_attr( 'btn-outline-dark' )
			);

			// Add the nav button to the end of the menu.
			$items .= $nav_button_2;
		}
		return $items;
	}

	/**
	 * Makes a call to the WordPress.org Themes API, v1.0
	 *
	 * @param string $action Either query_themes (a list of themes), theme_information (Information about a specific theme), hot_tags (List of the most popular theme tags), feature_list (List of valid theme tags).
	 * @param array  $api_params Parameters.
	 * @return object Only the body of the raw response as a PHP object.
	 */
	public function call_wp_api_themes( $action, $api_params = array() ) {
		$url       = 'https://api.wordpress.org/themes/info/1.0/';
		$args      = (object) $api_params;
		$http_args = array(
			'body' => array(
				'action'  => $action,
				'timeout' => 15,
				'request' => serialize( $args ),
			),
		);

		$request = wp_remote_post( $url, $http_args );

		if ( is_wp_error( $request ) ) {
			// error_log('WP_ERROR = ');error_log( print_r( $request, true ) );
			return false;
		}

		return maybe_unserialize( wp_remote_retrieve_body( $request ) );
	}

	/**
	 * Theme stats shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 */
	public function theme_stats( $atts = array() ) {

		// Default options.
		$atts = shortcode_atts(
			array(
				'slug' => 'twentytwentytwo',
				'arg'  => 'downloaded',
			),
			$atts,
		);

		// Create unique transient title for each arg.
		$transient_slug  = sanitize_title( $atts['slug'] );
		$transient_slug  = str_replace( '-', '_', $transient_slug );
		$transient_arg   = sanitize_title( $atts['arg'] );
		$transient_arg   = str_replace( '-', '_', $transient_arg );
		$transient_title = 'cabczyv_' . $transient_slug . '_' . $transient_arg;

		// If transient exists use that else call API.
		if ( get_transient( $transient_title ) !== false ) {
			$value = get_transient( $transient_title );
		} else {
			$api_params = array(
				'slug'   => $atts['slug'],
				'fields' => [
					'rating'          => true,
					'downloaded'      => true,
					'download_link'   => true,
					'last_updated'    => true,
					'homepage'        => true,
					'tags'            => true,
					'template'        => true,
					'screenshot_url'  => true,
					'active_installs' => true,
				],
			);
			$theme_object = $this->call_wp_api_themes( 'theme_information', $api_params );
			$value        = $theme_object->$transient_arg;
			set_transient( $transient_title, $value, 24 * HOUR_IN_SECONDS );
		}

		// If download count add some commas.
		if ( $transient_arg == 'downloaded' ) {
			$value = number_format( $value );
		}

		return $value;
	}

	/**
	 * Remove titles on menu -- for accessibility.
	 *
	 * @param string $menu Menu.
	 */
	public function conversions_menu_notitle( $menu ) {
		$menu = preg_replace( '/ title=\"(.*?)\"/', '', $menu );
		return $menu;
	}

	/**
	 * Navbar add notice above.
	 *
	 * @since 2021-05-22
	 *
	 * @param string $navbar_open Navbar opening wrapper.
	 */
	public function conversions_nav_open_wrapper( $navbar_open ) {

		$nav_notice = '<div role="alert" class="alert alert-info"><div class="container-fluid">Conversions v9.0 RC1 with Bootstrap 5 is available <a href="/download/" style="color:#0c5460"><strong>Download</strong></a></div></div>';

		$navbar_open = $nav_notice . $navbar_open;

		return $navbar_open;
	}

	/**
	 * Top bar styles.
	 */
	public function conversions_topbar_style() {
		?>
		<style>
			div.content-wrapper {
				margin-top: 128px;
				@media (min-width: 537px) {
					margin-top: 104px;
				}
			}
			#wrapper-navbar .alert {
				margin-bottom: 0;
			}
		</style>
		<?php
	}
}
$conversions_main_site = new Conversions_Main_Site();
