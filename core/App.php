<?php

namespace Core;

use Illuminate\Support\Facades\Log;
use Jobs\UpdateMembersDaily;

class App
{
    /**
     * @var App
     */
    protected static $_instance;

	/**
	 * @var array
	 */
    public $vendors = [];

	/**
	 * @var array
	 */
    protected $hooks = [];

	/**
	 * @var array
	 */
    protected $shortcodes = [];

	/**
	 * @var array
	 */
    protected $ajax = [];

	/**
	 * @var array
	 */
    protected $jobs = [];

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->load_instances();

//		$this->app_settings->generate_settings_page();
    }

    /**
     * App Singleton
     * @return App
     */
    public static function getInstance() {
        if ( is_null ( self::$_instance ) ) {
            self::$_instance = new App();
        }

        return self::$_instance;
    }

    /**
     * Load all core packages instance
     * @return void
     */
    private function load_instances()
    {
        $this->app_settings = new \Core\AppSettings();
        $this->vendors = new \Core\AppVendors();
        $this->app_models = new \Core\AppModels();
        $this->hooks = new \Core\AppHooks();
        $this->shortcodes = new \Core\AppShortCodes();
        $this->ajax = new \Core\AppAjax();
        $this->jobs = new \Core\AppJobs();
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set( $name, $value )
    {
        $this->$name = $value;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get( $name )
    {
        return $this->$name;
    }
}
