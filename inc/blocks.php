<?php
/**
 * Duck Diver Framework block registration and child extension loading.
 *
 * @package Duck Diver Framework
 */

if ( ! function_exists( 'dd_is_production_environment' ) ) {
	/**
	 * Determines whether the current environment should prefer minified assets.
	 *
	 * @return bool
	 */
	function dd_is_production_environment() {
		return 'production' === wp_get_environment_type();
	}
}

if ( ! function_exists( 'dd_resolve_theme_asset' ) ) {
	/**
	 * Resolves a theme asset, preferring a minified file in production when available.
	 *
	 * @param string $base_dir Absolute directory path.
	 * @param string $base_uri Absolute URI path.
	 * @param string $relative Relative asset path.
	 *
	 * @return array<string, string>|null
	 */
	function dd_resolve_theme_asset( $base_dir, $base_uri, $relative ) {
		$candidates = array( $relative );
		$extension  = pathinfo( $relative, PATHINFO_EXTENSION );

		if ( dd_is_production_environment() ) {
			$minified = preg_replace( '/\.' . preg_quote( $extension, '/' ) . '$/', '.min.' . $extension, $relative );
			if ( $minified && $minified !== $relative ) {
				array_unshift( $candidates, $minified );
			}
		}

		foreach ( $candidates as $candidate ) {
			$path = trailingslashit( $base_dir ) . str_replace( '/', DIRECTORY_SEPARATOR, $candidate );
			if ( file_exists( $path ) ) {
				return array(
					'path' => $path,
					'uri'  => trailingslashit( $base_uri ) . str_replace( DIRECTORY_SEPARATOR, '/', $candidate ),
				);
			}
		}

		return null;
	}
}

if ( ! function_exists( 'dd_get_script_asset_metadata' ) ) {
	/**
	 * Reads WordPress script asset metadata when present.
	 *
	 * @param string $script_path Absolute script path.
	 *
	 * @return array<string, mixed>
	 */
	function dd_get_script_asset_metadata( $script_path ) {
		$asset_file = preg_replace( '/(\.min)?\.js$/', '.asset.php', $script_path );
		$asset_data = array();
		$filemtime  = filemtime( $script_path );

		if ( $asset_file && file_exists( $asset_file ) ) {
			$asset_data = include $asset_file;
		}

		return array(
			'dependencies' => isset( $asset_data['dependencies'] ) ? $asset_data['dependencies'] : array(),
			'version'      => isset( $asset_data['version'] ) ? $asset_data['version'] . '.' . $filemtime : $filemtime,
		);
	}
}

if ( ! function_exists( 'dd_register_framework_block_category' ) ) {
	/**
	 * Registers the Duck Diver Framework block category.
	 *
	 * @param array $categories Existing categories.
	 *
	 * @return array
	 */
	function dd_register_framework_block_category( $categories ) {
		$categories[] = array(
			'slug'  => 'duck-diver-framework',
			'title' => __( 'Duck Diver Framework', 'dd_theme' ),
		);

		return $categories;
	}
	add_filter( 'block_categories_all', 'dd_register_framework_block_category' );
}

if ( ! function_exists( 'dd_add_framework_block_theme_support' ) ) {
	/**
	 * Enables editor features used by framework blocks.
	 *
	 * @return void
	 */
	function dd_add_framework_block_theme_support() {
		add_theme_support( 'align-wide' );
	}
	add_action( 'after_setup_theme', 'dd_add_framework_block_theme_support' );
}

if ( ! function_exists( 'dd_register_framework_block_editor_script' ) ) {
	/**
	 * Registers the shared parent block editor script.
	 *
	 * @return void
	 */
	function dd_register_framework_block_editor_script() {
		$asset = dd_resolve_theme_asset(
			get_template_directory(),
			get_template_directory_uri(),
			'dd-blocks/build/index.js'
		);

		if ( ! $asset ) {
			return;
		}

		$metadata = dd_get_script_asset_metadata( $asset['path'] );

		wp_register_script(
			'dd-framework-blocks-editor-script',
			$asset['uri'],
			$metadata['dependencies'],
			$metadata['version'],
			true
		);

		if ( function_exists( 'dd_get_button_icon_sprite_markup' ) ) {
			wp_add_inline_script(
				'dd-framework-blocks-editor-script',
				'(function(){var sprite=' . wp_json_encode( dd_get_button_icon_sprite_markup() ) . ';function inject(doc){if(!doc||!doc.body||doc.getElementById("dd-button-icon-sprite")){return;}doc.body.insertAdjacentHTML("afterbegin",sprite);}function injectAll(){inject(document);document.querySelectorAll("iframe").forEach(function(frame){try{inject(frame.contentDocument);}catch(error){}});}if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",injectAll);}else{injectAll();}new MutationObserver(injectAll).observe(document.documentElement,{childList:true,subtree:true});}());',
				'after'
			);
		}
	}
}

