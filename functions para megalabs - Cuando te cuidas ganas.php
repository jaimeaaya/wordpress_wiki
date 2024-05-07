<?php
/**
 * Twenty Seventeen functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since Twenty Seventeen 1.0
 */

/**
 * Twenty Seventeen only works in WordPress 4.7 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
	return;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function twentyseventeen_setup() {

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enables custom line height for blocks
	 */
	add_theme_support( 'custom-line-height' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	add_image_size( 'twentyseventeen-featured-image', 2000, 1200, true );

	add_image_size( 'twentyseventeen-thumbnail-avatar', 100, 100, true );

	// Set the default content width.
	$GLOBALS['content_width'] = 525;

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus(
		array(
			'top'    => __( 'Top Menu', 'twentyseventeen' ),
			'social' => __( 'Social Links Menu', 'twentyseventeen' ),
		)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'script',
			'style',
			'navigation-widgets',
		)
	);

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://wordpress.org/documentation/article/post-formats/
	 */
	add_theme_support(
		'post-formats',
		array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio',
		)
	);

	// Add theme support for Custom Logo.
	add_theme_support(
		'custom-logo',
		array(
			'width'      => 250,
			'height'     => 250,
			'flex-width' => true,
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width. When fonts are
	 * self-hosted, the theme directory needs to be removed first.
	 */
	$font_stylesheet = str_replace(
		array( get_template_directory_uri() . '/', get_stylesheet_directory_uri() . '/' ),
		'',
		(string) twentyseventeen_fonts_url()
	);
	add_editor_style( array( 'assets/css/editor-style.css', $font_stylesheet ) );

	// Load regular editor styles into the new block-based editor.
	add_theme_support( 'editor-styles' );

	// Load default block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for responsive embeds.
	add_theme_support( 'responsive-embeds' );

	// Define and register starter content to showcase the theme on new sites.
	$starter_content = array(
		'widgets'     => array(
			// Place three core-defined widgets in the sidebar area.
			'sidebar-1' => array(
				'text_business_info',
				'search',
				'text_about',
			),

			// Add the core-defined business info widget to the footer 1 area.
			'sidebar-2' => array(
				'text_business_info',
			),

			// Put two core-defined widgets in the footer 2 area.
			'sidebar-3' => array(
				'text_about',
				'search',
			),
		),

		// Specify the core-defined pages to create and add custom thumbnails to some of them.
		'posts'       => array(
			'home',
			'about'            => array(
				'thumbnail' => '{{image-sandwich}}',
			),
			'contact'          => array(
				'thumbnail' => '{{image-espresso}}',
			),
			'blog'             => array(
				'thumbnail' => '{{image-coffee}}',
			),
			'homepage-section' => array(
				'thumbnail' => '{{image-espresso}}',
			),
		),

		// Create the custom image attachments used as post thumbnails for pages.
		'attachments' => array(
			'image-espresso' => array(
				'post_title' => _x( 'Espresso', 'Theme starter content', 'twentyseventeen' ),
				'file'       => 'assets/images/espresso.jpg', // URL relative to the template directory.
			),
			'image-sandwich' => array(
				'post_title' => _x( 'Sandwich', 'Theme starter content', 'twentyseventeen' ),
				'file'       => 'assets/images/sandwich.jpg',
			),
			'image-coffee'   => array(
				'post_title' => _x( 'Coffee', 'Theme starter content', 'twentyseventeen' ),
				'file'       => 'assets/images/coffee.jpg',
			),
		),

		// Default to a static front page and assign the front and posts pages.
		'options'     => array(
			'show_on_front'  => 'page',
			'page_on_front'  => '{{home}}',
			'page_for_posts' => '{{blog}}',
		),

		// Set the front page section theme mods to the IDs of the core-registered pages.
		'theme_mods'  => array(
			'panel_1' => '{{homepage-section}}',
			'panel_2' => '{{about}}',
			'panel_3' => '{{blog}}',
			'panel_4' => '{{contact}}',
		),

		// Set up nav menus for each of the two areas registered in the theme.
		'nav_menus'   => array(
			// Assign a menu to the "top" location.
			'top'    => array(
				'name'  => __( 'Top Menu', 'twentyseventeen' ),
				'items' => array(
					'link_home', // Note that the core "home" page is actually a link in case a static front page is not used.
					'page_about',
					'page_blog',
					'page_contact',
				),
			),

			// Assign a menu to the "social" location.
			'social' => array(
				'name'  => __( 'Social Links Menu', 'twentyseventeen' ),
				'items' => array(
					'link_yelp',
					'link_facebook',
					'link_twitter',
					'link_instagram',
					'link_email',
				),
			),
		),
	);

	/**
	 * Filters Twenty Seventeen array of starter content.
	 *
	 * @since Twenty Seventeen 1.1
	 *
	 * @param array $starter_content Array of starter content.
	 */
	$starter_content = apply_filters( 'twentyseventeen_starter_content', $starter_content );

	add_theme_support( 'starter-content', $starter_content );
}
add_action( 'after_setup_theme', 'twentyseventeen_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function twentyseventeen_content_width() {

	$content_width = $GLOBALS['content_width'];

	// Get layout.
	$page_layout = get_theme_mod( 'page_layout' );

	// Check if layout is one column.
	if ( 'one-column' === $page_layout ) {
		if ( twentyseventeen_is_frontpage() ) {
			$content_width = 644;
		} elseif ( is_page() ) {
			$content_width = 740;
		}
	}

	// Check if is single post and there is no sidebar.
	if ( is_single() && ! is_active_sidebar( 'sidebar-1' ) ) {
		$content_width = 740;
	}

	/**
	 * Filters Twenty Seventeen content width of the theme.
	 *
	 * @since Twenty Seventeen 1.0
	 *
	 * @param int $content_width Content width in pixels.
	 */
	$GLOBALS['content_width'] = apply_filters( 'twentyseventeen_content_width', $content_width );
}
add_action( 'template_redirect', 'twentyseventeen_content_width', 0 );

if ( ! function_exists( 'twentyseventeen_fonts_url' ) ) :
	/**
	 * Register custom fonts.
	 *
	 * @since Twenty Seventeen 1.0
	 * @since Twenty Seventeen 3.2 Replaced Google URL with self-hosted fonts.
	 *
	 * @return string Fonts URL for the theme.
	 */
	function twentyseventeen_fonts_url() {
		$fonts_url = '';

		/*
		 * translators: If there are characters in your language that are not supported
		 * by Libre Franklin, translate this to 'off'. Do not translate into your own language.
		 */
		$libre_franklin = _x( 'on', 'Libre Franklin font: on or off', 'twentyseventeen' );

		if ( 'off' !== $libre_franklin ) {
			$fonts_url = get_template_directory_uri() . '/assets/fonts/font-libre-franklin.css';
		}

		return esc_url_raw( $fonts_url );
	}
endif;

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 * @deprecated Twenty Seventeen 3.2 Disabled filter because, by default, fonts are self-hosted.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed.
 * @return array URLs to print for resource hints.
 */
function twentyseventeen_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'twentyseventeen-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
// add_filter( 'wp_resource_hints', 'twentyseventeen_resource_hints', 10, 2 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function twentyseventeen_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Blog Sidebar', 'twentyseventeen' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your sidebar on blog posts and archive pages.', 'twentyseventeen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 1', 'twentyseventeen' ),
			'id'            => 'sidebar-2',
			'description'   => __( 'Add widgets here to appear in your footer.', 'twentyseventeen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Footer 2', 'twentyseventeen' ),
			'id'            => 'sidebar-3',
			'description'   => __( 'Add widgets here to appear in your footer.', 'twentyseventeen' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'twentyseventeen_widgets_init' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ... and
 * a 'Continue reading' link.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $link Link to single post/page.
 * @return string 'Continue reading' link prepended with an ellipsis.
 */
function twentyseventeen_excerpt_more( $link ) {
	if ( is_admin() ) {
		return $link;
	}

	$link = sprintf(
		'<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url( get_permalink( get_the_ID() ) ),
		/* translators: %s: Post title. Only visible to screen readers. */
		sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ), get_the_title( get_the_ID() ) )
	);
	return ' &hellip; ' . $link;
}
add_filter( 'excerpt_more', 'twentyseventeen_excerpt_more' );

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Seventeen 1.0
 */
