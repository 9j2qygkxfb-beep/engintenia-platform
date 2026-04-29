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
		$defaults = array( 'CCTV Systems', 'Networking', 'Solar Installations', 'Security', 'Electrical Works', 'MEP Contracting' );
		foreach ( $defaults as $term ) {
			if ( ! term_exists( $term, 'eng_project_category' ) ) {
				wp_insert_term( $term, 'eng_project_category' );
			}
		}
	}

	public static function seed_demo_projects() {
		if ( (int) get_option( 'eng_demo_projects_seeded', 0 ) ) {
			return;
		}
		$countries = Engintenia_Shortcodes::countries();
		$categories = get_terms( array( 'taxonomy' => 'eng_project_category', 'hide_empty' => false ) );
		for ( $i = 1; $i <= 100; $i++ ) {
			$project_id = wp_insert_post(
				array(
					'post_type'    => 'eng_project',
					'post_status'  => 'publish',
					'post_title'   => sprintf( __( 'Demo Engineering Project %d', 'engintenia-platform' ), $i ),
					'post_content' => __( 'Generated demo project for marketplace showcase.', 'engintenia-platform' ),
				)
			);
			if ( $project_id ) {
				update_post_meta( $project_id, '_eng_budget', '$' . wp_rand( 5000, 120000 ) );
				update_post_meta( $project_id, '_eng_country', $countries[ array_rand( $countries ) ] );
				update_post_meta( $project_id, '_eng_duration', wp_rand( 1, 12 ) . ' months' );
				if ( ! empty( $categories ) ) {
					wp_set_object_terms( $project_id, array( $categories[ array_rand( $categories ) ]->term_id ), 'eng_project_category' );
				}
			}
		}
		update_option( 'eng_demo_projects_seeded', 1 );
	}

	public static function activate() {
		self::register();
		self::seed_demo_projects();
		flush_rewrite_rules();
	}
}
