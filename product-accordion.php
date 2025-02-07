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



function allow_sample_product_accordion_to_be_inject_in_product_accordion_block( $metadata ) {
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
add_filter( 'block_type_metadata', 'allow_sample_product_accordion_to_be_inject_in_product_accordion_block' );



add_filter(
	'hooked_block_types',
	function ( $hooked_block_types, $relative_position, $anchor_block_type, $context ) {

		$anchor_info = array(
			'block_type'         => 'woocommerce/accordion-group',
			'position_to_anchor' => 'last_child',
		);

		if ( BlockifiedProductDetailsUtils::should_hook_accordion_item_in_product_details( $anchor_info, $anchor_block_type, $relative_position, $context ) ) {
			$hooked_block_types[] = 'sample/product-accordion';

		}

		return $hooked_block_types;
	},
	10,
	4
);

add_filter(
	'hooked_block_sample/product-accordion',
	function ( $parsed_hooked_block, $hooked_block_type, $relative_position, $parsed_anchor_block, $context) {
		$parsed_block_from_mockup = '<!-- wp:sample/product-accordion -->
<div class="wp-block-sample-product-accordion"><!-- wp:woocommerce/accordion-item {"openByDefault":true} -->
<div class="is-open wp-block-woocommerce-accordion-item"><!-- wp:woocommerce/accordion-header -->
<h3 class="wp-block-woocommerce-accordion-header accordion-item__heading"><button class="accordion-item__toggle"><span>Test</span><span class="accordion-item__toggle-icon has-icon-plus" style="width:1.2em;height:1.2em"><svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M11 12.5V17.5H12.5V12.5H17.5V11H12.5V6H11V11H6V12.5H11Z" fill="currentColor"></path></svg></span></button></h3>
<!-- /wp:woocommerce/accordion-header -->
<!-- wp:woocommerce/accordion-panel -->
<div class="wp-block-woocommerce-accordion-panel"><div class="accordion-content__wrapper"><!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eget turpis eget nunc fermentum ultricies. Nullam nec sapien nec0</p>
<!-- /wp:paragraph --></div></div>
<!-- /wp:woocommerce/accordion-panel --></div>
<!-- /wp:woocommerce/accordion-item --></div>
<!-- /wp:sample/product-accordion --></div>';

		$parsed_block_from_mockup = parse_blocks( $parsed_block_from_mockup );
		return $parsed_block_from_mockup[0];

	},
	10,
	5
);



