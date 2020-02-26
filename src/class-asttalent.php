<?php
// Create Custom post type "talent"

class AstTalent {
	public function __construct() {
		add_action( 'init', array( $this, 'create_post_type' ) );
		add_action( 'init', array( $this, 'create_taxonomy' ) );
		add_action( 'admin_menu', array( $this, 'ast_add_talent_fields' ) );
		add_action( 'save_post', array( $this, 'ast_save_talent_info_fields' ) );
		add_action( 'transition_post_status', array( $this, 'ast_add_talent_term' ), 10, 3 );
		add_filter( 'enter_title_here', array( $this, 'ast_enter_talent_name_here' ), 10, 2 );
	}

	static function create_post_type() {
		$support = array(// 投稿画面で表示される項目の設定
			'title', // 記事タイトル
			'thumbnail', // アイキャッチ画像
		);
		register_post_type(
			'talent', // URLになる部分
			array(
				'label'         => 'タレント', // 管理画面の左メニューに表示されるテキスト
				'labels'        => array(
					'all_items'          => 'タレント一覧', // 管理画面の左メニューの下層に表示されるテキスト
					'add_new'            => '新規追加',
					'add_new_item'       => '新規タレントを追加',
					'edit_item'          => 'タレント情報を編集',
					'new_item'           => '新規タレント登録',
					'view_item'          => 'タレント情報を表示',
					'search_items'       => 'タレントを検索',
					'not_found'          => 'タレント情報が見つかりませんでした。',
					'not_found_in_trash' => 'ゴミ箱内にタレント情報がありませんでした。',
					'enter_title_here'   => 'タレント名を入力',
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'supports'      => $support,
				'show_in_rest'  => true,
			)
		);
	}

	// change "enter title here" message
	static function ast_enter_talent_name_here( $enter_title_here, $post ) {
		$post_type = get_post_type_object( $post->post_type );
		if ( isset( $post_type->labels->enter_title_here ) && $post_type->labels->enter_title_here && is_string( $post_type->labels->enter_title_here ) ) {
			$enter_title_here = esc_html( $post_type->labels->enter_title_here );
		}
		return $enter_title_here;
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

	// Add custom fields section
	static function ast_add_talent_fields() {
		//add_meta_box(表示される入力ボックスのHTMLのID, ラベル, 表示する内容を作成する関数名, 投稿タイプ, 表示方法)
		//第4引数のpostをpageに変更すれば固定ページにオリジナルカスタムフィールドが表示されます(custom_post_typeのslugを指定することも可能)。
		//第5引数はnormalの他にsideとadvancedがあります。
		add_meta_box( 'ast_talent_info', 'タレント情報', array( $this, 'ast_talent_info_fields' ), 'talent', 'normal' );
	}

	// Add custom fields
	static function ast_talent_info_fields() {
		global $post;

		//下記に管理画面に表示される入力エリアを作ります。「get_post_meta()」は現在入力されている値を表示するための記述です。
		?>
		<table>
			<caption>
				<h3>氏名</h3>
				<p>注意：この氏名欄の内容は、出演情報との紐付けに使われます。変更時にそれぞれの情報のアップデートはされませんので個別にタクソノミーの情報修正が必要です。</p>
			</caption>
			<thead>
				<tr><th></th><th>姓</th><th>名</th></tr>
			</thead>
			<tbody>
				<tr>
					<th>日本語</th>
					<td>
						<input type="text" name="famname_j" value="<?php echo get_post_meta( $post->ID, 'famname_j', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="firstname_j" value="<?php echo get_post_meta( $post->ID, 'firstname_j', true ); ?>" size="20" autocomplete="off">
					</td>
				</tr>
				<tr>
					<th>英語</th>
					<td>
						<input type="text" name="famname_e" value="<?php echo get_post_meta( $post->ID, 'famname_e', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="firstname_e" value="<?php echo get_post_meta( $post->ID, 'firstname_e', true ); ?>" size="20" autocomplete="off">
					</td>
				</tr>
			</tbody>
		</table>

		<table>
			<caption>
				<h3>誕生日</h3>
			</caption>
			<tbody>
				<tr>
					<td>
						<input type="text" name="birth_month" value="<?php echo get_post_meta( $post->ID, 'birth_month', true ); ?>" size="4">月
					</td>
					<td>
						<input type="text" name="birth_date" value="<?php echo get_post_meta( $post->ID, 'birth_date', true ); ?>" size="4">日
					</td>
				</tr>
			</tbody>
		</table>

		<table>
			<caption>
				<h3>体型など</h3>
			</caption>
			<tbody>
				<tr>
					<th>
						身長
					</th>
					<td>
						<input type="text" name="tall" value="<?php echo get_post_meta( $post->ID, 'tall', true ); ?>" size="4">cm
					</td>
				</tr>
				<tr>
					<th>
						バスト
					</th>
					<td>
						<input type="text" name="bust" value="<?php echo get_post_meta( $post->ID, 'bust', true ); ?>" size="4">cm
					</td>
				</tr>
				<tr>
					<th>
						ウエスト
					</th>
					<td>
						<input type="text" name="waist" value="<?php echo get_post_meta( $post->ID, 'waist', true ); ?>" size="4">cm
					</td>
				</tr>
				<tr>
					<th>
						ヒップ
					</th>
					<td>
						<input type="text" name="hip" value="<?php echo get_post_meta( $post->ID, 'hip', true ); ?>" size="4">cm
					</td>
				</tr>
				<tr>
					<th>
						靴のサイズ
					</th>
					<td>
						<input type="text" name="shoes" value="<?php echo get_post_meta( $post->ID, 'shoes', true ); ?>" size="4">cm
					</td>
				</tr>
			</tbody>
		</table>

		<table>
			<caption>
				<h3>趣味／特技</h3>
			</caption>
			<tbody>
				<tr>
					<th>
						趣味
					</th>
					<td>
						<input type="text" name="hobby" value="<?php echo get_post_meta( $post->ID, 'hobby', true ); ?>" size="50">
					</td>
				</tr>
				<tr>
					<th>
						特技
					</th>
					<td>
						<input type="text" name="sp_skills" value="<?php echo get_post_meta( $post->ID, 'sp_skills', true ); ?>" size="50">
					</td>
				</tr>
			</tbody>
		</table>

		<table>
			<caption>
				<h3>SNS</h3>
			</caption>
			<tbody>
				<tr>
					<th>
						Twitter
					</th>
					<td>
						<input type="text" name="twitter" value="<?php echo get_post_meta( $post->ID, 'twitter', true ); ?>" size="50" placeholder="@無しのユーザー名">
					</td>
				</tr>
				<tr>
					<th>
						Facebook
					</th>
					<td>
						<input type="text" name="facebook" value="<?php echo get_post_meta( $post->ID, 'facebook', true ); ?>" size="50" placeholder="ユーザー名のみ">
					</td>
				</tr>
				<tr>
					<th>
						Instagram
					</th>
					<td>
						<input type="text" name="instagam" value="<?php echo get_post_meta( $post->ID, 'instagram', true ); ?>" size="50" placeholder="ユーザー名のみ">
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	// カスタムフィールドの値を保存
	static function ast_save_talent_info_fields( $post_id ) {
		if ( ! empty( $_POST['famname_j'] ) ) {
			update_post_meta( $post_id, 'famname_j', $_POST['famname_j'] );
		} else {
			delete_post_meta( $post_id, 'famname_j' );
		}

		if ( ! empty( $_POST['firstname_j'] ) ) {
			update_post_meta( $post_id, 'firstname_j', $_POST['firstname_j'] );
		} else {
			delete_post_meta( $post_id, 'firstname_j' );
		}

		if ( ! empty( $_POST['famname_e'] ) ) {
			$famname_e = mb_convert_kana( $_POST['famname_e'], 'an' );
			update_post_meta( $post_id, 'famname_e', $famname_e );
		} else {
			delete_post_meta( $post_id, 'famname_e' );
		}

		if ( ! empty( $_POST['firstname_e'] ) ) {
			$firstname_e = mb_convert_kana( $_POST['firstname_e'], 'an' );
			update_post_meta( $post_id, 'firstname_e', $firstname_e );
		} else {
			delete_post_meta( $post_id, 'firstname_e' );
		}

		if ( ! empty( $_POST['birth_month'] ) ) {
			$birth_month = mb_convert_kana( $_POST['birth_month'], 'an' );
			update_post_meta( $post_id, 'birth_month', $birth_month );
		} else {
			delete_post_meta( $post_id, 'birth_month' );
		}

		if ( ! empty( $_POST['birth_date'] ) ) {
			$birth_date = mb_convert_kana( $_POST['birth_date'], 'an' );
			update_post_meta( $post_id, 'birth_date', $birth_date );
		} else {
			delete_post_meta( $post_id, 'birth_date' );
		}

		if ( ! empty( $_POST['tall'] ) ) {
			update_post_meta( $post_id, 'tall', $_POST['tall'] );
		} else {
			delete_post_meta( $post_id, 'tall' );
		}

		if ( ! empty( $_POST['bust'] ) ) {
			update_post_meta( $post_id, 'bust', $_POST['bust'] );
		} else {
			delete_post_meta( $post_id, 'bust' );
		}

		if ( ! empty( $_POST['waist'] ) ) {
			update_post_meta( $post_id, 'waist', $_POST['waist'] );
		} else {
			delete_post_meta( $post_id, 'waist' );
		}

		if ( ! empty( $_POST['hip'] ) ) {
			update_post_meta( $post_id, 'hip', $_POST['hip'] );
		} else {
			delete_post_meta( $post_id, 'hip' );
		}

		if ( ! empty( $_POST['shoes'] ) ) {
			update_post_meta( $post_id, 'shoes', $_POST['shoes'] );
		} else {
			delete_post_meta( $post_id, 'shoes' );
		}

		if ( ! empty( $_POST['hobby'] ) ) {
			update_post_meta( $post_id, 'hobby', $_POST['hobby'] );
		} else {
			delete_post_meta( $post_id, 'hobby' );
		}

		if ( ! empty( $_POST['sp_skills'] ) ) {
			update_post_meta( $post_id, 'sp_skills', $_POST['sp_skills'] );
		} else {
			delete_post_meta( $post_id, 'sp_skills' );
		}

		if ( ! empty( $_POST['twitter'] ) ) {
			update_post_meta( $post_id, 'twitter', $_POST['twitter'] );
		} else {
			delete_post_meta( $post_id, 'twitter' );
		}

		if ( ! empty( $_POST['facebook'] ) ) {
			update_post_meta( $post_id, 'facebook', $_POST['facebook'] );
		} else {
			delete_post_meta( $post_id, 'facebook' );
		}

		if ( ! empty( $_POST['instagram'] ) ) {
			update_post_meta( $post_id, 'instagram', $_POST['instagram'] );
		} else {
			delete_post_meta( $post_id, 'instagram' );
		}
	}

	static function ast_add_talent_term( $new_status, $old_status, $post ) {
		// $new_status, $old_status を使って分岐
		if ( get_post_type( $post ) === 'talent' ) {
			if ( 'publish' === $new_status ) {
				switch ( $old_status ) {
					case 'new':
					case 'pending':
					case 'draft':
					case 'auto-draft':
					case 'future':
					case 'private':
						$post_id     = $post->ID;
						$famname_j   = $_POST['famname_j'];
						$firstname_j = $_POST['firstname_j'];
						$famname_e   = $_POST['famname_e'];
						$firstname_e = $_POST['firstname_e'];
						$term        = $famname_j . ' ' . $firstname_j;
						$slug        = $famname_e . '_' . $firstname_e;

						if ( ! term_exists( $slug, 'casting-talent' ) ) {
							wp_insert_term(
								$term,
								'casting-talent',
								array(
									'slug' => $slug,
								)
							);
						}
						break;
				}
			}
		}
	}
}

$ast_action_cpt_talent = new AstTalent();
