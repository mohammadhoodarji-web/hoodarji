<?php
/**
 * پروفایل پرستار.
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

	$hy_id        = get_the_ID();
	$hy_type      = hy_first_term( $hy_id, 'hy_care_type' );
	$hy_city      = hy_first_term( $hy_id, 'hy_city' );
	$hy_education = get_post_meta( $hy_id, 'hy_education', true );
	$hy_exp       = (int) get_post_meta( $hy_id, 'hy_experience', true );
	$hy_rating    = get_post_meta( $hy_id, 'hy_rating', true );
	$hy_reviews   = (int) get_post_meta( $hy_id, 'hy_reviews', true );
	$hy_rate      = (int) get_post_meta( $hy_id, 'hy_daily_rate', true );
	?>

	<header class="page-head">
		<div class="container profile__head">
			<?php hy_avatar( 'avatar--lg' ); ?>
			<div>
				<h1><?php the_title(); ?></h1>
				<p>
					<span class="badge">احراز هویت‌شده</span>
					<?php if ( $hy_type ) : ?>
						<?php echo esc_html( $hy_type->name ); ?>
					<?php endif; ?>
				</p>
			</div>
		</div>
	</header>

	<section class="section">
		<div class="container profile">
			<div class="entry">
				<?php the_content(); ?>
			</div>

			<aside class="facts">
				<dl>
					<?php if ( $hy_city ) : ?>
						<div><dt>شهر و استان</dt><dd><?php echo esc_html( $hy_city->name . ( $hy_city->description ? ' - ' . $hy_city->description : '' ) ); ?></dd></div>
					<?php endif; ?>
					<?php if ( $hy_exp ) : ?>
						<div><dt>سابقه کار</dt><dd><?php echo esc_html( hy_fa( $hy_exp ) ); ?> سال</dd></div>
					<?php endif; ?>
					<?php if ( '' !== $hy_rating ) : ?>
						<div><dt>امتیاز</dt><dd class="rating"><?php echo esc_html( hy_decimal( $hy_rating ) ); ?><?php echo $hy_reviews ? ' (' . esc_html( hy_fa( $hy_reviews ) ) . ' نظر)' : ''; ?></dd></div>
					<?php endif; ?>
					<?php if ( $hy_education ) : ?>
						<div><dt>تحصیلات</dt><dd><?php echo esc_html( $hy_education ); ?></dd></div>
					<?php endif; ?>
					<?php if ( $hy_rate ) : ?>
						<div><dt>پایه دستمزد روزانه</dt><dd><?php echo esc_html( hy_money( $hy_rate ) ); ?> تومان</dd></div>
					<?php endif; ?>
				</dl>
				<a class="btn" href="<?php echo esc_url( hy_url( 'account' ) ); ?>">انتخاب پرستار و چت</a>
			</aside>
		</div>
	</section>

	<?php endif; // محتوای طراحی‌شده با Elementor یا قالب پیش‌فرض. ?>
<?php endwhile; ?>

<?php
get_footer();
