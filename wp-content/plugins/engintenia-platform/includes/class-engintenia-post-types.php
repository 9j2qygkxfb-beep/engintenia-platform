<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engintenia_Post_Types {
	public static function register() {
		register_post_type(
			'eng_project',
			array(
				'labels'       => array(
					'name'          => __( 'Projects', 'engintenia-platform' ),
					'singular_name' => __( 'Project', 'engintenia-platform' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'author' ),
				'capability_type' => array( 'eng_project', 'eng_projects' ),
				'map_meta_cap' => true,
			)
		);

		register_post_type(
			'eng_proposal',
			array(
				'labels'       => array(
					'name'          => __( 'Proposals', 'engintenia-platform' ),
					'singular_name' => __( 'Proposal', 'engintenia-platform' ),
				),
				'public'       => false,
				'show_ui'      => true,
				'show_in_rest' => false,
				'supports'     => array( 'title', 'editor', 'author' ),
			)
		);

		register_post_type(
			'eng_subscription',
			array(
				'labels'       => array(
					'name'          => __( 'Subscriptions', 'engintenia-platform' ),
					'singular_name' => __( 'Subscription', 'engintenia-platform' ),
				),
				'public'       => false,
				'show_ui'      => true,
				'show_in_rest' => false,
				'supports'     => array( 'title', 'author' ),
			)
		);

		register_taxonomy(
			'eng_project_category',
			'eng_project',
			array(
				'labels'       => array(
					'name'          => __( 'Project Categories', 'engintenia-platform' ),
					'singular_name' => __( 'Project Category', 'engintenia-platform' ),
				),
				'public'       => true,
				'show_in_rest' => true,
				'hierarchical' => true,
			)
		);

		self::register_default_categories();
	}

	public static function register_default_categories() {
		$defaults = array( 'Construction', 'ELV', 'Fire Fighting', 'Electrical', 'Mechanical', 'HVAC' );
		foreach ( $defaults as $term ) {
			if ( ! term_exists( $term, 'eng_project_category' ) ) {
				wp_insert_term( $term, 'eng_project_category' );
			}
		}
	}

	public static function activate() {
		self::register();
		flush_rewrite_rules();
	}
}
