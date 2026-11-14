<?php
/**
 * پاورقی سایت.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="site-footer__top">
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php hy_logo_mark(); ?>
				<span class="brand__text">
					<strong><?php bloginfo( 'name' ); ?></strong>
				</span>
			</a>
			<nav class="footer-nav" aria-label="پیوندهای پاورقی">
				<?php if ( has_nav_menu( 'footer' ) ) : ?>
					<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'depth' => 1 ) ); ?>
				<?php else : ?>
					<?php foreach ( hy_nav_links() as $hy_key => $hy_label ) : ?>
						<a href="<?php echo esc_url( hy_url( $hy_key ) ); ?>"><?php echo esc_html( $hy_label ); ?></a>
					<?php endforeach; ?>
				<?php endif; ?>
			</nav>
		</div>
		<div class="footer-widgets">
			<?php for ( $hy_i = 1; $hy_i <= 3; $hy_i++ ) : ?>
				<?php if ( is_active_sidebar( 'hy-footer-' . $hy_i ) ) : ?><div class="footer-widgets__column"><?php dynamic_sidebar( 'hy-footer-' . $hy_i ); ?></div><?php endif; ?>
			<?php endfor; ?>
		</div>
		<p class="site-footer__copy"><?php echo esc_html( get_theme_mod( 'hy_footer_text', hy_default_footer_text() ) ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
