<?php
/**
 * صفحه‌ی ۴۰۴.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<section class="section">
	<div class="container">
		<h1>صفحه‌ای پیدا نشد</h1>
		<p class="lead lead--mt">نشانی را بررسی کنید یا به صفحه‌ی اصلی برگردید.</p>
		<div class="actions">
			<a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">بازگشت به صفحه‌ی اصلی</a>
		</div>
	</div>
</section>

<?php
get_footer();
