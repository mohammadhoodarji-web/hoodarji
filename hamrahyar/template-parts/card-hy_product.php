<?php
/**
 * کارت تجهیزات.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

$hy_id    = get_the_ID();
$hy_group = hy_first_term( $hy_id, 'hy_product_group' );
$hy_price = (int) get_post_meta( $hy_id, 'hy_price', true );
$hy_specs = get_post_meta( $hy_id, 'hy_specs', true );
?>
<article <?php post_class( 'card product-card' ); ?>>
	<a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php echo hy_product_image_html( $hy_id, 'medium_large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- helper escapes attributes. ?>
	</a>
	<div class="card__body">
		<?php if ( $hy_group ) : ?>
			<p class="card__meta"><?php echo esc_html( $hy_group->name ); ?></p>
		<?php endif; ?>
		<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php if ( $hy_specs ) : ?>
			<p class="card__text"><?php echo esc_html( $hy_specs ); ?></p>
		<?php endif; ?>
		<?php if ( $hy_price ) : ?>
			<div class="card__foot">
				<p class="price"><?php echo esc_html( hy_money( $hy_price ) ); ?> <small>تومان</small></p>
			</div>
		<?php endif; ?>
	</div>
</article>
