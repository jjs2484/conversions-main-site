<?php
/**
 * Plugin Name: Conversions Main Site
 * Description: Styles, scripts, and functions.
 * Version: 1.0.0
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
		add_action( 'conversions_homepage_after_features', [ $this, 'add_features_button' ], 777 );
		add_action( 'init', [ $this, 'register_cpt_docs' ] );
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

}
$conversions_main_site = new Conversions_Main_Site();
