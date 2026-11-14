<?php
/**
 * صفحه‌ی یک کالا.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$hy_elementor_document = ( did_action( 'elementor/loaded' ) && class_exists( 'Elementor\\Plugin' ) ) ? Elementor\Plugin::instance()->documents->get( get_the_ID() ) : false;
	if ( $hy_elementor_document && $hy_elementor_document->is_built_with_elementor() ) :
		the_content();
	else :

	$hy_id    = get_the_ID();
	$hy_group = hy_first_term( $hy_id, 'hy_product_group' );
	$hy_price = (int) get_post_meta( $hy_id, 'hy_price', true );
	$hy_specs = get_post_meta( $hy_id, 'hy_specs', true );
	?>

	<header class="page-head">
		<div class="container">
			<?php if ( $hy_group ) : ?>
				<p class="page-head__kicker"><?php echo esc_html( $hy_group->name ); ?></p>
			<?php endif; ?>
			<h1><?php the_title(); ?></h1>
			<?php if ( $hy_specs ) : ?>
				<p><?php echo esc_html( $hy_specs ); ?></p>
			<?php endif; ?>
		</div>
	</header>

	<section class="section">
		<div class="container profile">
			<div class="entry">
				<?php echo hy_product_image_html( $hy_id, 'large' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- helper escapes attributes. ?>
				<?php the_content(); ?>
			</div>
			<aside class="facts">
				<dl>
					<?php if ( $hy_price ) : ?>
						<div><dt>قیمت</dt><dd><?php echo esc_html( hy_money( $hy_price ) ); ?> تومان</dd></div>
					<?php endif; ?>
				</dl>
				<a class="btn btn--ghost" href="<?php echo esc_url( hy_url( 'shop' ) ); ?>">بازگشت به فروشگاه</a>
			</aside>
		</div>
	</section>

	<?php endif; // محتوای طراحی‌شده با Elementor یا قالب پیش‌فرض. ?>
<?php endwhile; ?>

<?php
get_footer();
