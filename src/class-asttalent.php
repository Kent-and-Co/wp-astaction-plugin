<?php
// Create Custom post type "talent"

class AstTalent {
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
			'talent', // URLになる部分
			array(
				'label'         => 'タレント', // 管理画面の左メニューに表示されるテキスト
				'labels'        => array(
					'all_items' => 'タレント一覧', // 管理画面の左メニューの下層に表示されるテキスト
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'supports'      => $support,
				'show_in_rest'  => true,
			)
		);
	}

	static function create_taxonomy() {
		// 新規分類を作成
		register_taxonomy(
			'talent-type',
			'talent',
			array(
				'label'             => 'タレント分類',
				'description'       => 'タレントの種類です。',
				'public'            => true,
				'show_ui'           => true,
				'show_in_nav_menus' => true,
				'hierarchical'      => true,
				'show_in_rest'      => true,
			)
		);
	}
}

$ast_action_cpt_talent = new AstTalent();
