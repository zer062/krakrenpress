<?php


namespace Core;


class AppSettings
{

    /**
     * App settings store key
     * @var string
     */
    const APP_STORE_KEY = '_app_settings';

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
     * Get app setting by key
     *
     * @param string $key
     * @return mixed|null
     */
    public function get_app_setting( $key ) {
        return isset( $this->app_settings[$key] ) ? $this->app_settings[$key] : null;
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
        update_site_option( self::APP_STORE_KEY, serialize( $this->app_settings ) );
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
        $merged_settings = [];
        $stored_settings = get_site_option( self::APP_STORE_KEY );

        if ( $stored_settings && !is_null( $stored_settings ) ) {
            $merged_settings = array_merge( $this->app_settings, unserialize( $stored_settings ) );
        }

        $main_app_settings = include ( APP_CONFIG_PATH . '/app.php' );

        $merged_settings = array_merge( $merged_settings, $main_app_settings );
        $this->app_settings = $merged_settings;

        return $this;
    }
}