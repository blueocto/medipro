<?php
/**
 * Loop Price
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
?>

<?php /* SKU */
	global $post, $product; 
	if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : 
?>
	<p class="product_code"><?php _e( 'Product Code:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'n/a', 'woocommerce' ); ?></span>.</p>
<?php endif; ?>

<p class="the_excerpt">
	<?php /* excerpt */
		$content = get_the_excerpt(); 
	$trimmed_content = wp_trim_words( $content, 20, '&hellip;' ); 
	echo $trimmed_content;
	?>
</p>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<p class="price"><?php echo $price_html; ?></p>
<?php endif; ?>

<a class="btn btn_viewcourses btn_viewproduct" href="<?php echo get_permalink( $product->ID ) ?>">View Product</a>