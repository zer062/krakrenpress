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
        //add_action( 'wp', [ $this, 'setup_jobs' ] );
	    //add_action( 'momi_update_members_daily', [ $this, 'update_members_daily' ] );
	    //add_filter( 'cron_schedules', [ $this, 'add_custom_cron_schedules' ] );
        $this->load_instances();
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

    public function setup_jobs() {

    	add_action( 'momi_update_members_daily', [ $this, 'momi_update_members_daily' ] );
    	add_action( 'momi_update_members_each_three_hours', [ $this, 'update_members_each_three_hours' ] );

    	if ( !wp_next_scheduled(  'momi_update_members_daily' ) ) {
		    wp_schedule_event( time(), 'daily', 'momi_update_members_daily' );
		    wp_schedule_event( time(), 'each_three_hours', 'update_members_daily' );
	    }
    }

    public function update_members_daily() {
	    (new UpdateMembersDaily())->exec();
    }

	/**
	 * @param $schedules
	 *
	 * @return mixed
	 */
    public  function add_custom_cron_schedules( $schedules ) {

	    $schedules['each_three_hours'] = array(
		    'interval' => 10800,
		    'display'  => esc_html__( 'Every Three Hours' ), );

	    return $schedules;
    }
}