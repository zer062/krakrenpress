<?php

namespace Core\Tentacles;

abstract class Model
{
	/**
	 * @var Model
	 */
	protected static $_instance;

    /**
     * @var string
     */
    public $slug;

	/**
	 * @var string
	 */
    public $singular_name;

    /**
     * @var string
     */
    public $plural_name;

    /**
     * @var bool
     */
    public $public = true;

    /**
     * @var bool
     */
    public $has_archive = true;

    /**
     * @var bool
     */
    public $show_in_rest = true;

	/**
	 * @var array
	 */
    public $support = ['title', 'editor', 'thumbnail'];

	/**
	 * @var string
	 */
    public $menu_icon = 'dashicons-admin-post';

	/**
	 * @var bool
	 */
    public $hierarchical = false;

	/**
	 * @var string
	 */
    public $menu_position = 20;

	/**
	 * @var bool
	 */
    public $can_export = true;

	/**
	 * @var bool
	 */
    public $query_var = true;

	/**
	 * @var bool
	 */
    public $show_in_nav_menus = true;

	/**
	 * @var bool
	 */
    public $show_ui = true;

	/**
	 * @var bool
	 */
    public $exclude_from_search = false;

	/**
	 * @var array
	 */
    public $taxonomies = [];

	/**
	 * @var \WP_Post
	 */
    protected $post;

	/**
	 * @var array
	 */
    protected $post_fields = [
		'post_status' => 'publish',
    ];

	/**
	 * @var int
	 */
    protected $ID;

	/**
	 * @var array
	 */
    protected $group;

	/**
	 * @var array
	 */
    public $fields = [];

	/**
	 * @var array
	 */
	public $capabilities = [];

	/**
	 * Model constructor.
	 *
	 * @param int|null $postId
	 */
    public function __construct( int $postId = null )
    {
    	$this->ID = $postId;
        $this->load_model_slug();
        $this->load_model_singular_name();
        $this->load_model_plural_name();
        $this->register();
        $this->load_taxonomies();
        $this->load_post();
//        $this->register_fields_group();
    }

	/**
	 * Load model post
	 *
	 * @return void
	 */
    protected function load_post()
    {
	    if ( !is_null( $this->ID ) ) {
	    	$the_post = get_post( $this->ID );
		    $this->post = $the_post->post_type === $this->slug ? $the_post : null;
		    $this->load_all_attributes();
	    }
    }

	/**
	 * @param $name
	 *
	 * @return mixed|null
	 */
    public function __get( $name ) {
    	if (isset($this->post->$name)) return $this->post->$name;
    	if (isset($this->post_fields[$name])) return $this->post_fields[$name];
	    return null;
    }

	/**
	 * @param $name
	 * @param $value
	 */
    public function __set( $name, $value ) {
    	if (isset($this->post->$name)) {
    		$this->post->$name = $value;
	    } else {
    		$this->post_fields[$name] = $value;
	    }
    }

