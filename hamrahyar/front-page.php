<?php
/**
 * صفحه‌ی اصلی: معرفی همراه‌یار.
 *
 * @package hamrahyar
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hy = hy_content();
$hy_elementor_document = ( did_action( 'elementor/loaded' ) && class_exists( 'Elementor\\Plugin' ) ) ? Elementor\Plugin::instance()->documents->get( get_queried_object_id() ) : false;
$hy_is_elementor_home = $hy_elementor_document && $hy_elementor_document->is_built_with_elementor();
if ( $hy_is_elementor_home ) :
    while ( have_posts() ) : the_post(); the_content(); endwhile;
else :
?>

<section class="hero">
	<div class="container hero__grid">
		<div class="hero__copy">
			<p class="hero__kicker"><?php echo esc_html( $hy['hero']['kicker'] ); ?></p>
			<h1><?php echo esc_html( $hy['hero']['title'] ); ?></h1>
			<p class="lead"><?php echo esc_html( $hy['hero']['text'] ); ?></p>
			<div class="actions">
				<a class="btn" href="<?php echo esc_url( hy_url( 'nurses' ) ); ?>"><?php echo esc_html( $hy['hero']['primary'] ); ?></a>
				<a class="btn btn--ghost" href="<?php echo esc_url( hy_url( 'shop' ) ); ?>"><?php echo esc_html( $hy['hero']['secondary'] ); ?></a>
			</div>
		</div>

		<?php
		$hy_featured = new WP_Query(
			array(
				'post_type'      => 'hy_nurse',
				'posts_per_page' => 1,
				'meta_key'       => 'hy_rating',
				'orderby'        => 'meta_value_num',
				'order'          => 'DESC',
				'no_found_rows'  => true,
			)
		);
		if ( $hy_featured->have_posts() ) :
			?>
			<aside class="hero__aside" aria-label="نمونه پروفایل پرستار">
				<?php
				while ( $hy_featured->have_posts() ) {
					$hy_featured->the_post();
					get_template_part( 'template-parts/card', 'hy_nurse' );
				}
				wp_reset_postdata();
				?>
			</aside>
		<?php endif; ?>
	</div>
</section>

<section class="section" id="problem">
	<div class="container">
		<?php hy_section_head( $hy['problem']['title'], $hy['problem']['text'] ); ?>
		<div class="issues">
			<?php foreach ( $hy['problem']['items'] as $item ) : ?>
				<article class="issue">
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<p><?php echo esc_html( $item['text'] ); ?></p>
					<p class="issue__meta">
						<span><?php echo esc_html( $item['tag'] ); ?></span>
						<strong><?php echo esc_html( $item['stat'] ); ?></strong>
					</p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--wash" id="solution">
	<div class="container">
		<?php hy_section_head( $hy['solution']['title'] ); ?>
		<p class="statement"><?php echo esc_html( $hy['solution']['statement'] ); ?></p>
		<div class="features">
			<?php foreach ( $hy['solution']['items'] as $item ) : ?>
				<div class="feature">
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<p><?php echo esc_html( $item['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section" id="users">
	<div class="container">
		<?php hy_section_head( $hy['users']['title'] ); ?>
		<div class="duo">
			<?php
			$hy_groups = array(
				'nurses' => hy_url( 'account' ),
				'family' => hy_url( 'nurses' ),
			);
			foreach ( $hy_groups as $hy_group => $hy_link ) :
				$hy_data = $hy['users'][ $hy_group ];
				?>
				<article class="panel">
					<p class="panel__label"><?php echo esc_html( $hy_data['label'] ); ?></p>
					<h3><?php echo esc_html( $hy_data['title'] ); ?></h3>
					<p><?php echo esc_html( $hy_data['text'] ); ?></p>
					<ul class="ticks">
						<?php foreach ( $hy_data['list'] as $hy_line ) : ?>
							<li><?php echo esc_html( $hy_line ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a class="btn <?php echo 'family' === $hy_group ? '' : 'btn--ghost'; ?>" href="<?php echo esc_url( $hy_link ); ?>"><?php echo esc_html( $hy_data['cta'] ); ?></a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section--wash" id="nurse-path">
	<div class="container">
		<?php hy_section_head( $hy['nurse_path']['title'] ); ?>
		<?php hy_render_steps( $hy['nurse_path']['steps'] ); ?>
	</div>
</section>

<section class="section" id="family-find">
	<div class="container">
		<?php hy_section_head( $hy['family_find']['title'] ); ?>
		<?php hy_render_steps( $hy['family_find']['steps'] ); ?>
	</div>
</section>

<section class="section section--wash" id="family-chat">
	<div class="container split">
		<div>
			<?php hy_section_head( $hy['family_chat']['title'], $hy['family_chat']['text'] ); ?>
			<?php hy_render_steps( $hy['family_chat']['steps'], 'steps--stack' ); ?>
		</div>
		<?php $hy_sms = $hy['family_chat']['sms']; ?>
		<figure class="sms" aria-label="نمونه پیامک">
			<figcaption class="sms__bar">
				<?php echo esc_html( $hy_sms['title'] ); ?>
				<span><?php echo esc_html( $hy_sms['number'] ); ?></span>
			</figcaption>
			<div class="sms__bubble">
				<p class="sms__from">
					<strong><?php echo esc_html( $hy_sms['from'] ); ?></strong>
					<span><?php echo esc_html( $hy_sms['time'] ); ?></span>
				</p>
				<p><strong><?php echo esc_html( $hy_sms['head'] ); ?></strong></p>
				<p><?php echo esc_html( $hy_sms['body'] ); ?></p>
			</div>
		</figure>
	</div>
</section>

<section class="section" id="escrow">
	<div class="container">
		<?php hy_section_head( $hy['escrow']['title'], $hy['escrow']['text'] ); ?>
		<?php hy_render_steps( $hy['escrow']['steps'] ); ?>
	</div>
</section>

<section class="section section--wash" id="companion">
	<div class="container">
		<?php hy_section_head( $hy['companion']['title'] ); ?>
		<div class="features">
			<?php foreach ( $hy['companion']['items'] as $item ) : ?>
				<div class="feature">
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<p><?php echo esc_html( $item['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="actions">
			<a class="btn btn--ghost" href="<?php echo esc_url( hy_url( 'companion' ) ); ?>"><?php echo esc_html( $hy['companion']['cta'] ); ?></a>
		</div>
	</div>
</section>

<section class="section" id="summary">
	<div class="container">
		<div class="split">
			<div>
				<?php hy_section_head( $hy['summary']['title'], $hy['summary']['text'] ); ?>
			</div>
			<dl class="stats">
				<?php foreach ( $hy['summary']['stats'] as $stat ) : ?>
					<div>
						<dt><?php echo esc_html( $stat[1] ); ?></dt>
						<dd><?php echo esc_html( $stat[0] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		</div>

		<div class="subhead">
			<h2><?php echo esc_html( $hy['roadmap']['title'] ); ?></h2>
			<p><?php echo esc_html( $hy['roadmap']['text'] ); ?></p>
		</div>
		<div class="features">
			<?php foreach ( $hy['roadmap']['items'] as $item ) : ?>
				<div class="feature">
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<p><?php echo esc_html( $item['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="closing">
	<div class="container">
		<h2><?php echo esc_html( $hy['closing']['title'] ); ?></h2>
		<p><?php echo esc_html( $hy['closing']['text'] ); ?></p>
		<div class="actions">
			<a class="btn btn--light" href="<?php echo esc_url( hy_url( 'shop' ) ); ?>"><?php echo esc_html( $hy['closing']['primary'] ); ?></a>
			<a class="btn btn--outline-light" href="<?php echo esc_url( hy_url( 'nurses' ) ); ?>"><?php echo esc_html( $hy['closing']['secondary'] ); ?></a>
		</div>
	</div>
</section>

<?php endif; // پایان محتوای پیش‌فرض یا طراحی Elementor.
get_footer();
