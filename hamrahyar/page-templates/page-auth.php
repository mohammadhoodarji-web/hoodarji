<?php
/**
 * Template Name: ورود / ثبت‌نام
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hy_auth     = hy_content()['auth'];
$hy_register = get_option( 'users_can_register' ) ? wp_registration_url() : wp_login_url();
?>

<header class="page-head">
	<div class="container">
		<h1><?php the_title(); ?></h1>
	</div>
</header>

<section class="section">
	<div class="container">
		<div class="duo">

			<section class="panel" aria-labelledby="family-title">
				<p class="panel__label"><?php echo esc_html( $hy_auth['family_role'] ); ?></p>
				<h2 id="family-title"><?php echo esc_html( $hy_auth['family']['title'] ); ?></h2>
				<p><?php echo esc_html( $hy_auth['family']['text'] ); ?></p>

				<?php if ( is_user_logged_in() ) : ?>
					<?php $hy_user = wp_get_current_user(); ?>
					<p class="panel__msg">شما با نام «<?php echo esc_html( $hy_user->display_name ); ?>» وارد شده‌اید.</p>
					<a class="btn" href="<?php echo esc_url( hy_url( 'nurses' ) ); ?>">یافتن پرستار</a>
				<?php else : ?>
					<?php
					wp_login_form(
						array(
							'redirect'       => hy_url( 'nurses' ),
							'label_username' => 'نام کاربری یا ایمیل',
							'label_password' => 'رمز عبور',
							'label_remember' => 'مرا به خاطر بسپار',
							'label_log_in'   => $hy_auth['family']['login'],
						)
					);
					?>
				<?php endif; ?>
			</section>

			<section class="panel" aria-labelledby="nurse-title">
				<p class="panel__label"><?php echo esc_html( $hy_auth['nurse_role'] ); ?></p>
				<h2 id="nurse-title"><?php echo esc_html( $hy_auth['nurse']['title'] ); ?></h2>
				<div class="panel__body">
					<?php hy_render_steps( $hy_auth['nurse']['steps'], 'steps--stack' ); ?>
				</div>
				<a class="btn" href="<?php echo esc_url( $hy_register ); ?>"><?php echo esc_html( hy_content()['users']['nurses']['cta'] ); ?></a>
			</section>

		</div>
	</div>
</section>

<?php
get_footer();
