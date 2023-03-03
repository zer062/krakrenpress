<?php

namespace Core\Tentacles;

abstract class Taxonomy {

	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * @var string
	 */
	public $slug;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $singular_name;

	/**
	 * @var bool
	 */
	public $public = true;

	/**
	 * @var bool
	 */
	public $publicly_queryable = true;

	/**
	 * @var bool
	 */
	public $hierarchical = true;

	/**
	 * @var bool
	 */
	public $show_ui = true;

	/**
	 * @var bool
	 */
	public $show_in_menu = true;

	/**
	 * @var bool
	 */
	public $show_in_nav_menus = true;

	/**
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * @var bool
	 */
	public $show_in_quick_edit = true;

	/**
	 * @var bool
	 */
	public $show_admin_column = true;

	/**
	 * Taxonomy constructor.
	 *
	 * @param Model $model
	 */
	public function __construct( Model $model) {
		$this->load_taxonomy_slug();
		$this->load_taxonomy_names();
		$this->model = $model;
	}

	/**
	 * Load model slug
	 *
	 * @return void
	 */
	private function load_taxonomy_slug() {

		if ( is_null( $this->slug ) ) {
			$explode_class_name = explode('\\', get_class( $this ) );
			$this->slug = strtolower( end( $explode_class_name ) );
		}
	}

	/**
	 * Load model singular name
	 *
	 * @return void
	 */
	private function load_taxonomy_names() {

		if ( is_null( $this->singular_name ) ) {
			$explode_class_name = explode('\\', get_class( $this ) );
			$this->singular_name = end( $explode_class_name ) ;
			$this->name = end( $explode_class_name ) . 's' ;
		}
	}

	/**
	 * Register the taxonomy
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', function() {
			register_taxonomy( $this->slug, [ $this->model->slug], [
				'labels' => [
					'name' => __( $this->name,  APP_DOMAIN ),
					'singular_name' => __( $this->singular_name, APP_DOMAIN )
				],
				'hierarchical' => $this->hierarchical,
				'public' => $this->public,
				'show_ui' => $this->show_ui,
				'show_admin_column' => $this->show_admin_column,
				'show_in_nav_menus' => $this->show_in_nav_menus,
				'show_in_rest' => $this->show_in_rest
			]);
		}, 0);
	}
}
