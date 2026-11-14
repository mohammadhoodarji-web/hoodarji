<?php
/**
 * کارت نوشته‌ی معمولی.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;
?>
<article <?php post_class( 'card' ); ?>>
	<div class="card__body">
		<p class="card__meta"><?php echo esc_html( get_the_date() ); ?></p>
		<h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="card__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
	</div>
</article>