function twentyseventeen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentyseventeen_javascript_detection', 0 );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function twentyseventeen_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'twentyseventeen_pingback_header' );

/**
 * Display custom color CSS.
 */
function twentyseventeen_colors_css_wrap() {
	if ( 'custom' !== get_theme_mod( 'colorscheme' ) && ! is_customize_preview() ) {
		return;
	}

	require_once get_parent_theme_file_path( '/inc/color-patterns.php' );
	$hue = absint( get_theme_mod( 'colorscheme_hue', 250 ) );

	$customize_preview_data_hue = '';
	if ( is_customize_preview() ) {
		$customize_preview_data_hue = 'data-hue="' . $hue . '"';
	}
	?>
	<style type="text/css" id="custom-theme-colors" <?php echo $customize_preview_data_hue; ?>>
		<?php echo twentyseventeen_custom_colors_css(); ?>
	</style>
	<?php
}
add_action( 'wp_head', 'twentyseventeen_colors_css_wrap' );

/**
 * Enqueues scripts and styles.
 */
function twentyseventeen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	$font_version = ( 0 === strpos( (string) twentyseventeen_fonts_url(), get_template_directory_uri() . '/' ) ) ? '20230328' : null;
	wp_enqueue_style( 'twentyseventeen-fonts', twentyseventeen_fonts_url(), array(), $font_version );

	// Theme stylesheet.
	wp_enqueue_style( 'twentyseventeen-style', get_stylesheet_uri(), array(), '20240402' );

	// Theme block stylesheet.
	wp_enqueue_style( 'twentyseventeen-block-style', get_theme_file_uri( '/assets/css/blocks.css' ), array( 'twentyseventeen-style' ), '20220912' );

	// Load the dark colorscheme.
	if ( 'dark' === get_theme_mod( 'colorscheme', 'light' ) || is_customize_preview() ) {
		wp_enqueue_style( 'twentyseventeen-colors-dark', get_theme_file_uri( '/assets/css/colors-dark.css' ), array( 'twentyseventeen-style' ), '20191025' );
	}

	// Register the Internet Explorer 9 specific stylesheet, to fix display issues in the Customizer.
	if ( is_customize_preview() ) {
		wp_register_style( 'twentyseventeen-ie9', get_theme_file_uri( '/assets/css/ie9.css' ), array( 'twentyseventeen-style' ), '20161202' );
		wp_style_add_data( 'twentyseventeen-ie9', 'conditional', 'IE 9' );
	}

	// Register the Internet Explorer 8 specific stylesheet.
	wp_register_style( 'twentyseventeen-ie8', get_theme_file_uri( '/assets/css/ie8.css' ), array( 'twentyseventeen-style' ), '20161202' );
	wp_style_add_data( 'twentyseventeen-ie8', 'conditional', 'lt IE 9' );

	// Register the html5 shiv.
	wp_register_script( 'html5', get_theme_file_uri( '/assets/js/html5.js' ), array(), '20161020' );
	wp_script_add_data( 'html5', 'conditional', 'lt IE 9' );

	// Skip-link fix is no longer enqueued by default.
	wp_register_script( 'twentyseventeen-skip-link-focus-fix', get_theme_file_uri( '/assets/js/skip-link-focus-fix.js' ), array(), '20161114', array( 'in_footer' => true ) );

	wp_enqueue_script(
		'twentyseventeen-global',
		get_theme_file_uri( '/assets/js/global.js' ),
		array( 'jquery' ),
		'20211130',
		array(
			'in_footer' => false, // Because involves header.
			'strategy'  => 'defer',
		)
	);

	$twentyseventeen_l10n = array(
		'quote' => twentyseventeen_get_svg( array( 'icon' => 'quote-right' ) ),
	);

	if ( has_nav_menu( 'top' ) ) {
		wp_enqueue_script(
			'twentyseventeen-navigation',
			get_theme_file_uri( '/assets/js/navigation.js' ),
			array( 'jquery' ),
			'20210122',
			array(
				'in_footer' => false, // Because involves header.
				'strategy'  => 'defer',
			)
		);
		$twentyseventeen_l10n['expand']   = __( 'Expand child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['collapse'] = __( 'Collapse child menu', 'twentyseventeen' );
		$twentyseventeen_l10n['icon']     = twentyseventeen_get_svg(
			array(
				'icon'     => 'angle-down',
				'fallback' => true,
			)
		);
	}

	wp_localize_script( 'twentyseventeen-global', 'twentyseventeenScreenReaderText', $twentyseventeen_l10n );

	wp_enqueue_script(
		'jquery-scrollto',
		get_theme_file_uri( '/assets/js/jquery.scrollTo.js' ),
		array( 'jquery' ),
		'2.1.3',
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'twentyseventeen_scripts' );

/**
 * Enqueues styles for the block-based editor.
 *
 * @since Twenty Seventeen 1.8
 */
function twentyseventeen_block_editor_styles() {
	// Block styles.
	wp_enqueue_style( 'twentyseventeen-block-editor-style', get_theme_file_uri( '/assets/css/editor-blocks.css' ), array(), '20230614' );
	// Add custom fonts.
	$font_version = ( 0 === strpos( (string) twentyseventeen_fonts_url(), get_template_directory_uri() . '/' ) ) ? '20230328' : null;
	wp_enqueue_style( 'twentyseventeen-fonts', twentyseventeen_fonts_url(), array(), $font_version );
}
add_action( 'enqueue_block_editor_assets', 'twentyseventeen_block_editor_styles' );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentyseventeen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	if ( 740 <= $width ) {
		$sizes = '(max-width: 706px) 89vw, (max-width: 767px) 82vw, 740px';
	}

	if ( is_active_sidebar( 'sidebar-1' ) || is_archive() || is_search() || is_home() || is_page() ) {
		if ( ! ( is_page() && 'one-column' === get_theme_mod( 'page_options' ) ) && 767 <= $width ) {
			$sizes = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
		}
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentyseventeen_content_image_sizes_attr', 10, 2 );

/**
 * Filters the `sizes` value in the header image markup.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $html   The HTML image tag markup being filtered.
 * @param object $header The custom header object returned by 'get_custom_header()'.
 * @param array  $attr   Array of the attributes for the image tag.
 * @return string The filtered header image HTML.
 */
function twentyseventeen_header_image_tag( $html, $header, $attr ) {
	if ( isset( $attr['sizes'] ) ) {
		$html = str_replace( $attr['sizes'], '100vw', $html );
	}
	return $html;
}
add_filter( 'get_header_image_tag', 'twentyseventeen_header_image_tag', 10, 3 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string[]     $attr       Array of attribute values for the image markup, keyed by attribute name.
 *                                 See wp_get_attachment_image().
 * @param WP_Post      $attachment Image attachment post.
 * @param string|int[] $size       Requested image size. Can be any registered image size name, or
 *                                 an array of width and height values in pixels (in that order).
 * @return string[] The filtered attributes for the image markup.
 */
function twentyseventeen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( is_archive() || is_search() || is_home() ) {
		$attr['sizes'] = '(max-width: 767px) 89vw, (max-width: 1000px) 54vw, (max-width: 1071px) 543px, 580px';
	} else {
		$attr['sizes'] = '100vw';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentyseventeen_post_thumbnail_sizes_attr', 10, 3 );

/**
 * Use front-page.php when Front page displays is set to a static page.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param string $template front-page.php.
 * @return string The template to be used: blank if is_home() is true (defaults to index.php),
 *                otherwise $template.
 */
function twentyseventeen_front_page_template( $template ) {
	return is_home() ? '' : $template;
}
add_filter( 'frontpage_template', 'twentyseventeen_front_page_template' );

/**
 * Modifies tag cloud widget arguments to display all tags in the same font size
 * and use list format for better accessibility.
 *
 * @since Twenty Seventeen 1.4
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array The filtered arguments for tag cloud widget.
 */
function twentyseventeen_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list';

	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentyseventeen_widget_tag_cloud_args' );

/**
 * Gets unique ID.
 *
 * This is a PHP implementation of Underscore's uniqueId method. A static variable
 * contains an integer that is incremented with each call. This number is returned
 * with the optional prefix. As such the returned value is not universally unique,
 * but it is unique across the life of the PHP process.
 *
 * @since Twenty Seventeen 2.0
 *
 * @see wp_unique_id() Themes requiring WordPress 5.0.3 and greater should use this instead.
 *
 * @param string $prefix Prefix for the returned ID.
 * @return string Unique ID.
 */
function twentyseventeen_unique_id( $prefix = '' ) {
	static $id_counter = 0;
	if ( function_exists( 'wp_unique_id' ) ) {
		return wp_unique_id( $prefix );
	}
	return $prefix . (string) ++$id_counter;
}

if ( ! function_exists( 'wp_get_list_item_separator' ) ) :
	/**
	 * Retrieves the list item separator based on the locale.
	 *
	 * Added for backward compatibility to support pre-6.0.0 WordPress versions.
	 *
	 * @since 6.0.0
	 */
	function wp_get_list_item_separator() {
		/* translators: Used between list items, there is a space after the comma. */
		return __( ', ', 'twentyseventeen' );
	}
endif;

/**
 * Implement the Custom Header feature.
 */
require get_parent_theme_file_path( '/inc/custom-header.php' );

/**
 * Custom template tags for this theme.
 */
require get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Additional features to allow styling of the templates.
 */
require get_parent_theme_file_path( '/inc/template-functions.php' );

/**
 * Customizer additions.
 */
require get_parent_theme_file_path( '/inc/customizer.php' );

/**
 * SVG icons functions and filters.
 */
require get_parent_theme_file_path( '/inc/icon-functions.php' );

/**
 * Block Patterns.
 */
require get_template_directory() . '/inc/block-patterns.php';

/* custom JAYA */
// Función para cargar las tiendas
function cargar_tiendas() {
    global $wpdb;

    $idciudad = $_POST['idciudad']; // ID de la ciudad seleccionada

    // Consulta para obtener las tiendas según la ciudad seleccionada
    $tiendas = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT  * FROM wp_cs_tiendas where id in (select id_tienda from wp_cs_puntos_venta where id_ciudad = %d) ",
            $idciudad
        )
    );

    // Devolver las tiendas en formato JSON
    echo json_encode($tiendas);

    wp_die(); // Terminar el script
}
add_action('wp_ajax_cargar_tiendas', 'cargar_tiendas');
add_action('wp_ajax_nopriv_cargar_tiendas', 'cargar_tiendas'); 

// Función para cargar los puntos de venta
function cargar_puntos() {
    global $wpdb;

    $idciudad = $_POST['idciudad']; // ID de la ciudad seleccionado
	$idtienda = $_POST['idtienda']; // ID de la tienda seleccionado

    // Consulta para obtener los puntos según el departamento seleccionado
    $puntos = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT  * FROM wp_cs_puntos_venta where id_ciudad = %d AND id_tienda = %s ; ",
            $idciudad, $idtienda
        )
    );

    // Devolver las ciudades en formato JSON
    echo json_encode($puntos);

    wp_die(); // Terminar el script
}
add_action('wp_ajax_cargar_puntos', 'cargar_puntos');
add_action('wp_ajax_nopriv_cargar_puntos', 'cargar_puntos'); 

