<?php

namespace WP_Timeliner\Queries;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_Timeliner\Common\Interfaces\Has_Hooks;
use WP_Timeliner\Helpers;
use WP_Timeliner\Schema\Post_Type_Achievement;
use WP_Timeliner\Schema\Taxonomy_Timeline;

/**
 * Logic related to Achievements queries
 */
class Achievement implements Has_Hooks {
	/**
	 * Register necessary hooks.
	 */
	public function hooks() {
		add_action( 'pre_get_posts', [ $this, 'order_achievements_by_start_date' ] );
	}

	/**
	 * Order Achievements by start date metadata
	 *
	 * @param WP_Query $query
	 * @return void
	 */
	public function order_achievements_by_start_date( $query ) {
		if ( $query->is_main_query() && ( is_post_type_archive( Post_Type_Achievement::POST_TYPE ) || is_tax( Taxonomy_Timeline::TAXONOMY ) ) ) {
			$query->set( 'posts_per_page', '50' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_key', '_achievement_start_date' );
			$query->set( 'order', 'DESC' );
		}
	}
}
