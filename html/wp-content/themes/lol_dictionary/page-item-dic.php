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
		<div class="p-item-search-header">
			<?php // TODO: 別ファイルから参照する.
			$item_tags_option_html = '
				<option value="All">すべて</option>
				<hr>
				<option value="体力">体力</option>
				<option value="マナ">マナ</option>
				<option value="攻撃力">攻撃力</option>
				<option value="魔力">魔力</option>
				<option value="物理防御">物理防御</option>
				<option value="魔法防御">魔法防御</option>
				<hr>
				<option value="移動速度">移動速度</option>
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
			'; 
			?>
			<div class="p-item-tags-select">
				<label class="p-item-tags-select__label" for="tags">絞り込み１：</label>
				<select class="p-item-tags-select__dropdown js-tag-dropdown1" id="tags1">
					<?php echo $item_tags_option_html; ?>
				</select>
			</div>
			<div class="p-item-tags-select">
				<label class="p-item-tags-select__label" for="tags">絞り込み２：</label>
				<select class="p-item-tags-select__dropdown js-tag-dropdown2" id="tags2">
					<?php echo $item_tags_option_html; ?>
				</select>
			</div>
		</div>
		
		<?php
		$args = array(
			'post_type' => 'items',
			'posts_per_page' => -1,
			'paged' => get_query_var('paged') ? get_query_var('paged') : 1
		);
		$the_query = new WP_Query($args);

		if ($the_query->have_posts()) : ?>
			<div class="p-item-notfound js-item-notfound">
				アイテムが見つかりません。
				<button class="p-item-notfound__unfilter-button js-unfilter-button">絞り込みを解除</button>
			</div>
			<ul class="p-item-list">
				<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
					<?php
					$id = get_post_meta(get_the_ID(), 'id', true);
					if(empty($into) && has_term('', 'role', get_the_ID())) :
						// タクソノミーのタームを取得
						$roles = wp_get_post_terms(get_the_ID(), 'role', array('fields' => 'names'));
						$roles_list = implode(',', $roles);
						$tags = wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
						$tags_list = implode(',', $tags);
					?>
					<li class="p-item-list__item js-item" data-role="<?php echo esc_attr($roles_list); ?>" data-tag="<?php echo esc_attr($tags_list); ?>">
						<button class="p-item-card js-item-button">
							<?php
							$id = get_post_meta(get_the_ID(), 'id', true);
							$image_path = get_image_path('/items/') . $id . '.webp';
							?>
							<img class="p-item-card__icon" src="<?php echo esc_url($image_path); ?>" alt="" width="40" height="40">
							<div class="p-item-card__name">
								<?php
								the_title();
							
								// DEBUG: IDを表示
								// echo ' [' . esc_html($id);
								?>
							</div>
							<div class="p-item-card__gold">
								<?php $gold = get_post_meta(get_the_ID(), 'gold', true); ?>
								<?php echo esc_html($gold) . ' G'; ?>
							</div>
							<div class="p-item-card__content">
								<?php
								$stats = get_post_meta(get_the_ID(), 'stats', true);
								$stats_list = explode(',', $stats);
								foreach ($stats_list as $stat) {
									$stat_key = explode(':', $stat)[0];
									$stat_value = explode(':', $stat)[1];
									echo '<p>' . esc_html($stat_key) . ':<strong>' . esc_html($stat_value) . '</strong></p>';
								}
								// DEBUG: tagsを表示
								// $tags = wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
								// $tags_list = implode(', ', $tags);
								// foreach ($tags as $tag) {
								// 	echo '<p class="tag">' . esc_html($tag) . '</p>';
								// }
								?>
							</div>
							<div class="p-item-card__sub">
								<?php
								// * plaintextを表示 *
								$plaintext = get_post_meta(get_the_ID(), 'plaintext', true);
								echo '<p class="plaintext">' . esc_html($plaintext) . '</p>';

								$from = get_post_meta(get_the_ID(), 'from', true);
								$from_list = explode(', ', $from);
								if (!empty($from_list)) {
									foreach ($from_list as $from) {
										$image_path = get_image_path('/items/') . $from . '.webp';
										echo '<img class="from" src="' . esc_attr($image_path) . '" alt="" width="30" height="30">';
									}
								}
								?>
							</div>
							<div class="p-item-card__ability">
								<?php
								$passives = get_post_meta(get_the_ID(), 'passives', true);
								$passives_list = explode(',', $passives);
								$actives = get_post_meta(get_the_ID(), 'actives', true);
								$actives_list = explode(',', $actives);
								// パッシブを表示
								foreach ($passives_list as $passive) {
									if (empty($passive)) {
										continue;
									}
									echo '<p class="separate">' . ($passive) . '</p>';
								}
								// アクティブを表示
								foreach ($actives_list as $active) {
									if (empty($active)) {
										continue;
									}
									echo '<p class="separate">' . ($active) . '</p>';
								}
								?>
							</div>
						</button>
					</li>
					<?php endif; ?>
				<?php endwhile; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p><?php _e('No items found.', 'text-domain'); ?></p>
		<?php endif; ?>

		<div class="p-item-search-footer">
			<ul class="p-item-role-nav">
			<?php
			// TODO: 別ファイルから参照する.
			$ROLES = ['Fighter', 'Marksman', 'Assassin', 'Mage', 'Tank', 'Support', 'All'];

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
	</section>

	<div class="p-item-modal">
		<div class="p-item-modal__overlay js-modal-overlay">
			<div class="p-item-detail">
				<div id="modal-content"></div>
			</div>
		</div>
	</div>
</main><!-- #main -->
<!-- #primary -->

<?php
get_footer();
