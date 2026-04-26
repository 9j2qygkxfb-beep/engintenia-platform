<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engintenia_REST {
	public static function register_routes() {
		register_rest_route(
			'engintenia/v1',
			'/projects',
			array(
				'methods'             => 'GET',
				'callback'            => array( __CLASS__, 'get_projects' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'engintenia/v1',
			'/notifications',
			array(
				'methods'             => 'GET',
				'callback'            => array( __CLASS__, 'get_notifications' ),
				'permission_callback' => function() {
					return is_user_logged_in();
				},
			)
		);
	}

	public static function get_projects() {
		$query = new WP_Query(
			array(
				'post_type'      => 'eng_project',
				'posts_per_page' => 100,
			)
		);

		$data = array();
		foreach ( $query->posts as $project ) {
			$owner = get_user_by( 'id', $project->post_author );
			$item  = array(
				'id'          => $project->ID,
				'title'       => $project->post_title,
				'description' => $project->post_content,
				'budget'      => get_post_meta( $project->ID, '_eng_budget', true ),
				'country'     => get_post_meta( $project->ID, '_eng_country', true ),
				'city'        => get_post_meta( $project->ID, '_eng_city', true ),
				'category'    => wp_get_post_terms( $project->ID, 'eng_project_category', array( 'fields' => 'names' ) ),
			);

			if ( is_user_logged_in() && Engintenia_Subscriptions::has_active_subscription( get_current_user_id() ) ) {
				$item['company_name'] = $owner ? $owner->display_name : '';
				$item['email']        = $owner ? $owner->user_email : '';
				$item['phone']        = $owner ? get_user_meta( $owner->ID, 'eng_phone', true ) : '';
			}

			$data[] = $item;
		}

		return rest_ensure_response( $data );
	}

	public static function get_notifications() {
		$notifications = get_user_meta( get_current_user_id(), 'eng_notifications', true );
		return rest_ensure_response( is_array( $notifications ) ? array_reverse( $notifications ) : array() );
	}
}
