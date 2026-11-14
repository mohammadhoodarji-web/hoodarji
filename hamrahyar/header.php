<?php
/**
 * سربرگ سایت.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

$hy_tagline = get_bloginfo( 'description' );
if ( '' === $hy_tagline ) {
	$hy_tagline = 'کنار شما، همراه عزیزانتان';
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#content">پرش به محتوا</a>

<header class="site-header">
	<div class="container site-header__bar">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php if ( has_custom_logo() ) : ?>
				<?php echo wp_get_attachment_image( (int) get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'brand__logo' ) ); ?>
				<span class="screen-reader-text"><?php bloginfo( 'name' ); ?></span>
			<?php else : ?>
				<?php hy_logo_mark(); ?>
				<span class="brand__text">
					<strong><?php bloginfo( 'name' ); ?></strong>
					<small><?php echo esc_html( $hy_tagline ); ?></small>
				</span>
			<?php endif; ?>
		</a>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav">
			<span class="screen-reader-text">باز و بسته کردن منو</span>
			<span class="nav-toggle__bars" aria-hidden="true"></span>
		</button>

		<nav id="site-nav" class="site-nav" aria-label="منوی اصلی">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-list',
					'fallback_cb'    => 'hy_fallback_menu',
					'depth'          => 1,
				)
			);
			?>
			<?php if ( is_user_logged_in() ) : ?>
				<a class="btn btn--sm" href="<?php echo esc_url( hy_url( 'account' ) ); ?>"><?php echo esc_html( get_theme_mod( 'hy_header_cta', 'حساب کاربری' ) ); ?></a>
			<?php else : ?>
				<a class="btn btn--sm" href="<?php echo esc_url( hy_url( 'account' ) ); ?>"><?php echo esc_html( get_theme_mod( 'hy_header_cta', 'ورود / ثبت‌نام' ) ); ?></a>
			<?php endif; ?>
		</nav>
	</div>
</header>

<main id="content" class="site-main">
