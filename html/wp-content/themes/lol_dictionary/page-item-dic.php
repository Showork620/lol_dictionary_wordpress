<?php
/**
 * Template Name: Items Page
 *
 * @package WordPress
 * @subpackage Template
 */

get_header(); ?>

<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		// デバッグメッセージ
		error_log('page-items.php template loaded');

		// post_type が items の投稿を取得
		$args = array(
			'post_type' => 'items',
			'posts_per_page' => -1,
			'paged' => get_query_var('paged') ? get_query_var('paged') : 1
		);
		$the_query = new WP_Query($args);

		if ($the_query->have_posts()) : ?>
			<ul class="p-item-list">
				<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
					<li class="p-item-list__item">
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('thumbnail'); ?>
							<p class="id"><?php echo get_post_meta(get_the_ID(), 'id', true); ?></p>
							<p class="name"><?php the_title(); ?></p>
						<?php endif; ?>
					</li>
				<?php endwhile; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p><?php _e('No items found.', 'text-domain'); ?></p>
		<?php endif; ?>

		</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
