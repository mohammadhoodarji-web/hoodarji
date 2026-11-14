<?php
/**
 * محتوای نمونه: هنگام فعال‌سازی قالب، یک بار پرستاران، تجهیزات و صفحه‌ها ساخته می‌شوند.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

/**
 * ساخت ترم‌ها اگر وجود نداشته باشند.
 *
 * @param string $taxonomy Taxonomy.
 * @param array  $terms    slug => array( name, description ).
 */
function hy_seed_terms( $taxonomy, array $terms ) {
	foreach ( $terms as $slug => $term ) {
		if ( ! term_exists( $slug, $taxonomy ) ) {
			wp_insert_term(
				$term[0],
				$taxonomy,
				array(
					'slug'        => $slug,
					'description' => isset( $term[1] ) ? $term[1] : '',
				)
			);
		}
	}
}

/**
 * ساخت محتوای نمونه (فقط یک بار).
 */
function hy_seed_demo() {
	if ( get_option( 'hy_demo_seeded' ) ) {
		return;
	}

	hy_seed_terms(
		'hy_care_type',
		array(
			'elderly' => array( 'پرستار پدر و مادر (سالمند)' ),
			'child'   => array( 'پرستار کودک و نوزاد' ),
		)
	);
	hy_seed_terms(
		'hy_city',
		array(
			'tehran'  => array( 'تهران', 'استان تهران' ),
			'mashhad' => array( 'مشهد', 'خراسان رضوی' ),
			'isfahan' => array( 'اصفهان', 'استان اصفهان' ),
			'shiraz'  => array( 'شیراز', 'استان فارس' ),
			'tabriz'  => array( 'تبریز', 'آذربایجان شرقی' ),
		)
	);
	hy_seed_terms(
		'hy_product_group',
		array(
			'mobility'    => array( 'توانبخشی و حرکتی' ),
			'bed'         => array( 'تخت و بستری' ),
			'respiratory' => array( 'تنفسی و اکسیژن' ),
			'monitoring'  => array( 'پایش علائم حیاتی' ),
		)
	);

	// پرستاران: نام، نوع، شهر، تحصیلات، سابقه، امتیاز، نظرات، دستمزد روزانه، بیوگرافی.
	$nurses = array(
		array( 'سارا احمدی', 'elderly', 'tehran', 'کارشناسی پرستاری - دانشگاه علوم پزشکی تهران', 8, 4.9, 64, 1600000, '۸ سال سابقه مراقبت تخصصی از سالمندان آلزایمری، دیابتی و کم‌توان حرکتی، مسلط به تزریقات، سرم‌تراپی و کنترل علائم حیاتی.' ),
		array( 'نرگس کریمی', 'child', 'mashhad', 'کارشناسی روان‌شناسی کودک و مدرک مادریاری', 6, 4.8, 49, 1350000, 'مراقب دلسوز کودک و نوزاد، مسلط به بازی‌های آموزشی، تغذیه اصولی کودک و کمک‌های اولیه کودکان.' ),
		array( 'امیرحسین شریفی', 'elderly', 'isfahan', 'کاردانی فوریت‌های پزشکی و بهیاری', 10, 5.0, 82, 1800000, 'متخصص مراقبت از پدران سالمند، فیزیوتراپی حرکتی در منزل، جابه‌جایی با ویلچر و پیشگیری از زخم بستر.' ),
		array( 'الهام حسینی', 'child', 'tehran', 'کارشناسی علوم تربیتی و مربی مهد', 5, 4.7, 37, 1400000, 'همراهی صبورانه با کودکان ۱ تا ۸ سال، آموزش شعر و نقاشی، تنظیم خواب و برنامه غذایی کودک.' ),
		array( 'مهدی رضوان', 'elderly', 'shiraz', 'کارشناسی پرستاری - شیراز', 7, 4.9, 53, 1700000, 'مراقبت ۲۴ ساعته و ساعتی از بیماران پس از جراحی و سالمندان، تعویض پانسمان استریل و پایش قند و فشار خون.' ),
		array( 'زهرا تبریزی', 'elderly', 'tabriz', 'گواهینامه بین‌المللی مراقبت از سالمند', 9, 4.8, 41, 1550000, 'همدم و مراقب مادران سالمند، مدیریت دقیق ساعت دارو، آشپزی رژیمی و همراهی در مراجعات پزشکی.' ),
	);

	foreach ( $nurses as $n ) {
		$id = wp_insert_post(
			array(
				'post_type'    => 'hy_nurse',
				'post_status'  => 'publish',
				'post_title'   => $n[0],
				'post_content' => $n[8],
				'post_excerpt' => $n[8],
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			wp_set_object_terms( $id, $n[1], 'hy_care_type' );
			wp_set_object_terms( $id, $n[2], 'hy_city' );
			update_post_meta( $id, 'hy_education', $n[3] );
			update_post_meta( $id, 'hy_experience', $n[4] );
			update_post_meta( $id, 'hy_rating', $n[5] );
			update_post_meta( $id, 'hy_reviews', $n[6] );
			update_post_meta( $id, 'hy_daily_rate', $n[7] );
		}
	}

	// تجهیزات: عنوان، دسته، قیمت، مشخصات.
	$products = array(
		array( 'ویلچر تاشو ارتوپدی و برقی همراه‌یار', 'mobility', 12800000, 'بدنه استیل ضدزنگ، زیرپایی متحرک، قابلیت تاشدن سریع، تحمل وزن ۱۲۰ کیلوگرم', 'wheelchair.jpg' ),
		array( 'تخت بیمارستانی برقی سه شکن (مراقبت در منزل)', 'bed', 29500000, 'دارای ریموت کنترل، تنظیم ارتفاع و زاویه سر و پا، بدنه ABS قابل شستشو و چرخ ترمزدار', 'hospital-bed.jpg' ),
		array( 'تشک مواج سلولی ضد زخم بستر (همراه پمپ بی‌صدا)', 'bed', 4950000, 'جلوگیری و درمان زخم بستر سالمندان، گردش هوای هوشمند ۲۴ ساعته، روکش ضدآب', 'pressure-mattress.jpg' ),
		array( 'دستگاه اکسیژن‌ساز خانگی ۵ لیتری دیجیتال', 'respiratory', 34200000, 'خلوص اکسیژن ۹۶٪، مجهز به خروجی نبولایزر، نمایشگر لمسی و آلارم افت خلوص', 'oxygen-concentrator.jpg' ),
		array( 'واکر چرخ‌دار تاشو و عصای چهارپایه طبی', 'mobility', 2350000, 'آلومینیوم فوق سبک، قابلیت تنظیم ارتفاع ۸ مرحله‌ای، دسته طبی ضدتعریق', 'walker-cane.jpg' ),
		array( 'فشارسنج دیجیتال بازویی سخنگو فارسی', 'monitoring', 2890000, 'اعلام صوتی فشار خون به زبان فارسی، تشخیص آریتمی قلبی، حافظه ۱۲۰ تست', 'blood-pressure-monitor.jpg' ),
		array( 'پالس اکسیمتر انگشتی و تب‌سنج لیزری غیرتماسی', 'monitoring', 1650000, 'سنجش دقیق اکسیژن خون (SpO2) و ضربان قلب در ۵ ثانیه به همراه تب‌سنج دیجیتال', 'oximeter-thermometer.jpg' ),
		array( 'بالابر بیمار (لیفت بیمار) هیدرولیکی خانگی', 'mobility', 19400000, 'جابهجایی آسان و ایمن بیمار از تخت به ویلچر یا سرویس بهداشتی بدون فشار به کمر همراه' ),
	);

	foreach ( $products as $p ) {
		$id = wp_insert_post(
			array(
				'post_type'   => 'hy_product',
				'post_status' => 'publish',
				'post_title'  => $p[0],
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			wp_set_object_terms( $id, $p[1], 'hy_product_group' );
			update_post_meta( $id, 'hy_price', $p[2] );
			update_post_meta( $id, 'hy_specs', $p[3] );
			if ( ! empty( $p[4] ) ) { update_post_meta( $id, 'hy_default_image', sanitize_file_name( $p[4] ) ); }
		}
	}

	// صفحه‌ها با قالب اختصاصی.
	$pages = array(
		'companion' => array( 'پنل همراه بیمار', 'page-templates/page-companion.php' ),
		'account'   => array( 'ورود / ثبت‌نام', 'page-templates/page-auth.php' ),
	);
	foreach ( $pages as $slug => $page ) {
		if ( ! get_page_by_path( $slug ) ) {
			wp_insert_post(
				array(
					'post_type'   => 'page',
					'post_status' => 'publish',
					'post_title'  => $page[0],
					'post_name'   => $slug,
					'meta_input'  => array( '_wp_page_template' => $page[1] ),
				)
			);
		}
	}

	flush_rewrite_rules();
	update_option( 'hy_demo_seeded', 1 );
}
add_action( 'after_switch_theme', 'hy_seed_demo' );


/** افزودن تصاویر پیش‌فرض به کالاهای نمونه‌ی نصب‌های قبلی. */
function hy_migrate_product_images() {
    if ( get_option( 'hy_product_images_migrated' ) ) { return; }
    $images = array( 'ویلچر' => 'wheelchair.jpg', 'تخت' => 'hospital-bed.jpg', 'تشک' => 'pressure-mattress.jpg', 'اکسیژن' => 'oxygen-concentrator.jpg', 'واکر' => 'walker-cane.jpg', 'فشارسنج' => 'blood-pressure-monitor.jpg', 'پالس' => 'oximeter-thermometer.jpg', 'بالابر' => 'patient-lift.jpg' );
    $products = get_posts( array( 'post_type' => 'hy_product', 'post_status' => 'any', 'numberposts' => -1 ) );
    foreach ( $products as $product ) {
        if ( get_post_meta( $product->ID, 'hy_default_image', true ) || has_post_thumbnail( $product->ID ) ) { continue; }
        foreach ( $images as $keyword => $file ) {
            if ( false !== mb_strpos( $product->post_title, $keyword ) ) { update_post_meta( $product->ID, 'hy_default_image', $file ); break; }
        }
    }
    update_option( 'hy_product_images_migrated', 1 );
}
add_action( 'admin_init', 'hy_migrate_product_images' );