	/**
     * Load model slug
     *
     * @return void
     */
    private function load_model_slug() {

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
    private function load_model_singular_name() {

        if ( is_null( $this->singular_name ) ) {
            $explode_class_name = explode('\\', get_class( $this ) );
            $this->singular_name = end( $explode_class_name ) ;
        }
    }

    /**
     * Load model plural name
     *
     * @return void
     */
    private function load_model_plural_name() {

        if ( is_null( $this->plural_name ) ) {
            $explode_class_name = explode('\\', get_class( $this ) );
            $this->plural_name = end( $explode_class_name ) . 's' ;
        }
    }

	/**
	 * Register all model taxonomies
	 *
	 * @return void
	 */
    private function load_taxonomies()
    {
    	if ( !empty( $this->taxonomies ) ) {
    		foreach ($this->taxonomies as $taxonomy ) {
			    (new $taxonomy( $this ) )->register();
		    }
	    }
    }

	/**
	 * Load all model attributes
	 *
	 * @return void
	 */
    private function load_all_attributes()
    {
    	if ( !is_null( $this->post ) ) {

    		foreach ( $this->post as $key => $value ) {
    			$this->$key = $value;
		    }

    		$meta_fields = get_post_meta( $this->post->ID );

    		if ( !empty( $meta_fields ) ) {
				$this->post_fields = $meta_fields;

    			foreach ( $meta_fields as $key => $value ) {
    				$this->$key = is_string( $value) && unserialize( $value ) ? unserialize( $value ) : $value;
			    }
		    }
	    }
    }

	/**
	 * @return void
	 */
	public function register_fields_group() {
		$this->group = [
			'key' => $this->slug . '_group_fields',
			'title' => __( $this->singular_name . ' Fields', APP_DOMAIN ),
			'fields' => $this->fields,
			'location' => [
				[
					[
						'param' => 'post_type',
						'operator' => '==',
						'value' => $this->slug,
					],
				],
			],
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'left',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		];

	    if( !empty( $this->group['fields'] ) && function_exists( 'acf_add_local_field_group' ) ) {
		    \acf_add_local_field_group( $this->group );
	    }
    }

    /**
     * Register Custom Post Type by Model
     *
     * @return void
     */
    private function register() {
        add_action( 'init', function() {
            register_post_type( $this->slug,
                array(
                    'labels' => [
                        'name' => __( $this->plural_name, APP_DOMAIN ),
                        'singular_name' => __( $this->singular_name, APP_DOMAIN ),
                        'add_new_item' => __( 'Add new ' . $this->singular_name, APP_DOMAIN ),
                        'edit_item' => __( 'Edit ' . $this->singular_name, APP_DOMAIN ),
                        'new_item' => __( 'New ' . $this->singular_name, APP_DOMAIN ),
                        'view_item' => __( 'View ' . $this->singular_name, APP_DOMAIN ),
                        'view_items' => __( 'View ' . $this->plural_name, APP_DOMAIN ),
                        'search_items' => __( 'Search ' . $this->plural_name, APP_DOMAIN ),
                        'not_found' => __( $this->plural_name . ' not found', APP_DOMAIN ),
                        'all_items' => __( 'All ' . $this->plural_name, APP_DOMAIN ),
                    ],
                    'exclude_from_search' => $this->exclude_from_search,
                    'public' => $this->public,
                    'has_archive' => $this->has_archive,
                    'rewrite' => [ 'slug' => $this->slug ],
                    'show_in_rest' => $this->show_in_rest,
                    'supports' => $this->support,
	                'menu_icon' => $this->menu_icon,
	                'menu_position' => $this->menu_position,
	                'hierarchical' => $this->hierarchical,
	                'can_export' => $this->can_export,
	                'query_var' => $this->query_var,
	                'show_in_nav_menus' => $this->show_in_nav_menus,
	                'show_ui' => $this->show_ui,
	                'capability_type' => 'post',
	                'capabilities' => $this->capabilities,
	                'meta_cap' => false,
                )
            );
        });
    }

	/**
	 * Save post data
	 *
	 * @return bool
	 */
    public function save() {
    	if (is_null( $this->ID ) ) {
    		$this->ID = wp_insert_post( array_merge(['post_type' => $this->slug], $this->post_fields ));
	    } else {
	        wp_update_post( $this->post );
	    }

    	foreach ( $this->post_fields as $key => $value) {
    		update_post_meta( $this->ID, $key, $value );
	    }

    	$this->load_post();

    	return true;
    }

	/**
	 * Delete model post
	 *
	 * @return bool
	 */
    public function delete() {
    	wp_delete_post( $this->post->ID );
    	return true;
    }

	/**
	 * Find post by id
	 *
	 * @param int $id
	 *
	 * @return static|null
	 */
    public static function find( int $id ) {
    	if( is_null( get_post( $id ) ) ) return null;
    	return new static($id);
    }
}
