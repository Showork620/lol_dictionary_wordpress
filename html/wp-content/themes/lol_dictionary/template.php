<?php
/**
 * The main template file
 *
 * @package WordPress
 * @subpackage Template
 */

	get_header();
?>
	<div class="l-page">
		<main class="l-main">
			<nav class="c-list-breadcrumb" aria-label="パンくずリスト">
				<ol class="c-list-breadcrumb__list">
					<li class="c-list-breadcrumb__item">
						<a href="/" class="c-list-breadcrumb__link">ホーム</a>
					</li>
					<li class="c-list-breadcrumb__item" aria-current="page">
						運送約款
					</li>
				</ol>
			</nav>
			<h1 class="c-heading-sub-page c-heading-sub-page--simple">
				運送約款
			</h1>
			<section class="l-container l-section u-mb-50">
				<p>以下のリンクをクリックしてご覧ください。</p>
				<ul class="u-mt-40">
					<li>
						・<a href="/assets/pdf/運送約款.pdf" class="c-note-link"
						target="_blank" rel="noreferrer noopener"
						>自動車航送の部（PDFファイル）</a>
					</li>
					<li>
						・<a href="/assets/pdf/運送約款.pdf" class="c-note-link"
						target="_blank" rel="noreferrer noopener"
						>受託手荷物及び小荷物運送の部（PDFファイル）</a>
					<li>
						・<a href="/assets/pdf/運送約款.pdf" class="c-note-link"
						target="_blank" rel="noreferrer noopener"
						>特殊手荷物運送の部（PDFファイル）</a>
					</li>
					<li>
						・<a href="/assets/pdf/運送約款.pdf" class="c-note-link"
						target="_blank" rel="noreferrer noopener"
						>旅客運送の部（PDFファイル）</a>
					</li>
				</ul>
			</section>
		</main>

<?php get_footer(); ?>
