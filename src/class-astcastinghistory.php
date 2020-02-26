<?php
// Create Custom post type "casting-history"

class AstCastingHistory {
	public function __construct() {
		add_action( 'init', array( $this, 'create_post_type' ) );
		add_action( 'admin_menu', array( $this, 'ast_add_casting_history_fields' ) );
		//add_action( 'save_post', array( $this, 'ast_save_casting_history_fields' ) );
		add_action( 'admin_head-post-new.php', array( $this, 'ast_load_casting_history_script' ) );
	}

	static function create_post_type() {
		$support = array( // 投稿画面で表示される項目の設定
			'title', // 記事タイトル
		);
		register_post_type(
			'casting-history', // URLになる部分
			array(
				'label'         => '出演履歴', // 管理画面の左メニューに表示されるテキスト
				'labels'        => array(
					'all_items' => '出演履歴一覧', // 管理画面の左メニューの下層に表示されるテキスト
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_position' => 5,
				'supports'      => $support,
				'show_in_rest'  => true,
			)
		);
	}

	// Add custom fields section
	static function ast_add_casting_history_fields() {
		//add_meta_box(表示される入力ボックスのHTMLのID, ラベル, 表示する内容を作成する関数名, 投稿タイプ, 表示方法)
		add_meta_box( 'ast_casting_history_info', '出演情報', array( $this, 'ast_casting_history_info_fields' ), 'casting-history', 'normal' );
	}

	// Add custom fields
	static function ast_casting_history_info_fields() {
		global $post;

		$casting_type = get_post_meta( $post->ID, 'casting_type', true );
		if ( empty( $casting_type ) ) {
			$casting_type = 'stage';
		}
		$selected  = ' selected';
		$disp_none = ' style="display:none;"';
		?>
		<table>
			<caption>
				<h3>作品情報</h3>
				<p>注意：この情報が各個人の出演履歴に反映されます。初日と楽日をおなじにすると単日公演として処理されます。</p>
				<select name="casting_type" id="casting_type">
					<option value="stage"<?php 'stage' === $casting_type ? print $selected : ''; ?>>舞台</option>
					<option value="tv"<?php 'tv' === $casting_type ? print $selected : ''; ?>>TV</option>
					<option value="movie"<?php 'movie' === $casting_type ? print $selected : ''; ?>>映画</option>
					<option value="cm"<?php 'cm' === $casting_type ? print $selected : ''; ?>>CM</option>
					<option value="pv"<?php 'pv' === $casting_type ? print $selected : ''; ?>>PV</option>
					<option value="show"<?php 'show' === $casting_type ? print $selected : ''; ?>>ショー</option>
					<option value="webtv"<?php 'webtv' === $casting_type ? print $selected : ''; ?>>WEB TV</option>
				</select>
			</caption>
			<thead>
				<tr class="stage"<?php 'stage' === $casting_type ? '' : print $disp_none; ?>><th>初日</th><th>楽日</th><th>タイトル</th><th>会場名</th><th>住所</th></tr>
				<tr class="tv"<?php 'tv' === $casting_type ? '' : print $disp_none; ?>><th>放映日時</th><th>タイトル</th><th>チャンネル・系列</th></tr>
				<tr class="movie"<?php 'movie' === $casting_type ? '' : print $disp_none; ?>><th>公開日</th><th>タイトル</th><th>配給会社</th></tr>
				<tr class="cm"<?php 'cm' === $casting_type ? '' : print $disp_none; ?>><th>放映開始日</th><th>タイトル</th><th>企業・ブランド名</th></tr>
				<tr class="pv"<?php 'pv' === $casting_type ? '' : print $disp_none; ?>><th>公開日</th><th>タイトル</th><th>アーティスト名</th></tr>
				<tr class="show"<?php 'show' === $casting_type ? '' : print $disp_none; ?>><th>初日</th><th>楽日</th><th>タイトル</th><th>会場名</th><th>住所</th></tr>
				<tr class="webtv"<?php 'webtv' === $casting_type ? '' : print $disp_none; ?>><th>公開日</th><th>タイトル</th><th>公開場所（リンクは付きません）</th></tr>
			</thead>
			<tbody>
				<tr class="stage"<?php 'stage' === $casting_type ? '' : print $disp_none; ?>>
					<td>
						<input type="text" name="stage_first_year" value="<?php echo get_post_meta( $post->ID, 'stage_first_year', true ); ?>" size="4" autocomplete="off" placeholder="YYYY">/
						<input type="text" name="stage_first_month" value="<?php echo get_post_meta( $post->ID, 'stage_first_month', true ); ?>" size="2" autocomplete="off" placeholder="MM">/
						<input type="text" name="stage_first_date" value="<?php echo get_post_meta( $post->ID, 'stage_first_date', true ); ?>" size="2" autocomplete="off" placeholder="DD">
					</td>
					<td>
						<input type="text" name="stage_final_year" value="<?php echo get_post_meta( $post->ID, 'stage_final_year', true ); ?>" size="4" autocomplete="off" placeholder="YYYY">/
						<input type="text" name="stage_final_month" value="<?php echo get_post_meta( $post->ID, 'stage_final_month', true ); ?>" size="2" autocomplete="off" placeholder="MM">/
						<input type="text" name="stage_final_date" value="<?php echo get_post_meta( $post->ID, 'stage_final_date', true ); ?>" size="2" autocomplete="off" placeholder="DD">
					</td>
					<td>
						<input type="text" name="stage_title" value="<?php echo get_post_meta( $post->ID, 'stage_title', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="stage_venue" value="<?php echo get_post_meta( $post->ID, 'stage_venue', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="stage_address" value="<?php echo get_post_meta( $post->ID, 'stage_address', true ); ?>" size="20" autocomplete="off">
					</td>
				</tr>
				<tr class="tv"<?php 'tv' === $casting_type ? '' : print $disp_none; ?>>
					<td>
						<input type="text" name="tv_first_year" value="<?php echo get_post_meta( $post->ID, 'tv_first_year', true ); ?>" size="4" autocomplete="off" placeholder="YYYY">/
						<input type="text" name="tv_first_month" value="<?php echo get_post_meta( $post->ID, 'tv_first_month', true ); ?>" size="2" autocomplete="off" placeholder="MM">/
						<input type="text" name="tv_first_date" value="<?php echo get_post_meta( $post->ID, 'tv_first_date', true ); ?>" size="2" autocomplete="off" placeholder="DD">&nbsp;
						<input type="text" name="tv_first_hour" value="<?php echo get_post_meta( $post->ID, 'tv_first_hour', true ); ?>" size="2" autocomplete="off" placeholder="HH">:
						<input type="text" name="tv_first_time" value="<?php echo get_post_meta( $post->ID, 'tv_first_time', true ); ?>" size="2" autocomplete="off" placeholder="MM">
					</td>
					<td>
						<input type="text" name="tv_title" value="<?php echo get_post_meta( $post->ID, 'tv_title', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="tv_channel" value="<?php echo get_post_meta( $post->ID, 'tv_channel', true ); ?>" size="20" autocomplete="off">
					</td>
				</tr>
				<tr class="movie"<?php 'movie' === $casting_type ? '' : print $disp_none; ?>>
					<td>
						<input type="text" name="movie_first_year" value="<?php echo get_post_meta( $post->ID, 'movie_first_year', true ); ?>" size="4" autocomplete="off" placeholder="YYYY">/
						<input type="text" name="movie_first_month" value="<?php echo get_post_meta( $post->ID, 'movie_first_month', true ); ?>" size="2" autocomplete="off" placeholder="MM">/
						<input type="text" name="movie_first_date" value="<?php echo get_post_meta( $post->ID, 'movie_first_date', true ); ?>" size="2" autocomplete="off" placeholder="DD">&nbsp;
					</td>
					<td>
						<input type="text" name="movie_title" value="<?php echo get_post_meta( $post->ID, 'movie_title', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="movie_provider" value="<?php echo get_post_meta( $post->ID, 'movie_provider', true ); ?>" size="20" autocomplete="off">
					</td>
				</tr>
				<tr class="cm"<?php 'cm' === $casting_type ? '' : print $disp_none; ?>>
					<td>
						<input type="text" name="cm_first_year" value="<?php echo get_post_meta( $post->ID, 'cm_first_year', true ); ?>" size="4" autocomplete="off" placeholder="YYYY">/
						<input type="text" name="cm_first_month" value="<?php echo get_post_meta( $post->ID, 'cm_first_month', true ); ?>" size="2" autocomplete="off" placeholder="MM">/
						<input type="text" name="cm_first_date" value="<?php echo get_post_meta( $post->ID, 'cm_first_date', true ); ?>" size="2" autocomplete="off" placeholder="DD">&nbsp;
					</td>
					<td>
						<input type="text" name="cm_title" value="<?php echo get_post_meta( $post->ID, 'cm_title', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="cm_brand" value="<?php echo get_post_meta( $post->ID, 'cm_brand', true ); ?>" size="20" autocomplete="off">
					</td>
				</tr>
				<tr class="pv"<?php 'pv' === $casting_type ? '' : print $disp_none; ?>>
					<td>
						<input type="text" name="pv_first_year" value="<?php echo get_post_meta( $post->ID, 'pv_first_year', true ); ?>" size="4" autocomplete="off" placeholder="YYYY">/
						<input type="text" name="pv_first_month" value="<?php echo get_post_meta( $post->ID, 'pv_first_month', true ); ?>" size="2" autocomplete="off" placeholder="MM">/
						<input type="text" name="pv_first_date" value="<?php echo get_post_meta( $post->ID, 'pv_first_date', true ); ?>" size="2" autocomplete="off" placeholder="DD">&nbsp;
					</td>
					<td>
						<input type="text" name="pv_title" value="<?php echo get_post_meta( $post->ID, 'pv_title', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="pv_artist" value="<?php echo get_post_meta( $post->ID, 'pv_artist', true ); ?>" size="20" autocomplete="off">
					</td>
				</tr>
				<tr class="show"<?php 'show' === $casting_type ? '' : print $disp_none; ?>>
					<td>
						<input type="text" name="show_first_year" value="<?php echo get_post_meta( $post->ID, 'show_first_year', true ); ?>" size="4" autocomplete="off" placeholder="YYYY">/
						<input type="text" name="show_first_month" value="<?php echo get_post_meta( $post->ID, 'show_first_month', true ); ?>" size="2" autocomplete="off" placeholder="MM">/
						<input type="text" name="show_first_date" value="<?php echo get_post_meta( $post->ID, 'show_first_date', true ); ?>" size="2" autocomplete="off" placeholder="DD">
					</td>
					<td>
						<input type="text" name="show_final_year" value="<?php echo get_post_meta( $post->ID, 'show_final_year', true ); ?>" size="4" autocomplete="off" placeholder="YYYY">/
						<input type="text" name="show_final_month" value="<?php echo get_post_meta( $post->ID, 'show_final_month', true ); ?>" size="2" autocomplete="off" placeholder="MM">/
						<input type="text" name="show_final_date" value="<?php echo get_post_meta( $post->ID, 'show_final_date', true ); ?>" size="2" autocomplete="off" placeholder="DD">
					</td>
					<td>
						<input type="text" name="show_title" value="<?php echo get_post_meta( $post->ID, 'show_title', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="show_venue" value="<?php echo get_post_meta( $post->ID, 'show_venue', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="show_address" value="<?php echo get_post_meta( $post->ID, 'show_address', true ); ?>" size="20" autocomplete="off">
					</td>
				</tr>
				<tr class="webtv"<?php 'webtv' === $casting_type ? '' : print $disp_none; ?>>
					<td>
						<input type="text" name="webtv_first_year" value="<?php echo get_post_meta( $post->ID, 'webtv_first_year', true ); ?>" size="4" autocomplete="off" placeholder="YYYY">/
						<input type="text" name="webtv_first_month" value="<?php echo get_post_meta( $post->ID, 'webtv_first_month', true ); ?>" size="2" autocomplete="off" placeholder="MM">/
						<input type="text" name="webtv_first_date" value="<?php echo get_post_meta( $post->ID, 'webtv_first_date', true ); ?>" size="2" autocomplete="off" placeholder="DD">
					</td>
					<td>
						<input type="text" name="webtv_title" value="<?php echo get_post_meta( $post->ID, 'webtv_title', true ); ?>" size="20" autocomplete="off">
					</td>
					<td>
						<input type="text" name="webtv_site" value="<?php echo get_post_meta( $post->ID, 'webtv_site', true ); ?>" size="20" autocomplete="off">
					</td>
				</tr>
			</tbody>
		</table>

		<table>
			<caption>
				<h3>出演情報</h3>
				<?php
				$terms_this_post = get_the_terms( $post->ID, 'casting-talent' );
				$cap_style1      = '';
				$cap_style2      = '';
				if ( ! empty( $terms_this_post ) ) :
					$cap_style2 = ' style="display: none;"';
				else :
					$cap_style1 = ' style="display: none;"';
				endif;
				?>
				<p class="casting_cap1"<?php echo $cap_style1; ?>>「役」のチェックを入れると、表示の際に役柄の後ろに「役」が付き、「○○役」となります。</p>
				<p class="casting_cap2"<?php echo $cap_style2; ?>>出演タレントの欄にチェックを入れると欄が表示されます。</p>
			</caption>
			<thead>
				<tr>
					<th>タレント</th>
					<th>役柄・役割</th>
					<th>役</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$args    = array(
					'hide_empty' => 0,
					'order_by'   => 'id',
					'order'      => 'DESC',
				);
				$talents = get_terms( 'casting-talent', $args );

				foreach ( $talents as $talent ) {
					$disp_flag = false;
					if ( ! empty( $terms_this_post ) ) {
						foreach ( $terms_this_post as $term_this_post ) {
							if ( $term_this_post->name === $talent->name ) {
								echo $term_this_post->name;
							}
						}
					}
					$style = $disp_flag ? '' : ' style="display: none;"';
					?>
					<tr class="<?php echo str_replace( ' ', '', $talent->name ); ?>"<?php echo $style; ?>>
						<th class="casting_talent_name"><?php echo $talent->name; ?></th>
						<td><input type="text" name="casting_role_<?php echo $talent->slug; ?>" value="<?php echo get_post_meta( $post->ID, 'casting_role_' . $talent->slug, true ); ?>"></td>
						<td><input type="checkbox" name="casting_check_<?php echo $talent->slug; ?>" value="<?php echo get_post_meta( $post->ID, 'casting_check_' . $talent->slug, true ); ?>"></td>
					</tr>
					<?php
				}
				?>
			</tbody>
		</table>
		<?php
	}

	// Load scipt file for admin-post
	static function ast_load_casting_history_script() {
		if ( get_post_type() === 'casting' ) {
			wp_enqueue_script( 'ast-casting-admin-script', plugins_url( 'js/casting-admin.js', __FILE__ ), array( 'jquery' ) );
		}
	}
}

$ast_action_cpt_casting_history = new AstCastingHistory;
