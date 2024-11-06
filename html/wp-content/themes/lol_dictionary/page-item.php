<?php
/**
 * Template Name: Items Page
 *
 * @package WordPress
 * @subpackage Template
 */

get_header(); ?>

<header class="l-header">
	<div class="l-header__background">
		<img src="<?php echo get_image_path('/common/header-bg.webp'); ?>" alt="">
	</div>
	<div class="l-header__inner">
		<h1 class="l-header__page-title">
			<span>軽い</span><span>LoL</span><span>辞典</span><span>(仮)</span>
		</h1>
		<div class="l-header__version">
			<p>アイテム辞典 v.0.2.22</p>
			<p>Patch 14.22 準拠</p>
		</div>
		<div class="l-header__description">
			<p>現在レジェンダリーアイテムのみ掲載しています。</p>
			<p>その他のアイテムや情報も随時追加予定です。</p>
		</div>
	</div>
</header>

<main id="main" class="l-main">
	<section class="l-section p-item-search-header">
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
			<option value="回復効果&シールド量">回復効果&シールド量</option>
		';
		$item_tags_option_html_2 = '
			<option value="All">絞り込みなし</option>
			<hr>
			<option value="通常攻撃時効果">通常攻撃時効果</option>
			<option value="シールド">シールド</option>
			<option value="アルティメットスキル">アルティメットスキル</option>
			<hr>
			<option value="スロウ効果">スロウ効果</option>
			<option value="行動妨害耐性">行動妨害耐性</option>
			<hr>
			<option value="負傷">負傷</option>
			<option value="ライフスティール">ライフスティール</option>
			<option value="オムニヴァンプ">オムニヴァンプ</option>
			<option value="体力割合ダメージ">体力割合ダメージ</option>
			<option value="体力レシオ">体力レシオ</option>
			<hr>
			<option value="発動効果あり">発動効果あり</option>
		';
		?>
		<div class="p-item-tags-select">
			<span class="p-item-tags-select__label" for="tags">絞り込み１：</span>
			<select class="p-item-tags-select__dropdown js-tag-dropdown1" id="tags1">
				<?php echo $item_tags_option_html; ?>
			</select>
		</div>
		<div class="p-item-tags-select">
			<span class="p-item-tags-select__label" for="tags">絞り込み２：</span>
			<select class="p-item-tags-select__dropdown js-tag-dropdown2" id="tags2">
				<?php echo $item_tags_option_html; ?>
			</select>
		</div>
		<div class="p-item-tags-select">
			<span class="p-item-tags-select__label" for="tags">特殊条件：</span>
			<select class="p-item-tags-select__dropdown js-tag-dropdown3" id="tags3">
				<?php echo $item_tags_option_html_2; ?>
			</select>
		</div>
	</section>
		
	<section class="l-section">
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
				<button class="c-button-regular js-unfilter-button">絞り込みを解除</button>
			</div>
			<ul class="p-item-list">
				<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
					<?php
					$id = esc_html( get_post_meta(get_the_ID(), 'id', true) );
					$name = esc_html( get_the_title() );
					$gold = esc_html( get_post_meta(get_the_ID(), 'gold', true) );
					$stats = esc_html( get_post_meta(get_the_ID(), 'stats', true) );
					$stats_list = explode(',', $stats);
					$plaintext = esc_html( get_post_meta(get_the_ID(), 'plaintext', true) );

					// リスト化するアイテムの設定 * * *
					if(has_term('', 'role', get_the_ID())) :
						// タクソノミーのタームを取得
						$roles = wp_get_post_terms(get_the_ID(), 'role', array('fields' => 'names'));
						$roles_list = implode(',', $roles);
						$tags =  wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
						$tags_list = implode(',', $tags);
					?>

					<li class="p-item-list__item js-item" data-role="<?php echo $roles_list; ?>" data-tag="<?php echo $tags_list; ?>">
						<a href="#default" id="<?php echo $id ?>" class="p-item-card js-item-button">
							<?php
							$image_path = esc_url( get_image_path('/items/'). $id .'.webp' );
							?>
							<img class="p-item-card__icon js-icon" src="<?php echo $image_path; ?>" alt="" width="40" height="40">
							<h2 class="p-item-card__name js-name">
								<?php
								echo $name;
								// DEBUG: IDを表示
								// echo ' : ' . $id;
								?>
							</h2>
							<div class="p-item-card__gold js-gold">
								<?php echo esc_html($gold) .' G'; ?>
							</div>
							<div class="p-item-card__close">
								<button class="button c-button-regular js-modal-close">×</button>
							</div>

							<!-- 基本情報 =============== -->
							<div class="p-item-card__stats js-stats">
								<?php
								$stats_html = '';
								foreach ($stats_list as $stat) {
									$stat_key = explode(':', $stat)[0];
									$stat_value = explode(':', $stat)[1];
									$stats_html .= '<p>'. esc_html($stat_key) .': <strong>'. esc_html($stat_value) .'</strong></p>';
								}
								echo $stats_html;

								// DEBUG: tagsを表示
								// $tags = wp_get_post_terms(get_the_ID(), 'post_tag', array('fields' => 'names'));
								// $tags_list = implode(', ', $tags);
								// foreach ($tags as $tag) {
								// 	echo '<p class="tag">' . esc_html($tag) . '</p>';
								// }
								?>
							</div>
							<div class="p-item-card__sub js-sub">
								<p class="plaintext"><?php echo esc_html($plaintext); ?></p>
								<?php
								// from画像							
								$from = esc_html( get_post_meta(get_the_ID(), 'from', true) );
								$specialRecipe = esc_html( get_post_meta(get_the_ID(), 'specialRecipe', true) );
								$from_list = explode(', ', $from);	

								foreach ($from_list as $from) {
									if (empty($from)) {
										continue;
									}
									$image_path = get_image_path('/items/') . $from .'.webp';
									echo '<img class="from" src="'. esc_attr($image_path) .'" alt="" width="30" height="30">';
								}

								if (!empty($specialRecipe)) {
									$image_path = get_image_path('/items/') . $specialRecipe .'.webp';
									echo '<img class="from" src="'. esc_attr($image_path) .'" alt="" width="30" height="30">';
								}
								?>
							</div>
							<div class="p-item-card__clickable">
								詳細
								<div class="c-icon-clickable p-item-icon"></div>
							</div>
							<?php
								// パッシブを表示
								$passives = get_post_meta(get_the_ID(), 'passives', true);
								$passives_list = explode(',', $passives);
								$actives = get_post_meta(get_the_ID(), 'actives', true);
								$actives_list = explode(',', $actives);
								
								// パッシブとアクティブのhtmlを生成
								$ability_html = '';
								foreach ($passives_list as $passive) {
									if (empty($passive)) {
										continue;
									}
									$ability_html .= '<p class=&quot;separate&quot;>'. $passive .'</p>';
								}
								// アクティブを表示
								foreach ($actives_list as $active) {
									if (empty($active)) {
										continue;
									}
									$ability_html .= '<p class=&quot;separate&quot;>'. $active .'</p>';
								}
							?>
							<div class="p-item-card__ability js-ability" data-html="<?php echo $ability_html; ?>">
							</div>
						</a>
					</li>
					<?php endif; ?>
				<?php endwhile; ?>
			</ul>
			<?php wp_reset_postdata(); ?>
		<?php else : ?>
			<p><?php _e('No items found.', 'text-domain'); ?></p>
		<?php endif; ?>
	</section>

	<section class="l-section p-item-search-footer">
		<ul class="p-item-role-nav">
		<?php
		// TODO: 別ファイルから参照する.
		$ROLES = ['Fighter', 'Marksman', 'Assassin', 'Mage', 'Tank', 'Support', 'All'];
		foreach ($ROLES as $role) : ?>
			<?php $image_path = get_image_path( '/icon-role/' ). $role .'.svg'; ?>
			<li class="p-item-role-nav__item">
				<?php // TODO: 初期選択はjs側で行う. ?>
				<button class="button js-role-button <?php echo 'All' === $role ? 'is-choiced' : '' ?>" data-role="<?php echo esc_attr($role); ?>">
					<img class="icon" src="<?php echo esc_url($image_path); ?>">
				</button>
			</li>
		<?php endforeach; ?>
		</ul>
	</section>


	<?php // #モーダル ?>
	<div class="p-item-modal" id="js-modal" name="detail">
		<div class="p-item-card" id="js-detail" tabindex="0">
			<img class="p-item-card__icon" id="js-icon" src="<?php echo esc_attr(get_image_path('/common/dummy_icon.svg')); ?>" alt="" width="40" height="40">
			<h2 class="p-item-card__name" id="js-name">name</h2>
			<div class="p-item-card__gold" id="js-gold">gold</div>
			<div class="p-item-card__close">
				<button class="button c-button-regular js-modal-close">×</button>
			</div>

			<!-- 基本情報 =============== -->
			<div class="p-item-card__stats" id="js-stats">aa</div>
			<div class="p-item-card__sub" id="js-sub">aa</div>

			<!-- パッシブ・アクティブ詳細 =============== -->
			<div class="p-item-card__ability" id="js-ability"></div>
			<div class="p-item-card__clickable">
				詳細<div class="c-icon-clickable p-item-icon"></div>
			</div>

			<!-- 近接 / 遠隔切り替え =============== -->
			<div class="p-item-card__toggle" id="js-toggle">
				<div class="label">近接・遠隔で能力変更あり</div>
				<label class="c-button-toggle">
					<input type="checkbox" class="js-toggle-melee-ranged">
					<span class="false-side">近接</span>
					/ <span class="true-side">遠隔</span>
					<div class="slider"></div>
				</label>
			</div>

			<!-- フォーム -->
			<div class="p-item-card__form p-item-card-admin-note" id="js-modal-form">
				<h3 class="p-item-card-admin-note__heading">誤りの報告／問い合わせフォーム（仮）</h3>
				<div class="l-section-small">
					<?php
					echo do_shortcode('[contact-form-7 id="dd3209b" title="総合コンタクト"]'); ?>
				</div>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