function agregar_scripts_personalizados() {

	if ( is_user_logged_in() ) {
		// El usuario está logeado
		?>
		<script>
			console.log('usuario loggeado');
			
			jQuery(document).ready(function($) {
				// no mostrar el formulario de login
				$('#inicia-sesion-home').css('display','none');
				$('#contenido-privado-home').css('display','block');
								
			});
		</script>
		<?php	

	} else {
		// El usuario no está logeado
		?>
		<script>
			console.log('usuario no loggeado');

			jQuery(document).ready(function($) {
				// no mostrar el formulario de login
				$('#inicia-sesion-home').css('display','block');
				$('#contenido-privado-home').css('display','none');
				
			});
		</script>
		<?php
	}

	if (is_page('registro-facturas')) {
		/*
		if ( current_user_can( 'manage_options' ) ) {
			// El usuario es administrador
			?>
			<script>
				console.log('El usuario es administrador');
				
			</script>
			<?php
		} else {
			// El usuario no es administrador
			?>
			<script>
				console.log('El usuario no es administrador');
				
			</script>
			<?php
		}
		*/
		if ( is_user_logged_in() ) {
			// El usuario está logeado
			$user_id = get_current_user_id();
			$completado_autenticacion_doble = get_user_meta($user_id, 'completado_autenticacion_doble', true);

			if($completado_autenticacion_doble == 1){

				

				// El usuario está logeado
				global $wpdb; // Acceso a la base de datos de WordPress

				// Consulta para obtener las ciudades
				$ciudades = $wpdb->get_results("SELECT id, ciudad FROM wp_cs_ciudades");
				$options = '<option value="">Selecciona tu ciudad</option>';
				// Comprueba si hay resultados
				if ($ciudades) {
					foreach ($ciudades as $ciudad) {
						$options .= '<option value="' . $ciudad->id . '">' . $ciudad->ciudad . '</option>';
					}
				}
				/* 
				// Consulta para obtener las tiendas
				$tiendas = $wpdb->get_results("SELECT id, nombre FROM wp_cs_tiendas");
				$options_t == '<option value="">Selecciona tu tienda</option>';
				// Comprueba si hay resultados
				if ($tiendas) {
					foreach ($tiendas as $tienda) {
						$toptions_t .= '<option value="' . $tienda->id . '">' . $tienda->nombre . '</option>';
					}
				} 
				*/

				$user_id = get_current_user_id();

				// Consulta facturas por usuario
				$cuentas = $wpdb->get_results("SELECT count(entry_id) contador FROM cuandotecuidasganas.wp_evf_entries where user_id = '".$user_id."';");
				$totalfacturas = 0;
				
				// Comprueba si hay resultados
				if ($cuentas) {
					foreach ($cuentas as $cuenta) {
						$totalfacturas =  $cuenta->contador;
					}
				} 
				?>
				<script>
					
					jQuery(document).ready(function($) {
						
						var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

						///cuenta-facturas
						$('#cuenta-facturas').html('<h1 style="font-size:40px;"><strong><?php echo $totalfacturas;?> Facturas</strong><br/>registradas</h1>');
						
						//trae las ciudades
						$('#evf-218-field_eogdCuANvB-1').html('<?php echo $options;?>');
						
						//trae las tiendas
						//$('#evf-218-field_Ts4CAH6xYN-2').html('<?php //echo $toptions_t;?>');
						$('#evf-218-field_eogdCuANvB-1').change(function() {
							var ciudad_id = $(this).val();
							$('#evf-218-field_kujkqA7juK-11').val($('#evf-218-field_eogdCuANvB-1 option:selected').text());

							// Realizar la petición AJAX para traer las tiendas
							$.ajax({
								url: ajaxurl,
								type: 'POST',
								data: {
									action: 'cargar_tiendas',
									idciudad: ciudad_id
								},
								success: function(response) {
									var tiendas = JSON.parse(response);

									// Limpiar el selector de tiendas
									$('#evf-218-field_Ts4CAH6xYN-2').empty();
									$('#evf-218-field_Ts4CAH6xYN-2').append($('<option>', {
										value: '',
										text: 'Selecciona tu tienda'
									}));
									// Llenar el selector de tiendas con las tiendas obtenidas
									$.each(tiendas, function(index, tienda) {
										$('#evf-218-field_Ts4CAH6xYN-2').append($('<option>', {
											value: tienda.id,
											text: tienda.nombre
										}));
									});
								}
							});
						});

						//trae los puntos de venta 
						$('#evf-218-field_Ts4CAH6xYN-2').change(function() {
							var id_tienda = $(this).val();
							var id_ciudad = $('#evf-218-field_eogdCuANvB-1').val();
							$('#evf-218-field_GAXXndBXmZ-12').val($('#evf-218-field_Ts4CAH6xYN-2 option:selected').text());

							// Realizar la petición AJAX para traer los puntos
							$.ajax({
								url: ajaxurl,
								type: 'POST',
								data: {
									action: 'cargar_puntos',
									idciudad: id_ciudad,
									idtienda: id_tienda
								},
								success: function(response) {
									var tiendas = JSON.parse(response);

									// Limpiar el selector de tiendas
									$('#evf-218-field_G5WrUsrR3i-3').empty();
									$('#evf-218-field_G5WrUsrR3i-3').append($('<option>', {
										value: '',
										text: 'Selecciona tu punto de venta'
									}));	
									// Llenar el selector de tiendas con las tiendas obtenidas
									$.each(tiendas, function(index, tienda) {
										$('#evf-218-field_G5WrUsrR3i-3').append($('<option>', {
											value: tienda.id,
											text: tienda.punto
										}));
									});
								}
							});
						});

						$('#evf-218-field_G5WrUsrR3i-3').change(function() {
							$('#evf-218-field_G8ADW63ljH-13').val($('#evf-218-field_G5WrUsrR3i-3 option:selected').text());
						});					
					});
				</script>
				<?php
			}else{
				wp_logout();
				// Redirigir a la página de inicio o a cualquier otra página deseada después de cerrar sesión
				wp_redirect(home_url());
				exit();
			}	
		} else {
			// El usuario no está logeado
			wp_redirect(home_url());
		}

	} //fin is page 'registro-facturas'

	if (is_page('login-val')) {
		/*
		if ( current_user_can( 'manage_options' ) ) {
			// El usuario es administrador
			?>
			<script>
				console.log('El usuario es administrador');
				
			</script>
			<?php
		} else {
			// El usuario no es administrador
			?>
			<script>
				console.log('El usuario no es administrador');
				
			</script>
			<?php
		}
		*/			
		if ( is_user_logged_in() ) {
			// El usuario está logeado
			$user_id = get_current_user_id();
			$current_user = wp_get_current_user();
			$email_current = $current_user->user_email;

			$codigo_guardado = get_user_meta($user_id, 'autenticacion_codigo', true); 
			$completado_autenticacion_doble = get_user_meta($user_id, 'completado_autenticacion_doble', true);

			?>
			<script>
				//console.log("<?php echo 'completado_autenticacion_doble: '.$completado_autenticacion_doble; ?>");
				//console.log("<?php echo 'codigo_guardado: '.$codigo_guardado; ?>");
				
				jQuery(document).ready(function($) {
					console.log('valida y muestra el correo encriptado');
					var comprobacion = "<?php echo $valido; ?>";
					var correoOriginal = "<?php echo $email_current; ?>";

					// Separar la parte del nombre de usuario y el dominio
					var partes = correoOriginal.split("@");
					var nombreUsuario = partes[0];
					var dominio = partes[1];

					// Camuflar el nombre de usuario
					var nombreUsuarioCamuflado = nombreUsuario.charAt(0) + "*".repeat(nombreUsuario.length - 2) + nombreUsuario.charAt(nombreUsuario.length - 1);

					// Camuflar el dominio
					var dominioCamuflado = dominio.charAt(0) + "****.***";

					// Mostrar el correo electrónico camuflado en la página
					$("#correoencriptado").text(nombreUsuarioCamuflado + "@" + dominioCamuflado);
									
				});
			</script>
			<?php

			$valido = 0;
			if (isset($_POST['auth_code']) ) { //&& $completado_autenticacion_doble == 0
				
				$codigo_ingresado = $_POST['auth_code'];

				?>
				<script>
					//console.log('entra a la validacion de codigo');
					//console.log("<?php echo 'codigo_ingresado: '.$codigo_ingresado; ?>");
					//console.log("<?php echo 'codigo_guardado: '.$codigo_guardado; ?>");
				</script>
				<?php
				
				if ($codigo_guardado && $codigo_ingresado == $codigo_guardado) {
					// Código correcto, permitir el inicio de sesión
					update_user_meta($user_id, 'completado_autenticacion_doble', 1);
					wp_redirect('/registro-facturas');
				} else {
					// Código incorrecto, mostrar un mensaje de error
					$respuesta = 'El código de autenticación es incorrecto';
					
					?>
					<script>						
						jQuery(document).ready(function($) {
							var loginurl = '/login';
							//console.log('codigo no valido');
							$("#preformulario").html("<?php echo $respuesta; ?>");
							$("#validar_codigo").html('<center>¿No recibiste el código? <a href="<?php echo wp_logout_url(  home_url() ); ?>"  style="color: var(--e-global-color-primary);font-weight: 900;">Reenviar código</a></center>');
																		
						}); 

					</script>
					<?php
				}
			}else{
				?>
			<script>
				jQuery(document).ready(function($) {

					var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

					$('#reenviar').on('click', function(e) {
						e.preventDefault();
				
						// Realizar una solicitud AJAX para cerrar la sesión
						$.ajax({
							url: ajaxurl, // Utiliza la variable global ajaxurl de WordPress
							type: 'POST',
							data: {
								action: 'cerrar_sesion_ajax' // Nombre de la acción que manejará la solicitud
							},
							success: function(response) {
								// Redireccionar a una página de confirmación u otra acción después de cerrar sesión
								//window.location.href = '<?php //echo wp_logout_url(home_url()); ?>';
								if (response === 'success') {
									// Redirigir o realizar otras acciones después de cerrar sesión
									location.reload(); // Recargar la página después de cerrar sesión
								}
							},
							error: function(error) {
								console.log(error);
								// Manejar errores si es necesario
							}
						});
					});
				});
			</script>
			<?php
			}			
			
		} else {
			// El usuario no está logeado
			wp_redirect(home_url());
		} // fin si está logeado	
		

	} //fin is page 'login-val'

}
add_action('wp_footer', 'agregar_scripts_personalizados');
add_action( 'um_registration_complete', 'my_registration_complete', 10, 2 );
function my_registration_complete( $user_id, $args ) {
	$user = get_userdata($user_id);

    $email = $user->user_email;
	$codigo = '7898';//generar_codigo_autenticacion(); 
	update_user_meta($user_id, 'autenticacion_codigo', $codigo); 
	update_user_meta($user_id, 'completado_autenticacion_doble', 1);

}

