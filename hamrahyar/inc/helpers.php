<?php
/**
 * توابع کمکی: اعداد فارسی، لینک‌ها، منو و اجزای تکراری.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

/**
 * تبدیل ارقام لاتین به فارسی.
 *
 * @param mixed $value Value.
 * @return string
 */
function hy_fa( $value ) {
	return strtr(
		(string) $value,
		array(
			'0' => '۰',
			'1' => '۱',
			'2' => '۲',
			'3' => '۳',
			'4' => '۴',
			'5' => '۵',
			'6' => '۶',
			'7' => '۷',
			'8' => '۸',
			'9' => '۹',
		)
	);
}

/**
 * مبلغ با جداکننده‌ی هزارگان و ارقام فارسی.
 *
 * @param mixed $amount Amount.
 * @return string
 */
function hy_money( $amount ) {
	return hy_fa( number_format( (float) $amount, 0, '.', ',' ) );
}

/**
 * عدد اعشاری با ممیز فارسی (مثل ۴٫۹).
 *
 * @param mixed $number Number.
 * @return string
 */
function hy_decimal( $number ) {
	return hy_fa( str_replace( '.', '٫', number_format( (float) $number, 1, '.', '' ) ) );
}

/**
 * نشانی صفحه‌ای که با یک قالب صفحه ساخته شده؛ در نبود آن، نشانی پیش‌فرض.
 *
 * @param string $template      Page template file.
 * @param string $fallback_slug Fallback slug.
 * @return string
 */
function hy_template_page_url( $template, $fallback_slug ) {
	static $cache = array();

	if ( ! isset( $cache[ $template ] ) ) {
		$ids                = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'meta_key'       => '_wp_page_template',
				'meta_value'     => $template,
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'no_found_rows'  => true,
			)
		);
		$cache[ $template ] = $ids ? get_permalink( $ids[0] ) : home_url( '/' . $fallback_slug . '/' );
	}

	return $cache[ $template ];
}

/**
 * نشانی بخش‌های اصلی سایت.
 *
 * @param string $key shop|nurses|companion|account|home.
 * @return string
 */
function hy_url( $key ) {
	switch ( $key ) {
		case 'shop':
			return (string) get_post_type_archive_link( 'hy_product' );
		case 'nurses':
			return (string) get_post_type_archive_link( 'hy_nurse' );
		case 'companion':
			return hy_template_page_url( 'page-templates/page-companion.php', 'companion' );
		case 'account':
			return hy_template_page_url( 'page-templates/page-auth.php', 'account' );
	}
	return home_url( '/' );
}

/**
 * آیا بخش داده‌شده همین الان نمایش داده می‌شود؟
 *
 * @param string $key Section key.
 * @return bool
 */
function hy_is_current( $key ) {
	switch ( $key ) {
		case 'home':
			return is_front_page();
		case 'shop':
			return is_post_type_archive( 'hy_product' ) || is_singular( 'hy_product' );
		case 'nurses':
			return is_post_type_archive( 'hy_nurse' ) || is_singular( 'hy_nurse' );
		case 'companion':
			return is_page_template( 'page-templates/page-companion.php' );
		case 'account':
			return is_page_template( 'page-templates/page-auth.php' );
	}
	return false;
}

/**
 * پیوندهای اصلی سایت (منوی پیش‌فرض و پاورقی).
 *
 * @return array
 */
function hy_nav_links() {
	return array(
		'home'      => 'معرفی همراه‌یار',
		'shop'      => 'فروشگاه تجهیزات بیمارستانی',
		'nurses'    => 'یافتن پرستار و چت',
		'companion' => 'پنل همراه بیمار',
		'account'   => 'ورود / ثبت‌نام',
	);
}

/**
 * منوی پیش‌فرض تا زمانی که منویی به جایگاه «منوی اصلی» اختصاص داده نشده.
 */
function hy_fallback_menu() {
	$links = hy_nav_links();
	unset( $links['account'] );

	echo '<ul class="nav-list">';
	foreach ( $links as $key => $label ) {
		$current = hy_is_current( $key );
		printf(
			'<li class="%1$s"><a href="%2$s"%3$s>%4$s</a></li>',
			$current ? 'current-menu-item' : '',
			esc_url( hy_url( $key ) ),
			$current ? ' aria-current="page"' : '',
			esc_html( $label )
		);
	}
	echo '</ul>';
}

/**
 * نشان قالب (لوگوی ساده).
 */
function hy_logo_mark() {
	echo '<span class="brand__mark" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg></span>';
}

/**
 * سرتیتر بخش: عنوان و متن کوتاه.
 *
 * @param string $title Title.
 * @param string $text  Optional text.
 */
