<?php
/**
 * نتایج جستجو.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<header class="page-head">
	<div class="container">
		<h1>نتایج جستجو برای «<?php echo esc_html( get_search_query() ); ?>»</h1>
	</div>
</header>

<section class="section">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="cards">
				<?php
				while ( have_posts() ) {
					the_post();
					get_template_part( 'template-parts/card', get_post_type() );
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
			<p class="empty">نتیجه‌ای پیدا نشد.</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
