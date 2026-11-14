<?php
/**
 * فروشگاه تجهیزات پزشکی و بیمارستانی.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hy_shop   = hy_content()['shop'];
$hy_group  = hy_query_arg( 'group' );
$hy_count  = (int) wp_count_posts( 'hy_product' )->publish;
$hy_groups = get_terms(
	array(
		'taxonomy'   => 'hy_product_group',
		'hide_empty' => true,
	)
);
$hy_base   = get_post_type_archive_link( 'hy_product' );
?>

<header class="page-head">
	<div class="container">
		<p class="page-head__kicker"><?php echo esc_html( $hy_shop['kicker'] ); ?></p>
		<h1><?php echo esc_html( $hy_shop['title'] ); ?></h1>
		<p><?php echo esc_html( $hy_shop['text'] ); ?></p>
	</div>
</header>

<section class="section">
	<div class="container">
		<div class="filters">
			<form class="field" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label for="shop-search">جستجوی تجهیزات</label>
				<input type="hidden" name="post_type" value="hy_product">
				<input type="search" id="shop-search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $hy_shop['placeholder'] ); ?>">
			</form>
		</div>

		<?php if ( ! is_wp_error( $hy_groups ) && $hy_groups ) : ?>
			<nav class="pills" aria-label="دسته‌های تجهیزات">
				<a class="pill<?php echo '' === $hy_group ? ' is-active' : ''; ?>" href="<?php echo esc_url( $hy_base ); ?>">
					<?php echo esc_html( $hy_shop['all'] . ' (' . hy_fa( $hy_count ) . ' کالا)' ); ?>
				</a>
				<?php foreach ( $hy_groups as $hy_term ) : ?>
					<a class="pill<?php echo $hy_group === $hy_term->slug ? ' is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( 'group', $hy_term->slug, $hy_base ) ); ?>">
						<?php echo esc_html( $hy_term->name ); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="cards">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/card', 'hy_product' );
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
			<p class="empty">کالایی با این مشخصات پیدا نشد.</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