if ( ! function_exists( 'dd_register_child_theme_block_editor_script' ) ) {
	/**
	 * Registers the optional child theme block editor script.
	 *
	 * @return string|null
	 */
	function dd_register_child_theme_block_editor_script() {
		if ( ! is_child_theme() ) {
			return null;
		}

		$asset = dd_resolve_theme_asset(
			get_stylesheet_directory(),
			get_stylesheet_directory_uri(),
			'dd-blocks/build/index.js'
		);

		if ( ! $asset ) {
			return null;
		}

		$metadata     = dd_get_script_asset_metadata( $asset['path'] );
		$dependencies = $metadata['dependencies'];

		if (
			wp_script_is( 'dd-framework-blocks-editor-script', 'registered' ) &&
			! in_array( 'dd-framework-blocks-editor-script', $dependencies, true )
		) {
			$dependencies[] = 'dd-framework-blocks-editor-script';
		}

		wp_register_script(
			'dd-child-framework-block-editor-script',
			$asset['uri'],
			$dependencies,
			$metadata['version'],
			true
		);

		return 'dd-child-framework-block-editor-script';
	}
}

if ( ! function_exists( 'dd_register_blocks_from_directory' ) ) {
	/**
	 * Registers all block metadata directories within a given path.
	 *
	 * @param string $blocks_dir Absolute block directory path.
	 * @param array  $args Optional register_block_type overrides.
	 *
	 * @return void
	 */
	function dd_register_blocks_from_directory( $blocks_dir, $args = array() ) {
		if ( ! is_dir( $blocks_dir ) ) {
			return;
		}

		$block_directories = glob( trailingslashit( $blocks_dir ) . '*', GLOB_ONLYDIR );

		if ( ! $block_directories ) {
			return;
		}

		foreach ( $block_directories as $block_directory ) {
			if ( file_exists( $block_directory . '/block.json' ) ) {
				register_block_type( $block_directory, $args );
			}
		}
	}
}

if ( ! function_exists( 'dd_register_framework_blocks' ) ) {
	/**
	 * Registers the parent framework block set.
	 *
	 * @return void
	 */
	function dd_register_framework_blocks() {
		dd_register_framework_block_editor_script();
		dd_register_blocks_from_directory(
			get_template_directory() . '/dd-blocks/blocks',
			array(
				'editor_script' => 'dd-framework-blocks-editor-script',
			)
		);
	}
	add_action( 'init', 'dd_register_framework_blocks', 10 );
}

if ( ! function_exists( 'dd_register_child_theme_blocks' ) ) {
	/**
	 * Registers child theme block metadata when available.
	 *
	 * @return void
	 */
	function dd_register_child_theme_blocks() {
		if ( ! is_child_theme() ) {
			return;
		}

		dd_register_blocks_from_directory( get_stylesheet_directory() . '/dd-blocks/blocks' );
	}
	add_action( 'init', 'dd_register_child_theme_blocks', 20 );
}

if ( ! function_exists( 'dd_enqueue_child_theme_block_assets' ) ) {
	/**
	 * Enqueues optional child theme block content styles for the editor.
	 *
	 * @return void
	 */
	function dd_enqueue_child_theme_block_assets() {
		if ( ! is_child_theme() || ! is_admin() ) {
			return;
		}

		$editor_style = dd_resolve_theme_asset(
			get_stylesheet_directory(),
			get_stylesheet_directory_uri(),
			'bootstrap/dd-blocks.editor.build.css'
		);

		if ( $editor_style ) {
			wp_enqueue_style(
				'dd-child-framework-block-editor-style',
				$editor_style['uri'],
				array( 'wp-edit-blocks' ),
				filemtime( $editor_style['path'] )
			);
		}
	}
	add_action( 'enqueue_block_assets', 'dd_enqueue_child_theme_block_assets' );
}

if ( ! function_exists( 'dd_enqueue_child_theme_block_editor_assets' ) ) {
	/**
	 * Enqueues the optional child theme editor extension script.
	 *
	 * @return void
	 */
	function dd_enqueue_child_theme_block_editor_assets() {
		$child_script_handle = dd_register_child_theme_block_editor_script();

		if ( ! $child_script_handle ) {
			return;
		}

		wp_enqueue_script( $child_script_handle );
	}
	add_action( 'enqueue_block_editor_assets', 'dd_enqueue_child_theme_block_editor_assets' );
}

