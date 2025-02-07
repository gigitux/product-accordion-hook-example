<div <?php echo get_block_wrapper_attributes(); ?>>
<?php

$product_id = get_the_ID();
$product = wc_get_product( $product_id );
$is_product_on_sale = $product->is_on_sale();

if ( $is_product_on_sale ) {
	echo $content;
} else {
	echo null;
}


?>

</div>