add_action( 'um_on_login_before_redirect', 'my_on_login_before_redirect', 10, 1 );
function my_on_login_before_redirect( $user_id ) {

	$user = get_userdata($user_id);

    $email = $user->user_email;
	$codigo = generar_codigo_autenticacion(); 
	update_user_meta($user_id, 'autenticacion_codigo', $codigo); 
	update_user_meta($user_id, 'completado_autenticacion_doble', 0);

 	$headers[] = 'Content-Type: text/html; charset=UTF-8';
 	$asunto = 'Código para iniciar sesión en Cuando Te Cuidas Ganas';
    // Cargar la plantilla de correo
    $template = file_get_contents('https://cuandotecuidasganas.com/wp-content/themes/twentyseventeen/template-mail-nuevo.html');

    // Reemplazar el código en la plantilla
    $template = str_replace('{{codigo}}', $codigo, $template);
	//wp_mail($email, $asunto, $template, $headers);

	
	// Requerir la biblioteca de SendGrid
	require_once 'vendor/autoload.php';
	$enviado = enviar_correo_desde_sendgrid($email, $asunto, $template);

	


	$pagina_redireccion = '/login-val'; 
    // Redirigir a la URL con el ID de usuario como parámetro
    wp_redirect($pagina_redireccion);
    exit();
}


