<?php
/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 2.0.1
 */

require_once dirname( __DIR__ ) . '/vendor/autoload.php';

Timber\Timber::init();
Timber::$dirname    = array( 'views' );
Timber::$autoescape = false;


class Timberland extends Timber\Site {
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'block_categories_all', array( $this, 'block_categories_all' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_assets' ) );

		parent::__construct();
	}

	public function add_to_context( $context ) {
		$context['site'] = $this;
		$context['sideMenu'] = Timber::get_menu("SideMenu");
		$context['topMenu'] = Timber::get_menu("TopMenu");
		return $context;
	}

	public function add_to_twig( $twig ) {
		return $twig;
	}

	public function theme_supports() {
		add_theme_support( 'automatic-feed-links' );
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);
		add_theme_support( 'custom-logo' );
		add_theme_support( 'menus' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
        $args = array(
            'default-image'      => get_template_directory() . '/assets/images/header.jpg',
            'width'              => 1000,
            'height'             => 180,
            'flex-width'         => true,
            'flex-height'        => true,
        );
        add_theme_support( 'custom-header', $args );
	}

	public function enqueue_assets() {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' );
		wp_dequeue_script( 'jquery' );

		$manifest  = null;
        $dist_path = get_template_directory() . '/assets/dist';

		if ( file_exists( $dist_path . '/.vite/manifest.json' ) ) {
			$manifest = json_decode( file_get_contents( $dist_path . '/.vite/manifest.json' ), true );
		}

		$vite_env = 'development';

        if ( is_array( $manifest ) && ($vite_env === 'production' || is_admin() )) {
            $js_file = 'theme/assets/main.js';
            wp_enqueue_script(
                'main',
                $dist_path . '/' . $manifest[ $js_file ]['file'],
                array(),
                '',
                array(
                    'strategy'  => 'defer',
                    'in_footer' => true,
                )
            );
        }

		if ( $vite_env === 'development' ) {
			function vite_head_module_hook() {
				echo '<script type="module" crossorigin src="http://localhost:3000/@vite/client"></script>';
				echo '<script type="module" crossorigin src="http://localhost:3000/theme/assets/main.js"></script>';
			}
			add_action( 'wp_head', 'vite_head_module_hook' );
		}
	}

	public function block_categories_all( $categories ) {
		return array_merge(
			array(
				array(
					'slug'  => 'custom',
					'title' => __( 'Custom' ),
				),
			),
			$categories
		);
	}
}

new Timberland();
