<?php
/**
 * Template Name: پنل همراه بیمار
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hy      = hy_content();
$hy_page = $hy['companion_page'];
$hy_body = '';
while ( have_posts() ) {
	the_post();
	$hy_body = get_the_content();
}

/**
 * شمارش مورد‌های انجام‌شده.
 *
 * @param array $items Rows where index 3 is the done flag.
 * @return int
 */
$hy_done = function ( $items ) {
	return count( array_filter( wp_list_pluck( $items, 3 ) ) );
};
?>

<header class="page-head">
	<div class="container">
		<h1><?php echo esc_html( $hy_page['title'] ); ?></h1>
		<p><?php echo esc_html( $hy_page['text'] ); ?></p>
	</div>
</header>

<section class="section">
	<div class="container">
		<div class="duo">

			<section class="panel" aria-labelledby="meds-title">
				<h2 id="meds-title"><?php echo esc_html( $hy_page['meds']['title'] ); ?></h2>
				<p><?php echo esc_html( $hy_page['meds']['sub'] ); ?></p>
				<p class="panel__count">
					<?php echo esc_html( hy_fa( $hy_done( $hy_page['meds']['items'] ) ) . ' از ' . hy_fa( count( $hy_page['meds']['items'] ) ) . ' مصرف‌شده' ); ?>
				</p>
				<ul class="log">
					<?php foreach ( $hy_page['meds']['items'] as $hy_row ) : ?>
						<li class="log__item">
							<span class="log__time"><?php echo esc_html( hy_fa( $hy_row[1] ) ); ?></span>
							<span class="log__name"><?php echo esc_html( $hy_row[0] ); ?><span class="log__sub"><?php echo esc_html( $hy_row[2] ); ?></span></span>
							<span class="status<?php echo $hy_row[3] ? ' is-done' : ''; ?>"><?php echo $hy_row[3] ? 'مصرف شد' : 'در انتظار'; ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>

			<section class="panel" aria-labelledby="tasks-title">
				<h2 id="tasks-title"><?php echo esc_html( $hy_page['tasks']['title'] ); ?></h2>
				<p><?php echo esc_html( $hy_page['tasks']['sub'] ); ?></p>
				<p class="panel__count">
					<?php echo esc_html( hy_fa( $hy_done( $hy_page['tasks']['items'] ) ) . ' از ' . hy_fa( count( $hy_page['tasks']['items'] ) ) . ' انجام‌شده' ); ?>
				</p>
				<ul class="log">
					<?php foreach ( $hy_page['tasks']['items'] as $hy_row ) : ?>
						<li class="log__item">
							<span class="log__time"><?php echo esc_html( hy_fa( $hy_row[1] ) ); ?></span>
							<span class="log__name"><?php echo esc_html( $hy_row[0] ); ?><span class="log__sub">ثبت توسط: <?php echo esc_html( $hy_row[2] ); ?></span></span>
							<span class="status<?php echo $hy_row[3] ? ' is-done' : ''; ?>"><?php echo $hy_row[3] ? 'انجام شد' : 'در انتظار'; ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>

		</div>
		<p class="note">اطلاعات این صفحه نمونه‌ی نمایشی است.</p>

		<?php if ( '' !== trim( $hy_body ) ) : ?>
			<div class="entry entry--after">
				<?php echo wp_kses_post( apply_filters( 'the_content', $hy_body ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
