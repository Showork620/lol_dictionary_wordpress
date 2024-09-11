<?php
/**
 * Template Name: Items Page
 *
 * @package WordPress
 * @subpackage Template
 */

get_header(); ?>

<main id="main" class="l-main">
	<section class="l-section">
		<nav>
			<ul class="p-item-role-nav">
			<?php
			// Role の配列
			$ROLES = ['Fighter', 'Marksman', 'Assassin', 'Mage', 'Tank', 'Support'];
			foreach ($ROLES as $role) : ?>
				<?php $image_path = get_image_path( '/icon-role/' ) . $role . '.svg'; ?>
				<li class="p-item-role-nav__item">
					<button class="button js-role-button <?php echo 'Fighter' === $role ? 'is-choiced' : '' ?>" data-role="<?php echo esc_attr($role); ?>">
						<img class="icon" src="<?php echo esc_url($image_path); ?>">
					</button>
				</li>
			<?php endforeach; ?>
			</ul>
		</nav>
		<?php
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
					<?php
					$into = get_post_meta(get_the_ID(), 'into', true);
					if(empty($into) && has_term('', 'role', get_the_ID())) :
						// 'role' タクソノミーのタームを取得
						$roles = wp_get_post_terms(get_the_ID(), 'role', array('fields' => 'names'));
						$roles_list = implode(',', $roles);
					?>
					<li class="p-item-list__item js-item" data-role="<?php echo esc_attr($roles_list); ?>">
						<?php if (has_post_thumbnail()) : ?>
							<?php the_post_thumbnail('thumbnail'); ?>
							<p class="name"><?php the_title(); ?></p>
						<?php endif; ?>
					</li>
					<?php endif; ?>
				<?php endwhile; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p><?php _e('No items found.', 'text-domain'); ?></p>
		<?php endif; ?>
	</section>
</main><!-- #main -->
<!-- #primary -->

<?php
get_footer();
