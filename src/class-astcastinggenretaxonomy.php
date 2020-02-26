<?php
// Create Custom post type "casting"

class AstCastingGenreTaxonomy {
	public function __construct() {
		add_action( 'init', array( $this, 'create_taxonomy' ) );
	}

	static function create_taxonomy() {
		// 新規分類を作成
		register_taxonomy(
			'casting-genre',
			array( 'casting', 'casting-history' ),
			array(
				'label'             => '出演ジャンル',
				'description'       => '出演ジャンルを選択するための分類です。',
				'public'            => true,
				'show_ui'           => true,
				'show_in_nav_menus' => true,
				'hierarchical'      => true,
				'show_in_rest'      => true,
			)
		);
	}
}

$ast_action_ctx_casting_genre = new AstCastingGenreTaxonomy();
