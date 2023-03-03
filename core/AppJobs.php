<?php


namespace Core;

class AppJobs
{
	/**
	 * @var array
	 */
    protected $jobs = [];

	/**
	 * AppModels constructor.
	 */
    public function __construct()
    {
        $this->load_jobs();
    }

	/**
	 * Load all model and register post types
	 *
	 * @return void
	 */
    protected function load_jobs()
    {
        $all_jobs = scandir( (new \Core\AppSettings)->get_app_setting( 'jobs_path' ) );

        foreach ( $all_jobs as $job ) {
            if ( $job === '.' || $job === '..' ) continue;
	        $job_class = str_replace('.php', '', "\Jobs\\{$job}" );
            $this->jobs[] = new $job_class();
        }
    }
}