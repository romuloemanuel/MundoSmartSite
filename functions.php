<?php
/**
 * Mundo Smart child theme: assistência técnica com identidade da logo.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_stylesheet_directory() . '/inc/media-admin.php';

define( 'MUNDOSMART_WHATSAPP', '5519989387457' );

function mundosmart_whatsapp_url( $message = '' ) {
	$text = $message ? $message : 'Olá! Quero assistência técnica no meu celular.';
	return 'https://wa.me/' . MUNDOSMART_WHATSAPP . '?text=' . rawurlencode( $text );
}

function mundosmart_whatsapp_icon() {
	return '<svg class="ms-whatsapp-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>';
}

function mundosmart_google_maps_url() {
	return 'https://www.google.com/maps/place/Mundo+Smart/@-21.4667958,-47.000261,17z/data=!4m8!3m7!1s0x94b7b97dc3effb9d:0xd915d529063e58ec!8m2!3d-21.4667958!4d-47.000261!9m1!1b1';
}

function mundosmart_google_reviews( $topic = '' ) {
	$reviews = array(
		array(
			'name'   => 'Rosangela Donizete Pinto',
			'rating' => 5,
			'topic'  => 'assistencia',
			'text'   => 'Estou muito feliz com a troca da tela do meu celular. Mundo Smart, muito obrigada! Super recomendo!',
			'show'   => true,
		),
		array(
			'name'   => 'Liniker Calixto',
			'rating' => 5,
			'topic'  => 'assistencia',
			'text'   => 'Mão de obra qualificada e um ótimo atendimento, loja bem estruturada. Parabéns.',
			'show'   => true,
		),
		array(
			'name'   => 'Gabriel Henrique dos Santos',
			'rating' => 5,
			'topic'  => 'assistencia',
			'text'   => 'Trabalho rápido e bom.',
			'show'   => true,
		),
		array(
			'name'   => 'Erika',
			'rating' => 5,
			'topic'  => 'assistencia',
			'text'   => 'Ótimo atendimento, trabalho excelente!',
			'show'   => true,
		),
		array(
			'name'   => 'Gabriela Pedigone',
			'rating' => 5,
			'topic'  => 'loja',
			'text'   => 'Super recomendo a Mundo Smart de Mococa. Loja completa, com atendimento rápido e uma variedade incrível de produtos.',
			'show'   => true,
		),
		array(
			'name'   => 'Gabriela Donato',
			'rating' => 5,
			'topic'  => 'loja',
			'text'   => 'Ótimo atendimento, qualidade dos produtos são excepcionais.',
			'show'   => true,
		),
		array(
			'name'   => 'Damilton Longo de Araujo',
			'rating' => 5,
			'topic'  => 'loja',
			'text'   => 'Loja muito bacana, com excelente atendimento e produtos de ótima qualidade.',
			'show'   => true,
		),
		array(
			'name'   => 'Felipe Calixto',
			'rating' => 5,
			'topic'  => 'loja',
			'text'   => 'Muito atenciosos, ótimo atendimento, gostei muito.',
			'show'   => true,
		),
		array(
			'name'   => 'Marcos Greghi',
			'rating' => 5,
			'topic'  => 'loja',
			'text'   => 'Ótimo atendimento e produtos de qualidade!',
			'show'   => true,
		),
		array(
			'name'   => 'Danilo',
			'rating' => 5,
			'topic'  => 'loja',
			'text'   => 'Ótima loja! Ótimo atendimento.',
			'show'   => true,
		),
	);

	return array_values(
		array_filter(
			$reviews,
			static function ( $review ) use ( $topic ) {
				if ( empty( $review['show'] ) || (float) $review['rating'] <= 4.5 ) {
					return false;
				}
				if ( $topic && ( $review['topic'] ?? '' ) !== $topic ) {
					return false;
				}
				return true;
			}
		)
	);
}

function mundosmart_reviews_markup( $limit = 6, $topic = '' ) {
	$reviews = array_slice( mundosmart_google_reviews( $topic ), 0, (int) $limit );
	if ( ! $reviews ) {
		return '';
	}
	$maps  = mundosmart_google_maps_url();
	$title = ( 'assistencia' === $topic ) ? 'Quem já <span>consertou aqui</span>' : 'Quem já <span>passou aqui</span>';
	$count = count( $reviews );
	ob_start();
	?>
	<section class="ms-section ms-reviews">
		<div class="ms-wrap">
			<div class="ms-reviews__head">
				<div>
					<p class="ms-kicker">Avaliações no Google</p>
					<h2><?php echo $title; ?></h2>
					<p class="ms-reviews__score"><strong>5,0</strong> · 49 avaliações no Google</p>
				</div>
				<a class="ms-btn ms-btn--ghost" href="<?php echo esc_url( $maps ); ?>" target="_blank" rel="noopener">Ver no Google</a>
			</div>
			<div class="ms-reviews__grid ms-reviews__grid--<?php echo (int) $count; ?>">
				<?php foreach ( $reviews as $review ) : ?>
				<blockquote class="ms-review">
					<p class="ms-review__stars" aria-label="<?php echo esc_attr( (int) $review['rating'] ); ?> de 5 estrelas"><?php echo esc_html( str_repeat( '★', (int) $review['rating'] ) ); ?></p>
					<p><?php echo esc_html( $review['text'] ); ?></p>
					<cite><?php echo esc_html( $review['name'] ); ?> · Google</cite>
				</blockquote>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function mundosmart_logo_url() {
	$id = (int) get_theme_mod( 'custom_logo' );
	if ( ! $id ) {
		$id = (int) get_option( 'site_logo' );
	}
	return $id ? wp_get_attachment_image_url( $id, 'full' ) : '';
}

function mundosmart_child_setup() {
	add_theme_support( 'hello-elementor-header-footer' );
}
add_action( 'after_setup_theme', 'mundosmart_child_setup' );

add_filter(
	'hello_elementor_viewport_content',
	static function () {
		return 'width=device-width, initial-scale=1, viewport-fit=cover';
	}
);

function mundosmart_favicon_url( $file ) {
	return get_stylesheet_directory_uri() . '/assets/' . ltrim( $file, '/' );
}

function mundosmart_favicon_tags() {
	$icon32  = mundosmart_favicon_url( 'favicon-32.png' );
	$icon48  = mundosmart_favicon_url( 'favicon-48.png' );
	$icon192 = mundosmart_favicon_url( 'favicon-192.png' );
	$apple   = mundosmart_favicon_url( 'apple-touch-icon.png' );
	echo '<link rel="icon" href="' . esc_url( $icon32 ) . '" sizes="32x32">' . "\n";
	echo '<link rel="icon" href="' . esc_url( $icon48 ) . '" sizes="48x48">' . "\n";
	echo '<link rel="icon" href="' . esc_url( $icon192 ) . '" sizes="192x192">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $apple ) . '" sizes="180x180">' . "\n";
}
add_action( 'wp_head', 'mundosmart_favicon_tags', 1 );
add_action( 'admin_head', 'mundosmart_favicon_tags', 1 );
add_action( 'login_head', 'mundosmart_favicon_tags', 1 );

function mundosmart_enqueue_assets() {
	wp_enqueue_style(
		'mundosmart-assistencia',
		get_stylesheet_directory_uri() . '/assets/assistencia.css',
		array(),
		'2.8.10'
	);
	wp_enqueue_script(
		'mundosmart-landing',
		get_stylesheet_directory_uri() . '/assets/landing.js',
		array(),
		'1.2.7',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'mundosmart_enqueue_assets' );

function mundosmart_rewrite_local_url( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return $url;
	}
	$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( (string) $_SERVER['HTTP_HOST'] ) : '';
	if ( '' === $host ) {
		return $url;
	}
	return preg_replace(
		'#https?://(?:localhost|127\.0\.0\.1)(?::[0-9]+)?#i',
		'http://' . $host,
		$url
	);
}

function mundosmart_theme_midia_url( $filename ) {
	$filename = basename( rawurldecode( (string) $filename ) );
	if ( '' === $filename || false !== strpos( $filename, '..' ) ) {
		return '';
	}
	$path = get_stylesheet_directory() . '/assets/midia/' . $filename;
	if ( ! is_file( $path ) ) {
		return '';
	}
	return get_stylesheet_directory_uri() . '/assets/midia/' . rawurlencode( $filename );
}

function mundosmart_upload_file_exists( $url ) {
	$uploads = wp_get_upload_dir();
	if ( empty( $uploads['baseurl'] ) || 0 !== strpos( (string) $url, $uploads['baseurl'] ) ) {
		return true;
	}
	$file = $uploads['basedir'] . substr( (string) $url, strlen( $uploads['baseurl'] ) );
	return is_file( $file );
}

function mundosmart_fallback_upload_url( $url ) {
	if ( ! is_string( $url ) || '' === $url ) {
		return $url;
	}
	$url = mundosmart_rewrite_local_url( $url );
	if ( mundosmart_upload_file_exists( $url ) ) {
		return $url;
	}
	$base = basename( (string) wp_parse_url( $url, PHP_URL_PATH ) );
	if ( '' === $base ) {
		return $url;
	}
	$theme = mundosmart_theme_midia_url( $base );
	if ( $theme ) {
		return $theme;
	}
	$orig = preg_replace( '/-\d+x\d+(?=\.[a-z0-9]+$)/i', '', $base );
	if ( $orig && $orig !== $base ) {
		$theme = mundosmart_theme_midia_url( $orig );
		if ( $theme ) {
			return $theme;
		}
	}
	return $url;
}

add_filter( 'wp_get_attachment_url', 'mundosmart_fallback_upload_url' );
add_filter( 'style_loader_src', 'mundosmart_rewrite_local_url' );
add_filter( 'script_loader_src', 'mundosmart_rewrite_local_url' );
add_filter(
	'wp_get_attachment_image_src',
	static function ( $image ) {
		if ( is_array( $image ) && ! empty( $image[0] ) ) {
			$image[0] = mundosmart_fallback_upload_url( $image[0] );
		}
		return $image;
	}
);
add_filter(
	'wp_calculate_image_srcset',
	static function ( $sources ) {
		if ( ! is_array( $sources ) ) {
			return $sources;
		}
		foreach ( $sources as &$source ) {
			if ( ! empty( $source['url'] ) ) {
				$source['url'] = mundosmart_fallback_upload_url( $source['url'] );
			}
		}
		return $sources;
	}
);
add_action(
	'template_redirect',
	static function () {
		if ( is_admin() ) {
			return;
		}
		ob_start( 'mundosmart_rewrite_local_url' );
	},
	0
);

function mundosmart_shop_carousel_items( $limit = 12, $include_hidden = false ) {
	$items = array();
	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => $include_hidden ? array( 'publish', 'draft', 'private' ) : 'publish',
			'posts_per_page' => max( 8, (int) $limit * 2 ),
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);
	$seen     = array();
	$fallback = home_url( '/brindes/' );
	foreach ( $query->posts as $post ) {
		$thumb_id = (int) get_post_thumbnail_id( $post );
		if ( ! $thumb_id || isset( $seen[ $thumb_id ] ) ) {
			continue;
		}
		$src = wp_get_attachment_image_url( $thumb_id, 'woocommerce_single' );
		if ( ! $src ) {
			$src = wp_get_attachment_image_url( $thumb_id, 'medium_large' );
		}
		if ( ! $src ) {
			continue;
		}
		$seen[ $thumb_id ] = true;
		$items[]           = array(
			'src'   => $src,
			'url'   => 'publish' === $post->post_status ? get_permalink( $post ) : $fallback,
			'title' => get_the_title( $post ),
		);
		if ( count( $items ) >= $limit ) {
			break;
		}
	}
	return $items;
}

function mundosmart_theme_file_uri( $path ) {
	$path = wp_normalize_path( (string) $path );
	$base = wp_normalize_path( get_stylesheet_directory() );
	if ( $path === '' || ! is_file( $path ) || 0 !== strpos( $path, $base ) ) {
		return '';
	}
	$rel = ltrim( substr( $path, strlen( $base ) ), '/' );
	return get_stylesheet_directory_uri() . '/' . $rel . '?v=' . (int) filemtime( $path );
}

function mundosmart_local_video_poster( $video_path ) {
	$video_path = wp_normalize_path( (string) $video_path );
	$stem       = preg_replace( '/\.[^.]+$/', '', $video_path );
	foreach ( array( 'jpg', 'jpeg', 'png', 'webp' ) as $ext ) {
		$img = $stem . '.' . $ext;
		if ( is_file( $img ) ) {
			return mundosmart_theme_file_uri( $img );
		}
	}
	return '';
}

function mundosmart_video_sound_button() {
	return '<button type="button" class="ms-video-sound is-muted" data-ms-sound aria-label="Ativar som" aria-pressed="false">'
		. '<svg class="ms-video-sound__off" viewBox="0 0 24 24" aria-hidden="true"><path d="M4.27 3 3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25A7.9 7.9 0 0 1 14 18.7v2.06a9.9 9.9 0 0 0 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM14 3.23v2.06a8 8 0 0 1 4.02 2.18l-1.46 1.46A6 6 0 0 0 14 7.18V3.23zM12 4 9.91 6.09 12 8.18V4z" fill="currentColor"/></svg>'
		. '<svg class="ms-video-sound__on" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10v4h4l5 5V5L7 10H3zm13.5 2A4.5 4.5 0 0 0 14 7.97v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" fill="currentColor"/></svg>'
		. '</button>';
}

function mundosmart_video_markup( $item, $loop = false ) {
	$src    = isset( $item['src'] ) ? (string) $item['src'] : '';
	$mime   = isset( $item['mime'] ) ? (string) $item['mime'] : 'video/mp4';
	$title  = isset( $item['title'] ) ? (string) $item['title'] : 'Vídeo';
	$poster = isset( $item['poster'] ) ? (string) $item['poster'] : '';
	if ( $src === '' ) {
		return '';
	}
	$preload = $poster ? 'none' : 'metadata';
	ob_start();
	if ( $poster ) {
		?>
		<img class="ms-video-poster" src="<?php echo esc_url( $poster ); ?>" alt="<?php echo esc_attr( $title ); ?>" loading="lazy" width="800" height="800">
		<?php
	}
	?>
	<video muted playsinline preload="<?php echo esc_attr( $preload ); ?>"<?php echo $loop ? ' loop' : ''; ?><?php echo $poster ? ' poster="' . esc_url( $poster ) . '"' : ''; ?> aria-label="<?php echo esc_attr( $title ); ?>">
		<source src="<?php echo esc_url( $src ); ?>" type="<?php echo esc_attr( $mime ); ?>">
	</video>
	<?php
	echo mundosmart_video_sound_button();
	return ob_get_clean();
}

function mundosmart_theme_image_item( $relative, $title = '', $url = '' ) {
	$path = get_stylesheet_directory() . '/assets/' . ltrim( (string) $relative, '/' );
	if ( ! is_file( $path ) ) {
		return null;
	}
	return array(
		'src'   => get_stylesheet_directory_uri() . '/assets/' . ltrim( (string) $relative, '/' ) . '?v=' . (int) filemtime( $path ),
		'url'   => $url,
		'title' => $title ? $title : mundosmart_hero_video_label( basename( $path ) ),
	);
}

function mundosmart_dir_image_items( $relative, $limit = 3 ) {
	$dir   = get_stylesheet_directory() . '/assets/' . ltrim( (string) $relative, '/' );
	$items = array();
	if ( ! is_dir( $dir ) ) {
		return $items;
	}
	$files = array_merge(
		glob( $dir . '/*.jpg' ) ?: array(),
		glob( $dir . '/*.jpeg' ) ?: array(),
		glob( $dir . '/*.png' ) ?: array(),
		glob( $dir . '/*.webp' ) ?: array()
	);
	natcasesort( $files );
	foreach ( $files as $file ) {
		$base    = basename( $file );
		$items[] = array(
			'src'   => get_stylesheet_directory_uri() . '/assets/' . ltrim( (string) $relative, '/' ) . '/' . rawurlencode( $base ) . '?v=' . (int) filemtime( $file ),
			'url'   => '',
			'title' => mundosmart_hero_video_label( $base ),
		);
		if ( count( $items ) >= (int) $limit ) {
			break;
		}
	}
	return $items;
}

function mundosmart_dir_video_items( $relative, $limit = 3 ) {
	$dir   = get_stylesheet_directory() . '/assets/' . ltrim( (string) $relative, '/' );
	$items = array();
	if ( ! is_dir( $dir ) ) {
		return $items;
	}
	$files = array_merge(
		glob( $dir . '/*.mp4' ) ?: array(),
		glob( $dir . '/*.webm' ) ?: array(),
		glob( $dir . '/*.ogg' ) ?: array()
	);
	natcasesort( $files );
	foreach ( $files as $file ) {
		$ext  = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
		$mime = 'webm' === $ext ? 'video/webm' : ( 'ogg' === $ext ? 'video/ogg' : 'video/mp4' );
		$base = basename( $file );
		$items[] = array(
			'src'    => get_stylesheet_directory_uri() . '/assets/' . ltrim( (string) $relative, '/' ) . '/' . rawurlencode( $base ) . '?v=' . (int) filemtime( $file ),
			'title'  => mundosmart_hero_video_label( $base ),
			'mime'   => $mime,
			'poster' => mundosmart_local_video_poster( $file ),
		);
		if ( count( $items ) >= (int) $limit ) {
			break;
		}
	}
	return $items;
}

function mundosmart_item_src_key( $item ) {
	$src = isset( $item['src'] ) ? (string) $item['src'] : '';
	return preg_replace( '/\?.*$/', '', $src );
}

function mundosmart_unique_items( $items, $limit = 3, &$used = array() ) {
	$out   = array();
	$limit = (int) $limit;
	if ( $limit <= 0 ) {
		return $out;
	}
	foreach ( (array) $items as $item ) {
		if ( ! is_array( $item ) || empty( $item['src'] ) ) {
			continue;
		}
		$key = mundosmart_item_src_key( $item );
		if ( '' === $key || isset( $used[ $key ] ) ) {
			continue;
		}
		$used[ $key ] = true;
		$out[]        = $item;
		if ( count( $out ) >= $limit ) {
			break;
		}
	}
	return $out;
}

function mundosmart_items_matching( $items, $needles ) {
	$matched = array();
	$rest    = array();
	foreach ( (array) $items as $item ) {
		$title = isset( $item['title'] ) ? (string) $item['title'] : '';
		$hit   = false;
		foreach ( (array) $needles as $needle ) {
			if ( $needle && false !== stripos( $title, $needle ) ) {
				$hit = true;
				break;
			}
		}
		if ( $hit ) {
			$matched[] = $item;
		} else {
			$rest[] = $item;
		}
	}
	return array( $matched, $rest );
}

function mundosmart_rotator_nav_markup( $prev_label = 'Imagem anterior', $next_label = 'Próxima imagem' ) {
	return '<div class="ms-hero-rotator__nav">'
		. '<button type="button" class="ms-hero-rotator__btn" data-ms-rotator-prev aria-label="' . esc_attr( $prev_label ) . '">'
		. '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 5 8 12l7 7" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
		. '</button>'
		. '<button type="button" class="ms-hero-rotator__btn" data-ms-rotator-next aria-label="' . esc_attr( $next_label ) . '">'
		. '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5l7 7-7 7" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>'
		. '</button>'
		. '</div>';
}

function mundosmart_shot_play_hint() {
	return '<div class="ms-shot-rotator__hint" aria-hidden="true"><span>'
		. '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z" fill="currentColor"/></svg>'
		. '</span></div>';
}

function mundosmart_mini_rotator_markup( $items, $label, $interval = 5000, $slide_class = 'is-product', $play = '' ) {
	$items     = array_values( array_filter( (array) $items ) );
	$hover     = ( 'hover' === $play );
	$is_video  = false;
	foreach ( $items as $item ) {
		if ( ! empty( $item['mime'] ) && 0 === strpos( (string) $item['mime'], 'video/' ) ) {
			$is_video = true;
			break;
		}
	}
	ob_start();
	?>
	<article class="ms-shot">
		<div class="ms-shot-rotator<?php echo $items ? '' : ' is-empty'; ?>"<?php echo $items ? ' data-ms-rotator' : ''; ?><?php echo ( $hover && $items ) ? ' data-ms-play="hover"' : ''; ?><?php echo ( $items && $interval ) ? ' data-ms-interval="' . (int) $interval . '"' : ''; ?> aria-roledescription="carrossel"<?php echo $label ? ' aria-label="' . esc_attr( $label ) . '"' : ''; ?>>
			<?php if ( $items ) : ?>
				<?php foreach ( $items as $index => $item ) : ?>
				<?php
				$item_video = ! empty( $item['mime'] ) && 0 === strpos( (string) $item['mime'], 'video/' );
				$item_class = $item_video ? 'is-video' : ( $slide_class ? $slide_class : 'is-product' );
				if ( $item_video ) {
					$is_video = true;
				}
				?>
				<div class="ms-hero-rotator__slide<?php echo 0 === (int) $index ? ' is-active' : ''; ?> <?php echo esc_attr( $item_class ); ?>" data-ms-rotator-slide>
					<?php if ( ! empty( $item['mime'] ) && 0 === strpos( (string) $item['mime'], 'video/' ) ) : ?>
					<?php echo mundosmart_video_markup( $item, 1 === count( $items ) ); ?>
					<?php elseif ( ! empty( $item['url'] ) ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>">
						<img src="<?php echo esc_url( $item['src'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" width="400" height="400">
					</a>
					<?php else : ?>
					<img src="<?php echo esc_url( $item['src'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" width="400" height="400">
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
				<?php
				if ( count( $items ) > 1 ) {
					echo mundosmart_rotator_nav_markup( $is_video ? 'Vídeo anterior' : 'Imagem anterior', $is_video ? 'Próximo vídeo' : 'Próxima imagem' );
				}
				if ( $hover ) {
					echo mundosmart_shot_play_hint();
				}
				?>
			<?php else : ?>
				<p class="ms-shot-rotator__empty">Vazio</p>
			<?php endif; ?>
		</div>
		<?php if ( $label ) : ?>
		<p class="ms-shot__label"><?php echo esc_html( $label ); ?></p>
		<?php endif; ?>
	</article>
	<?php
	return ob_get_clean();
}

function mundosmart_items_with_url( $items, $url ) {
	$out = array();
	foreach ( (array) $items as $item ) {
		if ( ! is_array( $item ) || empty( $item['src'] ) ) {
			continue;
		}
		$item['url'] = $url;
		$out[]       = $item;
	}
	return $out;
}

function mundosmart_topic_images( $admin_key, $limit, $url, $args = array() ) {
	unset( $args );
	return mundosmart_items_with_url( mundosmart_media_list( $admin_key, 'media', (int) $limit ), $url );
}

function mundosmart_home_topic_rotators() {
	$assistencia     = home_url( '/assistencia-tecnica/' );
	$loja            = home_url( '/loja/' );
	$whatsapp_iphone = mundosmart_whatsapp_url( 'Olá! Quero comprar ou trocar um iPhone.' );

	$groups = array(
		array(
			'label' => 'Troca e venda de aparelhos',
			'items' => mundosmart_items_with_url( mundosmart_media_list( 'home_troca', 'media', 6 ), $whatsapp_iphone ),
		),
		array(
			'label' => 'Assistência técnica',
			'items' => mundosmart_items_with_url( mundosmart_media_list( 'home_assistencia_fotos', 'media', 6 ), $assistencia ),
		),
		array(
			'label' => 'Itens da loja',
			'items' => mundosmart_items_with_url( mundosmart_media_list( 'home_loja', 'media', 6 ), $loja ),
		),
	);

	$html = '';
	foreach ( $groups as $group ) {
		$html .= mundosmart_mini_rotator_markup( $group['items'], $group['label'], 3000, 'is-product' );
	}

	ob_start();
	?>
	<section class="ms-section ms-section--strip">
		<div class="ms-wrap">
			<h2>Troca, <span>conserto</span> e loja</h2>
			<!-- ms-media-strict -->
			<div class="ms-shot-grid ms-home-topics">
				<?php echo $html; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function mundosmart_library_video_items( $needles = array(), $limit = 12 ) {
	$items = array();
	$query = new WP_Query(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post_mime_type' => 'video',
			'posts_per_page' => 30,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);
	$needles = array_values( array_filter( array_map( 'strval', (array) $needles ) ) );
	foreach ( $query->posts as $post ) {
		$item = mundosmart_attachment_to_item( $post->ID, 'video' );
		if ( ! $item ) {
			continue;
		}
		if ( $needles ) {
			$hay = $item['title'] . ' ' . basename( (string) $item['src'] );
			$hit = false;
			foreach ( $needles as $needle ) {
				if ( $needle && false !== stripos( $hay, $needle ) ) {
					$hit = true;
					break;
				}
			}
			if ( ! $hit ) {
				continue;
			}
		}
		$item['poster'] = mundosmart_video_poster_url( (int) $post->ID );
		$items[]        = $item;
		if ( count( $items ) >= (int) $limit ) {
			break;
		}
	}
	return $items;
}

function mundosmart_fill_video_items( $items, $limit, $more = array(), &$used = null ) {
	if ( ! is_array( $used ) ) {
		$used = array();
	}
	$out = mundosmart_unique_items( $items, $limit, $used );
	foreach ( (array) $more as $list ) {
		if ( count( $out ) >= (int) $limit ) {
			break;
		}
		$out = array_merge( $out, mundosmart_unique_items( $list, (int) $limit - count( $out ), $used ) );
	}
	return $out;
}

function mundosmart_video_haystack( $item ) {
	$src = isset( $item['src'] ) ? (string) $item['src'] : '';
	return strtolower(
		( isset( $item['title'] ) ? (string) $item['title'] : '' ) . ' ' . basename( preg_replace( '/\?.*$/', '', $src ) )
	);
}

function mundosmart_video_matches_needles( $item, $needles ) {
	$hay = mundosmart_video_haystack( $item );
	foreach ( (array) $needles as $needle ) {
		$needle = strtolower( (string) $needle );
		if ( $needle && false !== strpos( $hay, $needle ) ) {
			return true;
		}
	}
	return false;
}

function mundosmart_videos_for_group( $items, $group, $groups, $limit, &$used ) {
	$own     = isset( $group['needles'] ) ? (array) $group['needles'] : array();
	$foreign = array();
	foreach ( (array) $groups as $other ) {
		if ( ( $other['key'] ?? '' ) === ( $group['key'] ?? '' ) ) {
			continue;
		}
		$foreign = array_merge( $foreign, isset( $other['needles'] ) ? (array) $other['needles'] : array() );
	}
	$preferred = array();
	$neutral   = array();
	foreach ( (array) $items as $item ) {
		if ( mundosmart_video_matches_needles( $item, $foreign ) && ! mundosmart_video_matches_needles( $item, $own ) ) {
			continue;
		}
		if ( mundosmart_video_matches_needles( $item, $own ) ) {
			$preferred[] = $item;
		} else {
			$neutral[] = $item;
		}
	}
	return mundosmart_fill_video_items( $preferred, $limit, array( $neutral ), $used );
}

function mundosmart_assistencia_shot_rotators() {
	$groups = array(
		array(
			'label' => 'Reparo em iPhone',
			'key'   => 'assistencia_iphone',
		),
		array(
			'label' => 'Reparo em Android',
			'key'   => 'assistencia_android',
		),
		array(
			'label' => 'Reparos avançados',
			'key'   => 'assistencia_avancados',
		),
	);

	$html = '';
	foreach ( $groups as $group ) {
		$html .= mundosmart_mini_rotator_markup( mundosmart_media_list( $group['key'], 'media', 3 ), $group['label'], 0, 'is-video', 'hover' );
	}
	ob_start();
	?>
	<section class="ms-section ms-section--shots">
		<div class="ms-wrap">
			<h2>O que passa pela <span>assistência técnica</span></h2>
			<p class="ms-section__intro">iPhone, Android e reparo avançado — até 3 vídeos em cada. Passe o mouse em cima para assistir.</p>
			<div class="ms-shot-grid">
				<?php echo $html; ?>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}

function mundosmart_hero_video_label( $filename ) {
	$label = preg_replace( '/\.[^.]+$/', '', (string) $filename );
	$label = str_replace( array( '-', '_' ), ' ', $label );
	$label = trim( $label );
	return $label ? ucwords( $label ) : 'Vídeo';
}

function mundosmart_hero_videos() {
	$videos = array();
	$dir    = get_stylesheet_directory() . '/assets/videos';
	if ( is_dir( $dir ) ) {
		$files = array_merge(
			glob( $dir . '/*.mp4' ) ?: array(),
			glob( $dir . '/*.webm' ) ?: array(),
			glob( $dir . '/*.ogg' ) ?: array()
		);
		sort( $files );
		foreach ( $files as $file ) {
			$base     = basename( $file );
			$ext      = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );
			$mime     = 'webm' === $ext ? 'video/webm' : ( 'ogg' === $ext ? 'video/ogg' : 'video/mp4' );
			$videos[] = array(
				'src'    => get_stylesheet_directory_uri() . '/assets/videos/' . rawurlencode( $base ),
				'title'  => mundosmart_hero_video_label( $base ),
				'mime'   => $mime,
				'poster' => mundosmart_local_video_poster( $file ),
			);
		}
	}

	return $videos;
}

function mundosmart_force_logo_png( $image, $attachment_id ) {
	if ( (int) $attachment_id !== 991 || ! is_array( $image ) ) {
		return $image;
	}
	$theme = mundosmart_theme_midia_url( 'logo-mundo-smart.png' );
	$image[0] = $theme ? $theme : mundosmart_fallback_upload_url( content_url( 'uploads/2026/09/logo-mundo-smart.png' ) );
	return $image;
}
add_filter( 'wp_get_attachment_image_src', 'mundosmart_force_logo_png', 20, 2 );

function mundosmart_render_float() {
	if ( is_admin() ) {
		return;
	}
	$message = is_page_template( 'page-assistencia.php' ) ? 'Olá! Quero consertar meu celular.' : 'Olá! Quero falar com a Mundo Smart.';
	$url     = mundosmart_whatsapp_url( $message );
	?>
	<a class="ms-float" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="Chamar no WhatsApp">
		<?php echo mundosmart_whatsapp_icon(); ?>
		<span>Orçamento agora</span>
	</a>
	<?php
}
add_action( 'wp_footer', 'mundosmart_render_float' );

function mundosmart_landing_markup( $context = 'home' ) {
	$is_home     = ( 'home' === $context );
	$loja        = home_url( '/loja/' );
	$assistencia = home_url( '/assistencia-tecnica/' );
	ob_start();

	if ( $is_home ) {
		$whatsapp        = mundosmart_whatsapp_url( 'Olá! Quero falar com a Mundo Smart.' );
		$whatsapp_iphone = mundosmart_whatsapp_url( 'Olá! Quero comprar ou trocar um iPhone.' );
		$fachada_item    = mundosmart_media_one( 'home_fachada', 'image' );
		$fachada         = $fachada_item ? $fachada_item['src'] : get_stylesheet_directory_uri() . '/assets/fachada.jpg?v=3';
		$brindes         = home_url( '/brindes/' );
		$hero_products = mundosmart_media_list( 'home_fotos', 'media', 5 );
		if ( $hero_products ) {
			foreach ( $hero_products as &$hero_item ) {
				if ( empty( $hero_item['url'] ) ) {
					$hero_item['url'] = $brindes;
				}
			}
			unset( $hero_item );
		}
		$hero_videos = mundosmart_media_list( 'home_videos', 'media', 3 );
		?>
	<main class="ms-landing ms-landing--home">
		<section class="ms-hero">
			<div class="ms-wrap ms-hero__grid">
				<div>
					<p class="ms-kicker">Mundo Smart · Mococa/SP</p>
					<h1>Loja de celulares com assistência técnica <span>especializada</span>.</h1>
					<p class="ms-lead">Venda e troca de iPhone, conserto e personalizados para presente e brinde — no centro de Mococa.</p>
					<div class="ms-hero__actions">
						<a class="ms-btn ms-btn--whatsapp ms-btn--lg" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener">
							<?php echo mundosmart_whatsapp_icon(); ?>
							WhatsApp
						</a>
						<a class="ms-btn ms-btn--blue ms-btn--lg" href="<?php echo esc_url( $assistencia ); ?>">Ver assistência</a>
						<a class="ms-btn ms-btn--ghost ms-btn--lg" href="<?php echo esc_url( $loja ); ?>">Ver a loja</a>
					</div>
				</div>
				<div class="ms-logo-panel ms-fachada ms-hero-rotator" data-ms-rotator data-ms-interval="5000" aria-roledescription="carrossel">
					<div class="ms-hero-rotator__slide is-active is-fachada" data-ms-rotator-slide data-ms-label="Fachada">
						<img src="<?php echo esc_url( $fachada ); ?>" alt="Fachada da Mundo Smart" width="800" height="600">
					</div>
					<?php foreach ( $hero_products as $item ) : ?>
					<?php if ( ! empty( $item['mime'] ) && 0 === strpos( (string) $item['mime'], 'video/' ) ) : ?>
					<div class="ms-hero-rotator__slide is-video" data-ms-rotator-slide data-ms-label="<?php echo esc_attr( 'Vídeo · ' . wp_html_excerpt( $item['title'], 32, '…' ) ); ?>">
						<?php echo mundosmart_video_markup( $item ); ?>
					</div>
					<?php else : ?>
					<div class="ms-hero-rotator__slide is-product" data-ms-rotator-slide data-ms-label="<?php echo esc_attr( wp_html_excerpt( $item['title'], 42, '…' ) ); ?>">
						<a href="<?php echo esc_url( $item['url'] ); ?>">
							<img src="<?php echo esc_url( $item['src'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" width="400" height="400">
						</a>
					</div>
					<?php endif; ?>
					<?php endforeach; ?>
					<?php foreach ( $hero_videos as $video ) : ?>
					<div class="ms-hero-rotator__slide is-video" data-ms-rotator-slide data-ms-label="<?php echo esc_attr( 'Vídeo · ' . wp_html_excerpt( $video['title'], 32, '…' ) ); ?>">
						<?php echo mundosmart_video_markup( $video ); ?>
					</div>
					<?php endforeach; ?>
					<div class="ms-hero-rotator__nav">
						<button type="button" class="ms-hero-rotator__btn" data-ms-rotator-prev aria-label="Imagem anterior">
							<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 5 8 12l7 7" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
						<button type="button" class="ms-hero-rotator__btn" data-ms-rotator-next aria-label="Próxima imagem">
							<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 5l7 7-7 7" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
					</div>
				</div>
			</div>
		</section>

		<section class="ms-section ms-section--tight">
			<div class="ms-wrap">
				<div class="ms-paths">
					<a class="ms-path ms-path--iphone" href="<?php echo esc_url( $whatsapp_iphone ); ?>" target="_blank" rel="noopener">
						<span class="ms-card__tag">iPhone</span>
						<h3>Comprar ou trocar</h3>
						<p>Venda e troca de iPhone.</p>
					</a>
					<a class="ms-path" href="<?php echo esc_url( $assistencia ); ?>">
						<span class="ms-card__tag">Consertar</span>
						<h3>Assistência técnica</h3>
						<p>Tela, bateria e reparo de celular.</p>
					</a>
					<a class="ms-path ms-path--shop" href="<?php echo esc_url( $brindes ); ?>">
						<span class="ms-card__tag">Loja</span>
						<h3>Presente e brinde</h3>
						<p>Copos térmicos, capinhas e chaveiros personalizáveis.</p>
					</a>
				</div>
			</div>
		</section>

		<section class="ms-wrap">
			<div class="ms-proof ms-proof--3">
				<article><b>Tela e bateria em até 2h</b><span>Quando a peça está em estoque.</span></article>
				<article><b>Garantia de 6 meses</b><span>No serviço de conserto.</span></article>
				<article><b>No centro de Mococa</b><span>Rua Quinze de Novembro, 398.</span></article>
			</div>
		</section>

		<?php echo mundosmart_reviews_markup( 6 ); ?>

		<?php echo mundosmart_home_topic_rotators(); ?>

		<section class="ms-section">
			<div class="ms-wrap">
				<div class="ms-cta">
					<div>
						<h2>Rua Quinze de Novembro, <span>398</span></h2>
						<p>No centro de Mococa, em frente à Lojas Cem. Mococa/SP, CEP 13730-020.</p>
					</div>
					<a class="ms-btn ms-btn--whatsapp ms-btn--lg" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener">
						<?php echo mundosmart_whatsapp_icon(); ?>
						Chamar no WhatsApp
					</a>
				</div>
			</div>
		</section>
	</main>
		<?php
		return ob_get_clean();
	}

	$whatsapp   = mundosmart_whatsapp_url( 'Olá! Quero consertar meu celular.' );
	$hero_item  = mundosmart_media_one( 'assistencia_hero', 'image' );
	$hero       = $hero_item ? $hero_item['src'] : get_stylesheet_directory_uri() . '/assets/assistencia-hero.jpg';
	?>
	<main class="ms-landing ms-landing--assistencia">
		<section class="ms-hero">
			<div class="ms-wrap ms-hero__grid">
				<div>
					<p class="ms-kicker">Mundo Smart · Centro de Mococa</p>
					<h1>Seu celular de volta em até 2 horas. <span>Especialista em iPhone.</span></h1>
					<p class="ms-lead">Desculpa o aviso em cima da hora. A gente sabe que ficar sem celular é complicado — por isso o estoque fica à pronta entrega, para você ter o aparelho de volta o quanto antes.</p>
					<div class="ms-hero__actions">
						<a class="ms-btn ms-btn--whatsapp ms-btn--lg" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener">
							<?php echo mundosmart_whatsapp_icon(); ?>
							Pedir orçamento no WhatsApp
						</a>
					</div>
				</div>
				<div class="ms-logo-panel">
					<img src="<?php echo esc_url( $hero ); ?>" alt="Especialista em iPhone na Mundo Smart, com troca de tela e bateria">
				</div>
			</div>
		</section>

		<section class="ms-wrap">
			<div class="ms-proof ms-proof--3">
				<article><b>Análise antes do serviço</b><span>Diagnóstico completo. Explicamos o que precisa e só executamos depois disso.</span></article>
				<article><b>Garantia estendida</b><span>Até 6 meses no conserto — prazo maior do que o da concorrência.</span></article>
				<article><b>No centro de Mococa</b><span>Rua Quinze de Novembro, 398, em frente à Lojas Cem.</span></article>
			</div>
		</section>

		<section class="ms-section">
			<div class="ms-wrap">
				<h2>O que <span>consertamos</span></h2>
				<p class="ms-section__intro">Android e iPhone.</p>
				<div class="ms-services">
					<article class="ms-card"><span class="ms-card__tag">Android</span><h3>Samsung e Motorola</h3><p>E demais linhas.</p></article>
					<article class="ms-card"><span class="ms-card__tag">Tela e energia</span><h3>Display, bateria e carga</h3><p>Tela quebrada, touch falhando, bateria fraca ou aparelho sem carregar. Em até 2 horas, com peça em estoque.</p></article>
					<article class="ms-card ms-card--apple"><span class="ms-card__tag">Linha Apple</span><h3>Face ID e câmera</h3><p>Não reconhece o rosto, câmera preta, desfoco ou que não abre.</p></article>
					<article class="ms-card ms-card--apple"><span class="ms-card__tag">Avançado</span><h3>Reparo em placas</h3><p>Não liga, curto ou dano por líquido. Diagnóstico e reparo avançado com garantia.</p></article>
					<article class="ms-card"><span class="ms-card__tag">Sistema</span><h3>Software</h3><p>Não inicia, travou ou precisa de ajuste no sistema.</p></article>
				</div>
			</div>
		</section>

		<?php echo mundosmart_assistencia_shot_rotators(); ?>

		<?php echo mundosmart_reviews_markup( 6, 'assistencia' ); ?>

		<section class="ms-section ms-section--tight">
			<div class="ms-wrap">
				<p class="ms-loja-note">No mesmo endereço, a <a href="<?php echo esc_url( $loja ); ?>">loja</a>: acessórios para celular, capinhas e caixinha de som.</p>
			</div>
		</section>

		<section class="ms-section">
			<div class="ms-wrap">
				<h2>Como <span>funciona</span></h2>
				<div class="ms-steps">
					<div class="ms-step"><b>1. WhatsApp</b><p>Envie o modelo e o que aconteceu. Foto ajuda a fechar o orçamento.</p></div>
					<div class="ms-step"><b>2. Análise na loja</b><p>Olhamos o aparelho por completo e explicamos o que precisa. Só executamos o serviço depois disso.</p></div>
					<div class="ms-step"><b>3. Celular de volta</b><p>Troca de tela e bateria em até 2 horas, quando a peça estiver em estoque. Demais reparos com prazo combinado.</p></div>
				</div>
			</div>
		</section>

		<section class="ms-section">
			<div class="ms-wrap">
				<div class="ms-cta">
					<div>
						<h2>No centro de <span>Mococa</span></h2>
						<p>Rua Quinze de Novembro, 398, em frente à Lojas Cem, Mococa/SP, CEP 13730-020.</p>
					</div>
					<a class="ms-btn ms-btn--whatsapp ms-btn--lg" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener">
						<?php echo mundosmart_whatsapp_icon(); ?>
						Chamar no WhatsApp
					</a>
				</div>
			</div>
		</section>
	</main>
	<?php
	return ob_get_clean();
}

function mundosmart_assistencia_markup() {
	return mundosmart_landing_markup( 'assistencia' );
}

function mundosmart_brindes_markup() {
	$whatsapp   = mundosmart_whatsapp_url( 'Olá! Quero personalizados para presente ou brinde.' );
	$loja       = home_url( '/loja/' );
	$hero_item  = mundosmart_media_one( 'brindes_hero', 'image' );
	$photos     = mundosmart_media_list( 'brindes_galeria', 'media', 6 );
	if ( $photos ) {
		foreach ( $photos as &$photo ) {
			$photo['url'] = $loja;
		}
		unset( $photo );
	}
	if ( $hero_item ) {
		$hero = $hero_item['src'];
	} else {
		$hero = $photos ? $photos[0]['src'] : get_stylesheet_directory_uri() . '/assets/brinde-hero.jpg';
	}
	ob_start();
	?>
	<main class="ms-landing ms-landing--brindes">
		<section class="ms-hero">
			<div class="ms-wrap ms-hero__grid">
				<div>
					<p class="ms-kicker">Presente e brinde · Centro de Mococa</p>
					<h1>Personalizados para presente e <span>brinde</span>.</h1>
					<p class="ms-lead">Copos térmicos, capinhas e chaveiros com nome, logo ou arte. Para empresa, time ou presente — na loja, no centro de Mococa.</p>
					<div class="ms-hero__actions">
						<a class="ms-btn ms-btn--whatsapp ms-btn--lg" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener">
							<?php echo mundosmart_whatsapp_icon(); ?>
							Pedir orçamento no WhatsApp
						</a>
						<a class="ms-btn ms-btn--ghost ms-btn--lg" href="<?php echo esc_url( $loja ); ?>">Ver a loja</a>
					</div>
				</div>
				<div class="ms-logo-panel ms-brinde-panel">
					<img src="<?php echo esc_url( $hero ); ?>" alt="Copo térmico personalizado Mundo Smart">
				</div>
			</div>
		</section>

		<section class="ms-wrap">
			<div class="ms-proof ms-proof--3">
				<article><b>Copos térmicos</b><span>Personalizáveis com nome, logo ou arte.</span></article>
				<article><b>Capinhas</b><span>A arte que você quiser, no modelo do aparelho.</span></article>
				<article><b>Chaveiros</b><span>Para presente, time ou brinde da empresa.</span></article>
			</div>
		</section>

		<section class="ms-section">
			<div class="ms-wrap">
				<h2>Como <span>pedimos</span></h2>
				<div class="ms-steps">
					<div class="ms-step"><b>1. WhatsApp</b><p>Mande a arte, o logo ou o que quer gravar. Diga a quantidade — um presente ou um lote.</p></div>
					<div class="ms-step"><b>2. Confirmação</b><p>Fechamos o modelo, o prazo e o valor antes de produzir.</p></div>
					<div class="ms-step"><b>3. Na loja</b><p>Retire no centro de Mococa, Rua Quinze de Novembro, 398, em frente à Lojas Cem.</p></div>
				</div>
			</div>
		</section>

		<?php if ( $photos ) : ?>
		<section class="ms-section ms-section--strip">
			<div class="ms-wrap">
				<p class="ms-strip__label">Alguns trabalhos</p>
				<div class="ms-loja-strip">
					<?php foreach ( $photos as $item ) : ?>
					<?php if ( ! empty( $item['mime'] ) && 0 === strpos( (string) $item['mime'], 'video/' ) ) : ?>
					<div class="ms-loja-strip__video">
						<?php echo mundosmart_video_markup( $item ); ?>
					</div>
					<?php else : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>">
						<img src="<?php echo esc_url( $item['src'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" width="400" height="400">
					</a>
					<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<section class="ms-section">
			<div class="ms-wrap">
				<div class="ms-cta">
					<div>
						<h2>Orçamento no <span>WhatsApp</span></h2>
						<p>Presente, time ou brinde da empresa. Copos térmicos, capinhas e chaveiros. Centro de Mococa.</p>
					</div>
					<a class="ms-btn ms-btn--whatsapp ms-btn--lg" href="<?php echo esc_url( $whatsapp ); ?>" target="_blank" rel="noopener">
						<?php echo mundosmart_whatsapp_icon(); ?>
						Chamar no WhatsApp
					</a>
				</div>
			</div>
		</section>
	</main>
	<?php
	return ob_get_clean();
}
