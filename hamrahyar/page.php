<?php
/**
 * صفحه‌ی ساده.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<?php if ( ! ( did_action( 'elementor/loaded' ) && Elementor\Plugin::instance()->documents->get( get_the_ID() ) && Elementor\Plugin::instance()->documents->get( get_the_ID() )->is_built_with_elementor() ) ) : ?>
		<header class="page-head"><div class="container"><h1><?php the_title(); ?></h1></div></header>
		<section class="section"><div class="container"><div class="entry"><?php the_content(); ?></div></div></section>
	<?php else : ?>
		<?php the_content(); ?>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
