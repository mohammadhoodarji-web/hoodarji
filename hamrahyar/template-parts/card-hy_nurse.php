<?php
/**
 * کارت پرستار.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

$hy_id        = get_the_ID();
$hy_type      = hy_first_term( $hy_id, 'hy_care_type' );
$hy_city      = hy_first_term( $hy_id, 'hy_city' );
$hy_education = get_post_meta( $hy_id, 'hy_education', true );
$hy_exp       = (int) get_post_meta( $hy_id, 'hy_experience', true );
$hy_rating    = get_post_meta( $hy_id, 'hy_rating', true );
$hy_reviews   = (int) get_post_meta( $hy_id, 'hy_reviews', true );
$hy_rate      = (int) get_post_meta( $hy_id, 'hy_daily_rate', true );

$hy_place = array();
if ( $hy_city ) {
	$hy_place[] = $hy_city->name . ( $hy_city->description ? ' (' . $hy_city->description . ')' : '' );
}
if ( $hy_exp ) {
	$hy_place[] = hy_fa( $hy_exp ) . ' سال سابقه';
}
?>
<article <?php post_class( 'card nurse-card' ); ?>>
	<div class="card__body">
		<div class="nurse-card__top">
			<?php hy_avatar(); ?>
			<div>
				<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<?php if ( $hy_type ) : ?>
					<p class="card__meta"><?php echo esc_html( $hy_type->name ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<span class="badge">احراز هویت‌شده</span>

		<?php if ( '' !== $hy_rating ) : ?>
			<p class="rating">
				<?php echo esc_html( hy_decimal( $hy_rating ) ); ?>
				<?php if ( $hy_reviews ) : ?>
					<small>(<?php echo esc_html( hy_fa( $hy_reviews ) ); ?> نظر)</small>
				<?php endif; ?>
			</p>
		<?php endif; ?>

		<?php if ( $hy_place ) : ?>
			<p class="card__meta"><?php echo esc_html( implode( ' • ', $hy_place ) ); ?></p>
		<?php endif; ?>
		<?php if ( $hy_education ) : ?>
			<p class="card__meta">تحصیلات: <?php echo esc_html( $hy_education ); ?></p>
		<?php endif; ?>

		<p class="card__text"><?php echo esc_html( get_the_excerpt() ); ?></p>

		<div class="card__foot">
			<?php if ( $hy_rate ) : ?>
				<p class="price">
					<span class="price-label">پایه دستمزد روزانه</span>
					<?php echo esc_html( hy_money( $hy_rate ) ); ?> <small>تومان</small>
				</p>
			<?php endif; ?>
			<a class="btn btn--sm" href="<?php the_permalink(); ?>">انتخاب پرستار و چت</a>
		</div>
	</div>
</article>
