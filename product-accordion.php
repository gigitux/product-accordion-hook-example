<?php
use Automattic\WooCommerce\Blocks\Utils\BlockifiedProductDetailsUtils;

/**
 * Plugin Name:       Sample Product Accordion
 * Description:       Example block scaffolded with Create Block tool.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       product-accordion
 *
 * @package Sample
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function sample_product_accordion_block_init() {
	register_block_type( __DIR__ . '/build/product-accordion' );
}
add_action( 'init', 'sample_product_accordion_block_init' );


function my_filter_block_type_metadata( $metadata ) {
	if ( 'woocommerce/accordion-group' === $metadata['name'] ) {
		$metadata['allowedBlocks'] = array_merge(
			$metadata['allowedBlocks'],
			array( 'sample/product-accordion' )
		);
	}

	if ( 'woocommerce/accordion-item' === $metadata['name'] ) {
		$metadata['parent'] = array_merge(
			$metadata['parent'],
			array( 'sample/product-accordion' )
		);
	}
	return $metadata;
}
add_filter( 'block_type_metadata', 'my_filter_block_type_metadata' );



add_filter(
	'hooked_block_types',
	function ( $hooked_block_types, $relative_position, $anchor_block_type, $context ) {

		$anchor_info = array(
			'block_type'         => 'woocommerce/accordion-group',
			'position_to_anchor' => 'last_child',
		);

		if ( BlockifiedProductDetailsUtils::is_hook_accordion_item_block_to_anchor( $anchor_info, $anchor_block_type, $relative_position, $context ) ) {
			$hooked_block_types[] = 'sample/product-accordion';

		}

		return $hooked_block_types;
	},
	10,
	4
);

// add_filter(
// 	'hooked_block_types',
// 	function ( $hooked_block_types, $relative_position, $anchor_block_type, $context ) {



// 		if ( 'core/post-content' === $anchor_block_type && 'after' === $relative_position ) {
// 			$hooked_block_types[] = 'core/paragraph';

// 		}

// 		return $hooked_block_types;
// 	},
// 	10,
// 	4
// );

// function modify_hooked_copyright_date_block_in_footer( $parsed_hooked_block, $hooked_block_type, $relative_position, $parsed_anchor_block, $context  ) {

// 	// Has the hooked block been suppressed by a previous filter?
// 	if ( is_null( $parsed_hooked_block ) ) {
// 		return $parsed_hooked_block;
// 	}

// 	// Only apply the updated attributes if the block is hooked after a Site Title block.
// 	if (
// 		'core/post-content' === $parsed_anchor_block['blockName'] && 'after' === $relative_position
// 	) {
// 		$parsed_hooked_block['attrs'] = array(
// 			'content'     => '2019',
// 		);

// 		$parsed_hooked_block['innerContent'] = array(
// 			'<p><a href="#">' . __( 'Back to top' ) . '</a></p>'
// 		);
// 	}

// 	return $parsed_hooked_block;
// }
// add_filter( 'hooked_block_core/paragraph', 'modify_hooked_copyright_date_block_in_footer', 10, 5 );
