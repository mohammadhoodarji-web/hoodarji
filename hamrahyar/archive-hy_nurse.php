<?php
/**
 * فهرست پرستاران: فیلتر نوع مراقبت، شهر و مرتب‌سازی.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hy_n     = hy_content()['nurses'];
$hy_care  = hy_query_arg( 'care' );
$hy_city  = hy_query_arg( 'city' );
$hy_sort  = hy_query_arg( 'sort' );
$hy_cares = get_terms(
	array(
		'taxonomy'   => 'hy_care_type',
		'hide_empty' => false,
	)
);
$hy_citys = get_terms(
	array(
		'taxonomy'   => 'hy_city',
		'hide_empty' => false,
	)
);
?>

<header class="page-head">
	<div class="container">
		<h1><?php echo esc_html( $hy_n['title'] ); ?></h1>
		<p class="trust"><?php echo esc_html( $hy_n['trust'] ); ?></p>
	</div>
</header>

<section class="section">
	<div class="container">
		<form class="filters" method="get" action="<?php echo esc_url( get_post_type_archive_link( 'hy_nurse' ) ); ?>">
			<div class="field">
				<label for="f-care"><?php echo esc_html( $hy_n['care'] ); ?></label>
				<select id="f-care" name="care">
					<option value=""><?php echo esc_html( $hy_n['care_all'] ); ?></option>
					<?php if ( ! is_wp_error( $hy_cares ) ) : ?>
						<?php foreach ( $hy_cares as $hy_term ) : ?>
							<option value="<?php echo esc_attr( $hy_term->slug ); ?>" <?php selected( $hy_care, $hy_term->slug ); ?>><?php echo esc_html( $hy_term->name ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="field">
				<label for="f-city"><?php echo esc_html( $hy_n['city'] ); ?></label>
				<select id="f-city" name="city">
					<option value=""><?php echo esc_html( $hy_n['city_all'] ); ?></option>
					<?php if ( ! is_wp_error( $hy_citys ) ) : ?>
						<?php foreach ( $hy_citys as $hy_term ) : ?>
							<option value="<?php echo esc_attr( $hy_term->slug ); ?>" <?php selected( $hy_city, $hy_term->slug ); ?>><?php echo esc_html( $hy_term->name ); ?></option>
						<?php endforeach; ?>
					<?php endif; ?>
				</select>
			</div>
			<div class="field">
				<label for="f-sort"><?php echo esc_html( $hy_n['sort'] ); ?></label>
				<select id="f-sort" name="sort">
					<?php foreach ( $hy_n['sort_opts'] as $hy_value => $hy_label ) : ?>
						<option value="<?php echo esc_attr( $hy_value ); ?>" <?php selected( $hy_sort ? $hy_sort : 'rating', $hy_value ); ?>><?php echo esc_html( $hy_label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<button class="btn" type="submit">اعمال فیلتر</button>
		</form>

		<?php if ( have_posts() ) : ?>
			<div class="cards">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/card', 'hy_nurse' );
				}
				?>
			</div>
			<?php
			the_posts_pagination(
				array(
					'prev_text' => 'قبلی',
					'next_text' => 'بعدی',
				)
			);
			?>
		<?php else : ?>
			<p class="empty">پرستاری با این مشخصات پیدا نشد.</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
