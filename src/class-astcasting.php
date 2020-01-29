<?php
// Create Custom post type "casting"

class AstCasting {
	public function __construct() {
		add_action( 'init', array( $this, 'create_post_type' ) );
		add_action( 'init', array( $this, 'create_taxonomy' ) );
	}

	static function create_post_type() {
		$support = [// 投稿画面で表示される項目の設定
			'title', // 記事タイトル
			'editor', // 記事本文
			'thumbnail', // アイキャッチ画像
		];
		register_post_type(
			'casting', // URLになる部分
			array(
				'label'         => '出演情報', // 管理画面の左メニューに表示されるテキスト
				'labels'        => array(
					'all_items' => '出演情報一覧', // 管理画面の左メニューの下層に表示されるテキスト
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'supports'      => $support,
			)
		);
	}

	static function create_taxonomy() {
		// 新規分類を作成
		register_taxonomy(
			'casting-talent',
			'casting',
			array(
				'label'       => '出演タレント',
				'description' => '出演タレントを選択するための分類です。',
			)
		);
	}
}

$ast_action_cpt_casting = new AstCasting();
