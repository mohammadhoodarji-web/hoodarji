<?php
/**
 * نوشته‌ی تکی.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<header class="page-head">
		<div class="container">
			<h1><?php the_title(); ?></h1>
			<p class="entry-meta"><?php echo esc_html( get_the_date() ); ?></p>
		</div>
	</header>
	<section class="section">
		<div class="container">
			<div class="entry"><?php the_content(); ?></div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