// Función para generar un código aleatorio
function generar_codigo_autenticacion() {
    return substr(str_shuffle("0123456789"), 0, 4); // Generar un código de 4 dígitos
}

// Manejador de AJAX para cerrar sesión
add_action('wp_ajax_cerrar_sesion_ajax', 'cerrar_sesion_ajax_callback');
add_action('wp_ajax_nopriv_cerrar_sesion_ajax', 'cerrar_sesion_ajax_callback'); // Para usuarios no autenticados

function cerrar_sesion_ajax_callback() {
    wp_logout(); // Cierra la sesión del usuario actual
	echo 'success';
    wp_die(); // Termina la ejecución y devuelve una respuesta
}


// Función para enviar correo electrónico usando SendGrid
function enviar_correo_desde_sendgrid($destinatario, $asunto, $cuerpo) {
    $clave = 'AQUIVALAAPIK'; //cuenta mpolo paga
    $email = new \SendGrid\Mail\Mail();
    $email->setFrom("info@cuandotecuidasganas.com", "Megalabs");
    $email->setSubject($asunto);
    $email->addTo($destinatario);
    $email->addContent("text/html", $cuerpo);

    $sendgrid = new \SendGrid($clave);

    try {
        $response = $sendgrid->send($email);
        if ($response->statusCode() === 202) {
            return true; // Envío exitoso
        } else {
            error_log("Error al enviar el correo: " . $response->body());
            return false;
        }
    } catch (Exception $e) {
        error_log("Error al enviar el correo: " . $e->getMessage());
        return false;
    }
}