if ( ! function_exists( 'dd_get_button_icon_sprite_symbols' ) ) {
	/**
	 * Gets the SVG symbols used by framework button blocks.
	 *
	 * @return array<string, array<int, string>>
	 */
	function dd_get_button_icon_sprite_symbols() {
		return array(
			'arrow-right'        => array(
				'M1 8a.5.5 0 0 1 .5-.5h11.793L9.146 3.354a.5.5 0 1 1 .708-.708l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z',
			),
			'arrow-left'         => array(
				'M15 8a.5.5 0 0 0-.5-.5H2.707l4.147-4.146a.5.5 0 1 0-.708-.708l-5 5a.5.5 0 0 0 0 .708l5 5a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z',
			),
			'download'           => array(
				'M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z',
				'M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z',
			),
			'box-arrow-up-right' => array(
				'M8.636 3.5a.5.5 0 0 0 0 1H13.5v4.864a.5.5 0 0 0 1 0V3.5h-5.864z',
				'M14.354 3.646a.5.5 0 0 0-.708 0L5 12.293a.5.5 0 1 0 .708.708l8.646-8.647a.5.5 0 0 0 0-.708z',
				'M2.5 2A1.5 1.5 0 0 0 1 3.5v10A1.5 1.5 0 0 0 2.5 15h10a1.5 1.5 0 0 0 1.5-1.5V11a.5.5 0 0 0-1 0v2.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5H5a.5.5 0 0 0 0-1H2.5z',
			),
			'chevron-right'      => array(
				'M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z',
			),
			'phone'              => array(
				'M3.654 1.328a.678.678 0 0 1 .738-.162l2.522 1.01c.329.132.519.482.441.828l-.547 2.464a.678.678 0 0 1-.655.529l-1.059.02a11.72 11.72 0 0 0 4.89 4.89l.02-1.059a.678.678 0 0 1 .529-.655l2.464-.547a.678.678 0 0 1 .828.441l1.01 2.522a.678.678 0 0 1-.162.738l-1.12 1.12c-.517.517-1.279.738-2.026.493C7.593 12.673 3.327 8.407 2.04 4.473c-.245-.747-.024-1.509.493-2.026l1.12-1.119z',
			),
			'star'               => array(
				'M2.866 14.85c-.078.444.36.791.746.593L8 13.187l4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.93 0L5.354 5.12l-4.898.696c-.441.062-.613.636-.283.95l3.522 3.356-.829 4.729z',
			),
			'info-circle'        => array(
				'M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z',
				'm8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z',
				'M9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z',
			),
		);
	}
}

if ( ! function_exists( 'dd_get_button_icon_sprite_markup' ) ) {
	/**
	 * Gets the inline SVG sprite markup used by framework button blocks.
	 *
	 * @return string
	 */
	function dd_get_button_icon_sprite_markup() {
		ob_start();
		?>
		<svg id="dd-button-icon-sprite" xmlns="http://www.w3.org/2000/svg" style="display:none" aria-hidden="true" focusable="false">
			<?php foreach ( dd_get_button_icon_sprite_symbols() as $icon => $paths ) : ?>
				<symbol id="<?php echo esc_attr( 'icon-' . $icon ); ?>" viewBox="0 0 16 16">
					<?php foreach ( $paths as $path ) : ?>
						<path d="<?php echo esc_attr( $path ); ?>"></path>
					<?php endforeach; ?>
				</symbol>
			<?php endforeach; ?>
		</svg>
		<?php
		return trim( ob_get_clean() );
	}
}

if ( ! function_exists( 'dd_render_button_icon_sprite' ) ) {
	/**
	 * Outputs the inline SVG sprite used by framework button blocks.
	 *
	 * @return void
	 */
	function dd_render_button_icon_sprite() {
		static $rendered = false;

		if ( $rendered ) {
			return;
		}

		$rendered = true;

		echo dd_get_button_icon_sprite_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	add_action( 'wp_footer', 'dd_render_button_icon_sprite', 1 );
	add_action( 'admin_footer', 'dd_render_button_icon_sprite', 1 );
}

add_action( 'admin_menu', 'reusable_blocks_adminbar_item' );
function reusable_blocks_adminbar_item() {
	add_menu_page(
		'Reusable Blocks and Patterns',
		'Reusable Blocks',
		'manage_options',
		'site-editor.php?p=%2Fpattern&postType=wp_block',
		'',
		'dashicons-welcome-widgets-menus',
		20
	);
}
