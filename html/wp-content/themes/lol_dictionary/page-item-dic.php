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
		<div class="p-item-search-group">
			<div class="p-item-tags-select">
				<label class="p-item-tags-select__label" for="tags">絞り込み：</label>
				<select class="p-item-tags-select__dropdown" id="tags">
					<option value="体力">体力</option>
					<option value="マナ">マナ</option>
					<option value="攻撃力">攻撃力</option>
					<option value="魔力">魔力</option>
					<option value="物理防御">物理防御</option>
					<option value="魔法防御">魔法防御</option>
					<hr>
					<option value="移動速度（ブーツ以外）">移動速度（ブーツ以外）</option>
					<option value="ブーツ">ブーツ</option>
					<option value="攻撃速度">攻撃速度</option>
					<option value="スキルヘイスト">スキルヘイスト</option>
					<option value="クリティカル">クリティカル</option>
					<hr>
					<option value="物理防御貫通">物理防御貫通</option>
					<option value="魔法防御貫通">魔法防御貫通</option>
					<option value="体力回復効果">体力回復効果</option>
					<option value="マナ回復効果">マナ回復効果</option>
					<hr>
					<option value="スロウ効果">スロウ効果</option>
					<option value="行動妨害耐性">行動妨害耐性</option>
					<option value="通常攻撃時効果">通常攻撃時効果</option>
					<option value="ライフスティール">ライフスティール</option>
					<option value="オムニヴァンプ">オムニヴァンプ</option>
					<hr>
					<option value="発動効果あり">発動効果あり</option>
				</select>
			</div>
			<ul class="p-item-role-nav">
			<?php
			// TODO: 別ファイルから参照する（Role, TAGS の配列）
			$ROLES = ['Fighter', 'Marksman', 'Assassin', 'Mage', 'Tank', 'Support', 'All'];
			$TAGS = ["abilityhaste","active","armor","armorpenetration","attackspeed","aura","boots","consumable","cooldownreduction","criticalstrike","damage","goldper","health","healthregen","jungle","lane","lifesteal","magicpenetration","magicresist","mana","ManaRegen","NonbootsMovement","OnHit","Slow","SpellBlock","SpellDamage","SpellVamp","Stealth","Tenacity","Trinket","Vision"];

			foreach ($ROLES as $role) : ?>
				<?php $image_path = get_image_path( '/icon-role/' ) . $role . '.svg'; ?>
				<li class="p-item-role-nav__item">
					<button class="button js-role-button <?php echo 'Fighter' === $role ? 'is-choiced' : '' ?>" data-role="<?php echo esc_attr($role); ?>">
						<img class="icon" src="<?php echo esc_url($image_path); ?>">
					</button>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
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
						// タクソノミーのタームを取得
						$roles = wp_get_post_terms(get_the_ID(), 'role', array('fields' => 'names'));
						$roles_list = implode(',', $roles);
						$tags = wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
						$tags_list = implode(',', $tags);
					?>
					<li class="p-item-list__item js-item" data-role="<?php echo esc_attr($roles_list); ?>" data-tag="<?php echo esc_attr($tags_list); ?>">
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
