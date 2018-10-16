<?php

namespace WP_Timeliner\Schema;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_Timeliner\Common\Interfaces\Has_Hooks;
use Carbon_Fields\Field;
use WP_Timeliner\Helpers;
use WP_Timeliner\Frontend\Themes;

/**
 * Register Timeline fields
 */
class Fields_Timeline extends Abstract_Fields implements Has_Hooks {
	/**
	 * Register the Timeline metaboxes.
	 *
	 * @return array An array of Carbon_Fields\Container settings.
	 */
	protected function get_metaboxes() {
		return [
			[
				'type'      => 'term_meta',
				'id'        => 'timeline_settings',
				'title'     => __( 'Timeline settings', 'wp-timeliner' ),
				'condition' => [ 'term_taxonomy', '=', Taxonomy_Timeline::TAXONOMY ],
			],
		];
	}

	/**
	 * List fields for the "Timeline settings" page
	 *
	 * @return array An array of Carbon_Fields\Field fields.
	 */
	protected function fields_for_timeline_settings() {
		$fields         = [];
		$themes_classes = Themes::get_themes();

		$themes = array_map(
			function( $theme ) {
				return $theme->get_icon();
			},
			$themes_classes
		);

		$fields[] = Field::make( 'radio_image', 'timeline_theme', __( 'Theme', 'wp-timeliner' ) )
					->add_options( $themes );

		$date_formats = [
			'year' => date( 'Y' ),
			'month_year' => date( 'm-Y' ),
			'full_date' => date( get_option( 'date_format' ) ),
		];

		$fields[] = Field::make( 'select', 'timeline_date_format', __( 'Date format', 'wp-timeliner' ) )
					->add_options( 
						[
							'year'       => sprintf( __( 'Year only (%1$s)', 'wp-timeliner' ), $date_formats['year'] ),
							'month_year' => sprintf( __( 'Month and year (%1$s)', 'wp-timeliner' ), $date_formats['month_year'] ),
							'full_date'  => sprintf( __( 'Full date (%1$s)', 'wp-timeliner' ), $date_formats['full_date'] ),
						]
					);

		$fields[] = Field::make( 'checkbox', 'timeline_show_year_breaks', __( 'Display a separation per year.', 'wp-timeliner' ) )
					->set_option_value( 'on' )
					->set_help_text( __( 'Display the year every time two consecutive achievements go from one year to another.', 'wp-timeliner' ) );

		$fields[] = Field::make( 'text', 'timeline_button_text', __( 'Achievement buttons label', 'wp-timeliner' ) )
					->set_help_text( __( 'Override the default <em>Read more</em> achievement button label.', 'wp-timeliner' ) )
					->set_default_value( __( 'Read more', '' ) )
					->set_required();

		return $fields;
	}
}
