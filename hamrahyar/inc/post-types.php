<?php
/**
 * نوشته‌های اختصاصی: پرستار و تجهیزات، به‌همراه طبقه‌بندی‌ها، فیلدها و فیلتر فهرست‌ها.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

/**
 * ثبت نوع‌نوشته‌ها و طبقه‌بندی‌ها.
 */
function hy_register_types() {
	register_post_type(
		'hy_nurse',
		array(
			'labels'        => array(
				'name'          => 'پرستاران',
				'singular_name' => 'پرستار',
				'menu_name'     => 'پرستاران',
				'add_new'       => 'افزودن پرستار',
				'add_new_item'  => 'افزودن پرستار جدید',
				'edit_item'     => 'ویرایش پرستار',
				'all_items'     => 'همه پرستاران',
				'search_items'  => 'جستجوی پرستار',
				'not_found'     => 'پرستاری پیدا نشد.',
			),
			'public'        => true,
			'has_archive'   => 'nurses',
			'rewrite'       => array( 'slug' => 'nurse' ),
			'menu_icon'     => 'dashicons-id-alt',
			'menu_position' => 5,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'show_in_rest'  => true,
		)
	);

	register_post_type(
		'hy_product',
		array(
			'labels'        => array(
				'name'          => 'تجهیزات',
				'singular_name' => 'تجهیزات',
				'menu_name'     => 'فروشگاه تجهیزات',
				'add_new'       => 'افزودن کالا',
				'add_new_item'  => 'افزودن کالای جدید',
				'edit_item'     => 'ویرایش کالا',
				'all_items'     => 'همه کالاها',
				'search_items'  => 'جستجوی کالا',
				'not_found'     => 'کالایی پیدا نشد.',
			),
			'public'        => true,
			'has_archive'   => 'shop',
			'rewrite'       => array( 'slug' => 'product' ),
			'menu_icon'     => 'dashicons-heart',
			'menu_position' => 6,
			'supports'      => array( 'title', 'editor', 'thumbnail' ),
			'show_in_rest'  => true,
		)
	);

	$taxonomy_args = array(
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'hierarchical'      => true,
		'rewrite'           => false,
		'query_var'         => false,
	);

	register_taxonomy(
		'hy_care_type',
		'hy_nurse',
		array_merge(
			$taxonomy_args,
			array(
				'labels' => array(
					'name'          => 'نوع مراقبت',
					'singular_name' => 'نوع مراقبت',
					'add_new_item'  => 'افزودن نوع مراقبت',
					'all_items'     => 'همه انواع مراقبت',
				),
			)
		)
	);

	register_taxonomy(
		'hy_city',
		'hy_nurse',
		array_merge(
			$taxonomy_args,
			array(
				'labels' => array(
					'name'          => 'شهر',
					'singular_name' => 'شهر',
					'add_new_item'  => 'افزودن شهر',
					'all_items'     => 'همه شهرها',
				),
			)
		)
	);

	register_taxonomy(
		'hy_product_group',
		'hy_product',
		array_merge(
			$taxonomy_args,
			array(
				'labels' => array(
					'name'          => 'دسته‌ی تجهیزات',
					'singular_name' => 'دسته',
					'add_new_item'  => 'افزودن دسته',
					'all_items'     => 'همه دسته‌ها',
				),
			)
		)
	);
}
add_action( 'init', 'hy_register_types' );

/**
 * فیلدهای هر نوع‌نوشته: label و type (text | number | decimal).
 *
 * @return array
 */
function hy_meta_fields() {
	return array(
		'hy_nurse'   => array(
			'hy_education'  => array(
				'label' => 'تحصیلات',
				'type'  => 'text',
			),
			'hy_experience' => array(
				'label' => 'سابقه کار (سال)',
				'type'  => 'number',
			),
			'hy_rating'     => array(
				'label' => 'امتیاز (۰ تا ۵)',
				'type'  => 'decimal',
			),
			'hy_reviews'    => array(
				'label' => 'تعداد نظرات',
				'type'  => 'number',
			),
			'hy_daily_rate' => array(
				'label' => 'پایه دستمزد روزانه (تومان)',
				'type'  => 'number',
			),
		),
		'hy_product' => array(
			'hy_price' => array(
				'label' => 'قیمت (تومان)',
				'type'  => 'number',
			),
			'hy_specs' => array(
				'label' => 'مشخصات کوتاه',
				'type'  => 'text',
			),
		),
	);
}

/**
 * باکس فیلدها در صفحه‌ی ویرایش.
 */
