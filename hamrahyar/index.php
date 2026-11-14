<?php
/**
 * فهرست نوشته‌ها (پیش‌فرض).
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<header class="page-head">
	<div class="container">
		<h1><?php echo is_archive() ? esc_html( wp_strip_all_tags( get_the_archive_title() ) ) : 'نوشته‌ها'; ?></h1>
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
			<p class="empty">نوشته‌ای پیدا نشد.</p>
		<?php endif; ?>
	</div>
</section>

<?php
get_footer();