function hy_section_head( $title, $text = '' ) {
	echo '<div class="section__head"><h2>' . esc_html( $title ) . '</h2>';
	if ( '' !== $text ) {
		echo '<p>' . esc_html( $text ) . '</p>';
	}
	echo '</div>';
}

/**
 * فهرست مرحله‌ای شماره‌دار.
 *
 * @param array  $steps Steps: title, text, tag, note, chips.
 * @param string $class Extra class.
 */
function hy_render_steps( array $steps, $class = '' ) {
	echo '<ol class="steps ' . esc_attr( $class ) . '">';
	foreach ( $steps as $index => $step ) {
		echo '<li class="step"><span class="step__num" aria-hidden="true">' . esc_html( hy_fa( $index + 1 ) ) . '</span>';
		if ( ! empty( $step['tag'] ) ) {
			echo '<span class="step__tag">' . esc_html( $step['tag'] ) . '</span>';
		}
		echo '<h3>' . esc_html( $step['title'] ) . '</h3>';
		if ( ! empty( $step['text'] ) ) {
			echo '<p>' . esc_html( $step['text'] ) . '</p>';
		}
		if ( ! empty( $step['chips'] ) ) {
			echo '<ul class="chips">';
			foreach ( $step['chips'] as $chip ) {
				echo '<li>' . esc_html( $chip ) . '</li>';
			}
			echo '</ul>';
		}
		if ( ! empty( $step['note'] ) ) {
			echo '<p class="step__note">' . esc_html( $step['note'] ) . '</p>';
		}
		echo '</li>';
	}
	echo '</ol>';
}

/**
 * نخستین ترم یک طبقه‌بندی برای یک نوشته.
 *
 * @param int    $post_id  Post ID.
 * @param string $taxonomy Taxonomy.
 * @return WP_Term|null
 */
function hy_first_term( $post_id, $taxonomy ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	if ( is_array( $terms ) && ! empty( $terms ) ) {
		return reset( $terms );
	}
	return null;
}

/**
 * تصویر پرستار: تصویر شاخص یا حرف اول نام.
 *
 * @param string $class Extra class.
 */
function hy_avatar( $class = '' ) {
	echo '<span class="avatar ' . esc_attr( $class ) . '">';
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( 'thumbnail', array( 'alt' => '' ) );
	} else {
		echo '<span aria-hidden="true">' . esc_html( mb_substr( get_the_title(), 0, 1 ) ) . '</span>';
	}
	echo '</span>';
}


/**
 * تصویر همیشگی تجهیزات؛ تصویر شاخص کاربر بر تصویر پیش‌فرض اولویت دارد.
 *
 * @param int    $post_id شناسه محصول.
 * @param string $size اندازه تصویر.
 * @return string
 */
function hy_product_image_html( $post_id, $size = 'large' ) {
    $post_id = absint( $post_id );
    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail( $post_id, $size, array( 'alt' => get_the_title( $post_id ), 'loading' => 'lazy' ) );
    }

    $file = get_post_meta( $post_id, 'hy_default_image', true );
    if ( ! $file ) {
        $title = get_the_title( $post_id );
        $map = array(
            'ویلچر' => 'wheelchair.jpg', 'تخت' => 'hospital-bed.jpg', 'تشک' => 'pressure-mattress.jpg',
            'اکسیژن' => 'oxygen-concentrator.jpg', 'واکر' => 'walker-cane.jpg', 'عصا' => 'walker-cane.jpg',
            'فشارسنج' => 'blood-pressure-monitor.jpg', 'پالس' => 'oximeter-thermometer.jpg', 'تب‌سنج' => 'oximeter-thermometer.jpg',
            'بالابر' => 'patient-lift.jpg',
        );
        foreach ( $map as $keyword => $image ) {
            if ( false !== strpos( $title, $keyword ) ) { $file = $image; break; }
        }
    }
    if ( ! $file ) {
        $terms = get_the_terms( $post_id, 'hy_product_group' );
        $group = ( ! is_wp_error( $terms ) && ! empty( $terms ) ) ? $terms[0]->slug : '';
        $fallbacks = array( 'mobility' => 'wheelchair.jpg', 'bed' => 'hospital-bed.jpg', 'respiratory' => 'oxygen-concentrator.jpg', 'monitoring' => 'blood-pressure-monitor.jpg' );
        $file = isset( $fallbacks[ $group ] ) ? $fallbacks[ $group ] : 'wheelchair.jpg';
    }
    $url = get_theme_file_uri( '/assets/images/products/' . sanitize_file_name( $file ) );
    return sprintf( '<img src="%1$s" alt="%2$s" loading="lazy" decoding="async" />', esc_url( $url ), esc_attr( get_the_title( $post_id ) ) );
}
