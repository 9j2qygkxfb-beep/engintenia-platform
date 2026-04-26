<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Engintenia_Roles {
	public static function activate() {
		add_role(
			'eng_company',
			__( 'Company', 'engintenia-platform' ),
			array(
				'read'                  => true,
				'upload_files'          => true,
				'edit_eng_projects'     => true,
				'publish_eng_projects'  => true,
				'delete_eng_projects'   => true,
			)
		);

		add_role(
			'eng_subcontractor',
			__( 'Subcontractor', 'engintenia-platform' ),
			array(
				'read'         => true,
				'upload_files' => true,
			)
		);

		$admin = get_role( 'administrator' );
		if ( $admin ) {
			$admin->add_cap( 'edit_eng_projects' );
			$admin->add_cap( 'edit_others_eng_projects' );
			$admin->add_cap( 'publish_eng_projects' );
			$admin->add_cap( 'read_private_eng_projects' );
			$admin->add_cap( 'delete_eng_projects' );
			$admin->add_cap( 'manage_engintenia' );
		}
	}
}