function hy_add_meta_boxes() {
	foreach ( array_keys( hy_meta_fields() ) as $post_type ) {
		add_meta_box( 'hy_details', 'مشخصات', 'hy_render_meta_box', $post_type, 'normal', 'high' );
	}
}
add_action( 'add_meta_boxes', 'hy_add_meta_boxes' );

/**
 * نمایش فیلدها.
 *
 * @param WP_Post $post Post.
 */
function hy_render_meta_box( $post ) {
	$fields = hy_meta_fields();
	if ( empty( $fields[ $post->post_type ] ) ) {
		return;
	}

	wp_nonce_field( 'hy_save_meta', 'hy_meta_nonce' );
	echo '<table class="form-table" role="presentation">';
	foreach ( $fields[ $post->post_type ] as $key => $field ) {
		$value = get_post_meta( $post->ID, $key, true );
		$type  = 'text' === $field['type'] ? 'text' : 'number';
		$step  = 'decimal' === $field['type'] ? ' step="0.1" min="0" max="5"' : '';
		$step  = 'number' === $field['type'] ? ' min="0"' : $step;
		printf(
			'<tr><th scope="row"><label for="%1$s">%2$s</label></th><td><input class="regular-text" type="%3$s" id="%1$s" name="%1$s" value="%4$s"%5$s></td></tr>',
			esc_attr( $key ),
			esc_html( $field['label'] ),
			esc_attr( $type ),
			esc_attr( $value ),
			$step // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- fixed literal.
		);
	}
	echo '</table>';
}

/**
 * ذخیره‌ی فیلدها.
 *
 * @param int $post_id Post ID.
 */
function hy_save_meta( $post_id ) {
	if ( ! isset( $_POST['hy_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hy_meta_nonce'] ) ), 'hy_save_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields    = hy_meta_fields();
	$post_type = get_post_type( $post_id );
	if ( empty( $fields[ $post_type ] ) ) {
		return;
	}

	foreach ( $fields[ $post_type ] as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$raw = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );

		if ( 'number' === $field['type'] ) {
			$value = absint( $raw );
		} elseif ( 'decimal' === $field['type'] ) {
			$value = min( 5, max( 0, round( (float) $raw, 1 ) ) );
		} else {
			$value = $raw;
		}
		update_post_meta( $post_id, $key, $value );
	}
}
add_action( 'save_post', 'hy_save_meta' );

/**
 * مقدار یک پارامتر فیلتر در نشانی (care, city, sort, group).
 *
 * @param string $key Query arg.
 * @return string
 */
function hy_query_arg( $key ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter.
	return isset( $_GET[ $key ] ) ? sanitize_title( wp_unslash( $_GET[ $key ] ) ) : '';
}

/**
 * فیلتر و مرتب‌سازی فهرست پرستاران و تجهیزات.
 *
 * @param WP_Query $query Query.
 */
function hy_filter_archives( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( 'hy_nurse' ) ) {
		$query->set( 'posts_per_page', 12 );

		$tax = array();
		if ( '' !== hy_query_arg( 'care' ) ) {
			$tax[] = array(
				'taxonomy' => 'hy_care_type',
				'field'    => 'slug',
				'terms'    => hy_query_arg( 'care' ),
			);
		}
		if ( '' !== hy_query_arg( 'city' ) ) {
			$tax[] = array(
				'taxonomy' => 'hy_city',
				'field'    => 'slug',
				'terms'    => hy_query_arg( 'city' ),
			);
		}
		if ( $tax ) {
			$query->set( 'tax_query', $tax );
		}

		$key = ( 'exp' === hy_query_arg( 'sort' ) ) ? 'hy_experience' : 'hy_rating';
		$query->set(
			'meta_query',
			array(
				'relation' => 'OR',
				'hy_sort'  => array(
					'key'     => $key,
					'type'    => 'NUMERIC',
					'compare' => 'EXISTS',
				),
				array(
					'key'     => $key,
					'compare' => 'NOT EXISTS',
				),
			)
		);
		$query->set(
			'orderby',
			array(
				'hy_sort' => 'DESC',
				'title'   => 'ASC',
			)
		);
	}

	if ( $query->is_post_type_archive( 'hy_product' ) ) {
		$query->set( 'posts_per_page', 12 );

		if ( '' !== hy_query_arg( 'group' ) ) {
			$query->set(
				'tax_query',
				array(
					array(
						'taxonomy' => 'hy_product_group',
						'field'    => 'slug',
						'terms'    => hy_query_arg( 'group' ),
					),
				)
			);
		}
	}

	if ( $query->is_search() && 'hy_product' === $query->get( 'post_type' ) ) {
		$query->set( 'posts_per_page', 12 );
	}
}
add_action( 'pre_get_posts', 'hy_filter_archives' );
