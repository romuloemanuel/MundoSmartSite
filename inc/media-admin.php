<?php
/**
 * Biblioteca fácil de fotos e vídeos das landings.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function mundosmart_media_defaults() {
	return array(
		'home_fachada'                 => 0,
		'home_fotos'                   => array( 0, 0, 0, 0, 0 ),
		'home_videos'                  => array( 0, 0, 0 ),
		'home_videos_capas'            => array( 0, 0, 0 ),
		'home_personalizados'          => array( 0, 0, 0 ),
		'home_troca'                   => array( 0, 0, 0, 0, 0, 0 ),
		'home_assistencia_fotos'       => array( 0, 0, 0, 0, 0, 0 ),
		'home_loja'                    => array( 0, 0, 0, 0, 0, 0 ),
		'assistencia_hero'             => 0,
		'assistencia_iphone'           => array( 0, 0, 0 ),
		'assistencia_iphone_capas'     => array( 0, 0, 0 ),
		'assistencia_android'          => array( 0, 0, 0 ),
		'assistencia_android_capas'    => array( 0, 0, 0 ),
		'assistencia_avancados'        => array( 0, 0, 0 ),
		'assistencia_avancados_capas'  => array( 0, 0, 0 ),
		'brindes_hero'                 => 0,
		'brindes_galeria'              => array( 0, 0, 0, 0, 0, 0 ),
	);
}

function mundosmart_video_poster_url( $video_id, $poster_id = 0 ) {
	$poster_id = (int) $poster_id;
	if ( $poster_id > 0 ) {
		$url = wp_get_attachment_image_url( $poster_id, 'large' );
		if ( $url ) {
			return $url;
		}
	}

	$video_id = (int) $video_id;
	if ( $video_id <= 0 ) {
		return '';
	}

	$thumb_id = (int) get_post_thumbnail_id( $video_id );
	if ( $thumb_id > 0 ) {
		$url = wp_get_attachment_image_url( $thumb_id, 'large' );
		if ( $url ) {
			return $url;
		}
	}

	$auto = wp_get_attachment_image_url( $video_id, 'large' );
	return $auto ? $auto : '';
}

function mundosmart_get_media() {
	$saved = get_option( 'mundosmart_media', array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}
	$out = mundosmart_media_defaults();
	foreach ( $out as $key => $value ) {
		if ( ! array_key_exists( $key, $saved ) ) {
			continue;
		}
		if ( is_array( $value ) ) {
			$ids     = array_map( 'intval', (array) $saved[ $key ] );
			$out[ $key ] = array_pad( array_slice( $ids, 0, count( $value ) ), count( $value ), 0 );
		} else {
			$out[ $key ] = (int) $saved[ $key ];
		}
	}
	return $out;
}

function mundosmart_attachment_to_item( $id, $expect = '' ) {
	$id = (int) $id;
	if ( $id <= 0 ) {
		return null;
	}
	$post = get_post( $id );
	if ( ! $post || 'attachment' !== $post->post_type ) {
		return null;
	}
	$mime     = (string) $post->post_mime_type;
	$is_image = ( 0 === strpos( $mime, 'image/' ) );
	$is_video = ( 0 === strpos( $mime, 'video/' ) );
	if ( 'image' === $expect && ! $is_image ) {
		return null;
	}
	if ( 'video' === $expect && ! $is_video ) {
		return null;
	}
	if ( in_array( $expect, array( '', 'media' ), true ) && ! $is_image && ! $is_video ) {
		return null;
	}
	$src = wp_get_attachment_url( $id );
	if ( ! $src ) {
		return null;
	}
	if ( 0 === strpos( $mime, 'image/' ) ) {
		$img = wp_get_attachment_image_src( $id, 'large' );
		if ( ! empty( $img[0] ) ) {
			$src = $img[0];
		}
		$src = mundosmart_fallback_upload_url( $src );
	}
	return array(
		'id'    => $id,
		'src'   => $src,
		'title' => get_the_title( $id ) ? get_the_title( $id ) : 'Mídia',
		'mime'  => $mime ? $mime : ( 'video' === $expect ? 'video/mp4' : 'image/jpeg' ),
		'url'   => '',
	);
}

function mundosmart_media_one( $key, $expect = 'image' ) {
	$media = mundosmart_get_media();
	$id    = isset( $media[ $key ] ) ? (int) $media[ $key ] : 0;
	return mundosmart_attachment_to_item( $id, $expect );
}

function mundosmart_media_list( $key, $expect, $limit ) {
	$media = mundosmart_get_media();
	$ids   = isset( $media[ $key ] ) ? (array) $media[ $key ] : array();
	$capas = isset( $media[ $key . '_capas' ] ) ? (array) $media[ $key . '_capas' ] : array();
	$items = array();
	foreach ( $ids as $index => $id ) {
		$item = mundosmart_attachment_to_item( $id, $expect );
		if ( ! $item ) {
			continue;
		}
		if ( ! empty( $item['mime'] ) && 0 === strpos( (string) $item['mime'], 'video/' ) ) {
			$poster_id      = isset( $capas[ $index ] ) ? (int) $capas[ $index ] : 0;
			$item['poster'] = mundosmart_video_poster_url( (int) $id, $poster_id );
		}
		$items[] = $item;
		if ( count( $items ) >= (int) $limit ) {
			break;
		}
	}
	return $items;
}

function mundosmart_media_save() {
	if ( ! isset( $_POST['mundosmart_media_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mundosmart_media_nonce'] ) ), 'mundosmart_media_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'upload_files' ) ) {
		return;
	}

	$raw      = isset( $_POST['mundosmart_media'] ) ? wp_unslash( $_POST['mundosmart_media'] ) : array();
	$defaults = mundosmart_media_defaults();
	$clean    = array();
	foreach ( $defaults as $key => $value ) {
		if ( is_array( $value ) ) {
			$ids   = array();
			$count = count( $value );
			for ( $i = 0; $i < $count; $i++ ) {
				$ids[] = ( isset( $raw[ $key ] ) && is_array( $raw[ $key ] ) && isset( $raw[ $key ][ $i ] ) )
					? (int) $raw[ $key ][ $i ]
					: 0;
			}
			$clean[ $key ] = $ids;
		} else {
			$clean[ $key ] = isset( $raw[ $key ] ) ? (int) $raw[ $key ] : 0;
		}
	}
	update_option( 'mundosmart_media', $clean, false );
	update_option( 'mundosmart_media_rev', time(), false );
	mundosmart_purge_site_cache();
	wp_safe_redirect( add_query_arg( 'updated', '1', admin_url( 'admin.php?page=mundosmart-midia' ) ) );
	exit;
}

function mundosmart_purge_site_cache() {
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}
	if ( has_action( 'litespeed_purge_all' ) ) {
		do_action( 'litespeed_purge_all' );
	}
	if ( class_exists( '\LiteSpeed\Purge' ) && method_exists( '\LiteSpeed\Purge', 'purge_all' ) ) {
		try {
			\LiteSpeed\Purge::purge_all();
		} catch ( Exception $e ) {
			// Continua mesmo se o cache da Hostinger falhar.
		}
	}
}
add_action( 'admin_init', 'mundosmart_media_save' );

function mundosmart_media_menu() {
	add_menu_page(
		'Fotos e vídeos',
		'Fotos e vídeos',
		'upload_files',
		'mundosmart-midia',
		'mundosmart_media_admin_page',
		'dashicons-format-gallery',
		58
	);
}
add_action( 'admin_menu', 'mundosmart_media_menu' );

function mundosmart_media_admin_assets( $hook ) {
	if ( 'toplevel_page_mundosmart-midia' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style(
		'mundosmart-admin-media',
		get_stylesheet_directory_uri() . '/assets/admin-media.css',
		array(),
		'1.0.2'
	);
	wp_enqueue_script(
		'mundosmart-admin-media',
		get_stylesheet_directory_uri() . '/assets/admin-media.js',
		array( 'jquery' ),
		'1.0.2',
		true
	);
}
add_action( 'admin_enqueue_scripts', 'mundosmart_media_admin_assets' );

function mundosmart_admin_preview_html( $id, $type ) {
	$item = mundosmart_attachment_to_item( (int) $id, $type );
	if ( ! $item ) {
		return '<span class="ms-admin-slot__empty">Vazio</span>';
	}
	if ( 0 === strpos( $item['mime'], 'video/' ) ) {
		$poster = wp_get_attachment_image_url( (int) $id, 'medium' );
		if ( $poster ) {
			return '<img src="' . esc_url( $poster ) . '" alt="">';
		}
		return '<video src="' . esc_url( $item['src'] ) . '" muted preload="metadata"></video>';
	}
	$thumb = wp_get_attachment_image_url( (int) $id, 'medium' );
	return '<img src="' . esc_url( $thumb ? $thumb : $item['src'] ) . '" alt="">';
}

function mundosmart_admin_pick_label( $type ) {
	if ( 'video' === $type ) {
		return 'Escolher vídeo';
	}
	if ( 'media' === $type ) {
		return 'Escolher foto ou vídeo';
	}
	return 'Escolher foto';
}

function mundosmart_admin_slot( $name, $id, $type, $label ) {
	$id  = (int) $id;
	$has = $id > 0 && mundosmart_attachment_to_item( $id, $type );
	?>
	<div class="ms-admin-slot<?php echo $has ? ' has-file' : ''; ?>" data-type="<?php echo esc_attr( $type ); ?>">
		<div class="ms-admin-slot__preview"><?php echo mundosmart_admin_preview_html( $id, $type ); ?></div>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo (int) $id; ?>">
		<button type="button" class="button ms-admin-pick"><?php echo esc_html( $label ); ?></button>
		<button type="button" class="button-link ms-admin-clear">Limpar</button>
	</div>
	<?php
}

function mundosmart_admin_slots( $key, $ids, $type, $count ) {
	$ids   = array_pad( array_slice( array_map( 'intval', (array) $ids ), 0, $count ), $count, 0 );
	$label = mundosmart_admin_pick_label( $type );
	echo '<div class="ms-admin-slots">';
	foreach ( $ids as $index => $id ) {
		mundosmart_admin_slot( 'mundosmart_media[' . $key . '][' . (int) $index . ']', $id, $type, $label );
	}
	echo '</div>';
}

function mundosmart_admin_video_slots( $video_key, $capa_key, $video_ids, $capa_ids, $count ) {
	$video_ids = array_pad( array_slice( array_map( 'intval', (array) $video_ids ), 0, $count ), $count, 0 );
	$capa_ids  = array_pad( array_slice( array_map( 'intval', (array) $capa_ids ), 0, $count ), $count, 0 );
	echo '<div class="ms-admin-slots ms-admin-slots--videos">';
	foreach ( $video_ids as $index => $id ) {
		?>
		<div class="ms-admin-video">
			<p class="ms-admin-video__title">Vídeo <?php echo (int) $index + 1; ?></p>
			<div class="ms-admin-video__pair">
				<?php mundosmart_admin_slot( 'mundosmart_media[' . $video_key . '][' . (int) $index . ']', $id, 'media', 'Escolher foto ou vídeo' ); ?>
				<?php mundosmart_admin_slot( 'mundosmart_media[' . $capa_key . '][' . (int) $index . ']', $capa_ids[ $index ], 'image', 'Foto de capa' ); ?>
			</div>
		</div>
		<?php
	}
	echo '</div>';
}

function mundosmart_admin_single( $key, $id, $type ) {
	$label = mundosmart_admin_pick_label( $type );
	echo '<div class="ms-admin-slots">';
	mundosmart_admin_slot( 'mundosmart_media[' . $key . ']', $id, $type, $label );
	echo '</div>';
}

function mundosmart_media_admin_page() {
	if ( ! current_user_can( 'upload_files' ) ) {
		return;
	}
	$media = mundosmart_get_media();
	?>
	<div class="wrap ms-admin-media">
		<h1>Fotos e vídeos</h1>
		<p class="ms-admin-lead">Cada carrossel mostra <strong>somente</strong> o que você colocar naquele bloco. Foto ou vídeo. Depois clique em <strong>Salvar mídia</strong>.</p>
		<?php if ( isset( $_GET['updated'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>Mídia salva e cache limpo. Abra o site em aba anônima para conferir.</p></div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'mundosmart_media_save', 'mundosmart_media_nonce' ); ?>
			<?php submit_button( 'Salvar mídia' ); ?>

			<nav class="ms-admin-jump">
				<a href="#ms-home">Home</a>
				<a href="#ms-assistencia">Assistência</a>
				<a href="#ms-brindes">Brindes</a>
			</nav>

			<section class="ms-admin-card" id="ms-home">
				<h2>Home</h2>
				<div class="ms-admin-field">
					<h3>Fachada</h3>
					<p>Primeira imagem do carrossel principal.</p>
					<?php mundosmart_admin_single( 'home_fachada', $media['home_fachada'], 'image' ); ?>
				</div>
				<div class="ms-admin-field">
					<h3>Fotos do carrossel</h3>
					<p>Até 5 arquivos só deste carrossel da home (depois da fachada).</p>
					<?php mundosmart_admin_slots( 'home_fotos', $media['home_fotos'], 'media', 5 ); ?>
				</div>
				<div class="ms-admin-field">
					<h3>Vídeos do carrossel</h3>
					<p>Até 3 vídeos no painel da home. A foto de capa aparece antes do vídeo iniciar.</p>
					<?php mundosmart_admin_video_slots( 'home_videos', 'home_videos_capas', $media['home_videos'], $media['home_videos_capas'], 3 ); ?>
				</div>
				<div class="ms-admin-field">
					<h3>Carrossel: troca e venda</h3>
					<p>Só este carrossel: troca e venda. Até 6 fotos ou vídeos.</p>
					<?php mundosmart_admin_slots( 'home_troca', $media['home_troca'], 'media', 6 ); ?>
				</div>
				<div class="ms-admin-field">
					<h3>Carrossel: assistência técnica</h3>
					<p>Só este carrossel: assistência. Até 6 fotos ou vídeos.</p>
					<?php mundosmart_admin_slots( 'home_assistencia_fotos', $media['home_assistencia_fotos'], 'media', 6 ); ?>
				</div>
				<div class="ms-admin-field">
					<h3>Carrossel: itens da loja</h3>
					<p>Só este carrossel: itens da loja. Até 6 fotos ou vídeos.</p>
					<?php mundosmart_admin_slots( 'home_loja', $media['home_loja'], 'media', 6 ); ?>
				</div>
				<?php submit_button( 'Salvar mídia' ); ?>
			</section>

			<section class="ms-admin-card" id="ms-assistencia">
				<h2>Assistência técnica</h2>
				<div class="ms-admin-field">
					<h3>Foto principal</h3>
					<p>Imagem ao lado do texto, no topo da página.</p>
					<?php mundosmart_admin_single( 'assistencia_hero', $media['assistencia_hero'], 'image' ); ?>
				</div>
				<div class="ms-admin-field">
					<h3>Reparo em iPhone</h3>
					<p>Só este carrossel. Foto ou vídeo. A capa é opcional e aparece até o vídeo começar.</p>
					<?php mundosmart_admin_video_slots( 'assistencia_iphone', 'assistencia_iphone_capas', $media['assistencia_iphone'], $media['assistencia_iphone_capas'], 3 ); ?>
				</div>
				<div class="ms-admin-field">
					<h3>Reparo em Android</h3>
					<p>Só este carrossel Android. Foto ou vídeo. Não use os espaços do iPhone.</p>
					<?php mundosmart_admin_video_slots( 'assistencia_android', 'assistencia_android_capas', $media['assistencia_android'], $media['assistencia_android_capas'], 3 ); ?>
				</div>
				<div class="ms-admin-field">
					<h3>Reparos avançados</h3>
					<p>Só este carrossel de reparos avançados. Foto ou vídeo.</p>
					<?php mundosmart_admin_video_slots( 'assistencia_avancados', 'assistencia_avancados_capas', $media['assistencia_avancados'], $media['assistencia_avancados_capas'], 3 ); ?>
				</div>
				<?php submit_button( 'Salvar mídia' ); ?>
			</section>

			<section class="ms-admin-card" id="ms-brindes">
				<h2>Brindes</h2>
				<div class="ms-admin-field">
					<h3>Foto principal</h3>
					<?php mundosmart_admin_single( 'brindes_hero', $media['brindes_hero'], 'image' ); ?>
				</div>
				<div class="ms-admin-field">
					<h3>Alguns trabalhos</h3>
					<p>Só esta galeria. Até 6 fotos ou vídeos.</p>
					<?php mundosmart_admin_slots( 'brindes_galeria', $media['brindes_galeria'], 'media', 6 ); ?>
				</div>
			</section>

			<?php submit_button( 'Salvar mídia' ); ?>
		</form>
	</div>
	<?php
}
