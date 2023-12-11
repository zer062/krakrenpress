<?php


namespace Core;


class AppSettings
{

    /**
     * App settings store key
     * @var string
     */
    public static string $APP_STORE_KEY = '_app_settings';

    /**
     * @var array
     */
    protected $app_settings = [];

    /**
     * AppSettings constructor.
     */
    public function __construct()
    {
        $this->load_settings();
		add_action( 'admin_menu', [$this, 'settings_page'] );
		add_action( 'admin_init', [$this, 'register_settings'] );
    }

    /**
     * Get all app settings array
     *
     * @return array
     */
    public function get_settings()
    {
        return $this->app_settings;
    }

	/**
	 * @param $key
	 *
	 * @return false|mixed|null
	 */
	public function get_option($key) {
		return get_option( $this->app_settings['settings_key'] . '_' . $key );
	}

    /**
     * Get app setting by key
     *
     * @param string $key
     * @return mixed|null
     */
    public function get_app_setting( $key ) {
        return $this->app_settings[ $key ] ?? null;
    }

    /**
     * Set new value to App settings
     *
     * @param string $key
     * @param $value
     * @return $this
     */
    public function set_app_setting( $key, $value )
    {
        $this->app_settings[$key] = $value;
        return $this;
    }

    /**
     * Save all settings on WordPress database
     *
     * @return $this
     */
    public function store() {
        update_site_option( self::$APP_STORE_KEY, serialize( $this->app_settings ) );
        return $this;
    }

    /**
     * Remove setting by key
     *
     * @param $key
     * @return $this
     */
    public function remove( $key ) {
        unset( $this->app_settings[$key] );
        return $this;
    }

    /**
     * Load all app settings stored in WordPress database
     *
     * @return $this
     */
    public function load_settings()
    {
        $merged_settings = include ( APP_CONFIG_PATH . '/settings.php' );
        $stored_settings = get_site_option( self::$APP_STORE_KEY );

        if ( $stored_settings) {
            $merged_settings = array_merge( $this->app_settings, unserialize( $stored_settings ) );
        }

        $main_app_settings = include ( APP_CONFIG_PATH . '/app.php' );

        $merged_settings = array_merge( $merged_settings, $main_app_settings );
        $this->app_settings = $merged_settings;

        return $this;
    }

	/**
	 * @return void
	 */
	public function settings_page() {
		if (isset($this->app_settings['settings_page_slug']) && isset($this->app_settings['settings_menu_title'])) {
			add_submenu_page(
				'options-general.php',
				$this->app_settings['settings_page_title'],
				$this->app_settings['settings_menu_title'],
				'manage_options',
				$this->app_settings['settings_page_slug'],
				[$this, 'render_settings_page']
			);
		}
	}

	/**
	 * @return void
	 */
	public function render_settings_page() {

		$default_tab = null;
		$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<nav class="nav-tab-wrapper">
				<?php foreach ($this->app_settings['settings_tabs'] as $index => $tabs) :?>
					<a href="?page=<?php echo $this->app_settings['settings_page_slug'];?>&tab=<?php echo $tabs['slug'];?>" class="nav-tab <?php if(($tab === null && $index == 0) || $tab === $tabs['slug']):?>nav-tab-active<?php endif; ?>"><?php echo $tabs['title'];?></a>
				<?php endforeach; ?>
			</nav>

			<div class="tab-content">
				<?php foreach ($this->app_settings['settings_tabs'] ?? [] as $index => $tabs) :?>
				<?php if(($tab === null && $index == 0) || $tab === $tabs['slug']):?>
					<form method="post" action="options.php">
						<?php settings_fields( $this->app_settings['settings_key'] ); ?>
						<?php do_settings_sections( $this->app_settings['settings_key'] ); ?>
						<?php submit_button(); ?>
					</form>
				<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * @return void
	 */
	public function register_settings() {
		if (isset($this->app_settings['settings_tabs'])) {
			foreach ($this->app_settings['settings_tabs'] ?? [] as $tab) {

				add_settings_section(
					$tab['slug'],
					$tab['description'],
					'',
					$this->app_settings['settings_key']
				);

				foreach ($tab['fields'] as $id => $field) {
					add_settings_field(
						$this->app_settings['settings_key'] . '_' . $id,
						$field['label'],
						fn () => $this->render_settings_field($id, $field['type']),
						$this->app_settings['settings_key'],
						$tab['slug']
					);

					register_setting(
						$this->app_settings['settings_key'],
						$this->app_settings['settings_key'] . '_' . $id
					);
				}
			}
		}
	}

	/**
	 * @param $id
	 * @param $type
	 *
	 * @return void
	 */
	public function render_settings_field($id, $type) {
		$field = '';
		$option = get_option( $this->app_settings['settings_key'] . '_' . $id );
		switch ($type) {
			case 'text':
				$field = '<input id="%s" name="%s" type="text" value="%s" />';
				break;
			default:
				break;
		}

		echo sprintf(
			$field,
			$this->app_settings['settings_key'] . '_' . $id,
			$this->app_settings['settings_key'] . '_' . $id,
			esc_attr( $option ?? '' )
		);
	}
}
