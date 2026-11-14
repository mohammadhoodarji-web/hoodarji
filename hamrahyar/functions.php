<?php
/**
 * همراه‌یار — راه‌اندازی قالب.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

define( 'HY_VERSION', '1.1.0' );

require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/content.php';
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/demo-content.php';

/**
 * امکانات قالب.
 */
function hy_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'custom-units' );
	add_theme_support( 'appearance-tools' );
	add_theme_support( 'woocommerce' );
	// سازگاری رسمی با Elementor و کنترل عرض/پس‌زمینه‌ی صفحات.
	add_theme_support( 'elementor' );
	add_theme_support( 'custom-background' );
	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 48,
			'width'       => 160,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	register_nav_menus( array( 'primary' => 'منوی اصلی', 'footer' => 'منوی پاورقی' ) );
}
add_action( 'after_setup_theme', 'hy_setup' );

/**
 * استایل‌ها و اسکریپت‌ها.
 */
function hy_assets() {
	wp_enqueue_style(
		'hy-font',
		'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css',
		array(),
		null
	);
	wp_enqueue_style( 'hy-style', get_stylesheet_uri(), array( 'hy-font' ), HY_VERSION );
	wp_enqueue_script(
		'hy-main',
		get_theme_file_uri( '/assets/js/main.js' ),
		array(),
		HY_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', 'hy_assets' );

/**
 * قالب فارسی و راست‌چین است؛ اگر زبان سایت راست‌چین نبود هم راست‌چین بماند.
 *
 * @param string $output Language attributes.
 * @return string
 */
function hy_language_attributes( $output ) {
	if ( is_rtl() ) {
		return $output;
	}
	return 'lang="fa-IR" dir="rtl"';
}
add_filter( 'language_attributes', 'hy_language_attributes' );

/**
 * خلاصه‌ی نوشته‌ها.
 */
add_filter(
	'excerpt_length',
	function () {
		return 28;
	}
);
add_filter(
	'excerpt_more',
	function () {
		return '…';
	}
);

/**
 * متن پیش‌فرض پاورقی.
 *
 * @return string
 */
function hy_default_footer_text() {
	return '© ۱۴۰۵ استارتاپ «همراه‌یار» — کنار شما، همراه عزیزانتان (پلتفرم پرستار کودک و سالمند و تجهیزات بیمارستانی)';
}

/**
 * شخصی‌سازی: متن پاورقی.
 *
 * @param WP_Customize_Manager $wp_customize Customizer.
 */
function hy_customize_register( $wp_customize ) {
	$wp_customize->add_section(
		'hy_footer',
		array(
			'title'    => 'پاورقی',
			'priority' => 160,
		)
	);
	$wp_customize->add_setting(
		'hy_footer_text',
		array(
			'default'           => hy_default_footer_text(),
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'hy_footer_text',
		array(
			'label'   => 'متن کپی‌رایت',
			'section' => 'hy_footer',
			'type'    => 'textarea',
		)
	);
}
add_action( 'customize_register', 'hy_customize_register' );


/** ناحیه‌های ابزارک برای ساخت پاورقی و بخش‌های قابل‌گسترش. */
function hy_register_widget_areas() {
    for ( $i = 1; $i <= 3; $i++ ) {
        register_sidebar( array(
            'name'          => sprintf( 'ستون %d پاورقی', $i ),
            'id'            => 'hy-footer-' . $i,
            'description'   => 'ابزارک‌ها، پیوندها و اطلاعات این ستون در پاورقی نمایش داده می‌شوند.',
            'before_widget' => '<section class="footer-widget">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="footer-widget__title">',
            'after_title'   => '</h3>',
        ) );
    }
}
add_action( 'widgets_init', 'hy_register_widget_areas' );

/** تنظیمات پیشرفته‌ی پوسته در سفارشی‌سازی وردپرس. */
function hy_advanced_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'hy_design', array( 'title' => 'طراحی و رنگ‌بندی همراه‌یار', 'priority' => 30, 'description' => 'رنگ اصلی، عرض محتوا و گوشه‌های کارت‌ها را تنظیم کنید.' ) );
    $settings = array(
        'hy_brand_color' => array( '#0f6b78', 'رنگ اصلی سایت', 'sanitize_hex_color' ),
        'hy_brand_dark'  => array( '#0a4a54', 'رنگ تیره‌ی اصلی', 'sanitize_hex_color' ),
        'hy_content_width' => array( '70', 'عرض محتوای سایت (rem)', 'absint' ),
        'hy_card_radius' => array( '12', 'گردی گوشه‌ها (px)', 'absint' ),
    );
    foreach ( $settings as $id => $setting ) {
        $wp_customize->add_setting( $id, array( 'default' => $setting[0], 'sanitize_callback' => $setting[2], 'transport' => 'refresh' ) );
        $wp_customize->add_control( $id, array( 'label' => $setting[1], 'section' => 'hy_design', 'type' => ( false !== strpos( $id, 'color' ) || 'hy_brand_dark' === $id ) ? 'color' : 'number', 'input_attrs' => array( 'min' => 8, 'max' => 100 ) ) );
    }

    $wp_customize->add_section( 'hy_home_content', array( 'title' => 'محتوای صفحه‌ی اصلی', 'priority' => 31 ) );
    $hero_fields = array(
        'hy_hero_kicker' => array( 'برچسب بالای عنوان', 'استارتاپ سلامت دیجیتال «همراه‌یار»' ),
        'hy_hero_title' => array( 'عنوان اصلی صفحه', 'کنار شما، همراه عزیزانتان' ),
        'hy_hero_text' => array( 'توضیح صفحه‌ی اصلی', 'پلتفرم آنلاین و هوشمند پیدا کردن پرستار کودک و سالمند و فروشگاه تخصصی تجهیزات بیمارستانی در منزل.' ),
        'hy_hero_primary' => array( 'متن دکمه‌ی اصلی', 'پیدا کردن پرستار' ),
        'hy_hero_secondary' => array( 'متن دکمه‌ی دوم', 'ورود به فروشگاه تجهیزات بیمارستانی' ),
    );
    foreach ( $hero_fields as $id => $field ) {
        $wp_customize->add_setting( $id, array( 'default' => $field[1], 'sanitize_callback' => 'sanitize_textarea_field' ) );
        $wp_customize->add_control( $id, array( 'label' => $field[0], 'section' => 'hy_home_content', 'type' => ( 'hy_hero_text' === $id ) ? 'textarea' : 'text' ) );
    }

    $wp_customize->add_section( 'hy_header_options', array( 'title' => 'سربرگ و دکمه‌ی ورود', 'priority' => 32 ) );
    $wp_customize->add_setting( 'hy_header_cta', array( 'default' => 'ورود / ثبت‌نام', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'hy_header_cta', array( 'label' => 'متن دکمه‌ی سربرگ', 'section' => 'hy_header_options', 'type' => 'text' ) );
}
add_action( 'customize_register', 'hy_advanced_customize_register', 20 );

/** اعمال متغیرهای طراحی انتخاب‌شده در سفارشی‌سازی. */
function hy_customizer_css() {
    $brand = get_theme_mod( 'hy_brand_color', '#0f6b78' );
    $dark  = get_theme_mod( 'hy_brand_dark', '#0a4a54' );
    $width = min( 100, max( 40, absint( get_theme_mod( 'hy_content_width', 70 ) ) ) );
    $radius = min( 32, max( 0, absint( get_theme_mod( 'hy_card_radius', 12 ) ) ) );
    $css = ':root{--brand:' . sanitize_hex_color( $brand ) . ';--brand-d:' . sanitize_hex_color( $dark ) . ';--w:' . $width . 'rem;--radius:' . $radius . 'px}';
    wp_add_inline_style( 'hy-style', $css );
}
add_action( 'wp_enqueue_scripts', 'hy_customizer_css', 20 );

/** محتوای سفارشی صفحه‌ی اصلی را از تنظیمات پوسته بخوان. */
function hy_customizer_home_content( $content ) {
    $keys = array( 'kicker' => 'hy_hero_kicker', 'title' => 'hy_hero_title', 'text' => 'hy_hero_text', 'primary' => 'hy_hero_primary', 'secondary' => 'hy_hero_secondary' );
    foreach ( $keys as $key => $theme_mod ) {
        $value = get_theme_mod( $theme_mod, '' );
        if ( '' !== $value ) { $content['hero'][ $key ] = $value; }
    }
    return $content;
}
add_filter( 'hy_content', 'hy_customizer_home_content' );

/** صفحه‌ی راهنمای افزونه‌های پیشنهادی. */
function hy_add_plugin_recommendations_page() {
    add_theme_page( 'افزونه‌های پیشنهادی', 'افزونه‌های پیشنهادی', 'install_plugins', 'hy-recommended-plugins', 'hy_render_plugin_recommendations' );
}
add_action( 'admin_menu', 'hy_add_plugin_recommendations_page' );
function hy_render_plugin_recommendations() {
    if ( ! current_user_can( 'install_plugins' ) ) { return; }
    $plugins = array(
        array( 'Elementor', 'صفحه‌ساز بصری برای طراحی صفحه‌ها، هدر و لندینگ‌پیج بدون کدنویسی.', 'elementor' ),
        array( 'WooCommerce', 'فروشگاه واقعی، سبد خرید، سفارش‌ها، درگاه پرداخت و مدیریت محصولات.', 'woocommerce' ),
        array( 'Advanced Custom Fields (ACF)', 'افزودن فیلدهای اختصاصی و حرفه‌ای به پرستاران و تجهیزات.', 'advanced-custom-fields' ),
        array( 'WPForms Lite', 'ساخت فرم تماس، درخواست پرستار و فرم‌های ثبت اطلاعات.', 'wpforms-lite' ),
        array( 'Rank Math SEO', 'مدیریت سئوی فنی، عنوان‌ها، نقشه‌ی سایت و داده‌های ساختاریافته.', 'seo-by-rank-math' ),
        array( 'LiteSpeed Cache', 'بهینه‌سازی سرعت و کش؛ بهترین عملکرد در سرورهای LiteSpeed.', 'litespeed-cache' ),
    );
    echo '<div class="wrap"><h1>افزونه‌های پیشنهادی همراه‌یار</h1><p>این افزونه‌ها همراه قالب نصب نمی‌شوند؛ برای جلوگیری از نصب اجباری یا تداخل، هرکدام را فقط در صورت نیاز و از مخزن رسمی وردپرس نصب کنید.</p><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;max-width:1100px">';
    foreach ( $plugins as $plugin ) {
        $url = self_admin_url( 'plugin-install.php?tab=search&type=term&s=' . rawurlencode( $plugin[2] ) );
        echo '<div style="background:#fff;border:1px solid #dcdcde;border-radius:8px;padding:18px"><h2>' . esc_html( $plugin[0] ) . '</h2><p>' . esc_html( $plugin[1] ) . '</p><a class="button button-primary" href="' . esc_url( $url ) . '">مشاهده و نصب از مخزن وردپرس</a></div>';
    }
    echo '</div><p style="margin-top:20px"><strong>نکته:</strong> برای فروشگاه، ووکامرس را نصب کنید. برای طراحی بصری، Elementor مناسب است؛ هم‌زمان چند صفحه‌ساز نصب نکنید.</p></div>';
}


/** ثبت جایگاه‌های هدر و فوتر برای Elementor Theme Builder. */
function hy_elementor_register_locations( $elementor_theme_manager ) {
    if ( method_exists( $elementor_theme_manager, 'register_all_core_location' ) ) {
        $elementor_theme_manager->register_all_core_location();
    }
}
add_action( 'elementor/theme/register_locations', 'hy_elementor_register_locations' );

/** فعال‌کردن ویرایشگر صفحه‌ساز برای انواع محتوای قالب. */
function hy_elementor_post_type_support() {
    foreach ( array( 'page', 'post', 'hy_nurse', 'hy_product' ) as $post_type ) {
        if ( post_type_exists( $post_type ) ) {
            add_post_type_support( $post_type, 'elementor' );
        }
    }
}
add_action( 'init', 'hy_elementor_post_type_support', 20 );

/** متغیرهای رنگ و تایپوگرافی برای کنترل‌های سراسری Elementor. */
function hy_elementor_global_style_compatibility() {
    if ( ! did_action( 'elementor/loaded' ) ) { return; }
    // رنگ‌های پوسته در CSS عمومی در دسترس‌اند و تنظیمات Global Elementor اولویت بالاتری دارند.
}
add_action( 'elementor/loaded', 'hy_elementor_global_style_compatibility' );

/** افزودن کلاس مشخص به بدنه برای استایل‌دهی دقیق صفحات طراحی‌شده با Elementor. */
function hy_elementor_body_class( $classes ) {
    if ( did_action( 'elementor/loaded' ) && ( is_singular() || is_page() ) ) {
        $classes[] = 'hy-elementor-ready';
    }
    return $classes;
}
add_filter( 'body_class', 'hy_elementor_body_class' );
